<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categories =  Category::orderBy('name', 'ASC')->with('sub_category')->where('status', 1)->get();
        $brands = Brand::orderBy('name', 'ASC')->where('status', 1)->get();
        $products = Product::where('status', 1);

        //apply filters here
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id',$category->id);
        }
        // if (!empty($subCategorySlug)) {
        //     $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
        //     $products = $products->where('sub_category_id',$subCategory->id);
        // }

        $products = Product::orderBy('id', 'DESC');
        $products = Product::get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;

        return view('front.shop', $data);
    }
}
