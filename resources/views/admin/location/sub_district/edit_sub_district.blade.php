@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Sub-Districts</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Sub-District</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>New Sub-District</strong>
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
                    <h5>New Sub-District</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.update_sub_district', ['sub_district_id' => encrypt($sub_district_record->id)]) }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">State</label>
                                <input type="text" placeholder="Enter State Name" class="form-control" name="state_name" value="{{ $sub_district_record->state_name }}" required disabled> 
                                @error('state_name')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">District</label>
                                <input type="text" placeholder="Enter District Name" class="form-control" name="district_name" value="{{ $sub_district_record->district_name }}" required disabled> 
                                @error('district_name')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">Sub-District Name</label>
                                <input type="text" placeholder="Enter Sub-District Name" class="form-control" name="sub_district_name" value="{{ $sub_district_record->sub_district_name }}"> 
                                @error('sub_district_name')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Save</button>
                                    <a href="{{ route('admin.sub_district_list') }}" class="btn btn-sm btn-danger">Cancel</a>
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
