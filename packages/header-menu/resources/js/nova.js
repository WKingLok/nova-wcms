import { Button as NovaUiButton } from "laravel-nova-ui";

registerPages();
registerComponents();
registerLaravelNovaUI();

// register laravel nova ui
function registerLaravelNovaUI() {
    Nova.component("NovaUiButton", NovaUiButton);
}
// register components
function registerComponents() {
    const components = require.context("@package/Components/", true, /.vue$/i);
    components.keys().forEach((fileName) => {
        Nova.component(
            "HeaderMenu" +
                fileName
                    .split("/")
                    .pop()
                    ?.replace(/\.\w+$/, ""),
            components(fileName).default
        );
    });
}

// register page
function registerPages() {
    const pages = require.context("@package/Pages/", true, /.vue$/i);
    pages.keys().forEach((fileName) => {
        Nova.inertia(
            "HeaderMenu." +
                fileName
                    .split("/")
                    .pop()
                    ?.replace(/\.\w+$/, ""),
            pages(fileName).default
        );
    });
}
