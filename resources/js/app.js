import './bootstrap';

// Import modules...
import _ from 'lodash';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import Antd from 'ant-design-vue';
import 'ant-design-vue/lib/select/style/index.css';
import Sentry from './sentry';
import 'v-calendar/dist/style.css';
import VCalendar from 'v-calendar';
import '../sass/app.scss';
import langs from './langs';
import methods from './methods';

const el = document.getElementById('app');

langs.loadLanguage(document.querySelector('html').getAttribute('lang'), true)
.then((locale) => {

  console.log(locale);

  const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

  // const app = createApp({
  //   locale,
  //   render: () =>
  //     h(InertiaApp, {
  //       initialPage: JSON.parse(el.dataset.page),
  //       resolveComponent: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  //       locale: locale.locale,
  //     }),
  //   mounted() {
  //     this.$nextTick(() => {
  //       Sentry.setContext(this, locale);
  //     });
  //   }
  // });

  createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
      const app = createApp({ render: () => h(App, props) });

      app.mixin({ methods: _.assign({
        route,
        loadLanguage: function(locale, set) {
          return langs.loadLanguage(locale, set);
        }
      }, methods) })
        .use(plugin)
        .use(ZiggyVue)
        .use(langs.i18n)
        .use(Antd)
        .use(VCalendar)
        .component('Link', Link)
        .mount(el);

      // Sentry.init(app, process.env.MIX_SENTRY_RELEASE);

      return app;
    },
    progress: {
      color: '#4B5563',
    },
  });

  // InertiaProgress.init({ color: '#4B5563' });

});
