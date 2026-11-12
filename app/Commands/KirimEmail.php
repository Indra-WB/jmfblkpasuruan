<?php

namespace App\Commands;

use App\Libraries\JmfService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class KirimEmail extends BaseCommand
{
    protected $group       = 'JMF';
    protected $name        = 'email:kirim';
    protected $description = 'Mengirim antrean email notifikasi Job Market Fair BLK Pasuruan';

    public function run(array $params)
    {
        $limit = isset($params[0]) ? (int) $params[0] : 30;
        $res = JmfService::processEmailQueue($limit);
        CLI::write("Antrean diproses: {$res['total']} | Terkirim: {$res['sent']} | Gagal: {$res['failed']}", 'green');
    }
}
