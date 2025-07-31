<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportingMensuelExport;


class ExportReportingController extends Controller
{
    public function export(Request $request)
    {
        $annee = $request->input('annee');
        $mois = $request->input('mois');

        $filename = 'reporting-' . $mois . '-' . $annee . '.xlsx';

        return Excel::download(new ReportingMensuelExport($annee, $mois), $filename);
    }
}
