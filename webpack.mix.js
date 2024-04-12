let mix = require('laravel-mix');
const { BugsnagSourceMapUploaderPlugin } = require('webpack-bugsnag-plugins')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.options({ processCssUrls: false })
    .js('resources/assets/js/scripts.js', 'public/js')
    .less('resources/assets/less/styles.less', 'css/styles.css');

if (mix.inProduction()) {
    mix.version()
        .sourceMaps()
        .webpackConfig({
            plugins: [
                new BugsnagSourceMapUploaderPlugin({
                    apiKey: 'e6dafe167baecf4f0cd93ba2ac4fc986',
                    publicPath: 'https://dgtournaments.com/',
                    appVersion: 'current',
                    overwrite: true
                })
            ]
        });
}