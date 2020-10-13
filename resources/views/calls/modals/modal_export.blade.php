<!-- Modal -->
<div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content w-25">
            {{-- <div class="modal-header">
                <i class="ri-information-line ri-xl text-info mr-2"></i>
                <h5 class="modal-title" id="modalPrevStatusLabel">@Lang('general.daily_reports.process_status_comment')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> --}}
            <div class="modal-body min-h-100 pt-4">
                <div class="col-md-12 pt-5" id="content">
                    @include('layouts.partials.spinner_backdrop', ['show' => true])
                </div>
            </div>
        </div>
    </div>
</div>
