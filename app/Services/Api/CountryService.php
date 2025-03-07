<?php

namespace App\Services\Api;

use App\Models\Api\Country;
use Illuminate\Support\Collection;

class CountryService extends ApiService
{
    public function list(): Collection
    {
        $response = $this->getHttp()->get('/information/country');
        
        if ($response->successful() && $response->json('code') === '00') {
            return collect($response->json('data'))->flatMap(function ($continent) {
                return collect($continent['country'])->map(fn ($country) => 
                    Country::fromApiResponse($country, $continent['id'])
                );
            });
        }
        
        return collect();
    }
    
    public function create(array $data): ?Country
    {
        $response = $this->getHttp()->post('/information/country', [
            'continent_id' => $data['continent_id'],
            'name' => $data['name'],
            'en_name' => $data['en_name'],
            'code3' => $data['code3'],
            'tel_area' => $data['tel_area'],
        ]);
        
        if ($response->successful() && $response->json('code') === '00') {
            $data = $response->json('data');
            return Country::fromApiResponse($data, $data['continent_id']);
        }
        
        return null;
    }
    
    public function delete(int $id): bool
    {
        $response = $this->getHttp()->delete("/information/country/{$id}");
        
        return $response->successful() && $response->json('code') === '00';
    }
} 