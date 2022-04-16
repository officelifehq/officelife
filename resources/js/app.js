require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Antd from 'ant-design-vue';
import 'ant-design-vue/lib/select/style/index.css';
import Sentry from './sentry';
import 'v-calendar/dist/style.css';
import VCalendar from 'v-calendar';

const langs = require('./langs').default;

const el = document.getElementById('app');

langs.loadLanguage(document.querySelector('html').getAttribute('lang'), true)
.then((locale) => {

  const app = createApp({
    locale,
    render: () =>
      h(InertiaApp, {
        initialPage: JSON.parse(el.dataset.page),
        resolveComponent: (name) => require(`./Pages/${name}`).default,
        locale: locale.locale,
      }),
    mounted() {
      this.$nextTick(() => {
        Sentry.setContext(this, locale);
      });
    }
  });

  Sentry.init(app, process.env.MIX_SENTRY_RELEASE);

  app.mixin({ methods: _.assign({
    route,
    loadLanguage: function(locale, set) {
      return langs.loadLanguage(locale, set);
    }
  }, require('./methods').default) })
    .use(InertiaPlugin)
    .use(langs.i18n)
    .use(Antd)
    .use(VCalendar)
    .mount(el);

  InertiaProgress.init({ color: '#4B5563' });

});
