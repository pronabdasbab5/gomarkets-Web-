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
                    <form method="POST" autocomplete="off" action="{{ route('admin.add_price_entry', ['product_id' => encrypt($product_id)]) }}">
                        @csrf

                        @if((count($size_list) > 0) && !empty($size_list))
                            @foreach ($size_list as $key => $item)
                                <div class="form-group row">
                                    <div class="col-lg-3"> 
                                        <input type="hidden" name="size_id[]" value="{{ $item->size_id }}" required> 
                                        <input type="text" class="form-control" value="{{ $item->size}}" style="text-align: left;" placeholder="Size Name" disabled required> 
                                    </div>
                                    <div class="col-lg-3"> 
                                        <input type="number" min="0" class="form-control" placeholder="Enter Stock" name="stock[]" required> 
                                    </div>
                                    <div class="col-lg-3"> 
                                        <input type="number" min="0" placeholder="Enter Price" class="form-control" name="price[]" required> 
                                    </div>
                                    <div class="col-lg-3"> 
                                        <input type="number" min="0" placeholder="Enter Discount" class="form-control" name="discount[]"> 
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Add</button>
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