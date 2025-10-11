<?php

namespace Rezaandreannn\SatuSehat;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Rezaandreannn\SatuSehat\Models\SatuSehatLog;
use Rezaandreannn\SatuSehat\Models\SatuSehatToken;

class SatuSehatService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $organizationId;
    protected $environment;
    protected $timeout;

    public function __construct()
    {
        $this->environment = config('satusehat.environment', 'sandbox');
        $this->baseUrl = config('satusehat.base_urls.' . $this->environment);
        $this->clientId = config('satusehat.client_id');
        $this->clientSecret = config('satusehat.client_secret');
        $this->organizationId = config('satusehat.organization_id');
        $this->timeout = config('satusehat.timeout', 30);
    }

    /**
     * Mendapatkan access token
     */
    public function getAccessToken(): ?string
    {
        // Cek token yang masih valid di database
        $validToken = SatuSehatToken::getValidToken($this->environment);



        if ($validToken) {
            $this->log('info', 'Token masih valid dari database');
            return $validToken->access_token;
        }

        // Jika tidak ada token valid, buat token baru
        return $this->generateNewToken();
    }

    /**
     * Generate token baru dari API
     */
    protected function generateNewToken(): ?string
    {
        $startTime = microtime(true);

        try {
            $response = Http::asForm()
                ->post($this->baseUrl . '/oauth2/v1/accesstoken?grant_type=client_credentials', [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

            $executionTime = microtime(true) - $startTime;

            if ($response->successful()) {
                $tokenData = $response->json();

                // Simpan token ke database
                $token = SatuSehatToken::create([
                    'token_type' => $tokenData['token_type'] ?? 'Bearer',
                    'access_token' => $tokenData['access_token'],
                    'expires_in' => $tokenData['expires_in'],
                    'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in'] - 60), // Buffer 60 detik
                    'environment' => $this->environment,
                ]);

                // Log success
                $this->logApiCall(
                    'POST',
                    '/oauth2/v1/accesstoken',
                    [],
                    $tokenData,
                    $response->status(),
                    'success',
                    null,
                    $executionTime
                );

                $this->log('info', 'Token baru berhasil dibuat dan disimpan');

                return $token->access_token;
            } else {
                $this->logApiCall(
                    'POST',
                    '/oauth2/v1/accesstoken',
                    [],
                    $response->json(),
                    $response->status(),
                    'failed',
                    $response->body(),
                    $executionTime
                );

                $this->log('error', 'Gagal mendapatkan token: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            $executionTime = microtime(true) - $startTime;
            $this->logApiCall(
                'POST',
                '/oauth2/v1/accesstoken',
                [],
                null,
                0,
                'error',
                $e->getMessage(),
                $executionTime
            );

            $this->log('error', 'Exception saat mendapatkan token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Melakukan HTTP request ke API Satu Sehat
     */
    public function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Tidak bisa mendapatkan access token',
                'data' => null
            ];
        }

        $startTime = microtime(true);

        try {
            $request = Http::timeout($this->timeout)
                ->withToken($token)
                ->contentType('application/json');


            $fullUrl = $this->baseUrl . $endpoint;

            switch (strtoupper($method)) {
                case 'GET':
                    $response = $request->get($fullUrl, $data);
                    break;
                case 'POST':
                    $response = $request->post($fullUrl, $data);
                    break;
                case 'PUT':
                    $response = $request->put($fullUrl, $data);
                    break;
                case 'PATCH':
                    $response = $request->patch($fullUrl, $data);
                    break;
                case 'DELETE':
                    $response = $request->delete($fullUrl, $data);
                    break;
                default:
                    throw new \InvalidArgumentException("Method $method tidak didukung");
            }

            $executionTime = microtime(true) - $startTime;
            $responseData = $response->json();

            if ($response->successful()) {
                $this->logApiCall(
                    $method,
                    $endpoint,
                    $data,
                    $responseData,
                    $response->status(),
                    'success',
                    null,
                    $executionTime
                );

                return [
                    'success' => true,
                    'message' => 'Request berhasil',
                    'data' => $responseData,
                    'status_code' => $response->status()
                ];
            } else {
                $this->logApiCall(
                    $method,
                    $endpoint,
                    $data,
                    $responseData,
                    $response->status(),
                    'failed',
                    $response->body(),
                    $executionTime
                );

                return [
                    'success' => false,
                    'message' => 'Request gagal: ' . $response->body(),
                    'data' => $responseData,
                    'status_code' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            $executionTime = microtime(true) - $startTime;
            $this->logApiCall(
                $method,
                $endpoint,
                $data,
                null,
                0,
                'error',
                $e->getMessage(),
                $executionTime
            );

            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'data' => null,
                'status_code' => 0
            ];
        }
    }

    /**
     * Shortcut methods untuk common operations
     */
    public function get(string $endpoint, array $params = []): array
    {
        return $this->makeRequest('GET', $endpoint, $params);
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('POST', $endpoint, $data);
    }

    public function put(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('PUT', $endpoint, $data);
    }

    public function patch(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('PATCH', $endpoint, $data);
    }

    public function delete(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('DELETE', $endpoint, $data);
    }

    /**
     * Log API call ke database
     */
    protected function logApiCall(
        string $method,
        string $endpoint,
        array $requestData,
        ?array $responseData,
        int $statusCode,
        string $status,
        ?string $errorMessage,
        float $executionTime
    ): void {
        if (!config('satusehat.logging.enabled', true)) {
            return;
        }

        try {
            SatuSehatLog::create([
                'method' => strtoupper($method),
                'endpoint' => $endpoint,
                'request_data' => $requestData,
                'response_data' => $responseData,
                'status_code' => $statusCode,
                'status' => $status,
                'error_message' => $errorMessage,
                'execution_time' => $executionTime,
                'environment' => $this->environment,
            ]);
        } catch (\Exception $e) {
            $this->log('error', 'Gagal menyimpan log: ' . $e->getMessage());
        }
    }

    /**
     * Log ke file
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        if (!config('satusehat.logging.enabled', true)) {
            return;
        }

        $channel = config('satusehat.logging.channel', 'daily');
        $logLevel = config('satusehat.logging.level', 'info');

        if ($this->shouldLog($level, $logLevel)) {
            Log::channel($channel)->$level('[SatuSehat] ' . $message, $context);
        }
    }

    /**
     * Cek apakah level log harus dicatat
     */
    protected function shouldLog(string $level, string $configLevel): bool
    {
        $levels = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3];
        return $levels[$level] >= $levels[$configLevel];
    }

    /**
     * Get environment yang sedang digunakan
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Set environment
     */
    public function setEnvironment(string $environment): self
    {
        $this->environment = $environment;
        $this->baseUrl = config('satusehat.base_urls.' . $environment);
        return $this;
    }
}
