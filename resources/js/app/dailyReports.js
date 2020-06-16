$(document).ready(() => {

    let today = new Date();

    function ISODateString(d){
        function pad(n){return n<10 ? '0'+n : n}
        return d.getUTCFullYear()+'-'
        + pad(d.getUTCMonth()+1)+'-'
        + pad(d.getUTCDate())+'T'
        + pad(d.getUTCHours())+':'
        + pad(d.getUTCMinutes());
        // + pad(d.getUTCSeconds())+'Z'
    }

    if($('#daily-reports-create').length > 0) {
        /**
         * Gets the selected article info
         *
         * @param {Event} event - the inherited event that called the function
         */
        function getArticleInfo(event) {
            $.ajax({
                type: 'POST',
                url: '/daily-reports/article/get-info',
                data: { id: $(event.target).val()},
                dataType: 'json',
                success: (data) => {
                    console.log('Data: ', data);
                    if(data.article.fixo == 1) {
                        $(event.target).closest('tr').find('#inputUnitPrice').val(parseFloat(data.article.precoUnitario).toFixed(2)).prop('readonly', true);
                    } else {
                        $(event.target).closest('tr').find('#inputUnitPrice').val(0).prop('read-only', true);
                    }
                }
            });
        }

        /**
         * Removes desired table row
         *
         * @param {Event} event - the inherited event that called the function
         */
        function removeLine(event) {
            $(event.target).closest('tr').remove();
        }

        $('#inputArticle').on('change', (e) => {
            getArticleInfo(e);
        });

        $('#addRow').on('click', () => {
            let tr = $('table#report-lines tbody tr:last-child').clone();
            tr.find('input').val('').prop('readonly', false);
            tr.find(':not(td:first-child) input[type="number"]').val(0);
            tr.removeClass('first');
            tr.find('#inputArticle').on('change', (e) => {
                getArticleInfo(e)
            });
            tr.find('#removeRow').on('click', (e) => {
                removeLine(e);
            });
            tr.find('#inputDatetime').val(ISODateString(today))
            console.log(tr[0]);
            $('table#report-lines tbody').append(tr);

        });
        $('#inputDatetime').val(ISODateString(today));
    }
});
