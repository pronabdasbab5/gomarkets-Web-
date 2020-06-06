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
                <strong>New Size Mapping</strong>
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
                    <h5>New Size Mapping</h5>
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
                    <form method="POST" autocomplete="off" action="{{ route('admin.add_mappping_size') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-4">
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
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">Sub-Category</label>
                                <select class="form-control" name="sub_category_id" id="sub_category_id"> 
                                    <option selected disabled>Choose Sub-Category</option>
                                </select>
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
                                            <option value="{{ $value->id }}">{{ $value->size }}</option>
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
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-danger">Cancel</a>
                                </center>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Size Mapping List</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example dt-responsive nowrap" style="width:100">
                            <thead>
                                <tr>
                                    <th>SlNo.</th>
                                    <th>Size</th>
                                    <th>Sub-Category</th>
                                    <th>Top-Category</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($mapping_size_list) > 0)
                                    @foreach ($mapping_size_list as $key => $item)
        
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->size }}</td>
                                            <td>{{ $item->sub_cate_name }}</td>
                                            <td>{{ $item->top_cate_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->toDayDateTimeString() }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->updated_at)->toDayDateTimeString() }}</td>
                                            <td class="text-left">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.edit_mappping_size', ['size_mapping_id' => encrypt($item->id)]) }}" class="btn-warning btn btn-sm">Edit</a>
                                                </div>
                                            </td>
                                        </tr> 
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SlNo.</th>
                                    <th>Size</th>
                                    <th>Sub-Category</th>
                                    <th>Top-Category</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
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
<script type="text/javascript">
$(document).ready(function(){
    $('.dataTables-example').DataTable({});

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