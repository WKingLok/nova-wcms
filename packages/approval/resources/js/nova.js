import ResourceTableRow from "./components/ResourceTableRow";
import ResourceDetail from "./components/ResourceDetail";

registerPages();
registerOverridedNovaComponent();

// register page
function registerPages() {
    const pages = require.context("@package/pages/", true, /.vue$/i);
    pages.keys().forEach((fileName) => {
        Nova.inertia(
            "Approval." +
                fileName
                    .split("/")
                    .pop()
                    ?.replace(/\.\w+$/, ""),
            pages(fileName).default,
        );
    });
}

function registerOverridedNovaComponent() {
    Nova.component("ResourceTableRow", ResourceTableRow);
    Nova.component("ResourceDetail", ResourceDetail);
}
