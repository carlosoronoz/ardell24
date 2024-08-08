<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Banner;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BannerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BannerResource\RelationManagers;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Banners';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banners';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form            
            ->schema([
                Group::make()->schema([
                    Section::make('Información del banner')
                        ->description('Detalle general del banner')
                        ->icon('heroicon-m-pencil-square')
                        ->schema([
                            Forms\Components\Select::make('type')
                                ->required()
                                ->columnSpanFull()
                                ->label('Tipo de banner')
                                ->options([
                                    'Home' => 'Home'
                                ]),
                            Forms\Components\TextInput::make('title')
                                ->label('Título'),
                            Forms\Components\TextInput::make('subtitle')
                                ->label('Subtitulo'),
                            Forms\Components\TextInput::make('url')
                                ->label('Url')
                                ->columnSpanFull()
                        ])->columns(2),

                    Section::make('Imagen')
                        ->description('Imagen del banner')
                        ->icon('heroicon-m-photo')
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->required()
                                ->disk('public')
                                ->directory('image-banners')
                                ->visibility('private')
                                ->label('Imagen')
                        ])
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make('Disponibilidad')
                        ->description('Disponibilidad y condición del producto')
                        ->icon('heroicon-m-eye')
                        ->schema([
                            Forms\Components\Toggle::make('status')
                                ->required()
                                ->label('Estatus')
                                ->onColor('success')
                                ->offColor('danger')
                                ->default(true)
                        ])
                ])
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de banner')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Título del banner')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen')
                    ->square(),
                Tables\Columns\ToggleColumn::make('status')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger')
                    ->label('Estatus'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        '1' => 'Activado',
                        '0' => 'Desactivado',
                    ])
                    ->label('Estatus')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
