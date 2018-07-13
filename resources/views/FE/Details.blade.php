@extends('layouts.homeMaster')
@section('homeMaster')
<div class="container p-0 mt-lg-4 main-bg">
    <div class="row m-0">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    @if ($headItem['images'])
                    <img class="card-img-top img-thumbnail mx-auto d-block fixed-size-head img-fluid" src="{{ asset('public/images') }}/{{ $headItem['images'][0]['url'] }}" alt="{{ $headItem['food'] }}">
                    @else
                    <img class="card-img-top img-thumbnail mx-auto d-block fixed-size-head img-fluid" src="{{ asset('public/images/1462234361-thit-cho-la-mo_TQEL.jpg') }}">
                    @endif
                </div>
                <div class="col-lg-5 m-lg-3 pt-4">
                    <p><small class="text-muted">
                    @php 
                    $c = 0;
                    @endphp
                    @foreach($headItem['types'] as $k => $v)
                        {{ $v['types'] }}
                        @if($c < count($headItem['types']) - 1)
                            /
                        @endif
                        @php 
                            $c+=1; 
                        @endphp
                    @endforeach
                    </small></p>
                    <p><h1 class="food-title">{{ $headItem['food'] }}</h1></p>
                    <p>{{ $headItem['description'] }}</p>
                    <p class="mb-0 star-line">
                        @if(Auth::user())
                        <span class="vote-frame" data-vote-item="{{ $headItem['id'] }}">
                            <span class="vote-frame-main">
                        @foreach ($headItem['rateStar'] as $s)
                            <i class="fa fa-{{ $s }} mr-1"></i>
                        @endforeach
                            </span>
                            <span class="vote-frame-o-star hide">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa fa-star-o mr-1 vote-star" data-vote-type="food" level="{{ $i + 1 }}"></i>
                            @endfor
                            </span>
                        </span>
                        @else
                        <a class="vote-frame" href="{{ url('/login') }}">
                            <span class="vote-frame-main">
                            @foreach ($headItem['rateStar'] as $s)
                                <i class="fa fa-{{ $s }} mr-1"></i>
                            @endforeach
                            </span>
                            <span class="vote-frame-o-star hide">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa fa-star-o mr-1" level="{{ $i + 1 }}"></i>
                            @endfor
                            </span>
                        </a>
                        @endif
                        <i class="ml-3 rating-line">{{ ($headItem['rate_times'] == 0) ? '0' : round($headItem['total_score']/$headItem['rate_times'], 2) }} / {{ $headItem['rate_times'] }} {{ __('rate') }}</i>
                    </p>
                    <p class="comment-line"><a href="{{ url('/details/') }}/{{ str_slug($headItem['food']) }}_{{ $headItem['id'] }}#commentSection"><i class="fa fa-comments-o mr-3"></i> {{ $headItem['countComment'] }} {{ __('comments') }}</a></p>
                    <p class="price-line font-16"><i class="fa fa-money mr-3 "></i> {{ $headItem['price'] }}</p>
                    <p class="calorie-line text-danger">
                        <i class="fa fa-sun-o"></i> {{ $headItem['total_calorie'] }} {{ __('Kcal') }}
                    </p>
                    @if(Auth::user())
                    <p class="favorite-line"><span class="favorite-icon" id="{{ $headItem['id'] }}" data-like="{{ $headItem['favorites'] ? 'like' : 'unlike' }}"><i class="fa fa-heart{{ $headItem['favorites'] ? ' text-danger' : '-o' }}"></i></span> {{ $headItem['favorites'] ? __('You liked this') : __('Add to favorite') }}</p>
                    @else
                    <p class="favorite-line"><a href="{{ url('/login') }}"><i class="fa fa-heart-o"></i> {{ __('Add to favorite') }}</a></p>
                    @endif  
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container p-0 mt-lg-4 main-bg">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('addresses') }}</h3>
        </div>
        <div class="card-body" id="bodyDetails">
            <div class="row">
                @foreach($headAddress as $k => $address)
                <div class="col-lg-3 mb-4">
                    <div class="card address-list">
                        @if ($address['avatar'])
                        <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images') }}/{{ $address['avatar'] }}" alt="{{ $address['address'] }}">
                        @else
                        <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images/store.jpg') }}">
                        @endif
                        <div class="card-body">
                            <h4 class="mb-1 card-title food-title font-14">{{ $address['address'] }}</h4>
                            <p class="mb-2 price-line font-13"><i class="fa fa-money mr-2"></i> {{ number_format($address['price'], 0) }} VND</p>
                            <p class="mb-0 star-line">
                                @if(Auth::user())
                                <span class="vote-frame" data-vote-item="{{ $address['id'] }}">
                                    <span class="vote-frame-main">
                                @foreach ($address['rateStar'] as $s)
                                    <i class="fa fa-{{ $s }} mr-1"></i>
                                @endforeach
                                    </span>
                                    <span class="vote-frame-o-star hide">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fa fa-star-o mr-1 vote-star" data-vote-type="address" level="{{ $i+1 }}"></i>
                                    @endfor
                                    </span>
                                </span>
                                @else
                                <a class="vote-frame" href="{{ url('/login') }}">
                                    <span class="vote-frame-main">
                                    @foreach ($address['rateStar'] as $s)
                                        <i class="fa fa-{{ $s }} mr-1"></i>
                                    @endforeach
                                    </span>
                                    <span class="vote-frame-o-star hide">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fa fa-star-o mr-1" level="{{ $i+1 }}"></i>
                                    @endfor
                                    </span>
                                </a>
                                @endif
                                <i class="ml-3 rating-line">{{ ($address['rate_times'] == 0) ? '0' : $address['total_score']/$address['rate_times'] }} / {{ $address['rate_times'] }} {{ _('rate') }}</i></p>
                            </p>
                            <p class="comment-line"><a href="javascript:void(0)" id="openAdrComtBox"><i class="fa fa-comments-o mr-3"></i> {{ $address['countAdrComt'] }} {{ __('comments') }}</a></p>
                            <p class="suggest-line"><i class="fa fa-plus-square-o mr-2"></i> {{ __('addedBy') }} <b class="text-info">{{ $address['whoAdded'] ? $address['whoAdded']['name'] : '???' }}</b></p>

                            @if(Auth::user())
                                @if(Auth::user()->role_id)
                                <p class="text-center mb-0"><a href="{{ url('admin/food/delItem') }}/{{ $headItem['id'] }}/{{ $address['id'] }}" class="btn btn-outline-danger btn-sm">{{ __('deleteItem') }}</a></p>
                                @endif
                            @endif
                        </div>
                    </div>
                    @if(($k + 1) % 3 == 0 || ($k + 1) % 4 == 0)
                    <div class="arrow_box-right card-parent display-comment hide">
                    @else
                        <div class="arrow_box-left card-parent display-comment hide">
                    @endif
                            <p class="text-right mb-0"><span class="btnHideWindow">&times;</span></p>
                            <div class="row p-0 m-0">
                                <div class="col-lg-3 p-0">
                                <p class="text-right pr-2">
                                @php
                                    $currentAdrStar = 5;
                                @endphp

                                @for($i = 0; $i < 5; $i++)
                                    <span class="font-10">
                                        @for($j = $i; $j < 5; $j++)
                                        <i class="fa fa-star"></i>
                                        @endfor

                                        @if(isset($address['countScore'][$currentAdrStar]))
                                        <kbd>{{ $address['countScore'][$currentAdrStar] }}</kbd>
                                        @else
                                        <kbd>0</kbd>
                                        @endif
                                    </span>
                                    <br>
                                    @php
                                        $currentAdrStar--;
                                    @endphp
                                @endfor
                                </p>
                                </div>
                                <div class="col-lg-9 p-0">
                                    @if(Auth::user())
                                    <div class="form-group">
                                        <input type="text" name="editCommentAddressArea" data-address-id="{{ $address['id'] }}" placeholder="{{ __('addComment') }}" class="form-control">
                                        <span class="notiAddComment"></span>
                                    </div>
                                    @else
                                    <p class="text-center">
                                        <a href="{{ url('login') }}" class="btn btn-outline-info">{{ __('loginToComment') }}</a>
                                    </p>
                                    @endif
                                    <div class="col-lg-12 p-0 commentLine">
                                        @foreach($address['adrComment'] as $adrCmt)
                                        <div class="col-lg-12  pb-2 border-comment">
                                            <p class="mb-1"><b class="text-info">{{ $adrCmt['user']['name'] }}</b> | 
                                            @if($adrCmt['score'])
                                                <span class="text-warning">{{ $adrCmt['score'] }} <i class="fa fa-star"></i></span>
                                            @else
                                                <span class="no-vote">{{ __('noVote') }}</span>
                                            @endif
                                            @if(Auth::user())
                                                @if(Auth::user()->role_id)
                                                    <span data-toggle-tooltip="tooltip" title="{{ __('deleteItem') }}" class="float-right"><a title="" class="btn btn-xs btn-outline-danger remove-comment-btn font-10"><i class="fa fa-trash-o"></i></a></span>
                                                @endif
                                            @endif
                                            </p>
                                            <footer class="blockquote-footer font-comment">{{ $adrCmt['comment'] }}</footer>
                                            <p><small class="float-right">{{ date('H:i, d/m/Y', $adrCmt['time']) }}</small></p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-3 mb-4">
                    <div class="add-icon-box" data-toggle-tooltip="tooltip" title="{{ __('addAdr') }}" data-toggle="modal" data-target="#modalAddAdr">
                            <h1>+</h1>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row p-3" id="commentSection">
                <div class="col-lg-12">
                    <p><h3>{{ __('rate') }} & {{ __('comments') }}</h3></p>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3">
                        @php
                            $currentStar = 5;
                        @endphp
                            @for($i = 0; $i < 5; $i++)
                                <p class="text-right">
                                    @for($j = $i; $j < 5; $j++)
                                    <i class="fa fa-star mr-2"></i>
                                    @endfor
                                    @if(isset($countScore[$currentStar]))
                                        <kbd class="font-14">{{ $countScore[$currentStar] }}</kbd>
                                    @else
                                        <kbd class="font-14">0</kbd>
                                    @endif
                                    @php
                                        $currentStar--
                                    @endphp
                                </p>
                            @endfor
                        </div>
                        <div class="col-lg-9">
                            @if(Auth::user())
                            <div class="form-group">
                                <textarea name="editCommentArea" class="form-control" placeholder="{{ __('addComment') }}"></textarea>
                                <span class="notiAddComment"></span>
                                <button type="button" id="btnAddComt" value="{{ $headItem['id'] }}" class="btn btn-outline-success btn-sm float-right mt-2"><i class="fa fa-check"></i> {{ __('Add Comment') }}</button>
                            </div>
                            @else
                            <p class="text-center">
                                <a href="{{ url('login') }}" class="btn btn-outline-info">{{ __('loginToComment') }}</a>
                            </p>
                            @endif
                            <div class="col-lg-12 mt-5 p-0 commentLine">
                            @foreach($listComments as $item)
                                <div class="col-lg-12 pt-2 pb-2 border-comment">
                                    <p class="mb-1"><b class="text-info">{{ $item['whoCommented'] }}</b> | 
                                    @if(isset($item['rate']))
                                    <span class="text-warning">{{ $item['rate'] }} <i class="fa fa-star"></i></span>
                                    @else
                                    <span class="no-vote">{{ __('noVote') }}</span>
                                    @endif
                                    @if(Auth::user())
                                        @if(Auth::user()->role_id)
                                            <span data-toggle-tooltip="tooltip" title="{{ __('deleteItem') }}" class="float-right"><a title="" class="btn btn-xs btn-outline-danger remove-comment-btn font-10"><i class="fa fa-trash-o"></i></a></span>
                                        @endif
                                    @endif
                                    </p>
                                    <footer class="blockquote-footer font-comment">{{ $item['comment'] }}</footer>
                                    <p><small class="float-right">{{ date('H:i, d/m/Y', $item['time']) }}</small></p>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalAddAdr" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                @if(Auth::user())
                {!! Form::open([
                    'method' => 'POST',
                    'url' => route('user.addAdr'),
                    'enctype' => 'multipart/form-data',
                ]) !!}
                    <div class="form-group" id="groupAddress">
                        <div class="group-address mb-2">
                            <div class="row">
                                <div class="col-lg-8">
                                    <label>{{ __('address') }} & {{ __('price') }}:</label>
                                    <input type="text" name="address" class="form-control add-address" placeholder="{{ __('address') }}">
                                    <div class="address-found-box">
                                    </div>
                                    <div class="row pt-1 pl-3 pl-3 mb-3">
                                        <input class="col-sm-4 form-control" name="price" type="text" placeholder="{{ __('price') }}">
                                        {!! Form::select('currency', $currency, null, [
                                            'class' => 'form-control col-sm-3'
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <img src="{{ asset('public/images') }}/store.jpg" class="img-thumbnail avatar-img">
                                    <input type="file" name="adrAvatar" class="hide adrAvatar">
                                </div>
                            </div>
                        </div>                       
                    </div>
                    <p class="text-center">
                        {!! Form::button('Add', [
                            'name' => 'food',
                            'value' => $headItem['id'],
                            'class' => 'btn btn-outline-info',
                        ]) !!}
                    </p>
                {!! Form::close() !!}
                @else
                    <p class="text-center"><a href="{{ url('/login') }}" class="btn btn-outline-info">{{ __('mustLogin') }}</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
