<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Models\Sale;
use Filament\Actions;
use App\Filament\Resources\SaleResource;
use App\Traits\NotifyWhatsapp;
use App\Traits\PdfGenerate;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    use NotifyWhatsapp, PdfGenerate;

    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('delete')
                ->label('Anular')
                ->action(fn (Sale $record) => $record->update(['status' => false, 'state' => 'Cancelado']))
                ->visible(fn (Sale $record): bool => $record->state != 'Aprobado')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-trash')
                ->after(function () {
                    redirect()->route('filament.admin.resources.sales.index');
                })
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        if ($record->state === 'Aprobado' && $record->num_transaction) {
            try {
                $this->ticketPdf($record);
                $data = [
                    'phone' => $record->Payer->phone,
                    'pdf' => route('home') . '/storage/pdf/tickets/' . $record->num_document . '.pdf',
                    'fullname' => $record->Payer->name . ' ' . $record->Payer->surname
                ];
                $this->edbTicketOrder($data); //client

            } catch (\Throwable $th) {
            }
        }
    }
}
