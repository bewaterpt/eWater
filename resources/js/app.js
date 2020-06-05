/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document).ready(() => {

    // Setup ajax headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    $('#inputArticle').on('change', (e) => {
        getArticleInfo(e);
    });

    $('#addRow').on('click', () => {
        let tr = $('table#insert-reports tbody tr:last-child').clone();
        tr.find('input').val('').prop('readonly', false);
        tr.find(':not(td:first-child) input[type="number"]').val(0);
        tr.removeClass('first');
        tr.find('#inputArticle').on('change', (e) => {
            getArticleInfo(e)
        });
        console.log(tr[0]);
        $('table#insert-reports tbody').append(tr);

    });

    mapOnArticleChange();
});
