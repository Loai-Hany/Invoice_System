<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('reports.customers_report', compact('sections'));
    }

    public function Search_customers(Request $request)
    {
        // بحث فى حالة عدم وجود تاريخ
        if ($request->section_id && $request->product && $request->start_at == '' && $request->end_at == '') {
            $invoices = Invoice::select('*')->where('section_id', $request->section_id)->where('product', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->with('invoices', $invoices);
        }
        // بحث فى حالة وجود تاريخ
        else {
            $start_at = $request->start_at;
            $end_at = $request->end_at;
            $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->where('section_id', $request->section_id)->where('product', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->with('invoices', $invoices);
        }
    }
}
