<!-- Modal -->
<div class="modal fade" id="modalTeamUsers" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <i class="ri-information-line ri-xl text-info mr-2"></i>
                <h5 class="modal-title" id="modalPrevStatusLabel">@Lang('settings.teams.users_on_this_team')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body min-h-200">
                <div class="col-md-12" id="content">
                    <div class="body p-2">
                    </div>
                    @include('layouts.partials.spinner_backdrop', ['show' => false])
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@Lang('general.close')</button>
            </div>
        </div>
    </div>
</div>
