<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceArcheivController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('/sections', SectionController::class);

Route::resource('/products', ProductController::class);

Route::resource('/invoices', InvoiceController::class);

Route::resource('/Archeiv', InvoiceArcheivController::class);

Route::get('/section/{id}', [InvoiceController::class, 'getProducts']);  // get all products which belongs to section AJAX.

Route::get('/status_update/{id}', [InvoiceController::class, 'status_update'])->name('status_update');

Route::resource('/invoices_details', InvoiceDetailsController::class);

Route::resource('/invoice_attachments', InvoiceAttachmentController::class);

Route::get('/view_file/{invoice_number}/{file_name}', [InvoiceDetailsController::class, 'view_file'])->name('view_file');

Route::get('/download_file/{invoice_number}/{file_name}', [InvoiceDetailsController::class, 'download_file'])->name('download_file');

Route::post('/delete_file', [InvoiceDetailsController::class, 'destroy'])->name('delete_file');

// Invoices Paids / unPaids / Partial Paids  .
Route::get('/invoice_paid', [InvoiceController::class, 'invoice_paid'])->name('invoice_paid');
Route::get('/invoice_unpaid', [InvoiceController::class, 'invoice_unpaid'])->name('invoice_unpaid');
Route::get('/invoice_partial_paid', [InvoiceController::class, 'invoice_partial_paid'])->name('invoice_partial_paid');


Route::get('/print_invoice/{id}', [InvoiceController::class, 'print_invoice'])->name('print_invoice');

Route::get('invoice_export', [InvoiceController::class, 'export'])->name('invoice_export');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

Route::get('/invoices_report', [InvoicesReportController::class, 'index'])->name('invoices_report');
Route::post('/Search_invoices', [InvoicesReportController::class, 'Search_invoices'])->name('Search_invoices');

Route::get('/customers_report', [InvoicesReportController::class, 'index'])->name('customers_report');
Route::post('/Search_customers', [InvoicesReportController::class, 'Search_customers'])->name('Search_customers');

Route::get('/markAsRead', [InvoiceController::class, 'markAsRead'])->name('markAsRead');

Route::get('/{page_id}', [AdminController::class, 'index']);
