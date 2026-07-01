<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \DB::table('course_categories')->orderBy('position')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = \DB::table('course_categories')->whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:course_categories,id',
            'position' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        \DB::table('course_categories')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'position' => $request->position,
            'is_active' => $request->has('is_active') ? true : false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = \DB::table('course_categories')->where('id', $id)->first();
        if (!$category) abort(404);

        $parentCategories = \DB::table('course_categories')->whereNull('parent_id')->where('id', '!=', $id)->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:course_categories,id',
            'position' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        \DB::table('course_categories')->where('id', $id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'position' => $request->position,
            'is_active' => $request->has('is_active') ? true : false,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        \DB::table('course_categories')->where('id', $id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
