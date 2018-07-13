@extends('layouts.adminMaster')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('public/js/plugins/nestable/css/style2.css') }}">
<div class="panel panel-success">
    <div class="panel-heading">
        <h2 class="panel-title">{{ __('typeAdminTitle') }}</h2>
    </div>
    <div class="panel-body">
        <div class="container">
            <div class="col-lg-1"></div>
            <div class="col-lg-8 dd" id="nestable">
                <table class="table table-bordered table-hover mb-1" >
                    <thead>
                        <td></td>
                        <td class="text-center">Order</td>
                        <td>Type</td>
                        <td class="text-center">In Use</td>
                        <td class="text-center">Options</td>
                    </thead>
                    <tbody class="dd-list">
                        @foreach($listType as $key => $item)
                        <tr class="dd-item" data-id="{{ $key+1 }}">
                            <td class="dd-handle"><i class="fa fa-minus"></i></td>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td><input type="text" name="types[]" id="{{ $item['id'] }}" value="{{ $item['types'] }}" class="edit-able food-type"></td>
                            <td class="text-center">{{ $item['in_use'] }}</td>
                            <td class="text-center"><button type="button" value="{{ $item['id'] }}" class="btn btn-xs btn-warning confirm-delete"><span class="glyphicon glyphicon-remove"></span></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-1 pull-right p-0">
                    <button type="button" name="addLine" class="btn btn-sm pull-right btn-success"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="message-box animated fadeIn" data-sound="alert" id="mb-confirm-delete">
    <div class="mb-container">
        <div class="mb-middle">
            <div class="mb-title"><span class="fa fa-sign-out"></span><strong>Delete</strong> ?</div>
            <div class="mb-content">
                <p>{{ __('warningRemoveType1') }} <span id="warn" class="label label-danger"></span> {{ __('warningRemoveType2') }}</p>                
                <p>{{ __('askDelete') }}</p>
            </div>
            <div class="mb-footer">
                <div class="pull-right">
                <form action="{{ route('type.confirm') }}" method="post">
                    {{ csrf_field() }}
                    <button type="submit" name="typeId" class="btn btn-success btn-lg">{{ __('yes') }}</button>
                    <button type="button" class="btn btn-default btn-lg mb-control-close">{{ __('no') }}</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('public/js/plugins/nestable/js/nestable.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/js/process/type.js') }}"></script>
<script type="text/javascript">
    $('.dd').nestable();
</script>
@endsection
