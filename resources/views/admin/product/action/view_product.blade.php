@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Product Details</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Product List</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Product Details</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox product-detail">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-5">
                            @if(count($product_image_list) > 0)
                            <div class="product-images">
                                @foreach($product_image_list as $key => $item)
                                    <img src="{{ asset('assets/product_images/'.$item->additional_image.'') }}">
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h2 class="font-bold m-b-xs">
                                {{ $product_record->product_name_english }} ({{ $product_record->product_name_assamese }})
                                @if(!empty($product_record->district_name))
                                     - {{ $product_record->district_name }},
                                @endif
                                @if(!empty($product_record->state_name))
                                    {{ $product_record->state_name }}
                                @endif
                            </h2>
                            <small>{{ $product_record->slug }}</small>
                            <div class="m-t-md">
                                <h2 class="product-main-price">{{ $product_record->niece_cate_name }}, {{ $product_record->sub_cate_name }}, {{ $product_record->top_cate_name }}</h2>
                            </div>
                            <hr>
                                    
                            <h4>Product description</h4>

                            <div class="small text-muted">
                                {!! $product_record->desc !!}
                            </div>
                            <dl class="small m-t-md">
                                @if(!empty($product_record->brand_name))
                                <dt>Brand</dt>
                                <dd>{{ $product_record->brand_name }}</dd>
                                @endif
                                <dt>Status</dt>
                                <dd>
                                    @if($product_record->status == 1)
                                        <button class="btn btn-primary btn-sm">Enable</button>
                                    @else
                                        <button class="btn btn-danger btn-sm">Disabled</button>
                                    @endif
                                </dd>
                                <dt>Created At</dt>
                                <dd>{{ \Carbon\Carbon::parse($product_record->created_at)->toDayDateTimeString() }}</dd>
                                <dt>Updated At</dt>
                                <dd>{{ \Carbon\Carbon::parse($product_record->updated_at)->toDayDateTimeString() }}</dd>
                            </dl>
                            <hr>
                            @if(!empty($product_price) && count($product_price) > 0)
                            <div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product_price as $key => $value)
                                        <tr>
                                            <td>{{ $value->size }}</td>
                                            <td>{{ $value->stock }}</td>
                                            <td>₹{{ $value->price }}</td>
                                            <td>
                                                @if(!empty($value->discount))
                                                    ₹{{ $value->discount }}%
                                                @endif
                                            </td>
                                            <td>
                                                @if($value->status == 1)
                                                    <button class="btn btn-primary btn-sm">Enable</button>
                                                @else
                                                    <button class="btn btn-danger btn-sm">Disabled</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="ibox-footer">
                        <a onclick="window.close()" class="btn btn-warning btn-sm ">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- slick carousel-->
<script src="{{ asset('backend/js/plugins/slick/slick.min.js') }}"></script>
<script>
$(document).ready(function(){
    $('.product-images').slick({
        dots: true
    });
});
</script>
@endsection