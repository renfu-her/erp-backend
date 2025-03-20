<?php

namespace App\Filament\Resources\PayrollResource\Pages;

use App\Filament\Resources\PayrollResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPayroll extends EditRecord
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading('刪除薪資')
                ->modalDescription('確定要刪除此薪資記錄嗎？此操作無法復原。')
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
            ->title('薪資已更新')
            ->body('薪資資料已成功更新。');
    }

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('薪資已刪除')
            ->body('薪資資料已成功刪除。');
    }
}
