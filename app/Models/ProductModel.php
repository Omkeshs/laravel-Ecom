<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProductModel extends Model
{
    use HasFactory;
    public function UpdateQuantity($items)
    {
        // read json file
        $path = storage_path() . "/json/product.json";
        $product = json_decode(file_get_contents($path), true);

        foreach ($items as $item) {
            if ($product[$item['id']]['id'] == $item['id']){
                $product[$item['id']]['quantity'] = $item['quantity'];
            }
        }
        return file_put_contents($path, json_encode($product));
    }


    public function ListProduct()
    {
        $path = storage_path() . "/json/product.json";
        $json = json_decode(file_get_contents($path), true); 
        return json_encode($json);        
    }
}