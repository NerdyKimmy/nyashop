import { createApp } from 'vue'
import  './index.css'
import './style.css'
import router from "./router";
import store from "./store";

import App from './App.vue'

createApp(App)
    .use(store)
    .use(router)
    .mount('#app')
