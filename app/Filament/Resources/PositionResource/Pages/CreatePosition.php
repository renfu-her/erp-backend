<?php

namespace App\Filament\Resources\PositionResource\Pages;

use App\Filament\Resources\PositionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePosition extends CreateRecord
{
    protected static string $resource = PositionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('職位已建立')
            ->body('職位資料已成功儲存。');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['benefits'] = array_map(function($benefit) {
            return $benefit['benefit'];
        }, $data['benefits'] ?? []);

        $data['requirements'] = array_map(function($requirement) {
            return $requirement['requirement'];
        }, $data['requirements'] ?? []);

        return $data;
    }
}
