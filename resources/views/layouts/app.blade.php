<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        {{-- <link rel="icon" href="{{ asset('/favicon.png') }}" type="image/x-icon"/> --}}
        <link href="https://fonts.googleapis.com/css?family=Courier" rel="stylesheet">
        <script src="https://cdn.tiny.cloud/1/cx6voesmf6uv8rlv64tv92yhqix2qj2puwg1cwcdvrshvn6r/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                                {{-- {{dd(collect(Route::getRoutes())->map(function ($route) {
                                    return in_array('allowed', $route->gatherMiddleware()) ? $route->getName() : null;
                                })->filter(function ($value) {
                                    return !is_null($value);
                                }))}} --}}
                                @if($pmodel->can('settings.'))
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            @Lang('settings.settings') <span class="caret"></span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
                                            @if($pmodel->can('settings.agents.list'))
                                                <a title="Temporáriamente Indisponível" class="dropdown-item disabled {{Route::currentRouteName() == 'settings.agents.list' ? 'bg-primary text-white disabled' : ''}}" href="{{ route('settings.agents.list')}}">@lang('general.agents')</a>
                                            @endif
                                            @if($pmodel->can('settings.failure_types.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.failure_types.list' ? 'bg-primary text-white disabled' : ''}}" href="{{ route('settings.failure_types.list')}}">@lang('general.failure_types')</a>
                                            @endif
                                            @if($pmodel->can('settings.permissions.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.permissions.list' ? 'bg-primary text-white disabled' : ''}}" href="{{ route('settings.permissions.list')}}">@lang('general.permissions')</a>
                                            @endif
                                            @if($pmodel->can('settings.roles.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.roles.list' ? 'bg-primary text-white disabled' : ''}}" href="{{ route('settings.roles.list')}}">@lang('general.roles')</a>
                                            @endif
                                            @if($pmodel->can('settings.statuses.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.statuses.list' ? 'bg-primary text-white disabled' : ''}}" href="{{ route('settings.statuses.list')}}">@lang('general.statuses')</a>
                                            @endif
                                            @if($pmodel->can('settings.teams.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.teams.list' ? 'bg-primary text-white disabled' : ''}}" href="{{ route('settings.teams.list')}}">@lang('general.teams')</a>
                                            @endif
                                            @if($pmodel->can('settings.users.list'))
                                                <a class="dropdown-item {{Route::currentRouteName() == 'settings.users.list' ? 'bg-primary text-white disabled' : ''}}" href="{{ route('settings.users.list')}}">@lang('general.users')</a>
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
                                @if($pmodel->can('interruptions.'))
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            @Lang('general.interruptions.interruptions') <span class="caret"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu" aria-labelledby="navbarDropdown">
                                            @if($pmodel->can('interruptions.list'))
                                                <a class="dropdown-item {{ Route::currentRouteName() == 'interruptions.list' ? 'disabled active' : '' }}" href="{{ route('interruptions.list')}}">@lang('general.list_all')</a>
                                            @endif
                                            @if($pmodel->can('interruptions.list_unscheduled'))
                                                <a class="dropdown-item {{ Route::currentRouteName() == 'interruptions.list_unscheduled' ? 'disabled active' : '' }}" href="{{ route('interruptions.list_unscheduled')}}">@lang('general.list_unscheduled')</a>
                                            @endif
                                            @if($pmodel->can('interruptions.list_scheduled'))
                                                <a class="dropdown-item {{ Route::currentRouteName() == 'interruptions.list_scheduled' ? 'disabled active' : '' }}" href="{{ route('interruptions.list_scheduled')}}">@lang('general.list_scheduled')</a>
                                            @endif
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
                                        {{-- <a class="dropdown-item {{ Route::currentRouteName() == 'settings.users.edit_self' ? 'bg-primary text-white' : '' }}" href="{{ route('settings.users.edit_self') }}">
                                            @Lang('general.edit_profile')
                                        </a> --}}
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
                                <a id="navbarDropdown" class="lang nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
                        <div class="card float-right d-none d-md-block" id="bug-report">
                            <a href="mailto:helpdesk@bewater.com.pt?subject=Bug%20Report {{ $currentUser ? ': ' . $currentUser->name : ''}}" title="{{ __('tooltips.report_a_bug') }}" class="btn btn-link btn-md btn-primary text-white">
                                    <i class="fas fa-headset"></i>
                            </a>
                        </div>
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
        </div>
        <!--scripts-->
        <script src="{{ asset('js/vendor.js') }}"></script>
        <script src="{{ asset('js/manifest.js') }}"></script>
        <script src="{{ asset('js/helpers.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>

