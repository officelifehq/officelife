const mix = require('laravel-mix');
const path = require('path');
require('laravel-mix-purgecss');

const purgeCssOptions = {
  enabled: true,
  // List of regex of CSS class to not remove
  whitelistPatterns: [/^ball-pulse/, /^ball-clip-rotate/, /^vs__/, /^dot-/, /^expense-badge-/, /^timesheet-badge-/, /^failed/, /^multiselect/, /^is-/, /^no-caret/],
  // List of regex of CSS class name whose child path CSS class will not be removed
  //  ex: to exclude "jane" in "mary jane": add "mary")
  whitelistPatternsChildren: [/^vs-/],
};

mix.js('resources/js/app.js', 'public/js').vue()
  .sass('resources/sass/app.scss', 'public/css')
  .purgeCss(purgeCssOptions)
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      alias: {
        '@': path.resolve('resources/js'),
        'vue-i18n': 'vue-i18n/dist/vue-i18n.cjs.js',
      },
    },
    devtool: "inline-source-map",
  })
  .babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
  })
  .sourceMaps(false)
  .setResourceRoot('../');

if (mix.inProduction()) {
  mix.version();
}
