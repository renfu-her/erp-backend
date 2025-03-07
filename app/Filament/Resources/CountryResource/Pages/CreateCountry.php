<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\Api\CountryService;
use App\Models\Api\Country;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;
    
    protected function handleRecordCreation(array $data): Country
    {
        $service = app(CountryService::class);
        $service->login('08116', 'Hezrid5@');
        
        $result = $service->create([
            'continent_id' => $data['continent_id'],
            'name' => $data['name'],
            'en_name' => $data['en_name'],
            'code3' => $data['code3'],
            'tel_area' => $data['tel_area'],
        ]);
        
        if (!$result) {
            $this->halt();
        }
        
        return $result;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 