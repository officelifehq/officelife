let mix = require('laravel-mix');
const path = require('path')
require('laravel-mix-purgecss');

let purgeCssOptions = {
  enabled: true,
  // List of regex of CSS class to not remove
  whitelistPatterns: [/^ball-pulse/],
  // List of regex of CSS class name whose child path CSS class will not be removed
  //  ex: to exclude "jane" in "mary jane": add "mary")
  whitelistPatternsChildren: []
};

mix.js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .purgeCss(purgeCssOptions)
  .webpackConfig({
    output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
    resolve: {
      alias: {
        vue$: 'vue/dist/vue.runtime.esm.js',
        '@': path.resolve('resources/js'),
      },
    },
  })
  .babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
  })
  .version()
  .sourceMaps(false);
