import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import vueDevTools from "vite-plugin-vue-devtools";

// https://vite.dev/config/
export default defineConfig({
	plugins: [vue(), vueDevTools()],
	resolve: {
		alias: {
			"@": fileURLToPath(new URL("./src", import.meta.url)),
		},
	},
	build: {
		emptyOutDir: false,
		rollupOptions: {
			output: {
				//manifest: '/build/manifest.json',
				assetFileNames: ({ name }) => {
					console.log(name);
					if (/\.(gif|jpe?g|png|svg)$/.test(name ?? "")) {
						return "images/[name]-[hash].[ext]";
					}

					if (/\.(ttf)$/.test(name ?? "")) {
						return "fonts/[name].[ext]";
					}
					return "[name].[ext]";
				},
				entryFileNames: `[name].js`,
				chunkFileNames: `chunks/[name].[hash].js`,
				// assetFileNames: `[name].[ext]`
			},
		},
	},
});
