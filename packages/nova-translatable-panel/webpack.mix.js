let mix = require("laravel-mix");
let path = require("path");
let tailwindcss = require("tailwindcss");

require("./nova.mix");

mix
  .setPublicPath("dist")
  .js("resources/js/asset.js", "js")
  .vue({ version: 3 })
  .postCss("resources/css/asset.css", "css", [
    tailwindcss("tailwind.config.js"),
  ])
  .alias({
    "@translatable": path.join(__dirname, "resources/js/"),
    "@": path.join(__dirname, "/../../vendor/laravel/nova/resources/js/"),
  })
  .nova("packages/translatable-panel");
