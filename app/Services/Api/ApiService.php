<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class ApiService
{
    protected string $baseUrl = 'https://api-dev.besttour.com.tw/api';
    protected ?string $token = null;
    
    public function login(string $id, string $password): bool
    {
        $response = Http::withoutVerifying()
            ->post("{$this->baseUrl}/auth/login", [
                'id' => $id,
                'pw' => $password,
            ]);
        
        if ($response->successful() && $response->json('code') === '00') {
            $this->token = $response->json('data.token');
            return true;
        }
        
        return false;
    }
    
    protected function getHttp(): PendingRequest
    {
        return Http::withoutVerifying()
            ->withHeaders([
                'Authorization' => "Bearer {$this->token}",
            ])
            ->baseUrl($this->baseUrl);
    }
} 