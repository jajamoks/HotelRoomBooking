/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//for routing the SPA
import VueRouter from 'vue-router';
Vue.use(VueRouter);


import VueAxios from 'vue-axios';
import axios from 'axios';

// for toast like notification
import Notifications from 'vue-notification'
Vue.use(Notifications)

// for date picker
import datePicker from 'vue-bootstrap-datetimepicker';
import 'bootstrap/dist/css/bootstrap.css';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
Vue.use(datePicker);

// for modal
import VModal from 'vue-js-modal'
//Vue.use(VModal)
Vue.use(VModal, { dynamic: true, dialog: true, dynamicDefaults: { clickToClose: false } })




import App from './App.vue';

import auth from './auth.js';
window.auth = auth;


//Vue.config.productionTip = false

Vue.use(VueAxios, axios);

//hotel routes
import HotelViewComponent from './components/HotelViewComponent.vue';
import HotelCreateComponent from './components/HotelCreateComponent.vue';
import HotelEditComponent from './components/HotelEditComponent.vue';


//room manager routes
import RoomViewComponent from './components/RoomViewComponent.vue';
import RoomCreateComponent from './components/RoomCreateComponent.vue';
import RoomEditComponent from './components/RoomEditComponent.vue';

//room type routes
import RoomTypeViewComponent from './components/RoomTypeViewComponent.vue';
import RoomTypeCreateComponent from './components/RoomTypeCreateComponent.vue';
import RoomTypeEditComponent from './components/RoomTypeEditComponent.vue';

//booking manager routes
import BookingViewComponent from './components/BookingViewComponent.vue';
import BookingCreateComponent from './components/BookingCreateComponent.vue';
import BookingEditComponent from './components/BookingEditComponent.vue';

//price list manager routes
import PriceListViewComponent from './components/PriceListViewComponent.vue';
import PriceListCreateComponent from './components/PriceListCreateComponent.vue';
import PriceListEditComponent from './components/PriceListEditComponent.vue';

const routes = [{
        name: '/',
        path: '/hotel',
        component: HotelViewComponent
    },
    {
        name: 'hotel',
        path: '/hotel',
        component: HotelViewComponent
    },
    {
        name: 'hotel_create',
        path: '/hotel/create',
        component: HotelCreateComponent
    },
    {
        name: 'hotel_edit',
        path: '/hotel/edit/:id',
        component: HotelEditComponent
    },
    {
        name: 'room',
        path: '/room',
        component: RoomViewComponent
    },
    {
        name: 'room_create',
        path: '/room/create',
        component: RoomCreateComponent
    },
    {
        name: 'room_edit',
        path: '/room/edit/:id',
        component: RoomEditComponent
    },
    {
        name: 'roomtype',
        path: '/roomtype',
        component: RoomTypeViewComponent
    },
    {
        name: 'roomtype_create',
        path: '/roomtype/create',
        component: RoomTypeCreateComponent
    },
    {
        name: 'roomtype_edit',
        path: '/roomtype/edit/:id',
        component: RoomTypeEditComponent
    },
    {
        name: 'booking',
        path: '/booking',
        component: BookingViewComponent
    },
    {
        name: 'booking_create',
        path: '/booking/create',
        component: BookingCreateComponent
    },
    {
        name: 'booking_edit',
        path: '/booking/edit/:id',
        component: BookingEditComponent
    },
    {
        name: 'pricelist',
        path: '/pricelist',
        component: PriceListViewComponent
    },
    {
        name: 'pricelist_create',
        path: '/pricelist/create',
        component: PriceListCreateComponent
    },
    {
        name: 'pricelist_edit',
        path: '/pricelist/edit/:id',
        component: PriceListEditComponent
    }
];



const router = new VueRouter({ mode: 'history', routes: routes });
const app = new Vue(Vue.util.extend({ router }, App)).$mount('#app');