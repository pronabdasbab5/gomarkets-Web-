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
                <a>Product</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Edit Product</strong>
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
                    <h5>Edit Product</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.update_product', ['product_id' => encrypt($product_record->id) ]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">State</label>
                                <select class="form-control" name="state_id" id="state_id">
                                    <option selected disabled>Choose State</option>
                                    @if(count($state_list) > 0)
                                        @foreach($state_list as $key => $item)
                                            @if($item->id == $state_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->state_name }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->state_name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('state_id')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">District</label>
                                <select class="form-control" name="district_id" id="district_id">
                                    <option selected disabled>Choose District</option>
                                    @if(count($district_list) > 0)
                                        @foreach($district_list as $key => $item)
                                            @if($item->id == $product_record->district_id)
                                                <option value="{{ $item->id }}" selected>{{ $item->district_name }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->district_name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('district_id')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Top-Category</label>
                                <select class="form-control" name="top_category_id" id="top_category_id">
                                    <option selected disabled>Choose Top-Category</option>
                                    @if(count($top_category_list) > 0)
                                        @foreach($top_category_list as $key => $item)
                                            @if($product_record->top_cate_id == $item->id)
                                                <option value="{{ $item->id }}" class="form-text-element" selected>{{ $item->top_cate_name }}</option>
                                            @else
                                                <option value="{{ $item->id }}" class="form-text-element">{{ $item->top_cate_name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('top_category_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Sub-Category</label>
                                <select class="form-control" name="sub_category_id" id="sub_category_id" onchange="nieceCNSizeLoad()"> 
                                    <option selected disabled>Choose Sub-Category</option>
                                    @if(count($sub_category_list) > 0)
                                        @foreach($sub_category_list as $key => $value)
                                            @if($product_record->sub_cate_id == $value->id)
                                                <option value="{{ $value->id }}" class="form-text-element" selected>{{ $value->sub_cate_name }}</option>
                                            @else
                                                <option value="{{ $value->id }}" class="form-text-element">{{ $value->sub_cate_name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('sub_category_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Niece-Category Name</label>
                                <select class="form-control" name="niece_category_id" id="niece_category_id"> 
                                    <option selected disabled>Choose Niece-Category</option>
                                    @if(count($niece_category_list) > 0)
                                        @foreach($niece_category_list as $key => $value)
                                            @if($product_record->niece_category_id == $value->id)
                                                <option value="{{ $value->id }}" class="form-text-element" selected>{{ $value->niece_cate_name }}</option>
                                            @else
                                                <option value="{{ $value->id }}" class="form-text-element">{{ $value->niece_cate_name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('niece_category_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Product Name (In English)</label>
                                <input type="text" class="form-control" value="{{ $product_record->product_name_english }}" name="product_name_english" id="product_name_english">
                                @error('product_name_english')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Product Name (In Assamese)</label>
                                <input type="text" class="form-control" value="{{ $product_record->product_name_assamese }}" name="product_name_assamese"> 
                                @error('product_name_assamese')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Slug (In English)</label>
                                <input type="text" class="form-control" name="slug" value="{{ $product_record->slug }}" id="slug" readonly> 
                                @error('product_name_assamese')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Brand</label>
                                <select class="form-control" name="brand_id" id="brand_id">
                                    <option></option>
                                    @if(count($brand_list) > 0)
                                        @foreach($brand_list as $key => $item)
                                            @if($product_record->brand_id == $item->id)
                                                <option value="{{ $item->id }}" class="form-text-element" selected>{{ $item->brand_name }}</option>
                                            @else
                                                <option value="{{ $item->id }}" class="form-text-element">{{ $item->brand_name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('brand_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                </div>
            </div>

            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Product Description</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <textarea name="product_desc">{{ $product_record->desc }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ibox ">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <center>
                            <button class="btn btn-sm btn-success" type="submit">Save</button>
                            <a onclick="window.close()" class="btn btn-warning btn-sm">Close</a>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
CKEDITOR.replace('product_desc');

$(document).ready(function(){
    $('#top_category_id').change(function(){
        var category_id = $('#top_category_id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        $.ajax({
            method: "POST",
            url   : "{{ url('/admin/retrive-sub-category') }}",
            data  : {
                'category_id': category_id
            },
            success: function(response) {

                $('#sub_category_id').html(response);
            }
        }); 
    });

    $("#product_name_english").keyup(function(){
        $("#slug").val($("#product_name_english").val().toLowerCase());
    });

    $('#sub_category_id').change(function(){
        var sub_category_id = $('#sub_category_id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        $.ajax({
            method: "POST",
            url   : "{{ url('/admin/retrive-niece-category') }}",
            data  : {
                'sub_category_id': sub_category_id
            },
            success: function(response) {

                $('#niece_category_id').html(response);
            }
        });  
    });
});
</script>
@endsection