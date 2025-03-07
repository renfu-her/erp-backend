<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Services\Api\CountryService;
use Illuminate\Database\Eloquent\Collection;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    public function getTableRecords(): Collection
    {
        $service = app(CountryService::class);
        $service->login('08116', 'Hezrid5@');
        
        return new Collection($service->list()->all());
    }
} 