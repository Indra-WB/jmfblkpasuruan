<?php

namespace App\Libraries;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Dompdf\Dompdf;
use Dompdf\Options;

class JmfService
{
    public static function getPengaturan(): array
    {
        $db = \Config\Database::connect();
        $rows = $db->table('pengaturan')->get()->getResultArray();
        $result = [];
        foreach ($rows as $r) {
            $result[$r['key']] = $r['value'];
        }
        return $result;
    }

    public static function getActiveEvent(): ?array
    {
        $db = \Config\Database::connect();
        $event = $db->table('events')->where('status', 'aktif')->orderBy('id', 'DESC')->get()->getRowArray();
        if (! $event) {
            $event = $db->table('events')->orderBy('id', 'DESC')->get()->getRowArray();
        }
        return $event ?: null;
    }

    public static function generateQrDataUri(string $text): string
    {
        $options = new QROptions([
            'outputInterface' => \chillerlan\QRCode\Output\QRGdImagePNG::class,
            'scale'           => 6,
            'outputBase64'    => true,
            'addQuietzone'    => true,
        ]);
        return (string) (new QRCode($options))->render($text);
    }

    public static function renderPdf(string $html, string $filename, string $paper = 'A4', string $orientation = 'portrait', bool $download = false)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();

        return response()
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', ($download ? 'attachment' : 'inline') . '; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    public static function queueEmail(string $to, string $subject, string $htmlBody): void
    {
        if (empty($to)) {
            return;
        }
        $db = \Config\Database::connect();
        $db->table('email_queue')->insert([
            'tujuan'     => $to,
            'subjek'     => $subject,
            'isi'        => $htmlBody,
            'status'     => 'antre',
            'percobaan'  => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function processEmailQueue(int $limit = 20): array
    {
        $db = \Config\Database::connect();
        $cfg = self::getPengaturan();
        $items = $db->table('email_queue')
            ->where('status', 'antre')
            ->where('percobaan <', 3)
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $sent = 0;
        $failed = 0;

        foreach ($items as $item) {
            if (empty($cfg['smtp_pass']) || empty($cfg['smtp_host'])) {
                // Simulate sent in local/demo if SMTP password is not configured yet
                $db->table('email_queue')->where('id', $item['id'])->update([
                    'status'    => 'terkirim',
                    'percobaan' => $item['percobaan'] + 1,
                    'error_msg' => 'Simulated (SMTP password belum diisi di Pengaturan)',
                    'sent_at'   => date('Y-m-d H:i:s'),
                ]);
                $sent++;
                continue;
            }

            try {
                $email = \Config\Services::email();
                $email->initialize([
                    'protocol'   => 'smtp',
                    'SMTPHost'   => $cfg['smtp_host'] ?? 'mail.uptblkpasuruan.com',
                    'SMTPUser'   => $cfg['smtp_user'] ?? 'jmf@uptblkpasuruan.com',
                    'SMTPPass'   => $cfg['smtp_pass'] ?? '',
                    'SMTPPort'   => (int) ($cfg['smtp_port'] ?? 465),
                    'SMTPCrypto' => $cfg['smtp_crypto'] ?? 'ssl',
                    'mailType'   => 'html',
                    'charset'    => 'utf-8',
                ]);
                $email->setFrom($cfg['smtp_user'] ?? 'jmf@uptblkpasuruan.com', $cfg['smtp_from_name'] ?? 'Panitia JMF BLK Pasuruan');
                $email->setTo($item['tujuan']);
                $email->setSubject($item['subjek']);
                $email->setMessage($item['isi']);

                if ($email->send()) {
                    $db->table('email_queue')->where('id', $item['id'])->update([
                        'status'    => 'terkirim',
                        'percobaan' => $item['percobaan'] + 1,
                        'sent_at'   => date('Y-m-d H:i:s'),
                    ]);
                    $sent++;
                } else {
                    $err = $email->printDebugger(['headers']);
                    $tries = $item['percobaan'] + 1;
                    $db->table('email_queue')->where('id', $item['id'])->update([
                        'status'    => $tries >= 3 ? 'gagal' : 'antre',
                        'percobaan' => $tries,
                        'error_msg' => substr($err, 0, 500),
                    ]);
                    $failed++;
                }
            } catch (\Throwable $e) {
                $tries = $item['percobaan'] + 1;
                $db->table('email_queue')->where('id', $item['id'])->update([
                    'status'    => $tries >= 3 ? 'gagal' : 'antre',
                    'percobaan' => $tries,
                    'error_msg' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        return ['sent' => $sent, 'failed' => $failed, 'total' => count($items)];
    }

    /**
     * Evaluates arrival session match for an applicant at check-in
     */
    public static function evaluateSesiStatus(array $event, ?array $sesi): array
    {
        if (empty($event['sesi_aktif']) || ! $sesi) {
            return [
                'code'    => 'bebas',
                'badge'   => 'success',
                'label'   => 'Sesi Bebas / Nonaktif',
                'allowed' => true,
                'luar'    => 0,
            ];
        }

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $inDate = ($nowDate === $sesi['tanggal']);
        $inTime = ($nowTime >= $sesi['jam_mulai'] && $nowTime <= $sesi['jam_selesai']);

        if ($inDate && $inTime) {
            return [
                'code'    => 'sesuai',
                'badge'   => 'success',
                'label'   => 'Sesuai Jadwal Sesi (' . $sesi['nama'] . ')',
                'allowed' => true,
                'luar'    => 0,
            ];
        }

        $reason = ! $inDate
            ? 'Beda Tanggal (' . $sesi['tanggal'] . ')'
            : ($nowTime < $sesi['jam_mulai'] ? 'Belum Waktunya (Jadwal: ' . substr($sesi['jam_mulai'], 0, 5) . ')' : 'Lewat Jam Sesi (Berakhir: ' . substr($sesi['jam_selesai'], 0, 5) . ')');

        $tolak = ($event['aturan_luar_sesi'] === 'tolak');

        return [
            'code'    => 'luar_sesi',
            'badge'   => $tolak ? 'danger' : 'warning',
            'label'   => 'Di Luar Jam Sesi: ' . $reason,
            'allowed' => ! $tolak,
            'luar'    => 1,
        ];
    }
}
