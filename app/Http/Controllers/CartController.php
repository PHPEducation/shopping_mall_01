<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Models\Product;
use Mail;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\BillDetail;

class CartController extends Controller
{
    public function getAddCart($id)
    {
        $product = Product::findOrFail($id);
        Cart::add([
            'id' => $id,
            'name' => $product->name_product,
            'quantity' => config('constant.one'),
            'price' => $product->price,
            'attributes' => array(
                'img' => $product->img,
            )
        ]);

        return redirect('cart/show');
    }

    public function getShowCart()
    {
        $data['total'] = Cart::getTotal();
        $data['items'] = Cart::getcontent();

        return view('frontend.cart', $data);
    }

    public function getDeleteCart($id)
    {
        if ($id == trans('frontend.all')) {
            Cart::clear();
        } else {
            Cart::remove($id);
        }

        return back();
    }

    public function getUpdateCart (Request $request)
    {
        Cart::update($request->id, array(
            'quantity' => array(
                'relative' => false,
                'value' => $request->quantity,
            ),
        ));
    }

    public function postComplete(Request $request)
    {
        $data['info'] = $request->all();
        $email = $request->email;
        $data['total'] = Cart::getTotal(); 
        $data['cart'] = Cart::getcontent();

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->address = $request->add;
        $customer->phone = $request->phone;
        $customer->note = $request->note;
        $customer->save();

        $bill = new Bill;
        $bill->customer_id = $customer->id;
        $bill->total = $data['total'];
        $bill->note = $request->note;
        $bill->save();

        foreach ($data['cart'] as $key => $value) {
            $billDetail = new BillDetail;
            $billDetail->bill_id = $bill->id;
            $billDetail->product_id = $key;
            $billDetail->quantity = $value['quantity'];
            $billDetail->unit_price = $value['price'];
            $billDetail->save();
        }
               
        Mail::send('frontend.email', $data, function ($message) use ($email) {
            $message->from(trans('frontend.emailAdmin'), trans('frontend.shop'));
            $message->to($email, $email);
            $message->cc(trans('frontend.emailShop'), trans('frontend.nameShop'));
            $message->subject('Xác nhận mua hàng Kim Cương shop');
        });
        Cart::clear();

        return redirect('complete');
    }

    public function getComplete()
    {
        return view('frontend.complete');
    }
}
