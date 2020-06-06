@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Size</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Size Mapping</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Edit Size Mapping</strong>
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
                    <h5>Edit Size Mapping</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.update_mappping_size', ['size_mapping_id' => encrypt($mapping_size_record->id)]) }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">Top-Category</label>
                                <input type="hidden" name="top_category_id" value="{{ $top_category_record->id }}" />
                                <input type="text" class="form-control" value="{{ $top_category_record->top_cate_name }}" disabled/>
                                @error('top_category_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">Sub-Category</label>
                                <input type="hidden" name="sub_category_id" value="{{ $sub_category_record->id }}" />
                                <input type="text" class="form-control" value="{{ $sub_category_record->sub_cate_name }}" disabled/>
                                @error('sub_category_id')
                                    <span class="form-text m-b-none" style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">Size</label>
                                <select class="form-control" name="size"> 
                                    <option selected disabled>Choose Size</option>
                                    @if(isset($size_list) && !empty($size_list) && (count($size_list) > 0))
                                        @foreach($size_list as $key => $value)
                                            @if ($value->id == $mapping_size_record->size_id)
                                            <option value="{{ $value->id }}" selected>{{ $value->size }}</option>
                                            @else  
                                            <option value="{{ $value->id }}">{{ $value->size }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('size')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Add</button>
                                    <a href="{{ route('admin.new_mappping_size') }}" class="btn btn-sm btn-danger">Back</a>
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
    $('.dataTables-example').DataTable({});
});
</script>
@endsection