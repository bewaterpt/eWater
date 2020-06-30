<!-- Modal -->
<div class="modal fade" id="modalInfo{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <i class="far fa-question-circle mt-1 mr-3 text-info h4 m-0"></i>
                <h5 class="modal-title" id="modalPrevStatusLabel">@Lang('info.title', ['subject' => $subject])</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body min-h-200">
                <div class="col-md-12" id="comment">
                    <div class="body p-2">
                        {!! $content !!}
                    </div>
                    @include('layouts.partials.spinner_backdrop')
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@Lang('general.close')</button>
            </div>
        </div>
    </div>
</div>