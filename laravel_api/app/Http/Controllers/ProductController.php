<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'products'=>Product::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name'=>'required',
            'price'=>'required',
            'description'=>'required',
            'wishlist'=>'required',
        ]);
        if($request->file('product_image')) {
            $product_image = $request->file('product_image');
            $file_name = time().'-'.$product_image->getClientOriginalName();
            move_uploaded_file($product_image->getPathName(),public_path().'/assets/product_image/'.$file_name);
        } else{
            $file_name = null;
        }
        $save_data = Product::create($request->all());
        $save_data->product_image = $file_name;
        $save_data->save();
        return response()->json(['success'=>'product added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        $fetch_product_by_id = Product::find($product->id);
        if($fetch_product_by_id){
            return response()->json([$fetch_product_by_id], 200);
        } else {
            return response()->json(["success"=>"no data found"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $validated = $request->validate([
            'name'=>'required',
            'price'=>'required',
            'description'=>'required',
            'wishlist'=>'required',
        ]);
        $update_data = Product::where('id',$product->id)->first();

        if($request->file('product_image')){
            $update_product_image = $request->file('product_image');
            $image_name = time() . '-' .$update_product_image->getClientOriginalName();
            move_uploaded_file($update_product_image->getPathName(),public_path().'/assets/product_image/'.$image_name);

            if(isset($update_data->product_image)){
                $delete_image_from_public_folder = public_path().'/assets/product_image/'.$update_data->product_image;
                unlink($delete_image_from_public_folder);
            }
        }else{
            $image_name = $update_data->product_image;
        }


        $update_date = $update_data->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description,
            'wishlist'=>$request->wishlist,
            'product_image'=>$image_name
        ]);

        return response()->json([$update_data], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $delete_product_by_id = Product::find($product->id)->delete();
        return response()->json(["success"=>"deleted successfully"]);
    }

    public function delete_image($id) {
        $get_image = Product::find($id);
        $delete_image = public_path().'/assets/product_image/'.$get_image->product_image;
        $get_image->product_image = NULL;
        $get_image->save();
        unlink($delete_image);
        return response()->json(["success"=>"removed image successfully"]);
    }

    public function search($key){
        if($key != ""){
            $search = Product::where('name','like',"%{$key}%")
                ->orWhere('price','like',"%{$key}%")
                ->orWhere('description','like',"%{$key}%")
                ->orWhere('wishlist','like',"%{$key}%")
                ->get();
        }else{
            $search = Product::all();
        }
            return $search;
    }

    public function login(Request $request){
        $check = User::where("email",$request->email)->first();
        if(isset($check)){
            $password_check = Hash::check($request->password, $check->password);
            if($password_check) {
                return response()->json(["success"=>"successfully logged in"]);
            }else{
                return response()->json(["error"=>"wrong password"], 404);
            }
        }else{
            return response()->json(["error"=>"user not found"], 404);
        }
    }
}
