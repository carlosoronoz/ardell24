<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use App\Models\Payer;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SaleResource\RelationManagers;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Ventas';

    protected static ?string $modelLabel = 'Venta';

    protected static ?string $pluralModelLabel = 'Ventas';

    protected static ?string $recordTitleAttribute = 'num_document';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Información de la venta')
                        ->description('Detalle general de la venta')
                        ->icon('heroicon-m-pencil-square')
                        ->schema([
                            Forms\Components\TextInput::make('type_document')
                                ->required()
                                ->label('Tipo de documento')
                                ->disabled(),
                            Forms\Components\TextInput::make('payer_name')
                                ->label('Cliente')
                                ->disabled()
                                ->afterStateHydrated(function ($set, $state, $record) {
                                    $payer = $record->Payer;
                                    if ($payer) {
                                        $set('payer_name', $payer->name . ' ' . $payer->surname);
                                    }
                                }),
                            Forms\Components\TextInput::make('payer_phone')
                                ->label('Teléfono')
                                ->disabled()
                                ->afterStateHydrated(function ($set, $state, $record) {
                                    $payer = $record->Payer;
                                    if ($payer) {
                                        $set('payer_phone', $payer->phone);
                                    }
                                }),
                            Forms\Components\TextInput::make('payer_address')
                                ->label('Dirección')
                                ->disabled()
                                ->afterStateHydrated(function ($set, $state, $record) {
                                    $payer = $record->Payer;
                                    if ($payer) {
                                        $set('payer_address', $payer->department . ' - ' . $payer->location) . ' - ' . $payer->address;
                                    }
                                }),
                            Forms\Components\DatePicker::make('date_document')
                                ->label('Fecha del documento')
                                ->disabled()
                                ->default(Date('d-m-Y')),
                            Forms\Components\TextInput::make('num_document')
                                ->disabled()
                                ->label('Nº documento')
                        ])->columns(2),

                    Section::make('Pago')
                        ->description('Método de pago de la venta')
                        ->icon('heroicon-m-credit-card')
                        ->schema([
                            Forms\Components\Select::make('type_operation')
                                ->required()
                                ->live()
                                ->label('Tipo de operación')
                                ->options([
                                    'Mercadopago' => 'Mercadopago',
                                    'Transferencia' => 'Transferencia',
                                ])
                                ->default('Mercadopago'),
                            Forms\Components\TextInput::make('num_transaction')
                                ->label('Nº transacción'),
                            Forms\Components\TextInput::make('total_amount')
                                ->required()
                                ->numeric()
                                ->prefix('$ ')
                                ->step(0.02)
                                ->label('Monto total'),
                            Forms\Components\TextInput::make('preference_id')
                                ->required(fn (Get $get): bool => $get('type_operation') == 'Mercadopago')
                                ->visible(fn (Get $get): bool => $get('type_operation') == 'Mercadopago')
                                ->label('Id de preferencia de mercadopago'),
                            Forms\Components\TextInput::make('preference_url')
                                ->required(fn (Get $get): bool => $get('type_operation') == 'Mercadopago')
                                ->visible(fn (Get $get): bool => $get('type_operation') == 'Mercadopago')
                                ->label('Url de preferencia de mercadopago'),
                            Forms\Components\TextInput::make('payment_id')
                                ->required(fn (Get $get): bool => $get('type_operation') == 'Mercadopago')
                                ->visible(fn (Get $get): bool => $get('type_operation') == 'Mercadopago')
                                ->label('Id del pago de mercadopago')
                        ])->columns(2)

                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Observaciones')
                        ->description('Notas adicionales del cliente')
                        ->icon('heroicon-m-chat-bubble-bottom-center-text')
                        ->schema([
                            Forms\Components\Textarea::make('notes')
                                ->columnSpanFull()
                                ->label('Notas')
                        ]),

                    Section::make('Estatus')
                        ->description('Estatus de la venta y envío')
                        ->icon('heroicon-m-eye')
                        ->schema([
                            Forms\Components\ToggleButtons::make('state')
                                ->required()
                                ->label('Estado del documento')
                                ->inline()
                                ->options([
                                    'Pendiente' => 'Pendiente',
                                    'Procesando' => 'Procesando',
                                    'Aprobado' => 'Aprobado',
                                ])
                                ->colors([
                                    'Pendiente' => 'danger',
                                    'Procesando' => 'warning',
                                    'Aprobado' => 'success',
                                ])
                                ->icons([
                                    'Pendiente' => 'heroicon-o-clock',
                                    'Procesando' => 'heroicon-o-arrow-path',
                                    'Aprobado' => 'heroicon-o-check-circle',
                                ])
                                ->default('Pendiente'),
                            Forms\Components\Hidden::make('status')
                                ->label('Estatus del documento')
                                ->required()
                                ->default(true)
                        ])
                ])

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', true)->where('state', '!=', 'Cancelado'))
            ->columns([
                Tables\Columns\TextColumn::make('type_documento')
                    ->label('Tipo de documento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Payer.name')
                    ->label('Nombre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Payer.surname')
                    ->label('Apellido')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_operation')
                    ->label('Tipo de operación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('num_document')
                    ->label('Nº del documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_document')
                    ->date('d-m-Y')
                    ->label('Fecha')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->prefix('$ ')
                    ->money('UYU')
                    ->label('Total'),
                Tables\Columns\TextColumn::make('state')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Anulado' => 'danger',
                        'Pendiente' => 'danger',
                        'Procesando' => 'warning',
                        'Aprobado' => 'success',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Anulado' => 'heroicon-o-clock',
                        'Pendiente' => 'heroicon-o-clock',
                        'Procesando' => 'heroicon-o-arrow-path',
                        'Aprobado' => 'heroicon-o-check-circle',
                    })
                    ->label('Estado'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DetailSaleRelationManager::class,
            RelationManagers\BeneficiarySaleRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
