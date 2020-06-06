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
                <strong>New Product</strong>
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
                    <h5>New Product</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.add_product') }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">State</label>
                                <select class="form-control" name="state_id" id="state_id">
                                    <option selected disabled>Choose State</option>
                                    @if(count($state_list) > 0)
                                        @foreach($state_list as $key => $item)
                                            <option value="{{ $item->id }}">{{ $item->state_name }}</option>
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
                                            <option value="{{ $item->id }}">
                                                {{ $item->top_cate_name }}
                                            </option>
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
                                </select>
                                @error('sub_category_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Niece-Category Name</label>
                                <select class="form-control" name="niece_category_id" id="niece_category_id"> 
                                    <option selected disabled>Choose Niece-Category</option>
                                </select>
                                @error('niece_category_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Product Images</label>
                                <input type="file" class="form-control" name="product_images[]" multiple> 
                                @error('product_images')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Product Name (In English)</label>
                                <input type="text" class="form-control" name="product_name_english" id="product_name_english">
                                @error('product_name_english')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Product Name (In Assamese)</label>
                                <input type="text" class="form-control" name="product_name_assamese"> 
                                @error('product_name_assamese')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Slug (In English)</label>
                                <input type="text" class="form-control" name="slug" id="slug" readonly> 
                                @error('product_name_assamese')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Brand</label>
                                <select class="form-control" name="brand_id" id="brand_id">
                                    <option selected disabled>Choose Brand</option>
                                    @if(count($brand_list) > 0)
                                        @foreach($brand_list as $key => $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->brand_name }}
                                            </option>
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
                            <textarea name="product_desc"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ibox ">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <center>
                            <button class="btn btn-sm btn-success" type="submit">Next</button>
                            </form>
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

    $('#state_id').change(function(){
        var state_id = $('#state_id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        $.ajax({
            method: "POST",
            url   : "{{ url('/admin/retrive-district') }}",
            data  : {
                'state_id': state_id
            },
            success: function(response) {
                
                $('#district_id').html(response);
            }
        }); 
    });
});
</script>
@endsection