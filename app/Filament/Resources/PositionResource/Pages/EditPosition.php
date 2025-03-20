<?php

namespace App\Filament\Resources\PositionResource\Pages;

use App\Filament\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading('刪除職位')
                ->modalDescription('確定要刪除此職位嗎？此操作無法復原。')
                ->modalSubmitActionLabel('確定刪除')
                ->modalCancelActionLabel('取消'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('職位已更新')
            ->body('職位資料已成功更新。');
    }

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('職位已刪除')
            ->body('職位資料已成功刪除。');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['benefits'] = array_map(function($benefit) {
            return $benefit['benefit'];
        }, $data['benefits'] ?? []);

        $data['requirements'] = array_map(function($requirement) {
            return $requirement['requirement'];
        }, $data['requirements'] ?? []);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['benefits'] = array_map(function($benefit) {
            return ['benefit' => $benefit];
        }, $data['benefits'] ?? []);

        $data['requirements'] = array_map(function($requirement) {
            return ['requirement' => $requirement];
        }, $data['requirements'] ?? []);

        return $data;
    }
}
