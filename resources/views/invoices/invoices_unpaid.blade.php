@extends('layouts.master')
@section('title')
    الفواتير غير المدفوعة
@endsection
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto" style="font-size: 28px !important;">الفواتير/</h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0" style="font-size: 18px !important;"> الفواتير
                    غير المدفوعة </span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if (session()->has('delete_invoice'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم حذف الفاتورة بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif

    @if (session()->has('status_update'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم تحديث حالة الدفع بنجاح",
                    type: "success"
                })
            }
        </script>
    @endif


    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">


                    {{-- <div class="col-sm-6 col-md-4 col-xl-3">
                        <a class="btn btn-primary btn-block" href="{{ route('invoices.create') }}"
                            style="font-size: 18px !important;"> اضافة فاتورة <i class="fa fa-plus"
                                style="font-size: 14px !important;"></i></a>
                    </div> --}}

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th class="wd-9p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">رقم الفاتورة</th>
                                    <th class="wd-15p border-bottom-0">تاريخ الفاتورة</th>
                                    <th class="wd-15p border-bottom-0">تاريخ الأستحقاق</th>
                                    <th class="wd-15p border-bottom-0">المنتج</th>
                                    <th class="wd-15p border-bottom-0">القسم</th>
                                    <th class="wd-9p border-bottom-0">الخصم</th>
                                    <th class="wd-9p border-bottom-0">نسبة الضريبة</th>
                                    <th class="wd-9p border-bottom-0">فيمة الضريبة</th>
                                    <th class="wd-9p border-bottom-0">الاجمالى</th>
                                    <th class="wd-15p border-bottom-0">الحالة</th>
                                    <th class="wd-15p border-bottom-0">العليات</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach ($invoices as $invoice)
                                    <?php $i++; ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td><a
                                                href="{{ route('invoices_details.edit', $invoice->id) }}">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td>{{ $invoice->invoice_date }}</td>
                                        <td>{{ $invoice->due_date }}</td>
                                        <td>{{ $invoice->product }}</td>
                                        <td>{{ $invoice->section->name }}</td>
                                        <td>{{ $invoice->discount }}</td>
                                        <td>{{ $invoice->rate_vat }}</td>
                                        <td>{{ $invoice->value_vat }}</td>
                                        <td>{{ $invoice->total }}</td>
                                        <td>
                                            @if ($invoice->value_status == 1)
                                                <span class="text-success">{{ $invoice->status }}</span>
                                            @elseif ($invoice->value_status == 2)
                                                <span class="text-danger">{{ $invoice->status }}</span>
                                            @else
                                                <span class="text-orange">{{ $invoice->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button aria-expanded="false" aria-haspopup="true"
                                                    class="btn ripple btn-primary" data-toggle="dropdown" type="button"
                                                    style="font-size: 16px !important;"> العمليات <i
                                                        class="fas fa-caret-down ml-1"></i></button>
                                                <div class="dropdown-menu tx-13">

                                                    {{-- تعديل الفاتورة --}}
                                                    <a class="dropdown-item"
                                                        style="font-size: 16px !important; border-bottom: 1px solid #ccc;"
                                                        href="{{ route('invoices.edit', $invoice->id) }}"> <i
                                                            class="fa fa-edit text-primary"></i> تعديل الفاتورة </a>


                                                    {{-- حذف الفاتورة --}}
                                                    <a class="dropdown-item" href="#"
                                                        style="font-size: 16px !important; border-bottom: 1px solid #ccc;"
                                                        data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                        data-target="#delete_invoice">
                                                        <i class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp; حذف
                                                        الفاتورة </a>


                                                    {{-- تغير حالة الدفع --}}
                                                    <a class="dropdown-item"
                                                        href="{{ route('invoices.show', $invoice->id) }}"
                                                        style="font-size: 16px !important; border-bottom: 1px solid #ccc;">
                                                        <i class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp; تغيير
                                                        حالة الدفع
                                                    </a>


                                                    {{-- ارشفة الفاتورة --}}
                                                    <a class="dropdown-item" href="#"
                                                        data-invoice_id="{{ $invoice->id }}" data-toggle="modal"
                                                        data-target="#Transfer_invoice"
                                                        style="font-size: 16px !important; border-bottom: 1px solid #ccc;">
                                                        <i class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل
                                                        الي
                                                        الارشيف</a>


                                                    {{-- طباعة الفاتورة --}}
                                                    <a class="dropdown-item" href="print_invoice/{{ $invoice->id }}"
                                                        style="font-size: 16px !important;"><i
                                                            class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                        الفاتورة
                                                    </a>

                                                </div>
                                            </div>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>


        <!-- حذف الفاتورة -->
        <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                            @csrf
                            @method('delete')
                    </div>
                    <div class="modal-body">
                        هل انت متاكد من عملية الحذف ؟
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ارشيف الفاتورة -->
        <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form action="{{ route('invoices.destroy', 'test') }}" method="post">
                            @csrf
                            @method('delete')
                    </div>
                    <div class="modal-body">
                        هل انت متاكد من عملية الارشفة ؟
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                        <input type="hidden" name="id_page" id="id_page" value="2">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-success">تاكيد</button>
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
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>

    <script>
        $('#Transfer_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>
@endsection
