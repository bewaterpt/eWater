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

$(document).ready(() => {
    $('[data-toggle="popover"]').popover();
});
