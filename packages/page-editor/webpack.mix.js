let mix = require("laravel-mix");
let path = require("path");
let tailwindcss = require("tailwindcss");

mix.override((webpackConfig) => {
    webpackConfig.resolve.modules = [
        "node_modules",
        __dirname + "/vendor/spatie/laravel-medialibrary-pro/resources/js",
    ];
});

mix.options({
    legacyNodePolyfills: false,
})
    .setPublicPath("dist")
    .js("resources/js/nova.js", "js")
    .vue({ version: 3 })
    .alias({
        "@": path.join(__dirname, "resources/js/"),
        "@front": path.join(__dirname, "../../resources/js"),
        "@frontLang": path.join(__dirname, "../../lang"),
    })
    .postCss("resources/css/nova.css", "css", [
        tailwindcss("tailwind.config.js"),
    ])
    .webpackConfig({
        externals: {
            vue: "Vue",
        },
    });
