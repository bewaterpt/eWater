@section('content')

<div class="inner-container-right">

    <div class="inner-title-container">
        <div class="inner-title">
            <h2>@lang('global.permissions')</h2>
        </div>
    </div>
    <div class="ln_solid after-title"></div>

      <div id="settings-permissions">

        {{array_shift($roles_header) }}

          <table>
                @foreach( $categorized_routes as $title => $categorized_route )
                    {{$counter = 0}}
                    {{$select_all = 1}}
                    {{$check_title=$title}}

                        <tr class="area">
                            <td class="category-title" >
                                <div class="header">
                                    {{ __('permissions.'.$title) }}
                                </div>
                            </td>
                            @foreach( $roles_header as $role_header )
                                <td class="role-name">
                                    <div class="header">
                                        {{ $role_header }}
                                    </div>
                                </td>
                            @endforeach
                        </tr>

                        @foreach( $categorized_route as $element )
                            <tr>
                                <td class="extra-padding-left-25">@lang('permissions.'.$element)</td>
                                {{$select_count = 1}}
                                @foreach( $role_data as $role )

                                    <td class="text-center permission-value">
                                        @if( isset( $role['permissions'][$title][$counter] ) )
                                            @if( $role['permissions'][$title][$counter]['checked'] )
                                                <input type="checkbox" class="square green" name="[{{ $role['role'] }}][{{ $role['permissions'][$title][$counter]['route'] }}]" value="1" checked>
                                            @else
                                                <input  type="checkbox" class="square green" name="[{{ $role['role'] }}][{{ $role['permissions'][$title][$counter]['route'] }}]" value="1">
                                            @endif
                                        @endif
                                    </td>
                                    {{$select_count++ }}
                                @endforeach
                            {{$counter++ }}
                            </tr>
                        @endforeach

                @endforeach

          </table>

        <div class="loading-cpage register hidden"><svg id="load" x="0px" y="0px" viewBox="0 0 150 150"><circle id="loading-inner" cx="75" cy="75" r="60"></circle></svg></div>

        <div class="button-area extra-margin-top-30 extra-margin-bottom-30 text-right">
            <a href="#" class="btn btn-primary js-permissions-update extra-mid-width">@lang('global.save')</a>
        </div>
    </div>
</div>

@endsection
