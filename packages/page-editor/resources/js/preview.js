import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { createI18n } from "vue-i18n";
import forEach from "lodash/forEach";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import lang_en from "@lang/en.json";
import lang_tc from "@lang/tc.json";
import lang_sc from "@lang/sc.json";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy";

fetch(`/api/route`, {
    method: "POST",
})
    .then((response) => response.json())
    .then((Ziggy) => {
        const _route = (name, params) => route(name, params, true, Ziggy);

        const locale = document.documentElement.lang
            ? document.documentElement.lang
            : "en";

        const i18n = createI18n({
            locale: locale,
            legacy: false,
            messages: {
                en: lang_en,
                tc: lang_tc,
                sc: lang_sc,
            },
        });

        createInertiaApp({
            resolve: (name) => {
                const pages = import.meta.glob(`./InertiaPages/**/*.vue`, {
                    eager: true,
                });
                var page = pages[`./InertiaPages/${name}.vue`].default;
                page.layout = page.layout || DefaultLayout;
                return page;
            },
            setup({ el, App, props, plugin }) {
                const app = createApp({ render: () => h(App, props) })
                    .use(plugin)
                    .use(i18n)
                    .component("Link", Link)
                    .provide("route", _route)
                    .mixin({
                        methods: { route: _route },
                    });

                //add components to vue
                const components = import.meta.glob(`@/components/**/*.vue`, {
                    eager: true,
                });
                forEach(components, (component, fileName) => {
                    app.component(
                        "Widgets" +
                            fileName
                                .split("/")
                                .pop()
                                ?.replace(/\.\w+$/, ""),
                        component.default,
                    );
                });

                app.mount(el);
            },
        });
    });
