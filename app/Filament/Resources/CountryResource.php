<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\Api\CountryService;
use App\Models\Api\Country;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = '系統管理';
    protected static ?string $modelLabel = '國家管理';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('continent_id')
                    ->label('洲別')
                    ->options([
                        1 => '非洲',
                        2 => '亞洲',
                        3 => '歐洲',
                        4 => '北美洲',
                        5 => '大洋洲',
                        6 => '南美洲',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('國家名稱')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('en_name')
                    ->label('英文名稱')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code3')
                    ->label('三位字母代碼')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('tel_area')
                    ->label('電話區號')
                    ->required()
                    ->maxLength(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('國家名稱'),
                Tables\Columns\TextColumn::make('e_name')
                    ->label('英文名稱'),
                Tables\Columns\TextColumn::make('code.three')
                    ->label('三位字母代碼'),
                Tables\Columns\TextColumn::make('tel_area')
                    ->label('電話區號'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->action(function (Country $record) {
                        $service = app(CountryService::class);
                        $service->login('08116', 'Hezrid5@');
                        $service->delete($record->id);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
        ];
    }
} 