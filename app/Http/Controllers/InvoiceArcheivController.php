<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class InvoiceArcheivController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archeiv_invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoice = Invoice::withTrashed()->where('id', $request->invoice_id)->restore();
        session()->flash('restore_invoice');
        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoice = Invoice::withTrashed()->where('id', $request->invoice_id)->first();
        $attachments = InvoiceAttachment::where('invoice_id', $request->invoice_id)->first();

        if (!empty($attachments->invoice_number)) {
            $directory = public_path("Files/$attachments->invoice_number");
            File::deleteDirectory($directory);
            // unlink('path')  => to delete file inside directories .
        }
        $invoice->forceDelete();
        session()->flash('delete_invoice');
        return redirect()->route('Archeiv.index');
    }
}
