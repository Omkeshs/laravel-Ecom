<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    public function List(){
        $path = storage_path() . "/json/order.json";
        $json = json_decode(file_get_contents($path), true); 
        return json_encode($json);
    }

    public function Create($order){
        // updating order json file
        $orderPath = storage_path() . "/json/order.json";
        file_put_contents($orderPath, json_encode($order));
        return;
    }

    public function UpdateStatus($order){
        // updating order json file
        $orderPath = storage_path() . "/json/order.json";
        file_put_contents($orderPath, json_encode($order));
        return;
    }
}
