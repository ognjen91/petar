require('./bootstrap');
import Vue from 'vue';
import 'vue-datetime/dist/vue-datetime.css'

// window.Vue = require('vue').default;

import Vuetify from 'vuetify'
Vue.use(Vuetify)
// import zhHans from 'vuetify/lib/locale/zh-Hans'


// =====VUEX======
import Vuex from 'vuex'
import storeData from './store/booker-store'
const store = new Vuex.Store(storeData)

import BookerApplication from './BookerApplication.vue'
import DatePicker from './Components/Booker/DatePicker.vue'
import ExcursionTypeSelect from './Components/Booker/ExcursionTypeSelect.vue'
import BookerRedirectParamsSelect from './Components/Booker/BookerRedirectParamsSelect.vue'
import CancelReservationButton from './Components/Booker/CancelReservationButton.vue'
import NavigationDrawer from './Components/Booker/NavigationDrawer.vue'
import PrivateExcursionsCalendar from './Components/Booker/PrivateExcursionsCalendar.vue'
import PrivateExcursionsCreate from './Components/Booker/PrivateExcursionsCreate.vue'
import Footer from './Components/Booker/Footer.vue'


Vue.component('booker-application', BookerApplication)
Vue.component('date-picker', DatePicker)
Vue.component('excursion-type-select', ExcursionTypeSelect)
Vue.component('booker-redirect-params-select', BookerRedirectParamsSelect)
Vue.component('cancel-reservation-button', CancelReservationButton)
Vue.component('navigation-drawer', NavigationDrawer)
Vue.component('private-excursions-calendar', PrivateExcursionsCalendar)
Vue.component('private-excursions-create', PrivateExcursionsCreate)
Vue.component('booker-footer', Footer)

// ===CREW COMPONENTS===
import SelectDate from './Components/Crew/SelectDate.vue'
Vue.component('select-date', SelectDate)

const app = new Vue({
    el: '#app',
    store,
    vuetify: new Vuetify(),
});

