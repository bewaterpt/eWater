/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
import Laraform from 'laraform';

Vue.use(Laraform);


// Setup ajax headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Prevent unwanted scrolling of the page when clicking JavaScript handled links
function stopSpontaneousSrcolling() {
    $('a[href="#"]').click(function(event) {
        event.preventDefault();
    });
}

stopSpontaneousSrcolling();

// TinyMCE Langs
require('./config/tinymce/lang/pt_PT');

$('button[type="submit"]').on('click', (e) => {
    $(e.target).find('button[type="submit"]').attr('disabled', true);

    return true;
});

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        name = this.name.replace(/(\[\])$/, '');
        if (o[name]) {
            if (!o[name].push) {
                o[name] = [o[name]];
            }
            o[name].push(this.value || '');
        } else {
            o[name] = this.value || '';
        }
    });
    return o;
};

$(() => {
    $('[data-toggle="popover"]').popover({
        html: true,
        title: function() {
            console.log(this);
            return $(document).find('#' + this.id + ' .popover').find('#title').html()
        },
        content: function() {
            return $(document).find('#' + this.id + ' .popover').find('#content').html()
        },
    });

    $('[data-onload]').each(function(i, el) {
        customOnload(el, $(el).attr('data-onload'));
    });
});
