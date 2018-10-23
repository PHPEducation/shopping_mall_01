<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Models\Product;
use Mail;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Status;

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

        Status::create([
            'bill_id' => $bill->id,
            'status' => 1,
            'note' => '',
        ]);

        foreach ($data['cart'] as $key => $value) {
            BillDetail::create([
                'bill_id' => $bill->id,
                'product_id' => $key,
                'quantity' => $value['quantity'],
                'unit_price' => $value['price'],
                'name_product' => $value['name'],
            ]);
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
