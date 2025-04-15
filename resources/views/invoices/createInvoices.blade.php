@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!---Internal Fileupload css-->
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!---Internal Fancy uploader css-->
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
    <!--Internal Sumoselect css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
    <!--Internal  TelephoneInput css-->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">
@endsection
@section('title')
    اضافة فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto" style="font-size: 28px !important;">الفواتير</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0" style="font-size: 18px !important;">/
                    اضافة فاتورة</span>
            </div>
        </div>
    </div>


    {{-- Handl Errors , Create , Update And Delete Messages --}} '
    @if ($errors->any())
        <div class="alert alert-danger alert-dissmisble fade show w-100 my-1">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endauth

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dissmisble fade show w-100 my-1">
            <strong> {{ session('Add') }} </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('Edit'))
        <div class="alert alert-success alert-dissmisble fade show w-100 my-1">
            <strong> {{ session('Edit') }} </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('Delete'))
        <div class="alert alert-success alert-dissmisble fade show w-100 my-1">
            <strong> {{ session('Delete') }} </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- breadcrumb -->
@endsection
@section('content')

    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body" style="font-size: 17px !important;">


                    <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        {{-- 1 --}}
                        <div class="row">
                            <div class="col-md-4">
                                <label for="invoice_number" class="control-label">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                    title="يرجي ادخال رقم الفاتورة" required>
                            </div>

                            <div class="col-md-4">
                                <label for="invoice_date" class="control-label">تاريخ الفاتورة</label>
                                <input type="date" value="{{ date('Y-m-d') }}" class="form-control"
                                    name="invoice_date" required>
                            </div>

                            <div class="col-md-4">
                                <label for="due_date" class="control-label">تاريخ الاستحقاق</label>
                                <input type="date" class="form-control" name="due_date" required>
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label for="section" class="control-label">القسم</label>
                                <select name="section" class="form-control SlectBox">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد القسم</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"> {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="product" class="control-label">المنتج</label>
                                <select id="product" name="product" class="form-control">
                                    <option value="" selected disabled>حدد المنتج</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->title }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="amount_collection" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control" id="amount_collection"
                                    name="amount_collection" autocomplete="off">
                            </div>
                        </div>

                        {{-- 3 --}}

                        <div class="row mt-2">

                            <div class="col-md-4">
                                <label for="amount_commission" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="amount_commission"
                                    name="amount_commission" title="يرجي ادخال مبلغ العمولة " required
                                    autocomplete="off">
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="discount"
                                    name="discount" title="يرجي ادخال مبلغ الخصم " value=0 required>
                            </div>

                            <div class="col-md-4">
                                <label for="rate_vat" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select name="rate_vat" id="rate_vat" class="form-control" onchange="myFunction()">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد نسبة الضريبة</option>
                                    <option value=" 2%">2%</option>
                                    <option value=" 4%">4%</option>
                                    <option value=" 5%">5%</option>
                                    <option value=" 6%">6%</option>
                                    <option value=" 8%">8%</option>
                                    <option value=" 10%">10%</option>
                                    <option value="12%">12%</option>
                                    <option value="14%">14%</option>
                                    <option value="15%">15%</option>
                                    <option value="16%">16%</option>
                                    <option value="18%">18%</option>
                                    <option value="20%">20%</option>
                                    <option value="22%">22%</option>
                                    <option value="24%">24%</option>
                                    <option value="25%">25%</option>
                                    <option value="26%">26%</option>
                                    <option value="28%">28%</option>
                                    <option value="30%">30%</option>
                                    <option value="32%">32%</option>
                                    <option value="34%">34%</option>
                                    <option value="35%">35%</option>
                                </select>
                            </div>

                        </div>

                        {{-- 4 --}}

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="value_rat" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="value_rat" name="value_vat" readonly>
                            </div>

                            <div class="col-md-6">
                                <label for="total" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="total" name="total" readonly>
                            </div>
                        </div>

                        {{-- 5 --}}
                        <div class="row mt-2">
                            <div class="col">
                                <label for="note">ملاحظات</label>
                                <textarea class="form-control" id="note" name="note" rows="4"></textarea>
                            </div>
                        </div><br>

                        <p class="text-danger">* صيغة المرفق pdf, jpeg , jpg , png </p>
                        <h5 class="card-title">المرفقات</h5>

                        <div class="col-sm-12 col-md-12">
                            <input type="file" name="file_name" class="dropify"
                                accept=".pdf,.jpg, .png, image/jpeg, image/png" data-height="70" />
                        </div><br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary" style="font-size: 16px !important;">حفظ
                                البيانات</button>
                        </div>


                    </form>
                </div>
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
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
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


    <script>
        $(document).ready(function() {
            $('select[name="section"]').on('change', function() {
                var section_id = $(this).val();
                if (section_id) {
                    $.ajax({
                        url: "{{ URL::to('section') }}/" + section_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="product"]').empty();
                            $.each(data, function(key, value) { // (index , ele)
                                $('select[name="product"]').append(`<option vlaue="${value}">${value}</option>`);
                            });
                        },
                    });

                } else {
                    console.log('AJAX load did not work');
                }
            });

        });
    </script>


    <script>
        function myFunction() {

            var amount_commission = parseFloat(document.getElementById("amount_commission").value);
            var discount = parseFloat(document.getElementById("discount").value);
            var rate_vat = parseFloat(document.getElementById("rate_vat").value);

            var amount_commission_after_discount = amount_commission - discount;


            if (typeof amount_commission === 'undefined' || !amount_commission) {

                alert('يرجي ادخال مبلغ العمولة ');

            } else {
                var value_rat = amount_commission_after_discount * rate_vat / 100;

                var total = parseFloat(amount_commission_after_discount + value_rat);

                sumq = parseFloat(value_rat).toFixed(2);

                sumt = parseFloat(total).toFixed(2);

                document.getElementById("value_rat").value = sumq;

                document.getElementById("total").value = sumt;

            }

        }
    </script>


@endsection
