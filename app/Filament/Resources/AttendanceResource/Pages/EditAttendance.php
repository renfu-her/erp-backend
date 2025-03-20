<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAttendance extends EditRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading('刪除考勤')
                ->modalDescription('確定要刪除此考勤記錄嗎？此操作無法復原。')
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
            ->title('考勤已更新')
            ->body('考勤資料已成功更新。');
    }

    protected function getDeletedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('考勤已刪除')
            ->body('考勤資料已成功刪除。');
    }
}
