<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductAttribute;

class ProductAttributeController extends BaseController
{
        
    public function index(Request $request){
        //getting the product
        $product = Product::findOrFail($request->id);
        //attaching data (title and subtitle) with all views using view()->share() method.
        view()->share(['pageTitle' => 'Food Menu', 'subTitle' => 'List of Food items']);
        //listing all the attributes for the current product.        
        $attributes =  $product->attributes;               
        return view('admin.products.viewattribute', compact('attributes', 'product'));      
    }

    public function create(Request $request){
        //getting the product
        $product = Product::findOrFail($request->id);
        //attaching data (title and subtitle) with all views using view()->share() method.
        view()->share(['pageTitle' => 'Food Menu', 'subTitle' => 'List of Food items']);
        return view('admin.products.createattribute', compact('product'));
    }

    public function edit($id){
        //getting the product attribue        
        $productAttribute = ProductAttribute::findOrFail($id);        
        //attaching data (title and subtitle) with all views using view()->share() method.
        view()->share(['pageTitle' => 'Food Menu', 'subTitle' => 'List of Food items']);
        return view('admin.products.editattribute', compact('productAttribute'));
    }

    public function store(Request $request){

        $this->validate($request,[                       
            'size'               =>  'required|max:191',
            'price'              =>  'required|regex:/^\d+(\.\d{1,2})?$/',
            'discount_price'     =>  'regex:/^\d+(\.\d{1,2})?$/',  
        ]);

        $params = $request->except('_token'); // getting all the category form inputs except csrf token.

        $productAttribute = ProductAttribute::create($params);

        if (!$productAttribute) {
            return $this->responseRedirectBack('Error occurred while creating product.', 'error', true, true);
        }
        return redirect()->route('admin.products.attribute.index', $request->product_id);  

    }

    public function update(Request $request){

        $this->validate($request,[                       
            'size'               =>  'required|max:191',
            'price'              =>  'required|regex:/^\d+(\.\d{1,2})?$/',
            'discount_price'     =>  'regex:/^\d+(\.\d{1,2})?$/',  
        ]);

        $params = $request->except('_token'); // getting all the product attributes inputs except csrf token.
        // updating the requested product attribute data
        $productAttribute = ProductAttribute::where('id', $request->id)->update($params);        

        if (!$productAttribute) {
            return $this->responseRedirectBack('Error occurred while creating product.', 'error', true, true);
        }
        return redirect()->route('admin.products.attribute.index', $request->product_id);  

    }

    public function delete($id){
        
        $productAttribute = ProductAttribute::findOrFail($id);
        //fetching the product_id
        $product_id = $productAttribute->product_id;
        $productAttribute->delete();
        $this->responseRedirectBack(' product attribute is deleted successfully', 'success', false, false);
        return redirect()->route('admin.products.attribute.index', $product_id);  
    }
    
}
