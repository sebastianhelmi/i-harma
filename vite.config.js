import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/scss/app.scss",
                "resources/js/app.js",
                "resources/sass/pm.scss",
                "resources/sass/head-of-division.scss",
                "'resources/sass/purchasing.scss'",
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                api: "modern",
                quietDeps: true,
            },
        },
    },
});
