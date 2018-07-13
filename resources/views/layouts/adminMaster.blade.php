<!DOCTYPE html>
<html>
<head>
    <title>Ffood ({{ $countFoodWaiting }})</title>
    <base href="{{url('/')}}" token="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <base > -->
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/theme-default.css')}}">
    <link rel="stylesheet" href="{{ asset('public/js/plugins/icheckbox/skins/flat/red.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/select2/dist/css/select2.min.css') }}" >
    <script type="text/javascript" src="{{asset('public/js/plugins/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript">
        var baseUrl = $('base').attr('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('base').attr('token')
            }
        });
    </script>
</head>
<body style="height: ">
<div class="page-container page-navigation-toggled page-container-wide">
    <div class="page-sidebar">
        <ul class="x-navigation x-navigation-minimized">
            <li class="xn-logo">
                <a href="#">BLA</a>
                <a href="#" class="x-navigation-control"></a>
            </li>
            <li class="xn-profile">
                <div class="profile">
                    <div class="profile-data">
                        <div class="profile-data-name">
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                </div>
            </li>
            <li class="xn-profile">
                <a href="#" class="profile-mini">
                    <img src="{{ asset('public/images/user.png') }}" alt="John Doe">
                </a>
            </li>
            <li data-toggle-tooltip="tooltip" title="{{ __('typeAdminTitle') }}" data-placement="right">
                <a href="{{ url('admin/type') }}">
                    <i class="fa fa-list"></i>
                    <span class="xn-text"></span>
                </a>
            </li>
            <li data-toggle-tooltip="tooltip" title="{{ __('foodAdminTitle') }}" data-placement="right">
                <a href="{{ url('admin/food') }}">
                    <i class="fa fa-list"></i>
                    <span class="xn-text"></span>
                </a>
            </li>
            <li data-toggle-tooltip="tooltip" title="{{ __('setupHomepage') }}" data-placement="right">
                <a href="{{ url('admin/food') }}#setupHomepage">
                    <i class="fa fa-list"></i>
                    <span class="xn-text">{{ __('setupHomepage') }}</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="page-content" style="height: -webkit-fill-available;">
        <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
            <li class="xn-icon-button">
                <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
            </li>
            <li   class="xn-icon-button pull-right">
                <a href="/#" id="logoutBtn" class="mb-control" ><span class="fa fa-sign-out"></span></a>                        
            </li>
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-comments"></span></a>
                <div class="informer informer-danger" id="countNewFood1">{{ $countFoodWaiting }}</div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging ui-draggable">
                    <div class="panel-heading ui-draggable-handle">
                        <h3 class="panel-title"><span class="fa fa-comments"></span> Messages</h3>                                
                        <div class="pull-right">
                            <span class="label label-danger" id="countNewFood2">{{ $countFoodWaiting }} new</span>
                        </div>
                    </div>
                    <div class="panel-body list-group list-group-contacts scroll mCustomScrollbar _mCS_2 mCS-autoHide mCS_no_scrollbar" style="height: 200px;"><div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">
                    @foreach($foodWaiting as $item)
                        <a href="{{ url('details') }}/{{ str_slug($item['food']) }}_{{ $item['id'] }}" class="list-group-item" target="_blank">
                            <div class="list-group-status status-waiting"></div>
                            <img src="{{ asset('public/images/') }}/{{ $item['images'] ? $item['images'][0]['url'] : '39079980-food-wallpapers.jpg' }}" class="pull-left" alt="{{ $item['food'] }}">
                            <span class="contacts-title">{{ $item['food'] }}</span>
                            <p>Has been added by <b>{{ $item['food_user']['name'] }}</b> on {{ date('d/m/Y', $item['create_at']) }}</p>
                        </a>
                    @endforeach
                    </div><div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>     
                    <div class="panel-footer text-center">
                        <a href="pages-messages.html">Show all messages</a>
                    </div>                            
                </div>                        
            </li>
        </ul>
        <div class="page-content-wrap">
        @yield('content')                    
        </div>                            
    </div>
</div>
<div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
    <div class="mb-container">
        <div class="mb-middle">
            <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
            <div class="mb-content">
                <p>{{ __('confirmLogout') }}</p>                    
                <p>{{ __('confirmLogout2') }}</p>
            </div>
            <div class="mb-footer">
                <div class="pull-right">
                    <a href="{{ url('/logout') }}" class="btn btn-success btn-lg">{{ __('yes') }}</a>
                    <button class="btn btn-default btn-lg mb-control-close">{{ __('no') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('public')}}/js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{asset('public')}}/js/plugins/bootstrap/bootstrap.min.js"></script>  
<script type='text/javascript' src="{{asset('public')}}/js/plugins/icheck/icheck.min.js"></script>        
<script type="text/javascript" src="{{asset('public')}}/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="{{asset('public')}}/js/plugins/scrolltotop/scrolltopcontrol.js"></script>
<script type="text/javascript" src="{{asset('public')}}/js/plugins/bootstrap/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="{{asset('public')}}/js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="{{asset('public')}}/js/plugins/datatables/jquery.dataTables.min.js"></script> 
<script src="{{ asset('public/assets/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{asset('public')}}/js/plugins.js"></script>
<script type="text/javascript" src="{{asset('public')}}/js/actions.js"></script>
@if(session('messages'))
<script type="text/javascript">
    $(document).ready(function(){
        var html = `
            <div class="col-md-2 mt-5 text-center notification noti-{{ session('messages')['type'] }}">
                <p class="mt-3">
                    <i class="fa fa-check"></i>
                    <b id="notiMessage">{{ session('messages')['message'] }}!</b>
                </p>
            </div>
        `;
        $('body').append(html);
        setTimeout(function(){
            $('.notification').remove();
        }, 3000);
    }); 
</script>
@endif
</body>
</html>
