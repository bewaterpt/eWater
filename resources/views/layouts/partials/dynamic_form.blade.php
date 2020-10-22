<form id="{{ $form->name }}" action="{{ route('settings.forms.store') }}" method="POST">
    {{-- {{dd($form->fields()->get())}} --}}
    @foreach ($form->fields()->get() as $field)
        <label for="{{ $field->name }}">{{ $field->label }}</label>
        @if($field->type == 'select')
            {{dd($field->options)}}
            <{{ $field->tag }} type="{{ $field->type }}" class="form-control {{ $field->getClasses() }}">
                @foreach($field->getOptions() as $option)
                    {{-- {{dd($option)}} --}}
                    <option value="{{ $option->value }}">{{ $option->label }}</option>
                @endforeach
            </{{$field->tag}}>
        @elseif(in_array($field->type, ['checkbox', 'radio']))
            @foreach($field->getOptions() as $option)
                <{{ $field->tag }} type="{{ $field->type }}" class="form-control {{ $field->getClasses() }}" value="{{ $option->value }}">{{ $option->label }}</{{$field->type}}>
            @endforeach
        @else
            <{{ $field->tag }} type="{{ $field->type }}" class="form-control {{ $field->getClasses() }}"></{{ $field->tag }}>a
        @endif
    @endforeach
</form>
