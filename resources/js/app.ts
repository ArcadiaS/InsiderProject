import './bootstrap.js'
import { createApp } from 'vue'
import ElementPlus from 'element-plus/es'
import 'element-plus/dist/index.css'
import App from "./App.vue";
import router from './src/router/router.ts';
import { createPinia } from 'pinia'

import 'element-plus/dist/index.css'
import 'element-plus/theme-chalk/dark/css-vars.css'

const pinia = createPinia()
const app = createApp(App);
app.use(pinia)
app.use(router);
app.use(ElementPlus, { size: 'large', zIndex: 3000 })
app.mount('#app');