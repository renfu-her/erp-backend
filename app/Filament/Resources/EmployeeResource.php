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
                Forms\Components\TextInput::make('id_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if (!$state || !$get('birth_date')) return;
                        
                        $birthDate = date('Ymd', strtotime($get('birth_date')));
                        $set('password', $state . $birthDate);
                    })
                    ->label('身份證號碼'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(debounce: 1000)
                    ->validationMessages([
                        'unique' => '此電子郵件已被使用',
                    ])
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create' && $operation !== 'edit') {
                            return;
                        }
                        
                        if (! $state || ! str_contains($state, '@')) {
                            return;
                        }

                        $exists = Employee::where('email', $state)->exists();
                        if ($exists) {
                            $set('email_exists', true);
                        } else {
                            $set('email_exists', false);
                        }
                    })
                    ->suffixIcon(fn ($state, $record) => 
                        $state && str_contains($state, '@') && Employee::where('email', $state)
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                            ->exists()
                            ? 'heroicon-o-x-circle'
                            : 'heroicon-o-check-circle'
                    )
                    ->suffixIconColor(fn ($state, $record) => 
                        $state && str_contains($state, '@') && Employee::where('email', $state)
                            ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                            ->exists()
                            ? 'danger'
                            : 'success'
                    )
                    ->label('電子郵件'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->label('電話'),
                Forms\Components\TextInput::make('address')
                    ->label('地址'),
                Forms\Components\DatePicker::make('birth_date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if (!$state || !$get('id_number')) return;
                        
                        $birthDate = date('Ymd', strtotime($state));
                        $idNumber = $get('id_number');
                        $set('password', $idNumber . $birthDate);
                    })
                    ->label('生日'),
                Forms\Components\DatePicker::make('hire_date')
                    ->required()
                    ->label('到職日期'),
                Forms\Components\Select::make('position_id')
                    ->label('職位')
                    ->relationship('position', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (! $state) {
                            $set('salary', null);
                            return;
                        }
                        
                        $position = \App\Models\Position::find($state);
                        if ($position) {
                            $set('salary', $position->base_salary);
                        }
                    }),
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
                Forms\Components\Select::make('disability_level')
                    ->options([
                        'none' => '無',
                        'mild' => '輕度',
                        'moderate' => '中度',
                        'severe' => '重度',
                        'profound' => '極重度',
                    ])
                    ->default('none')
                    ->native(false)
                    ->label('殘障等級'),
                Forms\Components\TextInput::make('disability_card_number')
                    ->label('殘障手冊號碼')
                    ->hidden(fn (Forms\Get $get): bool => $get('disability_level') === 'none'),
                Forms\Components\DatePicker::make('disability_card_expiry')
                    ->label('殘障手冊有效期限')
                    ->hidden(fn (Forms\Get $get): bool => $get('disability_level') === 'none'),
                Forms\Components\Select::make('health_insurance_grade')
                    ->options([
                        '1' => '第一級（25,250元）',
                        '2' => '第二級（26,400元）',
                        '3' => '第三級（27,600元）',
                        '4' => '第四級（28,800元）',
                        '5' => '第五級（30,300元）',
                        '6' => '第六級（31,800元）',
                        '7' => '第七級（33,300元）',
                        '8' => '第八級（34,800元）',
                        '9' => '第九級（36,300元）',
                        '10' => '第十級（38,200元）',
                        '11' => '第十一級（40,100元）',
                        '12' => '第十二級（42,000元）',
                        '13' => '第十三級（43,900元）',
                        '14' => '第十四級（45,800元）',
                        '15' => '第十五級（48,200元）',
                    ])
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (!$state) return;
                        
                        $grades = [
                            '1' => 25250,
                            '2' => 26400,
                            '3' => 27600,
                            '4' => 28800,
                            '5' => 30300,
                            '6' => 31800,
                            '7' => 33300,
                            '8' => 34800,
                            '9' => 36300,
                            '10' => 38200,
                            '11' => 40100,
                            '12' => 42000,
                            '13' => 43900,
                            '14' => 45800,
                            '15' => 48200,
                        ];
                        
                        $set('health_insurance_amount', $grades[$state] ?? null);
                    })
                    ->label('健保投保等級'),
                Forms\Components\TextInput::make('health_insurance_amount')
                    ->numeric()
                    ->prefix('$')
                    ->readonly()
                    ->label('健保投保金額'),
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
                Forms\Components\Hidden::make('password')
                    ->default('temp'),
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
                Tables\Columns\TextColumn::make('disability_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'none' => 'gray',
                        'mild' => 'info',
                        'moderate' => 'warning',
                        'severe' => 'danger',
                        'profound' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'none' => '無',
                        'mild' => '輕度',
                        'moderate' => '中度',
                        'severe' => '重度',
                        'profound' => '極重度',
                        default => '無',
                    })
                    ->label('殘障等級'),
                Tables\Columns\TextColumn::make('health_insurance_grade')
                    ->formatStateUsing(fn (?string $state): string => $state ? "第{$state}級" : '')
                    ->label('健保等級'),
                Tables\Columns\TextColumn::make('health_insurance_amount')
                    ->money('twd')
                    ->label('投保金額'),
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
        return parent::getEloquentQuery();
    }
}
