const mix = require('laravel-mix');
const path = require('path');
require('laravel-mix-purgecss');

const purgeCssOptions = {
  safelist: {
    // List of regex of CSS class to not remove
    standard: [/^ball-pulse/, /^ball-clip-rotate/, /^vs__/, /^dot-/, /^expense-badge-/, /^timesheet-badge-/, /^failed/],
    // List of regex of CSS class name whose child path CSS class will not be removed
    //  ex: to exclude "jane" in "mary jane": add "mary")
    deep: [/^vue-loaders/, /^vs-/, /^ant-/, /^vc-/],
  }
};

mix.js('resources/js/app.js', 'public/js').vue()
  .sass('resources/sass/app.scss', 'public/css')
  .purgeCss(purgeCssOptions)
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
  })
  .alias({
    vue$: path.join(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
    '@': path.resolve('resources/js'),
  })
  .babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
  })
  .sourceMaps(process.env.MIX_PROD_SOURCE_MAPS || false, 'eval-cheap-module-source-map', 'source-map')
  .setResourceRoot('../');

if (mix.inProduction()) {
  mix.version();
}
