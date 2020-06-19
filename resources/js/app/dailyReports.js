$(document).ready(() => {

    let today = new Date();

    console.log($('input.work-number'));

    function ISODateString(d){
        function pad(n){return n<10 ? '0'+n : n}
        return d.getUTCFullYear()+'-'
        + pad(d.getUTCMonth()+1)+'-'
        + pad(d.getUTCDate())+'T'
        + pad(d.getUTCHours())+':'
        + pad(d.getUTCMinutes());
        // + pad(d.getUTCSeconds())+'Z'
    }

    $('#report').on('submit', (event) => {
        event.preventDefault();
        let rows = [];
        $('div.card.work').each((index, work) => {
            rows = {[$(work).find('input.work-number').val()]: {}};
            $(work).find('tbody tr').each((index, tr) => {
                trIndex = index;
                rows[$(work).find('input.work-number').val()] = {[trIndex]: {}};

                $(document).find('.card.work input:not(.work-number), select').each((index, input) => {
                    rows[$(work).find('input.work-number').val()][trIndex][input.name] = input.value;
                });
            });
        });

        console.log(JSON.stringify(rows));

        $.ajax({
            method: 'POST',
            url: $('#report').attr('action'),
            data: JSON.stringify(rows),
            contentType: 'json',
            success: (data) => {
                console.log(data);
            }
        });
    });

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
        function removeRow(event) {
            $(event.target).closest('tr').remove();
        }

        function removeWork(event) {
            $(event.target).closest('.card.work:not(#original-work)').remove();
        }

        function addRow (event) {
            let tr = $(event.target).parents('.card.work').find('table#report-lines tbody tr:last-child').clone();
            console.log(tr);
            tr.find('input').val('').prop('readonly', false);
            tr.find(':not(td:first-child) input[type="number"]').val(0);
            tr.removeClass('first');
            tr.find('#inputArticle').on('change', (e) => {
                getArticleInfo(e)
            });
            tr.find('#removeRow').on('click', (e) => {
                removeRow(e);
            });
            tr.find('#inputDatetime').val(ISODateString(today))
            console.log(tr[0]);
            $(event.target).parents('.card.work').find('table#report-lines tbody').append(tr);
            // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
            $('a[href="#"]').click(function(event) {
                event.preventDefault();
            });
            replaceVal(event);
        }

        function replaceVal(event) {
            work = $(event.target).closest('.card.work');
            console.log(work);
            work.find('input.driven-km').attr('name', work.find('input.driven-km').attr('name').replace(/\[.*\b\]/, '[' + (work.find('input.work-number').val() != 0 ? work.find('input.work-number').val() : 'replace') + ']'));
            work.find('input.real-work-number').val(work.find('input.work-number').val());
        }

        $('#inputArticle').on('change', (e) => {
            getArticleInfo(e);
        });

        $('#addRow').on('click', (event) => {
            addRow(event);
        });

        $('input.work-number').val('').on('change', (event) => {
            replaceNames(event);
        });

        $('a.remove-work').on('click', (event) => {
            removeWork(event);
        });

        $('input.work-number').on('keyup change', (event) => {
            replaceVal(event);
        });

        $('a.add-work').on('click', (event) => {
            let work = $(event.target).parents('.card').find('.card.work:last-of-type').clone();
            console.log(work);
            work.removeAttr('id');

            work.find('input.work-number, input.driven-km').val('').on('change', (event) => {
                replaceNames(event);
            });

            let trs = work.find('table#report-lines tbody tr');
            trs.each((index, tr) => {
                console.log(index);
                if (trs.length - (index + 1) == 0) {
                    return false
                }
                $(tr).remove();
            });

            work.find('#addRow').on('click', (event) => {
                addRow(event);
            });

            work.find('a.remove-work').on('click', (event) => {
                removeWork(event);
            });

            work.find('input.work-number').on('keyup change', (event) => {
                replaceVal(event);
            });

            let tr = work.find('table#report-lines tbody tr:last-child');
            tr.find('input').val('').prop('readonly', false);
            tr.find(':not(td:first-child) input[type="number"]').val(0);
            tr.removeClass('first');
            tr.find('#inputArticle').on('change', (event) => {
                getArticleInfo(event)
            });
            tr.find('#removeRow').on('click', (event) => {
                removeLine(event);
            });
            tr.find('#inputDatetime').val(ISODateString(today))
            // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);

            $(event.target).parents('.card').find('.card.work:last-of-type').after(work);

            $('a[href="#"]').click(function(event) {
                event.preventDefault();
            });



            // tr.find('input').val('').prop('readonly', false);
            // tr.find(':not(td:first-child) input[type="number"]').val(0);
            // tr.removeClass('first');
            // tr.find('#inputArticle').on('change', (e) => {
            //     getArticleInfo(e)
            // });
            // tr.find('#removeRow').on('click', (e) => {
            //     removeLine(e);
            // });
            // tr.find('#inputDatetime').val(ISODateString(today))
            // console.log(tr[0]);
            // $('table#report-lines tbody').append(tr);
            // // window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
            // $('a[href="#"]').click(function(event) {
            //     event.preventDefault();
            // });
        });
        $('#inputDatetime').val(ISODateString(today));
    }
});
