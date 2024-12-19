const html = document.querySelector("html");
function callback(mutationList, observer) {
    mutationList.forEach(function (mutation) {
        if (
            mutation.type === "attributes" &&
            mutation.attributeName === "class"
        ) {
            //check if dark
            if (html.classList.contains("dark")) {
                html.setAttribute("data-mode", "dark");
            } else {
                html.setAttribute("data-mode", "light");
            }
        }
    });
}

const observer = new MutationObserver(callback);
observer.observe(html, {
    attributes: true,
});

if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
    html.setAttribute("data-mode", "dark");
}
