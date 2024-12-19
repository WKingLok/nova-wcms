import { createInertiaApp, Link } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { renderToString } from "@vue/server-renderer";
import { createSSRApp, h } from "vue";
import { createI18n } from "vue-i18n";
import { route } from "ziggy";
import forEach from "lodash/forEach";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import lang_en from "@lang/en.json";
import lang_tc from "@lang/tc.json";
import lang_sc from "@lang/sc.json";
import get from "lodash/get";
import fetch from "node-fetch";

fetch(`${process.env.APP_URL}/api/route`, {
    method: "POST",
})
    .then((response) => response.json())
    .then((Ziggy) => {
        const _route = (name, params) => route(name, params, true, Ziggy);

        const i18n = createI18n({
            locale: "en",
            messages: {
                en: lang_en,
                tc: lang_tc,
                sc: lang_sc,
            },
        });

        createServer((page) => {
            if (page.url.startsWith("/wcms")) {
                throw "wcms url";
            }

            return createInertiaApp({
                page,
                render: renderToString,
                resolve: (name) => {
                    const pages = import.meta.glob(`./Pages/**/*.vue`, {
                        eager: true,
                    });
                    var page = pages[`./Pages/${name}.vue`].default;
                    page.layout = page.layout || DefaultLayout;
                    return page;
                },
                setup({ App, props, plugin }) {
                    i18n.global.locale = get(
                        props,
                        "initialPage.props.locale",
                        "en"
                    );
                    const app = createSSRApp({ render: () => h(App, props) })
                        .use(plugin)
                        .use(i18n)
                        .component("Link", Link)
                        .provide("route", _route)
                        .mixin({
                            methods: { route: _route },
                        });

                    //add components to vue
                    const components = import.meta.glob(
                        `./Components/**/*.vue`,
                        {
                            eager: true,
                        }
                    );
                    forEach(components, (component, fileName) => {
                        app.component(
                            "Widgets" +
                                fileName
                                    .split("/")
                                    .pop()
                                    ?.replace(/\.\w+$/, ""),
                            component.default
                        );
                    });

                    return app;
                },
            });
        });
    });
