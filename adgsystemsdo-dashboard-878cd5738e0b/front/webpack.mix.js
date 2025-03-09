let mix = require('laravel-mix');
// mix.setPublicPath('dist')
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

 mix.js('src/js/reports/ventas.js', '../js/pagespecific/reportventas.js');
// mix.js('src/js/modules/modules.js', '../js/pagespecific/modules.js');
// mix.js('src/js/reports/devoluciones.js', '../js/pagespecific/reportdevoluciones.js');
// mix.js('src/js/reports/cobrosadelanto.js', '../js/pagespecific/reportcobrosadelanto.js');
// mix.js('src/js/reports/cobros.js', '../js/pagespecific/reportcobros.js');
// mix.js('src/js/reports/visitas.js', '../js/pagespecific/reportvisitas.js');
// mix.js('src/js/reports/depositos.js', '../js/pagespecific/reportdepositos.js');
// mix.js('src/js/reports/ventasduracion.js', '../js/pagespecific/reportventasduracion.js');
// mix.js('src/js/reports/horastrabajadas.js', '../js/pagespecific/reporthorastrabajadas.js');
//mix.js('src/js/devices.js', '../js/pagespecific/devicesdist.js');
// mix.js('src/js/reports/cuentacobrar.js', '../js/pagespecific/cuentacobrar.js');
// mix.js('src/js/reports/VentasProductos.js', '../js/pagespecific/ventasproductos.js');
// mix.js('src/js/reports/activity/activity.js', '../js/pagespecific/activity.js');
//mix.js('src/js/reports/activity/printActivity.js', '../js/pagespecific/printActivity.js');
// mix.js('src/js/reports/visitno/visitNoVentas.js', '../js/pagespecific/visitNoVentas.js');
// mix.js('src/js/reports/ranchera/ranchera.js', '../js/pagespecific/ranchera.js');

// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.standaloneSass('src', output); <-- Faster, but isolated from Webpack.
// mix.less(src, output);
// mix.stylus(src, output);
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });