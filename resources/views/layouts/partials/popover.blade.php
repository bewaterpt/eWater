<div id="{{ $id ?? "generic" }}-popover" class="popover d-none">
    <div id="title">
        <div class="{{ $type === 'error' ? 'bg-flamingo' : '' }}">
            {!! $title ?? "Popover" !!}
        </div>
    </div>
    <div id="content">
        <div>
            {!! $content ?? "Popover Generico " !!}
        </div>
    </div>
</div>
"
