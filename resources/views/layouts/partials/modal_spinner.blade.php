<!-- Modal -->
<div class="modal fade" id="modalSpinner" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content w-25">
            <div class="modal-body min-h-100 pt-4">
                <div class="col-md-12 pt-5" id="content">
                    @include('layouts.partials.spinner_backdrop', ['show' => true])
                </div>
            </div>
        </div>
    </div>
</div>
