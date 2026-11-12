<?php

namespace App\Controllers;

use App\Libraries\JmfService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    protected $helpers = ['url', 'form', 'text'];

    protected $db;
    protected array $pengaturan = [];
    protected ?array $activeEvent = null;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->db = \Config\Database::connect();
        $this->pengaturan = JmfService::getPengaturan();
        $this->activeEvent = JmfService::getActiveEvent();
    }

    protected function renderView(string $view, array $data = []): string
    {
        $data['pengaturan']  = $this->pengaturan;
        $data['activeEvent'] = $this->activeEvent;
        return view($view, $data);
    }
}
