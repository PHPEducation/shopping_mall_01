@extends('frontend.master')
@section('title', trans('frontend.cart'))
@section('main')
<link rel="stylesheet" href="css/cart.css">
@include('frontend.js.cart')
<div id="wrap-inner" class="col-md-9">
    <div class="row list-product">
        <div class="col-md-12">
            <div class="clearfix"></div>
            <h3>{{ trans('frontend.cart') }}</h3>
            @if(Cart::getTotalQuantity() >= 1)
            <table class="table table-bordered .table-responsive text-center">
                <tr class="active">
                    <td class="tdimg">{{ trans('frontend.descriptiveImage') }}</td>
                    <td class="tdproduct">{{ trans('frontend.nameProduct') }}</td>
                    <td class="tdproduct">{{ trans('frontend.quality') }}</td>
                    <td class="tdprice">{{ trans('frontend.unitPice') }}</td>
                    <td class="tdprice">{{ trans('frontend.intoMoney') }}</td>
                    <td class="tdimg">{{ trans('frontend.delete') }}</td>
                </tr>
                @foreach($items as $item)
                <tr>
                    <td><img class="img-responsive" src="{{ asset(config('constant.avatar') . $item->attributes->img) }}"></td>
                    <td>{{ $item->name }}</td>
                    <td>
                        <div class="form-group">
                            <input class="form-control" type="number" value="{{ $item->quantity }}" min="1" onchange="updateCart(this.value, '{{ $item->id }}')">
                        </div>
                    </td>
                    <td><span class="price">{{ number_format($item->price, 0, ',', '.') }} {{ trans('frontend.price') }}</span></td>
                    <td><span class="price">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} {{ trans('frontend.price') }}</span></td>
                    <td><a href="{{ asset('cart/delete/' . $item->id) }}"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>
                @endforeach
            </table>
            <div class="row" id="total-price">
                <div class="col-md-12">
                    {{ trans('frontend.totalPayment') }} <span class="total-price">{{ number_format($total, 0, ',', '.') }} {{ trans('frontend.price') }}</span>
                    <a href="{{ asset('/') }}" class="my-btn btn">{{ trans('frontend.buyNext') }}</a>
                    <a class="my-btn btn">{{ trans('frontend.update') }}</a>
                    <a href="{{ asset('cart/delete/all') }}" class="my-btn btn">{{ trans('frontend.deleteCart') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row list-product">
        <div class="col-md-9">
            <h3>{{ trans('frontend.purchase') }}</h3>
            {!! Form::open(array('route' => 'cart', 'method' => 'POST')) !!}
                <div class="form-group">
                    <label for="email">{{ trans('frontend.emailCustomer') }}</label>
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'id' => 'email', 'required']) !!}
                </div>
                <div class="form-group">
                    <label for="name">{{ trans('frontend.theirNames') }}</label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                </div>
                <div class="form-group">
                    <label for="phone">{{ trans('frontend.phoneUser') }}</label>
                    {!! Form::number('phone', null, ['class' => 'form-control', 'id' => 'phone']) !!}
                </div>
                <div class="form-group">
                    <label for="add">{{ trans('frontend.address') }}</label>
                    {!! Form::text('add', null, ['class' => 'form-control', 'id' => 'add', 'required']) !!}
                </div>
                <div class="form-group">
                    <label for="add">{{ trans('frontend.request') }}</label>
                    {!! Form::textarea('note', null, ['class' => 'form-control', 'rows' => '10', 'id' => 'cm', 'required']) !!}
                </div>
                <div class="form-group text-right">
                    {!! Form::submit(trans('frontend.orderFulfillment'), ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            @else
            <h2><div class="alert alert-danger">{{ trans('frontend.note') }}</div><h2>
            @endif
        </div>
    </div>
</div>
@stop
