<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceDetails $invoiceDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoice::find($id);
        $details = InvoiceDetails::where('invoice_id', $id)->get();
        $attachments = InvoiceAttachment::where('invoice_id', $id)->get();

        if (Auth::user()->roles_name == ["owner"]) {
            $notification_id = DB::table('notifications')->where('data->invoice_id', $id)->pluck('id');
            DB::table('notifications')->where('id', $notification_id)->update([
                'read_at' => now()
            ]);
            return view('invoices.invoices_details', compact('invoices', 'details', 'attachments'));
        } else {

            return view('invoices.invoices_details', compact('invoices', 'details', 'attachments'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceDetails $invoiceDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceDetails  $invoiceDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        InvoiceAttachment::find($request->id)->delete();
        $invoice_num = $request->invoice_number;
        $file = $request->file_name;
        $filePath = public_path("Files/$invoice_num/$file");
        unlink($filePath);
        session()->flash('Delete', 'تم حذف المرفق بنجاح');
        return redirect()->back();
    }

    public function view_file($invoice_number, $file_name)
    {
        $filePath = public_path("Files/$invoice_number/$file_name");
        return response()->file($filePath);
    }

    public function download_file($invoice_number, $file_name)
    {
        $filePath = public_path("Files/$invoice_number/$file_name");
        return response()->download($filePath);
    }
}
