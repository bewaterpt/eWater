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
    <tr>
        <td style="padding: 30px 20px;text-align: left;">
            @Lang('mail.interruptions.updated')
            <br>
            <br>
            <table style="text-align: left;border-collapse: collapse;" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        <b>@Lang('mail.interruptions.ref'):</b> {!! $prevInt->work_id !== $newInt->work_id ? "<span style='color: gray'><s>" . $prevInt->work_id . "</s></span> <span style='color: darkblue'>" . $newInt->work_id . "</span>" : $prevInt->work_id !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>@Lang('general.interruptions.start_date'):</b> {!! $prevInt->start_date !== $newInt->start_date ? "<span style='color: gray'><s>" . $carbon->parse($prevInt->start_date)->format('Y-m-d H:i:s') . "</s></span> <span style='color: darkblue'>" . $carbon->parse($newInt->start_date)->format('Y-m-d H:i:s') . "</span>" : $carbon->parse($prevInt->start_date)->format('Y-m-d H:i:s') !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>@Lang('general.interruptions.reinstatement_date'):</b> {!! $prevInt->reinstatement_date !== $newInt->reinstatement_date ? "<span style='color: gray'><s>" . $carbon->parse($prevInt->reinstatement_date)->format('Y-m-d H:i:s') . "</s></span> <span style='color: darkblue'>" . $carbon->parse($newInt->reinstatement_date)->format('Y-m-d H:i:s') . "</span>" : $carbon->parse($prevInt->reinstatement_date)->format('Y-m-d H:i:s') !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>@Lang('general.interruptions.type'):</b> {!! $prevInt->scheduled !== $newInt->scheduled ? "<span style='color: gray'><s>" . ($prevInt->scheduled ? __('general.interruptions.is_scheduled') : __('general.interruptions.is_unscheduled')) . "</s></span> <span style='color: darkblue'>" . ($newInt->scheduled ? __('general.interruptions.is_scheduled') : __('general.interruptions.is_unscheduled')) . "</span>" : ($prevInt->scheduled ? __('general.interruptions.is_scheduled') : __('general.interruptions.is_unscheduled')) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <b>@Lang('general.interruptions.affected_area'): </b>
                        {!! $helpers->transliterate($prevInt->affected_area) !== $helpers->transliterate($newInt->affected_area) ? "<span style='color: gray'><s>" . $prevInt->affected_area . "</s></span> <span style='color: darkblue'>" . $newInt->affected_area . "</span>" : $prevInt->affected_area !!}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;padding: 20px 0;">
                        <a class="button-container" href="{{ route('interruptions.view', ['id' => $prevInt->id]) }}">
                            <button class="button" style="">@Lang('mail.go_to_model')</button>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
