<?php

namespace App\Traits;

use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;

trait PdfGenerate
{
    public function orderPdf($sale)
    {
        $company = Company::first();
        $pdf = Pdf::loadView('pdf.order', ['sale' => $sale, 'company' => $company]);
        $filename = $sale->num_document . '.pdf';
        $pdf->save('storage/pdf/orders/' . $filename);
    }

    public function ticketPdf($sale)
    {
        $company = Company::first();
        $pdf = Pdf::loadView('pdf.ticket', ['sale' => $sale, 'company' => $company]);
        $filename = $sale->num_document . '.pdf';
        $pdf->save('storage/pdf/tickets/' . $filename);
    }
}
