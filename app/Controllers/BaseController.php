<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    
    /**
     * Preload helper.
     *
     * @var array
     */
    protected $helpers = ['url', 'form', 'text', 'html'];

    /**
     * Constructor.
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
    }

    /**
     * Ambil ID user dari session.
     */
    protected function userId(): int
    {
        return (int) session()->get('user_id');
    }

    /**
     * Response JSON umum.
     */
    protected function jsonResponse(array $data, int $status = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON($data);
    }

    /**
     * Response sukses.
     */
    protected function successJson(string $message, array $data = []): ResponseInterface
    {
        return $this->jsonResponse([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ]);
    }

    /**
     * Response error.
     */
    protected function errorJson(string $message, int $status = 400): ResponseInterface
    {
        return $this->jsonResponse([
            'status'  => 'error',
            'message' => $message,
        ], $status);
    }
}