<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = '人事管理';
    
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = '員工';

    protected static ?string $pluralModelLabel = '員工管理';

    protected static ?string $navigationLabel = '員工管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('employee_id')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('員工編號'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('姓名'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('電子郵件'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('電話'),
                Forms\Components\TextInput::make('address')
                    ->label('地址'),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('生日'),
                Forms\Components\DatePicker::make('hire_date')
                    ->required()
                    ->label('到職日期'),
                Forms\Components\Select::make('position_id')
                    ->label('職位')
                    ->relationship('position', 'name')
                    ->required(),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('部門'),
                Forms\Components\TextInput::make('salary')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('薪資'),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'active' => '在職',
                        'inactive' => '離職',
                        'on_leave' => '請假中',
                    ])
                    ->native(false)
                    ->default('active')
                    ->label('狀態'),
                Forms\Components\Textarea::make('notes')
                    ->label('備註'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_id')
                    ->searchable()
                    ->sortable()
                    ->label('員工編號'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('姓名'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('電子郵件'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->label('電話'),
                Tables\Columns\TextColumn::make('position.name')
                    ->searchable()
                    ->sortable()
                    ->label('職位'),
                Tables\Columns\TextColumn::make('department.name')
                    ->searchable()
                    ->sortable()
                    ->label('部門'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'on_leave' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => '在職',
                        'inactive' => '離職',
                        'on_leave' => '請假中',
                    })
                    ->label('狀態'),
                Tables\Columns\TextColumn::make('hire_date')
                    ->date('Y-m-d')
                    ->sortable()
                    ->label('到職日期'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('建立時間'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('更新時間'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => '在職',
                        'inactive' => '離職',
                        'on_leave' => '請假中',
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
