<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.showSections', compact('sections'));
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
            'name' => 'required|min:3|max:255|unique:sections,name',
            'description' => 'required',
        ], [
            'name.required' => 'يرجى ادخال اسم القسم',
            'name.unique' => 'القسم موجود مسبقا يرجى تغيير اسم القسم',
            'description.required' => 'يرجى ادخال اى ملاحظات عن القسم',

        ]);

        Section::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::user()->name,
        ]);
        session()->flash('Add', 'تم اضافة القسم بنجاح');
        return redirect()->route('sections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $section_id = $request->id;
        $request->validate([
            'name' => 'required|max:255|unique:sections,name,' . $section_id,
            'description' => 'required',
        ], [
            'name.required' => 'يرجى ادخال اسم القسم',
            'name.unique' => 'القسم موجود مسبقا يرجى تغيير اسم القسم',
            'description.required' => 'يرجى ادخال اى ملاحظات عن القسم',

        ]);
        $section = Section::find($section_id);
        $section->update($request->all());
        session()->flash('Edit', 'تم التعديل على بيانات القسم بنجاح');
        return redirect()->route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $section_id = $request->id;
        Section::find($section_id)->delete();
        session()->flash('Delete', 'تم حذف القسم بنجاح');
        return redirect()->route('sections.index');
    }
}
