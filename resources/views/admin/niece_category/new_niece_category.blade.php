@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Niece-Category</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Niece-Category</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>New Niece-Category</strong>
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
                    <h5>New Niece-Category</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.add_niece_category') }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Top-Category</label>
                                <select class="form-control" name="top_category_id" id="top_category_id">
                                    <option selected disabled>Choose Top-Category</option>
                                    @if(count($top_category_list) > 0)
                                        @foreach($top_category_list as $key => $item)
                                            <option value="{{ $item->id }}">{{ $item->top_cate_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('top_category_id')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Sub-Category</label>
                                <select placeholder="Enter Sub-Category Name" class="form-control" name="sub_category_id" id="sub_category_id"> 
                                    <option selected disabled>Choose Sub-Category</option>
                                </select>
                                @error('sub_category_id')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Niece-Category Name</label>
                                <input type="text" placeholder="Enter Niece-Category Name" class="form-control" name="niece_cate_name" value="{{ old('niece_cate_name') }}"> 
                                @error('niece_cate_name')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Banner</label>
                                <input type="file" class="form-control" name="banner"> 
                                @error('banner')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Add</button>
                                    <a href="{{ route('admin.niece_category_list') }}" class="btn btn-sm btn-danger">Cancel</a>
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

@section('script')
<script type="text/javascript">
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
});
</script>
@endsection