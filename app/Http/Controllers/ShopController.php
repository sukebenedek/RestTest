<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function allCategory()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function allProduct()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function categoryWithProduct()
    {
        $categories = Category::with('products')->get();
        return response()->json($categories);
    }

    public function filterProductByCategory($id)
    {

        $products = Category::find($id)->products;
        return response()->json($products);

        // elsődleges kulcsra keresünk (csak elsődleges kulcsra keres)
        // $category = Category::find($id);

        // if (!$category){
        //     return response()->json(['message' => 'A kategória nem található!'],404);
        // }


        // return response()->json($category,200);
    }

    public function filterProductByName($name){
        $products = Product::where('ProductName','Like','%' . $name . '%')
                                ->with('category')
                                ->get();

        $data = [];
        foreach ($products as $product){
            $data[] = [
                'ProductNumber' => $product->ProductNumber,
                'ProductName' => $product->ProductName,
                'ProductDescription' => $product->ProductDescription,
                'RetailPrice' => $product->RetailPrice,
                'QuantityOnHand' => $product->QuantityOnHand,

                'CategoryDescription' => $product->category->CategoryDescription,
            ];
        }
        return response()->json($data);
    }


    public function listOrders($id = null){
        if ($id){
            // ha van értéke
            $orders = Order::with('products')->find($id);
            if (!$orders){
                return response()->json(['message' => 'A rendelés nem található!'],404);
            }

            //return response()->json($orders);
            $data = [];
            $data = [
                'OrderNumber' => $orders->OrderNumber,
                'OrderDate' => $orders->OrderDate,
                'ShipDate' => $orders->ShipDate,
                'OrderTotal' => $orders->OrderTotal,
            ];
            foreach ($orders->products as $product){
                $data['orders'][] = [
                    'ProductName' => $product->ProductName,
                    'RetailPrice' => $product->RetailPrice,
                    'QuotedPrice' => $product->pivot->QuotedPrice,
                    'QuantityOrdered' => $product->pivot->QuantityOrdered
                ];
            }
            return response()->json($data);
        }

        $orders = Order::with('products')->get(); // összes adat
        return response()->json($orders);

    }
}
