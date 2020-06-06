@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Districts</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>District</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Edit District</strong>
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
                    <h5>Edit District</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.update_district', ['district_id' => encrypt($district_record->id)]) }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label style="font-weight: bold;">State</label>
                                <select class="form-control" name="state_id">
                                    <option selected disabled>Choose State</option>
                                    @if(count($state_list) > 0)
                                        @foreach($state_list as $key => $item)
                                            @if($item->id == $district_record->state_id)
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
                                <label style="font-weight: bold;">District Name</label>
                                <input type="text" placeholder="Enter District Name" class="form-control" name="district_name" value="{{ $district_record->district_name }}"> 
                                @error('district_name')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Add</button>
                                    <a href="{{ route('admin.district_list') }}" class="btn btn-sm btn-danger">Cancel</a>
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