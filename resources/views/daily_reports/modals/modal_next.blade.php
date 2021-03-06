<!-- Modal -->
<div class="modal fade" id="modalNextStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNextStatusLabel">@Lang('general.daily_reports.forward_process')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formNextStatus" method="POST" action="{{ route('daily_reports.next', ['id' => $report->latestUpdate()->id]) }}">
                @csrf
                <div class="modal-body">
                    <label for="text-editor">@Lang('forms.fields.comments')</label>
                    <textarea class="text-editor" name="comment"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@Lang('general.close')</button>
                    <input type="submit" value="{{__('general.save')}}" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>
</div>
