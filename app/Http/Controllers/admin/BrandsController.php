<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest('id');
        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }
    public function create()
    {
        return view('admin.brands.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);
        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();


            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            $request->session()->put('error','record not found');
            return redirect()->route('brands.index');
        }
        return view('admin.brands.edit', compact('brand'));
    }
    public function update($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            $request->session()->put('error', 'record not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
              //  'message' => 'brand not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id . 'id',
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();


           $request->session()->put('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
            ]);
        }else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    // public function destroy($categoryId, Request $request)
    // {
    //     $category = Category::find($categoryId);
    //     if (empty($category)) {
    //         $request->session()->put('error', 'Category not found');
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Category not found'
    //         ]);
    //     }
    //     File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
    //     File::delete(public_path() . '/uploads/category/' . $category->image);
    //     $category->delete();
    //     $request->session()->put('success', 'Category deleted successfully');

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Category deleted successfully'
    //     ]);
    // }
}
