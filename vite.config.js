import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    resolve: {
        alias: {
            ziggy: path.resolve("vendor/tightenco/ziggy"),
            "@": path.join(__dirname, "resources/js"),
            "@lang": path.join(__dirname, "lang"),
            "@helper": path.join(__dirname, "resources/js/helper"),
        },
    },
    plugins: [
        vue(),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "vendor/packages/page-editor/resources/js/preview.js",
            ],
            ssr: "resources/js/ssr.js",
            refresh: true,
        }),
    ],
});
