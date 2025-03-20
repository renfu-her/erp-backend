<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PositionResource\Pages;
use App\Filament\Resources\PositionResource\RelationManagers;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    
    protected static ?string $navigationGroup = '組織管理';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = '職位';
    
    protected static ?string $pluralModelLabel = '職位';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('職位名稱')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('職位代碼')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('職位描述')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('base_salary')
                    ->label('基本薪資')
                    ->numeric()
                    ->required(),
                Forms\Components\Repeater::make('benefits')
                    ->label('福利項目')
                    ->schema([
                        Forms\Components\TextInput::make('benefit')
                            ->label('福利')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('requirements')
                    ->label('任職要求')
                    ->schema([
                        Forms\Components\TextInput::make('requirement')
                            ->label('要求')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('是否啟用')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('職位名稱')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('職位代碼')
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_salary')
                    ->label('基本薪資')
                    ->money('twd')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('是否啟用')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('是否啟用'),
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
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }
}
