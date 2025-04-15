@extends('layouts.master')
@section('css')
@endsection
@section('title')
    تغير حالة الدفع
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto" style="font-size: 28px !important;">الفواتير</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0" style="font-size: 18px !important;">/
                    تغير حالة الدفع</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('status_update', $invoices->id) }}" method="get" autocomplete="off">
                        @csrf
                        {{-- 1 --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="invoice_number" class="control-label">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                    value="{{ $invoices->invoice_number }}" required readonly>
                            </div>

                            <div class="col-md-4">
                                <label>تاريخ الفاتورة</label>
                                <input type="date" class="form-control fc-datepicker" name="invoice_date"
                                    value="{{ $invoices->invoice_date }}" required readonly>
                            </div>

                            <div class="col-md-4">
                                <label>تاريخ الاستحقاق</label>
                                <input type="date" class="form-control fc-datepicker" name="due_date"
                                    value="{{ $invoices->due_date }}" required readonly>
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row my-2">
                            <div class="col-md-4">
                                <label for="section_id" class="control-label">القسم</label>
                                <select name="section_id" class="form-control">
                                    <!--placeholder-->
                                    <option value=" {{ $invoices->section->id }}">
                                        {{ $invoices->section->name }}
                                    </option>

                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="product" class="control-label">المنتج</label>
                                <select id="product" name="product" class="form-control" readonly>
                                    <option value="{{ $invoices->product }}"> {{ $invoices->product }}</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="amount_collection" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control" id="amount_collection-md-4"
                                    name="amount_collection" value="{{ $invoices->amount_collection }}" readonly>
                            </div>
                        </div>


                        {{-- 3 --}}

                        <div class="row my-2">

                            <div class="col-md-4">
                                <label for="amount_commission" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="amount_commission"
                                    value="{{ $invoices->amount_commission }}" required readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="discount" name="discount"
                                    value="{{ $invoices->discount }}" required readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="rate_vat" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select name="rate_vat" id="rate_vat" class="form-control" readonly>
                                    <!--placeholder-->
                                    <option value=" {{ $invoices->rate_vat }}">
                                        {{ $invoices->rate_vat }}
                                </select>
                            </div>

                        </div>

                        {{-- 4 --}}

                        <div class="row my-2">
                            <div class="col-md-6">
                                <label for="value_vat" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="value_vat" name="value_vat"
                                    value="{{ $invoices->value_vat }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="total" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="total" name="total" readonly
                                    value="{{ $invoices->total }}">
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="row">
                            <div class="col">
                                <label for="note">ملاحظات</label>
                                <textarea class="form-control" id="exampleTextarea" name="note" rows="3" readonly>
                                {{ $invoices->note }}</textarea>
                            </div>
                        </div><br>

                        <div class="row my-2">
                            <div class="col-md-6">
                                <label for="status">حالة الدفع</label>
                                <select class="form-control" name="status" id="status" required
                                    style="font-size: 16px !important;">
                                    <option selected disabled>-- حدد حالة الدفع --</option>
                                    <option value="مدفوعة">مدفوعة</option>
                                    <option value="مدفوعة جزئيا">مدفوعة جزئيا</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>تاريخ الدفع</label>
                                <input type="date" class="form-control fc-datepicker" name="payment_date" required
                                    style="font-size: 16px !important;">
                            </div>

                        </div><br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary tx-16">تحديث حالة الدفع</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>


@endsection
