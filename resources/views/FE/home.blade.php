@extends('layouts.homeMaster')
@section('homeMaster')
<!-- head -->
@if($allowHeadBoard)
<div class="container p-0 mt-lg-4 main-bg">
    <div class="row m-0">
        <div class="col-lg-6 p-0">
            @if ($headItem['images'])
            <img class="card-img-top img-thumbnail mx-auto d-block fixed-size-head img-fluid" src="{{ asset('public/images') }}/{{ $headItem['images'][0]['url'] }}" alt="{{ $headItem['food'] }}">
            @else
            <img class="card-img-top img-thumbnail mx-auto d-block fixed-size-head img-fluid" src="{{ asset('public/images/1462234361-thit-cho-la-mo_TQEL.jpg') }}">
            @endif
        </div>
        <div class="col-lg-5 m-lg-3">
            <p><small class="text-muted">
            @php 
            $c = 0;
            @endphp
            @foreach($headItem['types'] as $k => $v)
                {{ $v['types'] }}
                @if($c < count($headItem['types'])-1)
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
                        <i class="fa fa-star-o mr-1 vote-star" data-vote-type="food" level="{{ $i+1 }}"></i>
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
                        <i class="fa fa-star-o mr-1" level="{{ $i+1 }}"></i>
                    @endfor
                    </span>
                </a>
                @endif
                <i class="ml-3 rating-line">{{ ($headItem['rate_times'] == 0) ? '0' : round($headItem['total_score']/$headItem['rate_times'],2) }} / {{ $headItem['rate_times'] }} {{ __('rate') }}</i>
            </p>
            </p>
            <p class="price-line font-16"><i class="fa fa-money mr-3 "></i> {{ $headItem['price'] }}</p>
            @if(Auth::user())
            <p class="favorite-line"><span class="favorite-icon" id="{{ $headItem['id'] }}" data-like="{{ $headItem['favorites'] ? 'like' : 'unlike' }}"><i class="fa fa-heart{{ $headItem['favorites'] ? ' text-danger' : '-o' }}"></i></span> {{ $headItem['favorites'] ? __('You liked this') : __('Add to favorite') }}</p>
            @else
            <p class="favorite-line"><a href="{{ url('/login') }}"><i class="fa fa-heart-o"></i> {{ __('Add to favorite') }}</a></p>
            @endif  
                <blockquote class="blockquote m-1">
                    <a class="text-info" href="#"><p class="mb-0 text-info"><i class="fa fa-map-marker"></i> {{ __('addresses') }}:</p></a>
                    @if($headItem['addresses'])
                        @foreach($headItem['addresses'] as $v)
                            <footer class="blockquote-footer">{{ $v['address'] }}</footer>
                        @endforeach
                    @else
                        <footer class="blockquote-footer">Not have any address</footer>
                    @endif
                </blockquote>
            </a>
        </div>
    </div>
