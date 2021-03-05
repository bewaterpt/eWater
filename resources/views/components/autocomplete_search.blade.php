<div class="form-group col-md-12 component autocomplete-search">
    <input type="hidden" name="{{ $fieldName }}" value="[]"/>
    <label for="{{ $fieldId }}">{{ __($translationString) }}</label>
    <div contenteditable="true" id="{{ $fieldId }}" class="form-control search autocomplete{{ isset($inline) && $inline ?? '' === true ?: ' inline'}}" data-ajax="{{ $uri }}">
    </div>
    <div id="autocomplete-list" class="border position-absolute">
        <ul>
            <div class="loading">
                <div class="spinner">
                </div>
            </div>
        </ul>
    </div>
    @if (!$inline ?? '')
        <div id="selection-list">
        </div>
    @endif
    <div>
</div>
