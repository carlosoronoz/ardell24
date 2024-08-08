<?php

namespace App\Filament\Resources;

use App\Models\Tag;
use Filament\Forms;
use Filament\Tables;
use App\Models\Brand;
use App\Models\Gender;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-plus';

    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        // Obtener los tags desde la base de datos
        $tags = Tag::pluck('name')->toArray();

        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Información del producto')
                        ->description('Detalle general del producto')
                        ->icon('heroicon-m-pencil-square')
                        ->schema([
                            Forms\Components\TextInput::make('reference')
                                ->required()
                                ->unique(Product::class, 'reference', ignoreRecord: true)
                                ->label('Código del producto'),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $operation, ?string $state, Set $set) {
                                    if ($operation !== 'create') {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                })
                                ->label('Nombre del producto'),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique(Product::class, 'slug', ignoreRecord: true)
                                ->readOnly()
                                ->label('Slug'),
                            Forms\Components\TagsInput::make('tags')
                                ->separator(',')
                                ->suggestions($tags)
                                ->label('Tags'),
                            Forms\Components\MarkdownEditor::make('detail')
                                ->required()
                                ->disableToolbarButtons([
                                    'attachFiles',
                                ])
                                ->columnSpanFull()
                                ->label('Detalle'),
                            Forms\Components\MarkdownEditor::make('indication')
                                ->disableToolbarButtons([
                                    'attachFiles',
                                ])
                                ->columnSpanFull()
                                ->label('Indicaciones')
                        ])->columns(2),

                    Section::make('Imagenes')
                        ->description('Imagenes del producto')
                        ->icon('heroicon-m-photo')
                        ->schema([
                            Forms\Components\FileUpload::make('images')
                                ->image()
                                ->multiple()
                                ->required()
                                ->maxFiles(5)
                                ->reorderable()
                                ->columnSpanFull()
                                ->disk('public')
                                ->directory('image-products')
                                ->visibility('private')
                                ->label('Imagenes')

                        ])

                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Precio y stock')
                        ->description('Precio de venta, descuento e inventario')
                        ->icon('heroicon-m-currency-dollar')
                        ->schema([
                            Forms\Components\TextInput::make('professional_amount')
                                ->numeric()
                                ->prefix('$ ')
                                ->suffix('UYU')
                                ->step(0.02)
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $operation, ?string $state, Set $set) {
                                    $set('essential_amount', ($state * 1.4));
                                })
                                ->label('Precio profesional'),
                            Forms\Components\TextInput::make('essential_amount')
                                ->numeric()
                                ->prefix('$ ')
                                ->suffix('UYU')
                                ->step(0.02)
                                ->required()
                                ->label('Precio general'),
                            Forms\Components\TextInput::make('stock')
                                ->required()
                                ->numeric()
                                ->label('Cantidad'),
                            Forms\Components\TextInput::make('discount')
                                ->numeric()
                                ->helperText('Del 0 al 100')
                                ->label('Descuento')

                        ]),

                    Section::make('Categoria')
                        ->description('Categoria del producto')
                        ->icon('heroicon-m-flag')
                        ->schema([
                            Forms\Components\Select::make('brand')
                                ->relationship('Brand')
                                ->options(function (Get $get) {
                                    return Brand::all()->map(function ($brand) {
                                        return [
                                            'value' => $brand->id,
                                            'label' => $brand->name,
                                        ];
                                    })->pluck('label', 'value');
                                })
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('gender_id', null))
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->unique()
                                        ->label('Categoría'),
                                    Forms\Components\FileUpload::make('image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('image-brands')
                                        ->visibility('private')
                                        ->label('Imagen')
                                ])
                                ->label('Categoría'),
                            Forms\Components\Select::make('gender_id')
                                ->required()
                                ->relationship('Gender')
                                ->options(function (Get $get) {
                                    return Gender::where('brand_id', $get('brand'))->get()->map(function ($gender) {
                                        return [
                                            'value' => $gender->id,
                                            'label' => $gender->name,
                                        ];
                                    })->pluck('label', 'value');
                                })
                                ->preload()
                                ->searchable()
                                ->label('Sub - categoría')
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->unique()
                                        ->label('Sub - categoría'),
                                    Forms\Components\Hidden::make('brand_id')
                                        ->default(function ($livewire): int {
                                            return $livewire->data['brand'];
                                        })
                                ])
                        ]),

                    Section::make('Disponibilidad')
                        ->description('Disponibilidad y condición del producto')
                        ->icon('heroicon-m-eye')
                        ->schema([
                            Forms\Components\Toggle::make('status')
                                ->required()
                                ->label('Estatus')
                                ->onColor('success')
                                ->offColor('danger')
                                ->default(true),
                            Forms\Components\Toggle::make('status_wa')
                                ->required()
                                ->label('Estatus catálago de whatsapp')
                                ->onColor('success')
                                ->offColor('danger')
                                ->default(false),
                            Forms\Components\Select::make('condition')
                                ->required()
                                ->label('Condición')
                                ->options([
                                    'General' => 'General',
                                    'Nuevo' => 'Nuevo',
                                    'Promoción' => 'Promoción'
                                ])
                                ->default('General')

                        ])
                ])

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Imagenes')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->square(),
                Tables\Columns\TextColumn::make('reference')
                    ->label('Código del producto')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre del producto')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),
                Tables\Columns\TextColumn::make('professional_amount')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->prefix('$ ')
                    ->money('UYU')
                    ->label('Precio profesional')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger')
                    ->label('Estatus')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('status_wa')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger')
                    ->label('Estatus  whatsapp')
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                SelectFilter::make('condition')
                    ->options([
                        'General' => 'General',
                        'Nuevo' => 'Nuevo',
                        'Promoción' => 'Promoción'
                    ])
                    ->label('Condición'),
                SelectFilter::make('status')
                    ->options([
                        '1' => 'Activado',
                        '0' => 'Desactivado',
                    ])
                    ->label('Estatus'),
                SelectFilter::make('status_wa')
                    ->options([
                        '1' => 'Activado',
                        '0' => 'Desactivado',
                    ])
                    ->label('Estatus whatsapp'),
                SelectFilter::make('brand_id')
                    ->preload()
                    ->searchable()
                    ->relationship('Brand', 'name')
                    ->label('Categoría'),
                SelectFilter::make('gender_id')
                    ->preload()
                    ->searchable()
                    ->relationship('Gender', 'name')
                    ->label('Sub - categoría'),


            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['reference', 'name', 'tags'];
    }
}
