const mix = require('laravel-mix');
const CKEditorWebpackPlugin = require('@ckeditor/ckeditor5-dev-webpack-plugin');
const CKEStyles = require('@ckeditor/ckeditor5-dev-utils').styles;
const CKERegex = {
  svg: /ckeditor5-[^/\\]+[/\\]theme[/\\]icons[/\\][^/\\]+\.svg$/,
  css: /ckeditor5-[^/\\]+[/\\]theme[/\\].+\.css/,
};

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
Mix.listen('configReady', webpackConfig => {
  const rules = webpackConfig.module.rules;
  const targetSVG = /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/;
  const targetFont = /(\.(woff2?|ttf|eot|otf)$|font.*\.svg$)/;
  const targetCSS = /\.css$/;

  // exclude CKE regex from mix's default rules
  for (let rule of rules) {
    if (rule.test.toString() === targetSVG.toString()) {
      rule.exclude = CKERegex.svg;
    }
    else if (rule.test.toString() === targetFont.toString()) {
      rule.exclude = CKERegex.svg;
    }
    else if (rule.test.toString() === targetCSS.toString()) {
      rule.exclude = CKERegex.css;
    }
  }
});

mix.webpackConfig({
  plugins: [
    new CKEditorWebpackPlugin({
      language: 'en'
    }),
  ],
  module: {
    rules: [
      {
        test: CKERegex.svg,
        use: [ 'raw-loader' ]
      },
      {
        test: CKERegex.css,
        use: [
          {
            loader: 'style-loader',
            options: {
              singleton: true
            }
          },
          {
            loader: 'postcss-loader',
            options: CKEStyles.getPostCssConfig({
              themeImporter: {
                themePath: require.resolve('@ckeditor/ckeditor5-theme-lark')
              },
              minify: true
            })
          },
        ]
      }
    ]
  }
});

mix.js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css');
