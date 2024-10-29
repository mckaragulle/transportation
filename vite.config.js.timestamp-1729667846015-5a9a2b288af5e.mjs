// vite.config.js
import { defineConfig } from "file:///Users/mustafakaragulle/Sites/transportation/node_modules/vite/dist/node/index.js";
import laravel from "file:///Users/mustafakaragulle/Sites/transportation/node_modules/laravel-vite-plugin/dist/index.js";
import livewire from "file:///Users/mustafakaragulle/Sites/transportation/node_modules/@defstudio/vite-livewire-plugin/dist/index.mjs";
var vite_config_default = defineConfig({
  plugins: [
    laravel(
      {
        input: ["resources/js/app.js"],
        refresh: true
      }
    ),
    livewire({
      refresh: ["resources/js/app.js"]
    })
  ],
  server: {
    https: {
      key: "./localhost-key.pem",
      cert: "./localhost.pem"
    },
    hmr: {
      host: "localhost"
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvVXNlcnMvbXVzdGFmYWthcmFndWxsZS9TaXRlcy90cmFuc3BvcnRhdGlvblwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL1VzZXJzL211c3RhZmFrYXJhZ3VsbGUvU2l0ZXMvdHJhbnNwb3J0YXRpb24vdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL1VzZXJzL211c3RhZmFrYXJhZ3VsbGUvU2l0ZXMvdHJhbnNwb3J0YXRpb24vdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJztcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nO1xuaW1wb3J0IGxpdmV3aXJlIGZyb20gJ0BkZWZzdHVkaW8vdml0ZS1saXZld2lyZS1wbHVnaW4nOyAvLyA8LS0gaW1wb3J0XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gICAgcGx1Z2luczogW1xuICAgICAgICBsYXJhdmVsKFxuICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgIGlucHV0OiBbJ3Jlc291cmNlcy9qcy9hcHAuanMnXSxcbiAgICAgICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICAgICAgfVxuICAgICAgICApLFxuICAgICAgICBsaXZld2lyZSh7XG4gICAgICAgICAgICByZWZyZXNoOiBbJ3Jlc291cmNlcy9qcy9hcHAuanMnXSxcbiAgICAgICAgfSksXG4gICAgXSxcbiAgICBzZXJ2ZXI6IHtcbiAgICAgICAgaHR0cHM6IHtcbiAgICAgICAgICAgIGtleTogJy4vbG9jYWxob3N0LWtleS5wZW0nLFxuICAgICAgICAgICAgY2VydDogJy4vbG9jYWxob3N0LnBlbScsXG4gICAgICAgIH0sXG4gICAgICAgIGhtcjoge1xuICAgICAgICAgICAgaG9zdDogJ2xvY2FsaG9zdCcsXG4gICAgICAgIH0sXG4gICAgfSxcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFzVCxTQUFTLG9CQUFvQjtBQUNuVixPQUFPLGFBQWE7QUFDcEIsT0FBTyxjQUFjO0FBRXJCLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFNBQVM7QUFBQSxJQUNMO0FBQUEsTUFDSTtBQUFBLFFBQ0ksT0FBTyxDQUFDLHFCQUFxQjtBQUFBLFFBQzdCLFNBQVM7QUFBQSxNQUNiO0FBQUEsSUFDSjtBQUFBLElBQ0EsU0FBUztBQUFBLE1BQ0wsU0FBUyxDQUFDLHFCQUFxQjtBQUFBLElBQ25DLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxRQUFRO0FBQUEsSUFDSixPQUFPO0FBQUEsTUFDSCxLQUFLO0FBQUEsTUFDTCxNQUFNO0FBQUEsSUFDVjtBQUFBLElBQ0EsS0FBSztBQUFBLE1BQ0QsTUFBTTtBQUFBLElBQ1Y7QUFBQSxFQUNKO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
