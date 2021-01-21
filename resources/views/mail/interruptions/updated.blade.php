@extends('mail.base')

@section('styling')
    <style>
        .button {
            height: 35px;
            padding: 0 10px;
            border: none;
            border-radius: 4px;
            background-color: rgb(9, 148, 255);
            margin: auto;
            margin-top: 30px;
            right: 0;
            left: 0;
            font-weight: 700;
            color: white;
            pointer-events: all;
        }

        .button-container {
            pointer-events: none;
        }

        .button:hover {
            cursor: pointer;
            background-color: rgb(9, 115, 196);
        }

        .button:active {
            box-shadow: inset 1px -3px 10px 0px rgb(0 0 0 / 50%)
        }
    </style>
@endsection

@section('mail-content')
    {{-- @Lang('mail.interruptions.' . $scheduled . '.updated')
    <br>
    <br> --}}
    <table style="text-align: left;border-collapse: collapse;" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="width: 220px">
                <b>@Lang('general.interruptions.type'):</b>
            </td>
            <td>
                {{ $prevInt->scheduled ? __('general.interruptions.is_scheduled') : __('general.interruptions.is_unscheduled')}}
            </td>
        </tr>
        <tr>
            <td>
                <b>@Lang('mail.interruptions.ref'):</b>
            </td>
            <td>
                {!! $prevInt->work_id !== $newInt->work_id ? "<span style='color: gray'><s>" . $prevInt->work_id . "</s></span> <span style='color: darkblue'>" . $newInt->work_id . "</span>" : $prevInt->work_id !!}
            </td>
        </tr>
        <tr>
            <td>
                <b>@Lang('general.interruptions.start_date'):</b>
            </td>
            <td>
                {!! $prevInt->start_date !== $newInt->start_date ? "<span style='color: gray'><s>" . $carbon->parse($prevInt->start_date)->format('Y-m-d H:i') . "</s></span> <span style='color: darkblue'>" . $carbon->parse($newInt->start_date)->format('Y-m-d H:i') . "</span>" : $carbon->parse($prevInt->start_date)->format('Y-m-d H:i') !!}
            </td>
        </tr>
        <tr>
            <td style="white-space: nowrap">
                <b>@Lang('general.interruptions.reinstatement_date'):</b>
            </td>
            <td>
                {!! $prevInt->reinstatement_date !== $newInt->reinstatement_date ? "<span style='color: gray'><s>" . $carbon->parse($prevInt->reinstatement_date)->format('Y-m-d H:i') . "</s></span> <span style='color: darkblue'>" . $carbon->parse($newInt->reinstatement_date)->format('Y-m-d H:i') . "</span>" : $carbon->parse($prevInt->reinstatement_date)->format('Y-m-d H:i') !!}
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">
                <b>@Lang('general.interruptions.motive'): </b>
            </td>
            <td>
                @if ($prevInt->motive == null)
                    {!! "<span style='color: darkblue'>" . $newInt->motive->name . "</span>" !!}
                @else
                    {!! $prevInt->motive->id !== $newInt->motive->id ? "<span style='color: gray'><s>" . $prevInt->motive->name . "</s></span> <span style='color: darkblue'>" . $newInt->motive->name . "</span>" : $prevInt->motive->name !!}
                @endif
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top">
                <b>@Lang('general.interruptions.affected_area'): </b>
            </td>
            <td>
                {!! $helpers->transliterate($prevInt->affected_area) !== $helpers->transliterate($newInt->affected_area) ? "<span style='color: gray'><s>" . $prevInt->affected_area . "</s></span> <span style='color: darkblue'>" . $newInt->affected_area . "</span>" : $prevInt->affected_area !!}
            </td>
        </tr>
    </table>
@endsection
