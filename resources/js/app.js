/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router';

import router from './Routes/index';

Vue.use(VueRouter);

window.run = new Vue();

// VForm
import { Form, HasError, AlertError } from 'vform'
window.form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
    //

// Vue Pagination
Vue.component('pagination', require('laravel-vue-pagination'));
//

// Vue moment
Vue.use(require('vue-moment'));
//

// Vue Sweetalert 2
import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.toast = toast;
//

// Vue2-datepicker
import DatePicker from 'vue2-datepicker';
Vue.component('date-picker', DatePicker);
//

//Vue Editor
import Vuex from 'vuex'
import Vueditor from 'vueditor'

import 'vueditor/dist/style/vueditor.min.css'

// your config here
let config = {
    toolbar: [
        'removeFormat', 'undo', '|', 'elements', 'fontName', 'fontSize', 'foreColor', 'backColor', 'divider',
        'bold', 'italic', 'underline', 'strikeThrough', 'links', 'divider', 'subscript', 'superscript',
        'divider', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull', '|', 'indent', 'outdent',
        'insertOrderedList', 'insertUnorderedList', '|', 'picture', 'tables', '|', 'switchView'
    ],
    fontName: [
        { val: 'arial black' },
        { val: 'times new roman' },
        { val: 'Courier New' }
    ],
    fontSize: ['12px', '14px', '16px', '18px', '0.8rem', '1.0rem', '1.2rem', '1.5rem', '2.0rem'],
    uploadUrl: ''
};

Vue.use(Vuex);
Vue.use(Vueditor, config);
//

//Vue multiselect
import Multiselect from 'vue-multiselect'

// register globally
Vue.component('multiselect', Multiselect)
    //

// Vue CtkDate Picjer
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);
//

// Vue2 Filter
import Vue2Filters from 'vue2-filters'
import _ from 'lodash';

Vue.use(Vue2Filters)
    //

// VUE VIDEO PLAYER
import VueVideoPlayer from 'vue-video-player'
import 'video.js/dist/video-js.css'
Vue.use(VueVideoPlayer,
        /* {
           options: global default options,
           events: global videojs events
         } */
    )
    //

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
    router,
    data: {
        search: '',
    },
    methods: {
        searchresult: _.debounce(() => {
            console.log('asdasda')
            run.$emit('searching');
        }, 1000)
    }
});