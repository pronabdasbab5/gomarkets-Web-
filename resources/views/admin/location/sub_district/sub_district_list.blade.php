@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Sub-District</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Sub-District</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Sub-District List</strong>
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
                <h5>Sub-District List</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.new_sub_district_form') }}" class="btn btn-primary btn-sm">
                        Add Sub-District
                    </a>
                </div>
            </div>
            <div class="ibox-content">

                <div class="table-responsive">
            <table id="district_table" class="table table-striped table-bordered table-hover dataTables-example dt-responsive nowrap" style="width:100">
            <thead>
            <tr>
                <th>SlNo.</th>
                <th>Sub-District</th>
                <th>District</th>
                <th>State</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th>SlNo.</th>
                <th>Sub-District</th>
                <th>District</th>
                <th>State</th>
                <th>Status</th>
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
    $('#district_table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": "{{ route('admin.sub_district_list_data') }}",
            "dataType": "json",
            "type": "POST",
            "data":{ 
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "sub_district" },
            { "data": "district" },
            { "data": "state" },
            { "data": "status" },
            { "data": "created_at" },
            { "data": "updated_at" },
            { "data": "action" },
        ],    
    });
});
</script>
@endsection