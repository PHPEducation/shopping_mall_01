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
use App\LoyalCustomer;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Repositories\Post\PostCustomersRepository;
use App\Repositories\Post\PostBillRepository;
use App\Repositories\Post\PostBillDetailRepository;
use App\Repositories\Post\PostStatusRepository;

class CartController extends Controller
{
    protected $postCustomers, $postBill, $postBillDetail, $postStatus;

    public function __construct(PostCustomersRepository $postCustomers, PostBillRepository $postBill, PostBillDetailRepository $postBillDetail, PostStatusRepository $postStatus)
    {
        $this->postCustomers = $postCustomers;
        $this->postBill = $postBill;
        $this->postBillDetail = $postBillDetail;
        $this->postStatus = $postStatus;
    }

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
        if (Auth::guard('loyal_customer')->check()) {
            $user = Auth::guard('loyal_customer')->id();
            $data['arrs'] = LoyalCustomer::ShowCart()
                ->where('loyal_customers.id', $user)
                ->get();
            $data['check'] = 1;
        }

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
        if (Auth::guard('loyal_customer')->check()) {
            $user = LoyalCustomer::findOrFail(Auth::guard('loyal_customer')->id());
            $email = $user->email;
            $id = $user->id;
            $data['info'] = $request->all();
        } else {
            $data['info'] = $request->all();
            $email = $request->email;
            $id = null;
        }

        $data['total'] = Cart::getTotal(); 
        $data['cart'] = Cart::getcontent();

        $customer = $this->postCustomers->getAddCustomers($request, $id);
        $idBill = $customer->id;

        $bill = $this->postBill->getAddBill($request, $idBill, $data['total']);
        $idStatus = $bill->id;

        $this->postStatus->getAddStatus($idStatus);

        foreach ($data['cart'] as $key => $value) {           
            $this->postBillDetail->getAddBillDetail($idStatus, $key, $value['quantity'], $value['price'], $value['name']);
        }

        Mail::send('frontend.email', $data, function ($message) use ($email) {
            $message->from(trans('frontend.emailAdmin'), trans('frontend.shop'));
            $message->to($email, $email);
            $message->cc(trans('frontend.emailShop'), trans('frontend.nameShop'));
            $message->subject(trans('frontend.confirms'));
        });
        
        Cart::clear();

        return redirect('complete');
    }

    public function getComplete()
    {
        return view('frontend.complete');
    }
}
