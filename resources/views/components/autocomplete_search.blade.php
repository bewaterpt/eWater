<div class="form-group col-md-12"  >
    <input type="hidden" name="{{ $fieldName }}" data-ajax="{{ $uri }}"/>
    <label for="{{ $fieldId }}">{{ __($translationString) }}</label>
    <div contenteditable="true" id="{{ $fieldId }}" class="form-control search autocomplete" data-ajax="{{ $uri }}">
    </div>
    <div id="autocomplete-list">
        <ul>
            
        </ul>
    </div>
</div>
