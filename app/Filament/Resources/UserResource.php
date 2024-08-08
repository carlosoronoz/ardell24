<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Información del usuario')
                        ->description('Detalle general del usuario')
                        ->icon('heroicon-m-identification')
                        ->schema([
                            Forms\Components\Select::make('type_passport')
                                ->required()
                                ->label('Tipo de documento')
                                ->options([
                                    'Cédula' => 'Cédula',
                                    'Pasaporte' => 'Pasaporte',
                                    'Documento extranjero' => 'Documento extranjero'
                                ]),
                            Forms\Components\TextInput::make('passport')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(50)
                                ->label('Nº documento'),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(50)
                                ->label('Nombre'),
                            Forms\Components\TextInput::make('surname')
                                ->required()
                                ->maxLength(50)
                                ->label('Apellido'),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(180)
                                ->label('Email'),
                            Forms\Components\TextInput::make('phone')
                                ->tel()
                                ->mask('999 999999999')
                                ->placeholder('598 099999999')
                                ->maxLength(20)
                                ->label('Teléfono')
                        ])->columns(2),

                    Section::make('Avatar')
                        ->description('Avatar del usuario')
                        ->icon('heroicon-m-photo')
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->directory('image-users')
                                ->visibility('private')
                                ->label('Avatar')

                        ])

                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Contraseña')
                        ->description('Clave de acceso al sistema')
                        ->icon('heroicon-m-key')
                        ->schema([
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->maxLength(180)
                                ->label('Contraseña')
                        ]),

                    Section::make('Rol')
                        ->description('Permisos de acceso al sistema')
                        ->icon('heroicon-m-shield-check')
                        ->schema([
                            Forms\Components\Select::make('roles')
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->label('Rol')
                                ->default(['2'])
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255)
                                        ->label('Nombre'),
                                    Forms\Components\Select::make('permissions')
                                        ->preload()
                                        ->multiple()
                                        ->relationship('permissions', 'name')
                                        ->required()
                                        ->columnSpanFull()
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->required()
                                                ->unique()
                                                ->maxLength(255)
                                                ->label('Nombre del permiso'),
                                        ])
                                        ->label('Permisos')
                                ])
                        ])
                ])

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type_passport')
                    ->label('Tipo de documento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('passport')
                    ->label('Nº documento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('surname')
                    ->label('Apellido')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Usuarios registrados.';
    }
}
