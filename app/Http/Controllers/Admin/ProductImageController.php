<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UploadAble;
use App\Models\ProductImage;
use App\Contracts\ProductContract;

class ProductImageController extends Controller
{
    use UploadAble;
    protected $productRepository;

    public function __construct(ProductContract $productRepository){
        $this->productRepository = $productRepository;
    }

    public function upload(Request $request){
        //upload() method to save our product images.
        $product = $this->productRepository->findProductById($request->product_id);

        if($request->has('image')){
            $image = $this->uploadOne($request->image, 'products'); // uploading a single image using UploadAble trait.
            //saving the newly uploaded image path to product_images table by creating a new instance of ProductImage model 
            $productImage = new ProductImage([
                'full' => $image, // stroing image path
            ]);
            // saving the image using product images relationship.
            $product->images()->save($productImage);
        }

        return response()->json(['status' => 'Success']);
       
    }

    public function delete($id){
        // find the product image by id
        // here we are finding the record via Eloquent ORM
        $image = ProductImage::findOrFail($id);
        //checking if there is an image path exist in database delete the image first.
        if ($image->full != '') {
            $this->deleteOne($image->full);
        }
        //deleting the current image record 
        $image->delete();
        //and redirecting the user back to the product edit view
        return redirect()->back()->withInput(['tab' => 'images']);
    }


}
