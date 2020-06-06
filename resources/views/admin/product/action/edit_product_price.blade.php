@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Product</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Product Stock and Price</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>New Product Stock and Price</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Product Stock and Price Entry</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if (session()->has('msg'))
                        <div class="alert alert-success">
                            {{ session()->get('msg') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    <form method="POST" autocomplete="off" action="{{ route('admin.update_product_price', ['product_id' => encrypt($product_id)]) }}">
                        @csrf

                        @if (count($size_list))
                            @foreach ($size_list as $size_item)
                                @php
                                    $status = 0;
                                @endphp
                                @if (count($product_price_list))
                                    @foreach ($product_price_list as $price_item)
                                        @if(($price_item->product_id == $product_id) && ($price_item->size_id == $size_item->size_id))
                                            @php
                                                $status = 1;
                                            @endphp
                                            <div class="form-group row">
                                                <div class="col-lg-2"> 
                                                    <input type="hidden" name="size_id[]" value="{{ $price_item->size_id }}" required> 
                                                    <input type="text" class="form-control" value="{{ $price_item->size}}" style="text-align: left;" placeholder="Size Name" disabled required> 
                                                </div>
                                                <div class="col-lg-2"> 
                                                    <input type="number" min="0" value="{{ $price_item->stock}}" class="form-control" placeholder="Enter Stock" name="stock[]" required> 
                                                </div>
                                                <div class="col-lg-2"> 
                                                    <input type="number" min="0" value="{{ $price_item->price}}" placeholder="Enter Price" class="form-control" name="price[]" required> 
                                                </div>
                                                <div class="col-lg-2"> 
                                                    <input type="number" value="{{ $price_item->discount}}" placeholder="Enter Discount" class="form-control" name="discount[]"> 
                                                </div>
                                                <div class="col-lg-2"> 
                                                    @if ($price_item->status == 1)
                                                        <button class="btn-success btn btn-sm">Enable</button>
                                                        <a href="{{ route('admin.change_product_price_status', ['product_price_id' => encrypt($price_item->id), 'status' => encrypt(2)]) }}" class="btn-danger btn btn-sm">Disabled</a>
                                                    @else
                                                        <button class="btn-danger btn btn-sm">Disabled</button>
                                                        <a href="{{ route('admin.change_product_price_status', ['product_price_id' => encrypt($price_item->id), 'status' => encrypt(1)]) }}" class="btn-success btn btn-sm">Enabled</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                @if($status == 0)
                                    <div class="form-group row">
                                        <div class="col-lg-2"> 
                                            <input type="hidden" name="size_id[]" value="{{ $size_item->size_id }}" required> 
                                            <input type="text" class="form-control" value="{{ $size_item->size}}" style="text-align: left;" placeholder="Size Name" disabled required> 
                                        </div>
                                        <div class="col-lg-2"> 
                                            <input type="number" min="0" class="form-control" placeholder="Enter Stock" name="stock[]" required> 
                                        </div>
                                        <div class="col-lg-2"> 
                                            <input type="number" min="0" placeholder="Enter Price" class="form-control" name="price[]" required> 
                                        </div>
                                        <div class="col-lg-2"> 
                                            <input type="number" placeholder="Enter Discount" class="form-control" name="discount[]"> 
                                        </div>
                                        <div class="col-lg-2"> 
                                        </div>
                                    </div>
                                @endif
                            @endforeach  
                        @endif

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Save</button>
                                    <a onclick="window.close()" class="btn btn-warning btn-sm">Close</a>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection