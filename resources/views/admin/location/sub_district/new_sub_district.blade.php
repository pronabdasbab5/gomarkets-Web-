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
                    <form method="POST" autocomplete="off" action="{{ route('admin.add_sub_district') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">State</label>
                                <select class="form-control" name="state_id" id="state_id">
                                    <option selected disabled>Choose State</option>
                                    @if(count($state_list) > 0)
                                        @foreach($state_list as $key => $item)
                                            <option value="{{ $item->id }}">{{ $item->state_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('state_id')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">District</label>
                                <select class="form-control" name="district_id" id="district_id">
                                    <option selected disabled>Choose District</option>
                                </select>
                                @error('district_id')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                <label style="font-weight: bold;">Sub-District Name</label>
                                <input type="text" placeholder="Enter Sub-District Name" class="form-control" name="sub_district_name" value="{{ old('sub_district_name') }}"> 
                                @error('sub_district_name')
                                    <span class="form-text m-b-none">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <center>
                                    <button class="btn btn-sm btn-success" type="submit">Add</button>
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

@section('script')
<script type="text/javascript">
$(document).ready(function(){
    $('#state_id').change(function(){
        var state_id = $('#state_id').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        $.ajax({
            method: "POST",
            url   : "{{ url('/admin/retrive-district') }}",
            data  : {
                'state_id': state_id
            },
            success: function(response) {
                
                $('#district_id').html(response);
            }
        }); 
    });
});
</script>
@endsection