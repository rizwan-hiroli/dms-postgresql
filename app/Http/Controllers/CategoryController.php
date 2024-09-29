<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryDataRequest;
use Illuminate\Support\Facades\Storage;
use Exception;
use DB;
use Log;

class CategoryController extends Controller
{

    /**
     * Display category listing.
     *
     * @return void
     */
    public function index()
    {
        return view('categories.list');
    }

    /**
     * get all categories.
     *
     * @return void
     */
    public function getData()
    {
        $categories = Category::select(['id', 'name','description', 'created_at'])->latest();
        return DataTables::of($categories)
            ->addColumn('action', function($category){
                return view('partials.category-action-buttons', compact('category'))->render();
            })->make(true);
    }

    /**
     * Create Category form.
     * Display form to user. 
     * @return void
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Creating Category.
     * Validate and store Category.
     * @param CategoryDataRequest $request
     * @return void
     */
    public function store(CategoryDataRequest $request)
    {
        // Store the category.
        try {
            Category::create(['name' => $request->name,'description' => $request->description]);
        } catch (Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create category. Please try again later.');
        }

        return redirect()->route('categories.list')->with('success', 'Category created successfully.');
    }

    /**
     * List existing category.
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update existing category.
     *
     * @param CategoryDataRequest $request
     * @param [type] $id
     * @return void
     */
    public function update(CategoryDataRequest $request, $id)
    {
        try {
            // Update the category
            $category = Category::findOrFail($id);
            $category->update(['name' => $request->name,'description' => $request->description]);

        } catch (Exception $e) {
            Log::error('Category update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update category. Please try again later.');
        }

        return redirect()->route('categories.edit', $category->id)->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the category and all its dependent products.
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        $categories = Category::findOrFail($id);

        // Delete the category.
        $categories->delete();

        // Delete all product images.
        foreach ($categories->products as $product ) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }                
        }
        
        //deleting all related products also.
        $categories->products()->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}