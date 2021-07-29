require('./bootstrap');
import Vue from 'vue';
// window.Vue = require('vue').default;

import Vuetify from 'vuetify'
Vue.use(Vuetify)


// =====VUEX======
import Vuex from 'vuex'
import storeData from './store/booker-store'
const store = new Vuex.Store(storeData)

import BookerApplication from './BookerApplication.vue'
import DatePicker from './Components/Booker/DatePicker.vue'
import ExcursionTypeSelect from './Components/Booker/ExcursionTypeSelect.vue'

Vue.component('booker-application', BookerApplication)
Vue.component('date-picker', DatePicker)
Vue.component('excursion-type-select', ExcursionTypeSelect)


const app = new Vue({
    el: '#app',
    store,
    vuetify: new Vuetify(),
});

