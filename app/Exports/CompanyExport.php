<?php

namespace App\Exports;

use App\Models\Company;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompanyExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('exports.companies', [
            'companies' => Company::all()
        ]);
    }
}
