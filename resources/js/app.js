import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler.js'
import Videos from "./components/Videos.vue"

const app = createApp({
    components:{
        Videos,
    }
});

app.mount("#app");