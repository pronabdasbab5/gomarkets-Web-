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
                <a>Size</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Size List</strong>
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
                <h5>Size List</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.new_size') }}" class="btn btn-primary btn-sm">
                        Add Size
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
                <th>Created at</th>
                <th>Updated at</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($size_list) && !empty($size_list) && count($size_list) > 0)
                @foreach($size_list as $key => $item)
                <tr class="gradeX">
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->toDayDateTimeString() }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->updated_at)->toDayDateTimeString() }}</td>
                    <td class="text-left">
                        <div class="btn-group">
                            <a href="{{ route('admin.edit_size', ['size_id' => encrypt($item->id)]) }}" class="btn-warning btn btn-sm">Edit</a>
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
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({});
    });
</script>
@endsection