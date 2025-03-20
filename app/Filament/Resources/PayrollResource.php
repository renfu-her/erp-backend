<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Payroll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationGroup = '人事管理';
    
    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = '薪資記錄';

    protected static ?string $pluralModelLabel = '薪資管理';

    protected static ?string $navigationLabel = '薪資管理';

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
                Forms\Components\DatePicker::make('pay_date')
                    ->required()
                    ->label('發薪日期'),
                Forms\Components\TextInput::make('basic_salary')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('基本薪資'),
                Forms\Components\TextInput::make('overtime_pay')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->label('加班費'),
                Forms\Components\TextInput::make('bonus')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->label('獎金'),
                Forms\Components\TextInput::make('deductions')
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->label('扣除額'),
                Forms\Components\TextInput::make('net_salary')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('實發薪資'),
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
                Tables\Columns\TextColumn::make('pay_date')
                    ->date()
                    ->sortable()
                    ->label('發薪日期'),
                Tables\Columns\TextColumn::make('basic_salary')
                    ->money('TWD')
                    ->sortable()
                    ->label('基本薪資'),
                Tables\Columns\TextColumn::make('overtime_pay')
                    ->money('TWD')
                    ->sortable()
                    ->label('加班費'),
                Tables\Columns\TextColumn::make('bonus')
                    ->money('TWD')
                    ->sortable()
                    ->label('獎金'),
                Tables\Columns\TextColumn::make('deductions')
                    ->money('TWD')
                    ->sortable()
                    ->label('扣除額'),
                Tables\Columns\TextColumn::make('net_salary')
                    ->money('TWD')
                    ->sortable()
                    ->label('實發薪資'),
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
                //
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
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayroll::route('/create'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}