</div>
@endif
<!-- list -->
@if($allowListPanel)
<div class="container p-0 mt-lg-4 main-bg">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Best stuffs we have') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
            @if ($listItem)
                @foreach ($listItem as $item)
                <div class="col-lg-3 mt-3 list-food">
                    <div class="card">
                        @if ($item['images'])
                        <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images') }}/{{ $item['images'][0]['url'] }}" alt="{{ $item['food'] }}">
                        @else
                        <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images/1462234361-thit-cho-la-mo_TQEL.jpg') }}">
                        @endif
                        <div class="card-body">
                            <p class="mb-1">
                                @if($item['types'])
                                <small class="text-muted">
                                @php 
                                $c = 0;
                                @endphp
                                @foreach ($item['types'] as $k => $v)
                                    {{ $v['types'] }}
                                    @if ($c < count($item['types'])-1)
                                        /
                                    @endif
                                    @php 
                                        $c+=1; 
                                    @endphp                                    
                                @endforeach
                                </small>
                                @else
                                <small>&nbsp;</small>
                                @endif
                            </p>
                            <h4 class="mb-1 card-title food-title">{{ $item['food'] }}</h4>
                            <p class="mb-2 card-text">{{ $item['description'] ? $item['description'] : '&nbsp;' }}</p>
                            <p class="price-line font-16"><i class="fa fa-money mr-3 "></i> {{ $item['price'] }}</p>
                            @if(Auth::user())
                            <p class="favorite-line"><span class="favorite-icon" id="{{ $item['id'] }}" data-like="{{ $item['favorites'] ? 'like' : 'unlike' }}"><i class="fa fa-heart{{ $item['favorites'] ? ' text-danger' : '-o' }}"></i></span> {{ $item['favorites'] ? __('You liked this') : __('Add to favorite') }}</p>
                            @else
                            <p class="favorite-line"><a href="{{ url('/login') }}"><i class="fa fa-heart-o"></i> {{ __('Add to favorite') }}</a></p>
                            @endif
                            <p class="mb-0 star-line">
                                @if(Auth::user())
                                <span class="vote-frame" data-vote-item="{{ $item['id'] }}">
                                    <span class="vote-frame-main">
                                @foreach ($item['rateStar'] as $s)
                                    <i class="fa fa-{{ $s }} mr-1"></i>
                                @endforeach
                                    </span>
                                    <span class="vote-frame-o-star hide">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fa fa-star-o mr-1 vote-star" data-vote-type="food" level="{{ $i+1 }}"></i>
                                    @endfor
                                    </span>
                                </span>
                                @else
                                <a class="vote-frame" href="{{ url('/login') }}">
                                    <span class="vote-frame-main">
                                    @foreach ($item['rateStar'] as $s)
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
                                <i class="ml-3 rating-line font-11">{{ ($item['rate_times'] == 0) ? '0' : round($item['total_score']/$item['rate_times'], 2) }} / {{ $item['rate_times'] }} {{ __('rate') }}</i></p>
                            </p>
                            <small class="text-success" id="infoSaveComment_{{ $item['id'] }}"></small>
                            <p class="text-center mb-0 mt-3"><a class="btn btn-outline-info" target="_blank()" href="{{ url('/details/') }}/{{ str_slug($item['food']) }}_{{ $item['id'] }}"><i class="fa fa-map-marker"></i> {{ __('details') }}</a></p>
                      </div>
                    </div>
                    
                </div>
                @endforeach
            @else
                <div class="col-md-12 text-center">
                    <h3 class="text-info">{{ __('foundZero') }}</h3>
                </div>
            @endif
            <div class="col-md-12 mt-5 text-center">
                    <a href="{{ url('show') }}" class="btn btn-outline-primary">{{ __('View more') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- all -->
@if($allowAllPanel)
<div class="container p-0 mt-lg-4 main-bg">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('All stuffs') }}
                <span class="float-right">
                    <input type="text" name="searchBox" value="{{ isset($searchOldName) ? $searchOldName : '' }}" class="form-control" placeholder="{{ __('Search') }}" name="">
                </span>
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <h4>Sorts by:</h4>
                    <div class="col-lg-12 mb-3">
                        <button class="btn btn-default btn-75" data-toggle="collapse" data-target="#demo" aria-expanded="true">{{ __('types') }}</button>

                        <div id="demo" class="collapse mt-3 show" aria-expanded="true">
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

                        <div id="demo1" class="collapse mb-3 show" aria-expanded="true">
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
                    <p class="btn-75 text-right">
                        <button class="btn btn-sm btn-primary" name="btnSort" class="font-12">Sort <i class="fa fa-arrow-right"></i></button>
                    </p>
                </div>
                <div class="col-lg-9">
                    <div class="row">
                    @if ($listItem)
                        @foreach ($listItem as $item)
                        <div class="col-lg-4 mt-3 list-food">
                            <div class="card">
                                @if ($item['images'])
                                <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images') }}/{{ $item['images'][0]['url'] }}" alt="{{ $item['food'] }}">
                                @else
                                <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images/1462234361-thit-cho-la-mo_TQEL.jpg') }}">
                                @endif
                                <div class="card-body">
                                    <p class="mb-1">
                                        @if($item['types'])
                                        <small class="text-muted">
                                        @php 
                                        $c = 0;
                                        @endphp
                                        @foreach ($item['types'] as $k => $v)
                                            {{ $v['types'] }}
                                            @if ($c < count($item['types'])-1)
                                                /
                                            @endif
                                            @php 
                                                $c+=1; 
                                            @endphp                                    
                                        @endforeach
                                        </small>
                                        @else
                                        <small>&nbsp;</small>
                                        @endif
                                    </p>
                                    <h4 class="mb-1 card-title food-title">{{ $item['food'] }}</h4>
                                    <p class="mb-2 card-text">{{ $item['description'] ? $item['description'] : '&nbsp;' }}</p>
                                    <p class="price-line font-16"><i class="fa fa-money mr-3 "></i> {{ $item['price'] }}</p>
                                    @if(Auth::user())
                                    <p class="favorite-line"><span class="favorite-icon" id="{{ $item['id'] }}" data-like="{{ $item['favorites'] ? 'like' : 'unlike' }}"><i class="fa fa-heart{{ $item['favorites'] ? ' text-danger' : '-o' }}"></i></span> {{ $item['favorites'] ? __('You liked this') : __('Add to favorite') }}</p>
                                    @else
                                    <p class="favorite-line"><a href="{{ url('/login') }}"><i class="fa fa-heart-o"></i> {{ __('Add to favorite') }}</a></p>
                                    @endif
                                    <p class="calorie-line text-danger">
                                        <i class="fa fa-sun-o"></i> {{ $item['total_calorie'] }} {{ __('Kcal') }}
                                    </p>
                                    <p class="mb-0 star-line">
                                        @if(Auth::user())
                                        <span class="vote-frame" data-vote-item="{{ $item['id'] }}">
                                            <span class="vote-frame-main">
                                        @foreach ($item['rateStar'] as $s)
                                            <i class="fa fa-{{ $s }} mr-1"></i>
                                        @endforeach
                                            </span>
                                            <span class="vote-frame-o-star hide">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fa fa-star-o mr-1 vote-star" data-vote-type="food" level="{{ $i+1 }}"></i>
                                            @endfor
                                            </span>
                                        </span>
                                        @else
                                        <a class="vote-frame" href="{{ url('/login') }}">
                                            <span class="vote-frame-main">
                                            @foreach ($item['rateStar'] as $s)
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
                                        <i class="ml-3 rating-line font-11">{{ ($item['rate_times'] == 0) ? '0' : round($item['total_score']/$item['rate_times'], 2) }} / {{ $item['rate_times'] }} {{ __('rate') }}</i></p>
                                    </p>
                                    <small class="text-success" id="infoSaveComment_{{ $item['id'] }}"></small>
                                    <p class="text-center mb-0 mt-3"><a class="btn btn-outline-info" target="_blank()" href="{{ url('/details/') }}/{{ str_slug($item['food']) }}_{{ $item['id'] }}"><i class="fa fa-map-marker"></i> {{ __('details') }}</a></p>
                              </div>
                            </div>
                            
                        </div>
                        @endforeach
                    @else
                        <div class="col-md-12 text-center">
                            <h3 class="text-info">{{ __('foundZero') }}</h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- favorite -->
