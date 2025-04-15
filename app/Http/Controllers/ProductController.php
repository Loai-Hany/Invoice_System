<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('section')->get();
        $sections = Section::all();
        return view('products.showProducts', compact('products', 'sections'));
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
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'section_id' => 'required|exists:sections,id',
        ], [
            'title.required' => 'يرجى ادخال اسم المنتج',
            'description.required' => 'يرجى ادخال اى ملاحظات عن المنتج',
            'section_id.required' => 'يرجى اختيار قسم من الأقسام المقترحة لديكم!',

        ]);

        $products_data = $request->all();
        Product::create($products_data);
        session()->flash('Add', 'تم اضافة المنتج بنجاح');
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'section_id' => 'required|exists:sections,id',
        ], [
            'title.required' => 'يرجى ادخال اسم المنتج',
            'description.required' => 'يرجى ادخال اى ملاحظات عن المنتج',
            'section_id.required' => 'يرجى اختيار قسم من الأقسام المقترحة لديكم!',

        ]);
        $product = Product::find($request->id);
        $product->update($request->all());
        session()->flash('Edit', 'تم التعديل على بيانات المنتج بنجاح');
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Product::find($request->id)->delete();
        session()->flash('Delete', 'تم حذف المنتج بنجاح');
        return redirect()->route('products.index');
    }
}
