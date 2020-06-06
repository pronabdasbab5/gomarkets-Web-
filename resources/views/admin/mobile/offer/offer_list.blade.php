@extends('admin.template.master')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Offer Banner</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a>Offer Banner</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Offer Banner List</strong>
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
                <h5>Offer Banner List</h5>
                <div class="ibox-tools">
                    <a href="{{ route('admin.new_offer') }}" class="btn btn-primary btn-sm">
                        Add Offer Banner
                    </a>
                </div>
            </div>
            <div class="ibox-content">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover dataTables-example dt-responsive nowrap" style="width:100">
                    <thead>
                    <tr>
                        <th>SlNo.</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($offer_list) && !empty($offer_list) && count($offer_list) > 0)
                        @foreach($offer_list as $key => $item)
                        <tr class="gradeX">
                            <td>{{ $item->id }}</td>
                            <td><img src="{{ asset('assets/mobile/offer/'.$item->banner.'') }}" height="100px" /></td>
                            <td>
                                @if ($item->status == 1)
                                    <button class="btn-success btn btn-sm">Enable</button>
                                @else
                                    <button class="btn-danger btn btn-sm">Disabled</button>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->toDayDateTimeString() }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->updated_at)->toDayDateTimeString() }}</td>
                            <td class="text-left">
                                <div class="btn-group">
                                    @if ($item->status == 2)
                                        <a href="{{ route('admin.change_offer_status', ['offer_id' => encrypt($item->id), 'status' => encrypt(1)]) }}" class="btn-success btn btn-sm">Enabled</a>
                                    @endif
                                    <a href="{{ route('admin.edit_offer', ['offer_id' => encrypt($item->id)]) }}" class="btn-warning btn btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>SlNo.</th>
                        <th>Image</th>
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
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({});
    });
</script>
@endsection