<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function ListOrder(){
        $OrderModel = new OrderModel;
        return $OrderModel->List();
    }

    public function PlaceOrder(Request $req){
        

        $items = $req->input();
        $order = json_decode($this->ListOrder(), true); 

        // Get Product Details
        $productController = new ProductController;
        $product = json_decode($productController->ListProduct(), true);
        
        $upateProductReq = array();
        $orderQuantity = $orderAmmount = $premiumProductCount = $discount = $finalAmount = 0;
        foreach ($items['items'] as $item) {
            $reqProductID = $item['product_id'];
            $reqQuantity = $item['quantity'];
            if (isset($product[$reqProductID]['quantity'])) {
                $prod = $product[$reqProductID];
                if (($prod['quantity'] <= 0) || ($prod['quantity'] < $reqQuantity)){
                    return response("running low inventory", Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                $orderQuantity = $orderQuantity + $reqQuantity;
                $orderAmmount = $orderAmmount + ($prod['price'] * $reqQuantity);
                
                $pReq = Array(
                    "id" => $reqProductID,
                    "quantity" => $product[$reqProductID]['quantity'] - $reqQuantity,
                );

                array_push($upateProductReq, $pReq); 

                if ($prod['category'] == 1){
                    $premiumProductCount++;
                }
            } else if (!isset($product[$reqProductID]['quantity'])) {
                return response("Invalid Product ID", Response::HTTP_NOT_FOUND);
            }
        }

        // check if discount applicable
        if ($premiumProductCount >= 3) {
            $discount = $orderAmmount / 10;
            $finalAmount = $orderAmmount - $discount;
        }

        // Generating order
        $order[count($order)+1] = Array(
            "id"=> count($order)+1,
            "quantity"=> $orderQuantity,
            "order_status"=> "Placed",
            "order_amount"=> $orderAmmount,
            "discount"=> $discount,
            "final_amount"=> $finalAmount,
            "dispatched_date"=> ""
        );

        // creating order
        $OrderModel = new OrderModel;
        $OrderModel->Create($order);

        // updating product json file
        $productModel = new ProductModel;
        $productModel->UpdateQuantity($upateProductReq);

        return response(json_encode($order[count($order)]), Response::HTTP_OK);

    }

    public function UpdateOrder(Request $req, $id){
        
        // if input id is string
        if (!is_numeric($id)){
            return response("Invalid Order ID", Response::HTTP_BAD_REQUEST);
        }

        // check order
        $order = json_decode($this->ListOrder(), true);
        if (!isset($order[$id])){
            return response("Order not found", Response::HTTP_NOT_FOUND);
        }

        $order[(int)$id]['order_status'] = $req['order_status'];

        // update order
        $orderModel = new OrderModel;
        $orderModel->UpdateStatus($order);
        
        return response("Successfully updated ", Response::HTTP_OK);


    }

    public function DeleteOrder($id) {
        // check order
        $order = json_decode($this->ListOrder(), true);
        if (!isset($order[$id])){
            return response("Order not found", Response::HTTP_NOT_FOUND);
        }else {
            // remove order
            unset($order[$id]);
            
            // update order
            $orderModel = new OrderModel;
            $orderModel->UpdateStatus($order);
            return response("OrderID ".$id." Deleted Successfully ", Response::HTTP_OK);
        }
        
    }
}
