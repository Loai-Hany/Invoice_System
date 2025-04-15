<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;


class InvoicesReportController extends Controller
{
    public function index()
    {
        return view('reports.invoices_report');
    }

    public function Search_invoices(Request $request)
    {
        // بحث بنوع الفاتورة
        if ($request->radio == 1) {

            // بحث فى حالة عدم وجود تاريخ
            if ($request->type && $request->start_at == '' && $request->end_at == '') {
                $invoices = Invoice::select('*')->where('value_status', $request->type)->get();
                $type = $request->type;
                return view('reports.invoices_report', compact('type'))->with('invoices', $invoices);
            }
            // بحث فى حالة وجود تاريخ
            else {
                $start_at = $request->start_at;
                $end_at = $request->end_at;
                $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->where('value_status', $request->type)->get();
                $type = $request->type;
                return view('reports.invoices_report', compact('type', 'start_at', 'end_at'))->with('invoices', $invoices);
            }
        }
        // بحث برقم الفاتورة
        else {
            $invoices = Invoice::where('invoice_number', $request->invoice_number)->get();
            return view('reports.invoices_report')->with('invoices', $invoices);
        }
    }
}
