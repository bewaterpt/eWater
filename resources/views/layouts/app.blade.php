<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/vendor.js') }}" defer></script>
        <script src="{{ asset('js/manifest.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        {{-- <link rel="icon" href="{{ asset('/favicon.png') }}" type="image/x-icon"/> --}}
        <link href="https://fonts.googleapis.com/css?family=Courier" rel="stylesheet">
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }} {{--<img class="float-left" src="{{ asset('/favicon.png') }}" style="max-width: 30px"/>--}}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        @guest
                        @else
                         <!-- MENU -->
                            <ul class="navbar-nav mr-auto">
                                @if($pmodel->can('settings.'))
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            @Lang('settings.settings') <span class="caret"></span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
                                            @if($pmodel->can('settings.agents.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.agents.list' ? 'active disabled' : ''}}" href="{{ route('settings.agents.list')}}">@lang('general.agents')</a>
                                            @endif
                                            @if($pmodel->can('settings.roles.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.roles.list' ? 'active disabled' : ''}}" href="{{ route('settings.roles.list')}}">@lang('general.roles')</a>
                                            @endif
                                            @if($pmodel->can('settings.failure_types.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.failure_types.list' ? 'active disabled' : ''}}" href="{{ route('settings.failure_types.list')}}">@lang('general.failure_types')</a>
                                            @endif
                                            @if($pmodel->can('settings.users.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.users.list' ? 'active disabled' : ''}}" href="{{ route('settings.users.list')}}">@lang('general.users')</a>
                                            @endif
                                        </div>
                                    </li>
                                @endif
                                @if($pmodel->can('daily_reports.'))
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            @Lang('general.daily_reports.daily_reports') <span class="caret"></span>
                                        </a>
                                        {{-- {{dd(Route::currentRouteName())}} --}}
                                        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
                                            @if($pmodel->can('daily_reports.list'))
                                                <a class="dropdown-item {{ Route::currentRouteName() == 'daily_reports.list' ? 'disabled active' : '' }}" href="{{ route('daily_reports.list')}}">@lang('general.daily_reports.list')</a>
                                            @endif
                                            @if($pmodel->can('daily_reports.create'))
                                                <a class="dropdown-item {{ Route::currentRouteName() == 'daily_reports.create' ? 'active disabled' : '' }}" href="{{ route('daily_reports.create')}}">@lang('general.daily_reports.create')</a>
                                            @endif
                                            {{-- @if($pmodel->can('daily_reports.pending'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'daily_reports.pending' ? 'active disabled' : ''}}" href="{{ route('daily_reports.pending')}}">@lang('general.daily_reports.pending')</a>
                                            @endif
                                            @if($pmodel->can('daily_reports.approved'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'daily_reports.approved' ? 'active disabled' : ''}}" href="{{ route('daily_reports.approved')}}">@lang('general.daily_reports.approved')</a>
                                            @endif --}}
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        @endguest
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item {{ Route::currentRouteName() == 'settings.users.edit_self' ? 'active' : '' }}" href="{{ route('settings.users.edit_self') }}">
                                            @Lang('general.edit_profile')
                                        </a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"  onclick="
                                            event.preventDefault();
                                            document.getElementById('logout-form').submit();"
                                        >
                                            @Lang('general.logout')
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>

                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ explode('_', strtoupper(app()->getLocale()))[0] }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @foreach (config('app.locales') as $locale => $name)
                                            <a class="dropdown-item {{ $locale == app()->getLocale() ? 'active' : '' }}" href="{{ $locale != app()->getLocale() ? route('settings.locale.change', ['locale' => $locale]) : '' }}" ?>
                                                {!! $locale == app()->getLocale() ?  '<i class="fas fa-caret-right" style="text-indent: -13px; margin-right: -4px;"></i>' : ''!!} {{ $name }} ({{ strtoupper($locale) }})
                                            </a>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @if($errors)
                    @if($errors->custom->any())
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-8 alert alert-danger">
                                    <ul>
                                        @foreach ($errors->custom->all() as $error)
                                            <li>{!! $error !!}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                @yield('content')
            </main>
            <div class="card float-right" id="bug-report">
                <a href="mailto:helpdesk@bewater.com.pt" class="text-white">
                    <div class="card-header bg-primary">
                        <i class="fas fa-bug"></i>
                    </div>
                </a>
            </div>
        </div>
        <!--scripts-->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
