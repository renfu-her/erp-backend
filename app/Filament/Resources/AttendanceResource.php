<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationGroup = '人事管理';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = '出勤記錄';

    protected static ?string $pluralModelLabel = '出勤管理';

    protected static ?string $navigationLabel = '出勤管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('員工'),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->label('日期'),
                Forms\Components\TimePicker::make('check_in')
                    ->label('上班時間'),
                Forms\Components\TimePicker::make('check_out')
                    ->label('下班時間'),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'present' => '出席',
                        'absent' => '缺席',
                        'late' => '遲到',
                        'early_leave' => '早退',
                    ])
                    ->label('狀態'),
                Forms\Components\Textarea::make('notes')
                    ->label('備註'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable()
                    ->sortable()
                    ->label('員工'),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->label('日期'),
                Tables\Columns\TextColumn::make('check_in')
                    ->time()
                    ->sortable()
                    ->label('上班時間'),
                Tables\Columns\TextColumn::make('check_out')
                    ->time()
                    ->sortable()
                    ->label('下班時間'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                        'early_leave' => 'warning',
                    })
                    ->label('狀態'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('建立時間'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('更新時間'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => '出席',
                        'absent' => '缺席',
                        'late' => '遲到',
                        'early_leave' => '早退',
                    ])
                    ->label('狀態'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
