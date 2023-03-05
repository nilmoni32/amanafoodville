<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;



class ProductController extends Controller
{
    public function index(){        
        $products = Product::orderBy('id', 'asc')->where('featured', 0)->paginate(18);        
        return view('site.pages.product.allproducts', compact('products'));
    }
    public function categoryproductshow($slug){   

        if($slug == 'all'){
            // viewing all food menus.
            $products = Product::orderBy('id', 'asc')->where('featured', 0)->paginate(18); 
            return view('site.pages.product.allproducts', compact('products'));
        }else{
            // getting the category name by slug.
             $category = Category::where('slug',$slug)->first(); 
             return view('site.pages.product.categoryproduct', compact('category'));            
        }

    }

    public function search(Request $request){
        $search = $request->search; // getting the search key
        $products = Product::orWhere('name', 'like', '%'.$search.'%')
        ->orWhere('description', 'like', '%'.$search.'%')
        ->orWhere('price', 'like', '%'.$search.'%')
        ->orWhere('slug', 'like', '%'.$search.'%')->paginate(18); 

        return view('site.pages.product.search', compact('products', 'search'));
    }
}
