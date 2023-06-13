import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue"
import path from "path"
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'

export default defineConfig({
    plugins: [
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            resolvers: [ElementPlusResolver()],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        laravel({
            input: [
                "resources/js/app.ts",
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias:{
            "@/": path.join(__dirname, "/resources/js/src/"),
            "~": path.join(__dirname, "/node_modules/"),
        },
    },
    build: {
        chunkSizeWarningLimit: 1600,
    },
});