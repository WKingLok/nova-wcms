let mix = require("laravel-mix");
let path = require("path");
require("./nova.mix");

mix
  .setPublicPath("dist")
  .js("resources/js/field.js", "js")
  .vue({ version: 3 })
  .css("resources/css/field.css", "css")
  .alias({
    "@translatable": path.join(__dirname, "resources/js/"),
    "@": path.join(__dirname, "/../../vendor/laravel/nova/resources/js/"),
  })
  .nova("packages/translatable");
