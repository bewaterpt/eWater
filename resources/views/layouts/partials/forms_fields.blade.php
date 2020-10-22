<div id="fields" class="d-none">
    <div id="fieldText" class="field field-text mb-3 col-md-6">
        <div class="col spacing row border rounded py-3 shadow-sm position-relative m-0">
            <a href="#" class="text-danger position-absolute" id="remove-field" style="top:5px;right:10px;z-index:1000;">
                <i class="fas fa-times" style></i>
            </a>
            <div class="col-md-12 mb-2">
                <strong>
                    @Lang('forms.fields.text_field')
                </strong>
            </div>
            <input type="hidden" name="type[]" value="text">
            <input type="hidden" name="tag[]" value="input">
            <input type="hidden" name="options[]">

            <div class="form-group col-md-6">
                <label for="inputName">@Lang('forms.fields.name')</label>
                <input type="text" name="name[]" class="form-control" id="inputName" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputLabel">@Lang('forms.fields.label')</label>
                <input type="text" name="label[]" class="form-control" id="inputLabel" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label for="inputTitle">@Lang('forms.fields.tooltip')</label>
                <input type="text" name="title[]" class="form-control" id="inputTitle" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPlaceholder">@Lang('forms.fields.placeholder')</label>
                <input type="text" name="placeholder[]" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-12">
                <input type="checkbox" name="required[]" id="inputRequired">
                <label for="inputRequired">@Lang('forms.fields.required')</label>
            </div>
        </div>
    </div>
    <div id="fieldSelect" class="field field-text mb-3 col-md-6">
        <div class="col spacing row border rounded py-3 shadow-sm position-relative m-0">
            <a href="#" class="text-danger position-absolute" id="remove-field" style="top:5px;right:10px;z-index:1000;">
                <i class="fas fa-times" style></i>
            </a>
            <div class="col-md-12 mb-2">
                <strong>
                    @Lang('forms.fields.select_field')
                </strong>
            </div>
            <input type="hidden" name="type[]" value="select">
            <input type="hidden" name="tag[]" value="select">
            <input type="hidden" name="options[]">

            <div class="form-group col-md-6">
                <label for="inputName">@Lang('forms.fields.name')</label>
                <input type="text" name="name[]" class="form-control" id="inputName" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputLabel">@Lang('forms.fields.label')</label>
                <input type="text" name="label[]" class="form-control" id="inputLabel" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label for="inputTitle">@Lang('forms.fields.tooltip')</label>
                <input type="text" name="title[]" class="form-control" id="inputTitle" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPlaceholder">@Lang('forms.fields.placeholder')</label>
                <input type="text" name="placeholder[]" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-12">
                <input type="checkbox" name="required[]" id="inputRequired">
                <label for="inputRequired">@Lang('forms.fields.required')</label>
            </div>
            <div class="form-group col-md-12">
                <label for="inputPlaceholder">Inserir Opção</label>
                <input type="text" name="" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
                <a href="#" class="text-success position-absolute" id="insert-option" style="top:22%;right:30px">
                    <i class="fas fa-plus" style></i>
                </a>
                <select class="form-control mt-2 disabled select-field" multiple aria-multiselectable="true">
                </select>
            </div>
        </div>
    </div>
    <div id="fieldTextarea" class="field field-text mb-3 col-md-6">
        <div class="col spacing row border rounded py-3 shadow-sm position-relative m-0">
            <a href="#" class="text-danger position-absolute" id="remove-field" style="top:5px;right:10px;z-index:1000;">
                <i class="fas fa-times" style></i>
            </a>
            <div class="col-md-12 mb-2">
                <strong>
                    @Lang('forms.fields.textarea_field')
                </strong>
            </div>
            <input type="hidden" name="type[]" value="textarea">
            <input type="hidden" name="tag[]" value="textarea">
            <input type="hidden" name="options[]">

            <div class="form-group col-md-6">
                <label for="inputName">@Lang('forms.fields.name')</label>
                <input type="text" name="name[]" class="form-control" id="inputName" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputLabel">@Lang('forms.fields.label')</label>
                <input type="text" name="label[]" class="form-control" id="inputLabel" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label for="inputTitle">@Lang('forms.fields.tooltip')</label>
                <input type="text" name="title[]" class="form-control" id="inputTitle" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPlaceholder">@Lang('forms.fields.placeholder')</label>
                <input type="text" name="placeholder[]" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-12">
                <input type="checkbox" name="required[]" id="inputRequired">
                <label for="inputRequired">@Lang('forms.fields.required')</label>
            </div>
        </div>
    </div>
    <div id="fieldFile" class="field field-text mb-3 col-md-6">
        <div class="col spacing row border rounded py-3 shadow-sm position-relative m-0">
            <a href="#" class="text-danger position-absolute" id="remove-field" style="top:5px;right:10px;z-index:1000;">
                <i class="fas fa-times" style></i>
            </a>
            <div class="col-md-12 mb-2">
                <strong>
                    @Lang('forms.fields.file_field')
                </strong>
            </div>
            <input type="hidden" name="type[]" value="file">
            <input type="hidden" name="tag[]" value="file">
            <input type="hidden" name="options[]">

            <div class="form-group col-md-6">
                <label for="inputName">@Lang('forms.fields.name')</label>
                <input type="text" name="name[]" class="form-control" id="inputName" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputLabel">@Lang('forms.fields.label')</label>
                <input type="text" name="label[]" class="form-control" id="inputLabel" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label for="inputTitle">@Lang('forms.fields.tooltip')</label>
                <input type="text" name="title[]" class="form-control" id="inputTitle" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPlaceholder">@Lang('forms.fields.placeholder')</label>
                <input type="text" name="placeholder[]" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-12 row">
                <label for="inputRequired">@Lang('forms.fields.required')</label>
                <input type="checkbox" name="required[]" id="inputRequired">
                <label for="inputRequired">@Lang('forms.fields.allow_multiple')</label>
                <input type="checkbox" name="multiple[]" id="inputMultiple">
            </div>
        </div>
    </div>
    <div id="fieldCheckbox" class="field field-text mb-3 col-md-6">
        <div class="col spacing row border rounded py-3 shadow-sm position-relative m-0">
            <a href="#" class="text-danger position-absolute" id="remove-field" style="top:5px;right:10px;z-index:1000;">
                <i class="fas fa-times" style></i>
            </a>
            <div class="col-md-12 mb-2">
                <strong>
                    @Lang('forms.fields.select_field')
                </strong>
            </div>
            <input type="hidden" name="type[]" value="checkbox">
            <input type="hidden" name="tag[]" value="checkbox">
            <input type="hidden" name="options[]">

            <div class="form-group col-md-6">
                <label for="inputName">@Lang('forms.fields.name')</label>
                <input type="text" name="name[]" class="form-control" id="inputName" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputLabel">@Lang('forms.fields.label')</label>
                <input type="text" name="label[]" class="form-control" id="inputLabel" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label for="inputTitle">@Lang('forms.fields.tooltip')</label>
                <input type="text" name="title[]" class="form-control" id="inputTitle" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPlaceholder">@Lang('forms.fields.placeholder')</label>
                <input type="text" name="placeholder[]" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-12">
                <input type="checkbox" name="required[]" id="inputRequired">
                <label for="inputRequired">@Lang('forms.fields.required')</label>
            </div>
            <div class="form-group col-md-12">
                <label for="inputPlaceholder">Inserir Opção</label>
                <input type="text" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
                <a href="#" class="text-success position-absolute" id="insert-option" style="top:22%;right:30px">
                    <i class="fas fa-plus" style></i>
                </a>
                <select class="form-control mt-2 disabled" multiple aria-multiselectable="true">
                </select>
            </div>
        </div>
    </div>
    <div id="fieldRadio" class="field field-text mb-3 col-md-6">
        <div class="col spacing row border rounded py-3 shadow-sm position-relative m-0">
            <a href="#" class="text-danger position-absolute" id="remove-field" style="top:5px;right:10px;z-index:1000;">
                <i class="fas fa-times" style></i>
            </a>
            <div class="col-md-12 mb-2">
                <strong>
                    @Lang('forms.fields.select_field')
                </strong>
            </div>
            <input type="hidden" name="type[]" value="checkbox">
            <input type="hidden" name="tag[]" value="checkbox">

            <div class="form-group col-md-6">
                <label for="inputName">@Lang('forms.fields.name')</label>
                <input type="text" name="name[]" class="form-control" id="inputName" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputLabel">@Lang('forms.fields.label')</label>
                <input type="text" name="label[]" class="form-control" id="inputLabel" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
                <label for="inputTitle">@Lang('forms.fields.tooltip')</label>
                <input type="text" name="title[]" class="form-control" id="inputTitle" value="" required="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPlaceholder">@Lang('forms.fields.placeholder')</label>
                <input type="text" name="placeholder[]" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
            </div>
            <div class="form-group col-md-12">
                <input type="checkbox" name="required[]" id="inputRequired">
                <label for="inputRequired">@Lang('forms.fields.required')</label>
            </div>
            <div class="form-group col-md-12">
                <label for="inputPlaceholder">Inserir Opção</label>
                <input type="text" class="form-control" id="inputPlaceholder" value="" autocomplete="off">
                <a href="#" class="text-success position-absolute" id="insert-option" style="top:22%;right:30px">
                    <i class="fas fa-plus" style></i>
                </a>
                <select class="form-control mt-2 disabled" multiple aria-multiselectable="true">
                </select>
            </div>
        </div>
    </div>
</div>

