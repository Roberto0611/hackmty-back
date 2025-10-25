<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json($products);
    }

    public function getById($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    public function getByCategory($category_id){
        $products = Product::where('category_id', $category_id)->get();
        if($products->isEmpty()){
            return response()->json(['message' => 'No products found for this category'], 404);
        }
        return response()->json($products);
    }

    public function getPlacesProducts(){
        $rows = DB::table('places_products')
            ->join('places', 'places.id', '=', 'places_products.place_id')
            ->join('products', 'products.id', '=', 'places_products.product_id')
            ->select(
                'places_products.*',
                'places.name as place_name',
                'places.latitude',
                'places.longitude',
                'products.name as product_name'
            )
            ->get();

        return response()->json($rows);
    }
    public function getPlacesProductsById($id){
        $row = DB::table('places_products')
            ->join('places', 'places.id', '=', 'places_products.place_id')
            ->join('products', 'products.id', '=', 'places_products.product_id')
            ->select(
                'places_products.*',
                'places.name as place_name',
                'products.name as product_name'
            )
            ->where('places_products.id', $id)
            ->first();

        if(!$row){
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($row);
    }
    public function getPlacesProductsByPlace($place_id){
        $row = DB::table('places_products')
            ->join('places', 'places.id', '=', 'places_products.place_id')
            ->join('products', 'products.id', '=', 'places_products.product_id')
            ->select(
                'places_products.*',
                'places.name as place_name',
                'places.latitude',
                'places.longitude',
                'products.name as product_name'
            )
            ->where('places_products.place_id', $place_id)
            ->get();

        if($row->isEmpty()){
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($row);
    }
}
