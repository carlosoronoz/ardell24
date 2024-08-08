<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CompanyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyResource\RelationManagers;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Empresa';

    protected static ?string $modelLabel = 'Empresa';

    protected static ?string $pluralModelLabel = 'Empresas';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Información de la empresa')
                        ->description('Detalle general del la empresa')
                        ->icon('heroicon-m-pencil-square')
                        ->schema([
                            Forms\Components\TextInput::make('business_name')
                                ->maxLength(150)
                                ->required()
                                ->label('Nombre de la empresa'),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(150)
                                ->label('Email'),
                            Forms\Components\TextInput::make('phone')
                                ->tel()
                                ->required()
                                ->maxLength(20)
                                ->label('Teléfono'),
                            Forms\Components\TextInput::make('address')
                                ->maxLength(150)
                                ->label('Dirección'),
                            Forms\Components\Textarea::make('notes')
                                ->maxLength(65535)
                                ->columnSpanFull()
                                ->label('Descripción')
                        ])->columns(2),

                    Section::make('Redes sociales')
                        ->description('Principales redes sociales de la empresa')
                        ->icon('heroicon-m-hashtag')
                        ->schema([
                            Forms\Components\TextInput::make('instagram')
                                ->maxLength(150)
                                ->label('Instagram')

                        ]),

                    Section::make('Imagenes')
                        ->description('Imagenes de la empresa')
                        ->icon('heroicon-m-photo')
                        ->schema([
                            Forms\Components\FileUpload::make('logo')
                                ->image()
                                ->disk('public')
                                ->directory('image-company')
                                ->visibility('private')
                                ->label('Logo')

                        ])

                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Credenciales de pago')
                        ->description('Acceso y token de pasarelas de pago')
                        ->icon('heroicon-m-wallet')
                        ->schema([
                            Forms\Components\TextInput::make('credential')
                                ->maxLength(180)
                                ->required()
                                ->label('Token mercadopago'),
                            Forms\Components\TextInput::make('integrator_id')
                                ->required()
                                ->maxLength(180)
                                ->default('dev_3473303deb0c11eb92c30242ac130004')
                                ->label('Id integrador mercadopago'),
                            Forms\Components\Toggle::make('production_mode')
                                ->required()
                                ->label('Modo producción')
                                ->onColor('success')
                                ->offColor('danger')
                                ->default(true),

                        ]),

                    Section::make('Api de whatsapp')
                        ->description('Información de acceso a Whatsapp Api Cloud')
                        ->icon('heroicon-m-device-phone-mobile')
                        ->schema([
                            Forms\Components\TextInput::make('access_token_whatsapp')
                                ->required()
                                ->label('Token whatsapp'),
                            Forms\Components\TextInput::make('business_id')
                                ->maxLength(180)
                                ->required()
                                ->label('Id del negocio'),
                            Forms\Components\TextInput::make('catalog_id')
                                ->maxLength(180)
                                ->required()
                                ->label('Id catálogo de whatsapp'),
                            Forms\Components\TextInput::make('wa_business_id')
                                ->maxLength(180)
                                ->required()
                                ->label('Id de la cuenta del negocio de whatsapp'),
                            Forms\Components\TextInput::make('mobile_id')
                                ->maxLength(180)
                                ->required()
                                ->label('Id teléfono whatsapp'),
                            Forms\Components\TextInput::make('graph_version')
                                ->maxLength(30)
                                ->required()
                                ->label('Version graph')
                        ])

                ])

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->searchable()
                    ->label('Nombre de la empresa'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->label('Teléfono'),
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->square(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCompanies::route('/'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
