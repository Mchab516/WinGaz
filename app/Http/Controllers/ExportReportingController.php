<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportingMensuelExport;

class ExportReportingController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(new ReportingMensuelExport($request), 'reporting-filtré.xlsx');
    }
}
