let mix = require("laravel-mix");
let tailwindcss = require("tailwindcss");
require("./nova.mix");

mix.override((webpackConfig) => {
  webpackConfig.resolve.modules = [
    "node_modules",
    __dirname + "/vendor/spatie/laravel-medialibrary-pro/resources/js",
  ];
});
mix
  .options({
    legacyNodePolyfills: false,
  })
  .setPublicPath("dist")
  .js("resources/js/field.js", "js")
  .vue({ version: 3 })
  .postCss("resources/css/field.css", "css", [
    tailwindcss("tailwind.config.js"),
  ])
  .nova("packages/media-image");