@if($allowFavorite)
<div class="container p-0 mt-lg-4 main-bg">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Your Favorite') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
            @if(Auth::user())
                @if ($favoriteList)
                    @foreach ($favoriteList as $item)
                    <div class="col-lg-3 mt-3 list-food">
                        <div class="card">
                            @if ($item['images'])
                            <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images') }}/{{ $item['images'][0]['url'] }}" alt="{{ $item['food'] }}">
                            @else
                            <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images/1462234361-thit-cho-la-mo_TQEL.jpg') }}">
                            @endif
                            <div class="card-body">
                                <p class="mb-1">
                                    @if($item['types'])
                                    <small class="text-muted">
                                    @php 
                                    $c = 0;
                                    @endphp
                                    @foreach ($item['types'] as $k => $v)
                                        {{ $v['types'] }}
                                        @if ($c < count($item['types'])-1)
                                            /
                                        @endif
                                        @php 
                                            $c+=1; 
                                        @endphp                                    
                                    @endforeach
                                    </small>
                                    @else
                                    <small>&nbsp;</small>
                                    @endif
                                </p>
                                <h4 class="mb-1 card-title food-title">{{ $item['food'] }}</h4>
                                <p class="mb-2 card-text">{{ $item['description'] ? $item['description'] : '&nbsp;' }}</p>
                                <p class="price-line font-16"><i class="fa fa-money mr-3 "></i> {{ $item['price'] }}</p>
                                @if(Auth::user())
                                <p class="favorite-line"><span class="favorite-icon" id="{{ $item['id'] }}" data-like="{{ $item['favorites'] ? 'like' : 'unlike' }}"><i class="fa fa-heart{{ $item['favorites'] ? ' text-danger' : '-o' }}"></i></span> {{ $item['favorites'] ? __('You liked this') : __('Add to favorite') }}</p>
                                @else
                                <p class="favorite-line"><a href="{{ url('/login') }}"><i class="fa fa-heart-o"></i> {{ __('Add to favorite') }}</a></p>
                                @endif 
                                <p class="mb-0 star-line">
                                    @if(Auth::user())
                                    <span class="vote-frame" data-vote-item="{{ $item['id'] }}">
                                        <span class="vote-frame-main">
                                    @foreach ($item['rateStar'] as $s)
                                        <i class="fa fa-{{ $s }} mr-1"></i>
                                    @endforeach
                                        </span>
                                        <span class="vote-frame-o-star hide">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fa fa-star-o mr-1 vote-star" data-vote-type="food" level="{{ $i+1 }}"></i>
                                        @endfor
                                        </span>
                                    </span>
                                    @else
                                    <a class="vote-frame" href="{{ url('/login') }}">
                                        <span class="vote-frame-main">
                                        @foreach ($item['rateStar'] as $s)
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
<<<<<<< Updated upstream
                                    <i class="ml-3 rating-line font-11">{{ ($item['rate_times'] == 0) ? '0' : $item['total_score']/$item['rate_times'] }} / {{ $item['rate_times'] }} {{ __('rate') }}</i></p>
