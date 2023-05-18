<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;

class ProductController extends Controller
{
    public function ListProduct()
    {
        $productModel = new ProductModel;
        return $productModel->ListProduct();
    }

    public function UpdateProduct(Request $req)
    {
        $productModel = new ProductModel;
        $res = $productModel->UpdateQuantity($req->input());
        if ($res == 0){
            return response("unable to Update Quantity", 500);
        } else{
            return response("Quantity successfully updated.", 200);
        }
    }
}
