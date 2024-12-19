let mix = require("laravel-mix");
let path = require("path");
let tailwindcss = require("tailwindcss");

mix.options({
    legacyNodePolyfills: false,
})
    .setPublicPath("dist")
    .js("resources/js/nova.js", "js")
    .vue({ version: 3 })
    .alias({
        "@package": path.join(__dirname, "resources/js/"),
        "@": path.join(__dirname, "/vendor/laravel/nova/resources/js/"),
    })
    .postCss("resources/css/nova.css", "css", [
        tailwindcss("tailwind.config.js"),
    ])
    .webpackConfig({
        externals: {
            vue: "Vue",
        },
    });
