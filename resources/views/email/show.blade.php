<x-mail::message>
    # Hello Dear, {{ Auth::user()->name }}

    تم اضافة فاتورة جديدة على برنامج أدرة الفواتير
    <x-mail::button :url="'http://localhost:8000/invoices'">
        عرض الفاتورة
    </x-mail::button>

    شكرا,<br>
    {{ config('app.name') }}
</x-mail::message>
