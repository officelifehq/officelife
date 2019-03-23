
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

window.Vue = require('vue')

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// toaster
import Snotify from 'vue-snotify'
import 'vue-snotify/styles/simple.css'
Vue.use(Snotify)

// i18n
import VueI18n from 'vue-i18n'
Vue.use(VueI18n)

import messages from '../../public/js/langs/en.json'

export const i18n = new VueI18n({
  locale: 'en', // set locale
  fallbackLocale: 'en',
  messages: { 'en': messages }
})

// Start Turbolinks
require('turbolinks').start()

// Boot the Vue component
document.addEventListener('turbolinks:load', (event) => {
  const root = document.getElementById('app')

  if (window.vue) {
    window.vue.$destroy(true)
  }

  window.vue = new Vue({
    i18n,
    render: h => h(
      Vue.component(root.dataset.component), {
        props: JSON.parse(root.dataset.props)
      }
    )
  }).$mount(root)
})
