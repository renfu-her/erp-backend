<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;
use App\Filament\Resources\LeaveResource\RelationManagers;
use App\Models\Leave;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationGroup = '人事管理';
    
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = '請假記錄';

    protected static ?string $pluralModelLabel = '請假管理';

    protected static ?string $navigationLabel = '請假管理';

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
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'annual' => '年假',
                        'sick' => '病假',
                        'personal' => '事假',
                        'maternity' => '產假',
                        'paternity' => '陪產假',
                        'bereavement' => '喪假',
                        'other' => '其他',
                    ])
                    ->label('請假類型'),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('開始日期'),
                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->label('結束日期'),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->label('請假原因'),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending' => '待審核',
                        'approved' => '已核准',
                        'rejected' => '已拒絕',
                    ])
                    ->label('狀態'),
                Forms\Components\Textarea::make('rejection_reason')
                    ->label('拒絕原因')
                    ->visible(fn (Forms\Get $get) => $get('status') === 'rejected'),
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
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'annual' => 'success',
                        'sick' => 'danger',
                        'personal' => 'warning',
                        'maternity' => 'info',
                        'paternity' => 'info',
                        'bereavement' => 'gray',
                        'other' => 'gray',
                    })
                    ->label('請假類型'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->label('開始日期'),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->label('結束日期'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
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
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'annual' => '年假',
                        'sick' => '病假',
                        'personal' => '事假',
                        'maternity' => '產假',
                        'paternity' => '陪產假',
                        'bereavement' => '喪假',
                        'other' => '其他',
                    ])
                    ->label('請假類型'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => '待審核',
                        'approved' => '已核准',
                        'rejected' => '已拒絕',
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
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}
