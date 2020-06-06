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
                <strong>Product List</strong>
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
                <h5>Product List</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.new_product') }}" class="btn btn-primary btn-sm">
                        Add Product
                    </a>
                </div>
            </div>
            <div class="ibox-content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example dt-responsive nowrap" style="width:100">
                    <thead>
                    <tr>
                        <th>Sl No</th>
                        <th>State</th>
                        <th>District</th>
                        <th>Product Name (English)</th>
                        <th>Product Name (Assamese)</th>
                        <th>Niece-Category</th>
                        <th>Sub-Category</th>
                        <th>Top-Category</th>
                        <th>Brand</th>
                        <th>Product Images</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Sl No</th>
                        <th>State</th>
                        <th>District</th>
                        <th>Product Name (English)</th>
                        <th>Product Name (Assamese)</th>
                        <th>Niece-Category</th>
                        <th>Sub-Category</th>
                        <th>Top-Category</th>
                        <th>Brand</th>
                        <th>Product Images</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
    </div>
</div>
@endsection

@section('script')
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "{{ route('admin.product_list_data') }}",
                "dataType": "json",
                "type": "POST",
                "data":{ 
                    _token: "{{csrf_token()}}"
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "state" },
                { "data": "district" },
                { "data": "product_name_english" },
                { "data": "product_name_assamese" },
                { "data": "niece_category" },
                { "data": "sub_category" },
                { "data": "top_category" },
                { "data": "brand" },
                { "data": "product_images" },
                { "data": "action" },
            ], 
        });
    });
</script>
@endsection