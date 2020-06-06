@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Product Images List</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Product List</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Product Images List</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        @if(count($product_image_list) > 0)
            @foreach($product_image_list as $key => $item)
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <form method="POST" autocomplete="off" action="{{ route('admin.update_product_additional_image', ['additional_image_id' => encrypt($item->id) ]) }}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <input type="file" name="additional_image" id="additional_image" accept="image/*" required>
                                </div>
                                <div class="col-lg-4">
                                    <button type="submit" class="btn btn-primary btn-xs">
                                        Upload Image
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="ibox-content">
                        <img src="{{ asset('assets/product_images/'.$item->additional_image.'') }}" style="height: 200px;" id="img">
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$('#additional_image').change(function(e){

    var url = URL.createObjectURL(e.target.files[0]);
    $('#img').attr('src', url);
});
</script>
@endsection