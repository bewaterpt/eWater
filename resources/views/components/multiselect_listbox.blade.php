<fieldset id="multiselect-listbox-{{ $hiddenField }}" class="border-top border-bottom mb-3 multiselect-listbox" data-field="{{ $hiddenField }}">
    <div class="form-row mt-2 justify-content-center">
        <div class="form-group col-md-5">
            <label for="selectLeft">@Lang('forms.fields.without_access')</label>
            <select class="form-control selectpicker" multiple id="selectLeft">
                @foreach($left as $role)
                    <option value="{{ $role->id }}">{{ $role->{$lField} }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1 position-relative">
            <div id="btnContainer" class="position-absolute m-auto">
                <button id="addItems" class="btn btn-secondary">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <br>
                <button id="removeItems" class="btn btn-secondary mt-2">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
        <div class="form-group col-md-5">
            <label for="selectRight">@Lang('forms.fields.with_access')</label>
            <select class="form-control selectpicker" multiple id="selectRight">
                @foreach($right as $rItem)
                    <option value="{{ $rItem->id }}">{{ $rItem->{$rField} }}</option>
                @endforeach
            </select>
        </div>
    </div>
</fieldset>
