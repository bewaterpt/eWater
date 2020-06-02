$(document).ready(() => {
    $('#inputArticles').on('change', () => {
        const player = document.querySelector("lottie-player");
        $(player).toggleClass('invisible');
        $.ajax({
            type: 'GET',
            url: '/daily-report/get-price'
        });
    });
});
