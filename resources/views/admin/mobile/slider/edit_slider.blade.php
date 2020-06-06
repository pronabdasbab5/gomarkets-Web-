@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Slider</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Slider</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Edit Slider</strong>
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
                    <h5>Edit Slider</h5>
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

                    @if(!empty($slider_record->slider))
                        <center>
                            <img src="{{ asset('assets/mobile/slider/'.$slider_record->slider) }}" alt="Slider Image" height="150" width="200" style="object-fit: cover;" id="slider_img">
                        </center>
                    @endif
                    <form method="POST" autocomplete="off" action="{{ route('admin.update_slider', ['slider_id' => encrypt($slider_record->id) ]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label style="font-weight: bold;">Banner</label>
                                <input type="file" class="form-control" name="banner" id="banner"/> 
                                @error('banner')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Save</button>
                                    <a href="{{ route('admin.slider_list') }}" class="btn btn-sm btn-danger">Cancel</a>
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
$('#banner').change(function(e){

    var url = URL.createObjectURL(e.target.files[0]);
    $('#slider_img').attr('src', url);
});
</script>
@endsection