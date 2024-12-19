const defaultTheme = require("tailwindcss/defaultTheme");
const { generateRootCSSVars, generateTailwindColors } = require("./generators");

module.exports = {
    prefix: "hm-",
    darkMode: ["class", '[data-mode="dark"]'],
    content: ["./resources/js/**/*.{vue,js}"],
    purge: false,
    theme: {
        colors: generateTailwindColors(),
        extend: {
            fontFamily: {
                sans: ["Nunito Sans", ...defaultTheme.fontFamily.sans],
            },
            fontSize: { xxs: "11px" },
            maxWidth: { xxs: "15rem" },
            minHeight: (theme) => theme("spacing"),
            minWidth: (theme) => theme("spacing"),

            spacing: {
                5: "1.25rem",
                9: "2.25rem",
                11: "2.75rem",
            },

            top: (theme) => theme("inset"),
            width: (theme) => theme("spacing"),
        },
    },
};
