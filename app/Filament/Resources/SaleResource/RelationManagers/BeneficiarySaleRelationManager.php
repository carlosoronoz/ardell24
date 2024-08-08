<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BeneficiarySaleRelationManager extends RelationManager
{
    protected static string $relationship = 'Beneficiary';
    protected static ?string $title = 'Regalo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fullname')
                    ->required()
                    ->label('Nombre completo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->label('Correo electrónico')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->mask('999 999999999')
                    ->placeholder('598 099999999')
                    ->maxLength(20)
                    ->label('Teléfono'),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->label('Dirección'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Regalo')
            ->description('Detalle del destinatario del regalo')
            ->columns([
                Tables\Columns\TextColumn::make('fullname')
                    ->label('Nombre completo'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono'),
                Tables\Columns\TextColumn::make('address')
                    ->label('Dirección')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //  
            ])
            ->actions([
                //  
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //  
                ]),
            ]);
    }
}
