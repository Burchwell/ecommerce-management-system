import Vue  from 'vue';
import VueRouter from "vue-router";
import moment from 'vue-moment'
import JsonCSV from 'vue-json-csv'

import './bootstrap';

Vue.prototype.moment = moment

import  router  from './routes/index';

import store from "./store";


// Boostrap 4

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

// Install BootstrapVue
Vue.use(BootstrapVue)

// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
Vue.prototype.$store = store;

Vue.config.productionTip = false

Vue.use(VueRouter);

if (process.env.MIX_ENV_MODE === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
}

Vue.component('downloadCsv', JsonCSV);

const main = new Vue({
    el: '#container',
    router,
    store,
})
