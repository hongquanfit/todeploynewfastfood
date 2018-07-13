@extends('layouts.adminMaster')
@section('content')
<div class="panel panel-success">
    <div class="panel-heading">
        <h2 class="panel-title">{{ __('foodAdminTitle') }}</h2>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <h4>Sorts by:</h4>
                <div class="col-lg-12 mb-3">
                    <button class="btn btn-default btn-75" data-toggle="collapse" data-target="#demo" aria-expanded="true">{{ __('foods') }}</button>

                    <div id="demo" class="collapse mt-3 in" aria-expanded="true">
                    @foreach($listType as $k => $type)
                        <p>
                        <label for="t{{ $k }}">
                            <input type="checkbox" id="{{ $k }}" name="sortAsType[]" class="form-control icheckbox mr-1 sortAsType" value="{{ $type['id'] }}" @if(isset($typeValue)) {{ in_array($type['id'], $typeValue) ? 'checked' : '' }} @endif> 
                            {{ $type['types'] }}
                        </label>
                        </p>
                    @endforeach
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <button class="btn btn-default btn-75" data-toggle="collapse" data-target="#demo1" aria-expanded="true">{{ __('rate') }}</button>

                    <div id="demo1" class="collapse mb-3 in" aria-expanded="true">
                        @php 
                            $iStar = 5;
                        @endphp
                        @for ($i = 0; $i < 5; $i++)
                        <p class="mt-2">
                            <label for="s{{ $i }}">
                                <input type="checkbox" id="s{{ $i }}" name="sortAsRating[]" class="form-control icheckbox mr-1 sortAsRating" value="{{ $iStar }}" @if(isset($rateScore) && $rateScore) {{ in_array($iStar, $rateScore) ? 'checked' : '' }} @endif>
                                @for($j = 0; $j < $iStar; $j++)
                                <i class="fa fa-star mr-2"></i>
                                @endfor
                                @php $iStar-- @endphp
                            </label>
                        </p>
                        @endfor
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <button class="btn btn-default btn-75" data-toggle="collapse" data-target="#demo2" aria-expanded="true">{{ __('status') }}</button>

                    <div id="demo2" class="collapse mt-3 in" aria-expanded="true">
                    @foreach($listStatus as $k => $status)
                        <p>
                            <label for="stt{{ $k }}">
                                <input type="checkbox" id="stt{{ $k }}" name="sortAsStatus[]" class="form-control icheckbox mr-1 sortAsStatus" value="{{ $status['id'] }}" @if(isset($statusValue)) {{ in_array($status['id'], $statusValue) ? 'checked' : '' }} @endif>
                                <span class="label label-{{ $status['effect'] }} label-font-11">{{ $status['status'] }}</span>
                            </label>
                        </p>
                    @endforeach
                    </div>
                </div>
                <p class="btn-75 text-right">
                    <button class="btn btn-sm btn-primary" name="btnSort" class="font-12">Sort <i class="fa fa-arrow-right"></i></button>
                </p>
            </div>
            <div class="col-md-10">
                <table class="table table-hover table-bordered datatable table-responsive">
                    <thead>
                        <td>{{ __('image') }}</td>
                        <td>{{ __('fName') }}</td>
                        <td>{{ __('addBy') }}</td>
                        <td>{{ __('Created day') }}</td>
                        <td>{{ __('rateScore') }}</td>
                        <td>{{ __('rateTimes') }}</td>
                        <td>{{ __('Total Calorie') }}</td>
                        <td>{{ __('type') }}</td>
                        <td>{{ __('status') }}</td>
                        <td>{{ __('options') }}</td>
                    </thead>
                    <tbody>
                    @foreach($listFood as $k => $food)
                        <tr class="dd-item">
                            <td class="table-avatar" id="{{ $food['id'] }}">
                            @if($food['images'])
                                <img class="img-thumbnail" src="{{ url('public/images/') }}/{{ $food['images'][0]['url'] }}" alt="">
                            @endif
                            </td>
                            <td>
                                <input type="text" class="edit-able" id="{{ $food['id'] }}" value="{{ $food['food'] }}">
                            </td>
                            <td>{{ $food['food_user']['name'] }}</td>
                            <td>{{ date('d/m/Y', $food['create_at']) }}</td>
                            <td>{{ ($food['rate_times'] == 0) ? 0 : $food['total_score'] / $food['rate_times'] }}</td>
                            <td>{{ $food['rate_times'] }}</td>
                            <td class="edit-calorie" data-food="{{ $food['id'] }}" data-calo="{{ $food['total_calorie'] }}" data-toggle="modal" data-target="#editCalorieModal">{{ $food['total_calorie'] }}</td>
                            <td>
                                <select class="form-control select2 foodtype-edit" tabindex="-1" name="food_type[]" aria-hidden="true" multiple="multiple" id="{{ $food['id'] }}">
                                @foreach($listType as $ltype)
                                    <option value="{{ $ltype['id'] }}" @foreach($food['types'] as $ftype) {{ ($ltype['id'] == $ftype['id']) ? 'selected' : '' }} @endforeach>{{ $ltype['types'] }}</option>
                                @endforeach
                                </select>
                            </td>
                            <td>
                                <button data-toggle="collapse" data-target="#status{{ $k }}" id="{{ $food['id'] }}" btn-status-id="{{ $food['food_status']['id'] }}" btn-effect="{{ $food['food_status']['effect'] }}" class="btn btn-sm btn-{{ $food['food_status']['effect'] }}">{{ $food['food_status']['status'] }}</button>
                                <div id="status{{ $k }}" class="collapse mt-3">
                                @foreach ($listStatus as $status)
                                    <p>
                                    @if ($food['food_status_id'] != $status['id'])
                                        <span id="{{ $status['id'] }}" data-food-id="{{ $food['id'] }}" data-effect="{{ $status['effect'] }}" class="label label-{{ $status['effect'] }} label-font-9 status-cube">{{ $status['status'] }}</span>
                                    @endif
                                    </p>
                                @endforeach
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" data-toggle="modal" data-target="#moreInfoModal" data-toggle-tooltip="tooltip" title="{{ __('info') }}" class="btn btn-sm btn-info food-info" value="{{ $food['id'] }}"><i class="fa fa-info"></i></button>
                                <a href="{{ url('details') }}/{{ str_slug($food['food']) . '_' . $food['id'] }}" target="_blank" class="btn btn-sm btn-default" data-toggle-tooltip="tooltip" title="{{ __('details') }}"><i class="fa fa-arrow-right"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                        <input type="file" class="hide" name="hideImg">
                </table>
            </div>
            <div class="col-md-10">
                
            </div>
        </div>
    </div>
