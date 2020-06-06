@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Brand</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Brand</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>New Brand</strong>
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
                    <h5>New Brand</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.add_brand') }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">Brand Name</label>
                                <input type="text" placeholder="Enter Brand Name" class="form-control" name="brand_name" value="{{ old('brand_name') }}"> 
                                @error('brand_name')
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
                                    <a href="{{ route('admin.brand_list') }}" class="btn btn-sm btn-danger">Cancel</a>
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