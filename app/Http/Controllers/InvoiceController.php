<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Mail\AddInvoicesMail;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceDetails;
use App\Models\Product;
use App\Models\Section;
use App\Notifications\Add_Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::with('section')->get();
        return view('invoices.showInvoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('invoices.createInvoices', compact('sections', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'discount' => $request->discount,
            'rate_vat' => $request->rate_vat,
            'value_vat' => $request->value_vat,
            'total' => $request->total,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => Auth::user()->name,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        InvoiceDetails::create([
            'invoice_number' => $request->invoice_number,
            'section' => $request->section,
            'product' => $request->product,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'user' => Auth::user()->name,
            'invoice_id' => $invoice_id
        ]);

        if ($request->hasFile('file_name')) {
            $file_name = $request->file_name;
            $newFileName = $file_name->getClientOriginalName();
            $file_name->move('Files/' . $request->invoice_number, $newFileName);

            InvoiceAttachment::create([
                'invoice_number' => $request->invoice_number,
                'created_by' => Auth::user()->name,
                'invoice_id' => $invoice_id,
                'file_name' => $newFileName
            ]);
        }

        $user = Auth::user();
        $users = Auth::user()->where('id', "!=", $user->id)->get();

        Mail::to($user)->send(new AddInvoicesMail($invoice_id));   // Mail

        Notification::send($users, new Add_Invoices($invoice_id)); // Notification

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $invoices = Invoice::with('section')->find($invoice->id);
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $invoices = Invoice::find($invoice->id);
        $sections = Section::all();
        return view('invoices.editInvoices', compact('invoices', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $invoices = Invoice::find($invoice->id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_vat' => $request->value_vat,
            'rate_vat' => $request->rate_vat,
            'total' => $request->total,
            'note' => $request->note,
        ]);

        session()->flash('Edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        $attachments = InvoiceAttachment::where('invoice_id', $request->invoice_id)->first();
        if ($request->id_page == 2) {  // Archive

            $invoice->delete();
            session()->flash('archeive_invoice');
            return redirect()->route('invoices.index');
        } else {

            if (!empty($attachments->invoice_number)) {
                $directory = public_path("Files/$attachments->invoice_number");
                File::deleteDirectory($directory);
                // unlink('path')  => to delete file inside directories .
            }

            $invoice->forceDelete();
            session()->flash('delete_invoice');
            return redirect()->route('invoices.index');
        }
    }

    public function getProducts($id)
    {
        $products = DB::table('products')->where('section_id', $id)->pluck('title', 'id');
        return json_encode($products);
    }

    public function status_update(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        if ($request->status == 'مدفوعة') {  // 1

            $invoice->update([
                'status' => $request->status,
                'value_status' => 1,
                'payment_date' => $request->payment_date
            ]);

            InvoiceDetails::create([
                'invoice_number' => $request->invoice_number,
                'section' => $request->section_id,
                'product' => $request->product,
                'status' => $request->status,
                'value_status' => 1,
                'invoice_id' => $id,
                'user' => Auth::user()->name,
                'payment_date' => $request->payment_date,
                'note' => $request->note
            ]);
        } else {   // 3

            $invoice->update([
                'status' => $request->status,
                'value_status' => 3,
                'payment_date' => $request->payment_date
            ]);

            InvoiceDetails::create([
                'invoice_number' => $request->invoice_number,
                'section' => $request->section_id,
                'product' => $request->product,
                'status' => $request->status,
                'value_status' => 3,
                'invoice_id' => $id,
                'user' => Auth::user()->name,
                'payment_date' => $request->payment_date,
                'note' => $request->note
            ]);
        }
        session()->flash('status_update');
        return redirect()->route('invoices.index');
    }

    public function invoice_paid()
    {
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function invoice_unpaid()
    {
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }

    public function invoice_partial_paid()
    {
        $invoices = Invoice::where('value_status', 3)->get();
        return view('invoices.invoices_partialpaid', compact('invoices'));
    }

    public function print_invoice($id)
    {
        $invoices = Invoice::find($id);
        return view('invoices.print_invoice', compact('invoices'));
    }

    public function export()
    {
        return Excel::download(new InvoiceExport, 'قائمة الفاتورة.xlsx');
    }

    public function markAsRead()
    {
        $unreadNotifications = Auth::user()->unreadNotifications;
        if ($unreadNotifications) {
            // $unreadNotifications->markAsRead();
            foreach ($unreadNotifications as $notification) {
                $notification->delete();
            }
            return redirect()->back();
        }
    }
}