</div>

<div class="modal" id="moreInfoModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<div class="modal" id="chooseItemModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            
            </div>
        </div>
    </div>
</div>
<div class="modal" id="editCalorieModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            {!! Form::open([
                'url' => route('admin.food.changeNutrition'),
                'method' => 'POST',
            ]) !!}
                <div class="col-md-12" id="addLineArea">
                    
                </div>
                <div class="col-md-12 pl-5 ">
                    <p class="boder-calculate-total">
                        <span class="total-calo-when-caculated">
                            <b>Total:</b>
                            <b class="text-info ml-2">0 Calo</b>
                        </span>
                    </p>
                </div>
                <button type="button" class="btn btn-sm btn-primary" id="addLineNutrition"><i class="fa fa-plus"></i></button>
                <button type="submit" name="food_id" value="submit" class="btn btn-success pull-right">Save</button>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 hide" id="coreListNutrition">
    <div class="col-md-12">        
        <div class="col-md-6 form-group">
            <div class="row">
                <div class="col-md-1">
                    <button class="btn btn-xs btn-danger nutrifood-delete-line"><i class="fa fa-minus"></i></button>
                </div>
                <div class="col-md-11">
                    <select class="form-control toSetSelect2" style="width: 100%" name="nutrition[]">
                        @foreach($listNutri as $item)
                            <option value="{{ $item['id'] }}" data-nutri="{{ $item['calorie'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2 form-group">
            <input type="number" class="form-control content-gram" placeholder="{{ __('Gram of content') }}" name="gram[]">
        </div>
        <div class="col-md-4 caculate-calo-area">
            
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('public/js/process/food.js') }}"></script>
@endsection
