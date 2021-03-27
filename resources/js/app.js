require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

const langs = require('./langs').default;

const el = document.getElementById('app');

langs.loadLanguage('en', true).then((locale) => {

  createApp({
    locale,
    render: () =>
      h(InertiaApp, {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: (name) => require(`./Pages/${name}`).default,
        locale: locale.locale,

        // global methods
        methods: require('./methods').default
      }),
  })
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(langs.i18n)
    .mount(el);

  InertiaProgress.init({ color: '#4B5563' });

});
