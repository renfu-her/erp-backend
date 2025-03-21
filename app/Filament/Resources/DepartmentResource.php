<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationGroup = '組織管理';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = '部門';

    protected static ?string $pluralModelLabel = '部門';

    protected static ?string $navigationLabel = '部門管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('部門名稱')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('部門代碼')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('部門描述')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('manager_id')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->preload()
                    ->label('部門主管'),
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
                    ->label('部門名稱')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('部門代碼')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->searchable()
                    ->sortable()
                    ->label('部門主管'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('是否啟用')
                    ->boolean(),
                Tables\Columns\TextColumn::make('employees_count')
                    ->counts('employees')
                    ->label('員工數'),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