=======
                                    <i class="ml-3 rating-line font-11">{{ ($item['rate_times'] == 0) ? '0' : round($item['total_score']/$item['rate_times'], 2) }} / {{ $item['rate_times'] }} {{ __('rate') }}</i></p>
>>>>>>> Stashed changes
                                </p>
                                <small class="text-success" id="infoSaveComment_{{ $item['id'] }}"></small>
                                <p class="text-center mb-0 mt-3"><a class="btn btn-outline-info" target="_blank()" href="{{ url('/details/') }}/{{ str_slug($item['food']) }}_{{ $item['id'] }}"><i class="fa fa-map-marker"></i> {{ __('details') }}</a></p>
                          </div>
                        </div>
                        
                    </div>
                    @endforeach
                @else
                    <div class="col-md-12 text-center">
                        <h3 class="text-info">{{ __('foundZero') }}</h3>
                    </div>
                @endif
            @else
                <div class="col-md-12 text-center">
                    <a href="{{ url('login') }}" class="btn btn-outline-info">{{ __('mustLogin') }}</a>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endif
<!-- newest -->
@if($allowBottom)
<div class="container p-0 mt-lg-4 main-bg">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Newest Stuffs') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
            @if ($foodNewest)
                @foreach ($foodNewest as $item)
                <div class="col-lg-3 mt-3 list-food">
                    <div class="card">
                        @if ($item['images'])
                        <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images') }}/{{ $item['images'][0]['url'] }}" alt="{{ $item['food'] }}">
                        @else
                        <img class="card-img-top img-thumbnail mx-auto d-block fixed-size img-fluid" src="{{ asset('public/images/1462234361-thit-cho-la-mo_TQEL.jpg') }}">
                        @endif
                        <div class="card-body">
                            <p class="mb-1">
                                @if($item['types'])
                                <small class="text-muted">
                                @php 
                                $c = 0;
                                @endphp
                                @foreach ($item['types'] as $k => $v)
                                    {{ $v['types'] }}
                                    @if ($c < count($item['types'])-1)
                                        /
                                    @endif
                                    @php 
                                        $c+=1; 
                                    @endphp                                    
                                @endforeach
                                </small>
                                @else
                                <small>&nbsp;</small>
                                @endif
                            </p>
                            <h4 class="mb-1 card-title food-title">{{ $item['food'] }}</h4>
                            <p class="mb-2 card-text">{{ $item['description'] ? $item['description'] : '&nbsp;' }}</p>
                            <p class="price-line font-16"><i class="fa fa-money mr-3 "></i> {{ $item['price'] }}</p>
                            @if(Auth::user())
                            <p class="favorite-line"><span class="favorite-icon" id="{{ $item['id'] }}" data-like="{{ $item['favorites'] ? 'like' : 'unlike' }}"><i class="fa fa-heart{{ $item['favorites'] ? ' text-danger' : '-o' }}"></i></span> {{ $item['favorites'] ? __('You liked this') : __('Add to favorite') }}</p>
                            @else
                            <p class="favorite-line"><a href="{{ url('/login') }}"><i class="fa fa-heart-o"></i> {{ __('Add to favorite') }}</a></p>
                            @endif 
                            <p class="mb-0 star-line">
                                @if(Auth::user())
                                <span class="vote-frame" data-vote-item="{{ $item['id'] }}">
                                    <span class="vote-frame-main">
                                @foreach ($item['rateStar'] as $s)
                                    <i class="fa fa-{{ $s }} mr-1"></i>
                                @endforeach
                                    </span>
                                    <span class="vote-frame-o-star hide">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fa fa-star-o mr-1 vote-star" data-vote-type="food" level="{{ $i+1 }}"></i>
                                    @endfor
                                    </span>
                                </span>
                                @else
                                <a class="vote-frame" href="{{ url('/login') }}">
                                    <span class="vote-frame-main">
                                    @foreach ($item['rateStar'] as $s)
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
                                <i class="ml-3 rating-line">{{ ($item['rate_times'] == 0) ? '0' : round($item['total_score']/$item['rate_times'], 2) }} / {{ $item['rate_times'] }} {{ __('rate') }}</i></p>
                            </p>
                            <small class="text-success" id="infoSaveComment_{{ $item['id'] }}"></small>
                            <p class="text-center mb-0 mt-3"><a class="btn btn-outline-info" target="_blank()" href="{{ url('/details/') }}/{{ str_slug($item['food']) }}_{{ $item['id'] }}"><i class="fa fa-map-marker"></i> {{ __('details') }}</a></p>
                      </div>
                    </div>
                    
                </div>
                @endforeach
            @else
                <div class="col-md-12 text-center">
                    <h3 class="text-info">{{ __('foundZero') }}</h3>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection
