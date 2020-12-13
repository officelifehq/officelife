
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { app, plugin } from '@inertiajs/inertia-vue';
import Vue from 'vue';

Vue.config.productionTip = false;
Vue.mixin({ methods: { route: window.route } })
Vue.use(plugin);

// Axios for some ajax queries
window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// toaster
window.events = new Vue();
window.flash = function (message, level = 'success') {
  window.events.$emit('flash', { message, level });
};

// Progress
import { InertiaProgress } from '@inertiajs/progress'
InertiaProgress.init()

import Snotify from 'vue-snotify';
import 'vue-snotify/styles/simple.css';
Vue.use(Snotify);

// i18n
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);

import messages from '../../public/js/langs/en.json';

export const i18n = new VueI18n({
  locale: 'en', // set locale
  fallbackLocale: 'en',
  messages: { 'en': messages }
});

const el = document.getElementById('app');

new Vue({
  i18n,
  render: h => h(app, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: name => import(`@/Pages/${name}`).then(module => module.default),
    },
  }),
}).$mount(el);

