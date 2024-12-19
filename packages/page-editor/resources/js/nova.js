import IndexEditorButton from "./novaFields/EditorButton/IndexField";
import DetailEditorButton from "./novaFields/EditorButton/DetailField";
import IndexShareWidgetEditorButton from "./novaFields/ShareWidgetEditorButton/IndexField";
import DetailShareWidgetEditorButton from "./novaFields/EditorButton/DetailField";

import { Button as NovaUiButton } from "laravel-nova-ui";
import { createVfm } from "vue-final-modal";
import "vue-final-modal/style.css";

Nova.booting((app, store) => {
    app.component("index-editor-button", IndexEditorButton);
    app.component("detail-editor-button", DetailEditorButton);
    app.component(
        "index-share-widget-editor-button",
        IndexShareWidgetEditorButton,
    );
    app.component(
        "detail-share-widget-editor-button",
        DetailShareWidgetEditorButton,
    );
});

registerLaravelNovaUI();
registerPages();
registerComponents();

// register laravel nova ui
function registerLaravelNovaUI() {
    Nova.component("NovaUiButton", NovaUiButton);
}

//register vfm library
const vfm = createVfm();
Nova.app.use(vfm);

// register components
function registerComponents() {
    const components = require.context("@/components/", true, /.vue$/i);
    components.keys().forEach((fileName) => {
        Nova.component(
            "PageEditor" +
                fileName
                    .split("/")
                    .pop()
                    ?.replace(/\.\w+$/, ""),
            components(fileName).default,
        );
    });
}

// register page
function registerPages() {
    const pages = require.context("@/pages/", true, /.vue$/i);
    pages.keys().forEach((fileName) => {
        Nova.inertia(
            "PageEditor." +
                fileName
                    .split("/")
                    .pop()
                    ?.replace(/\.\w+$/, ""),
            pages(fileName).default,
        );
    });
}
