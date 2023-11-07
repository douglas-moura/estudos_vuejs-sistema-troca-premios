import App from './App.vue'
import router from './router'
import mdiVue from 'mdi-vue/v3'
import * as mdijs from '@mdi/js'
import emitterPlugin from './emitter.js'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

createApp(App).use(emitterPlugin).use(createPinia()).use(router).use(mdiVue, {
    icons: mdijs
}).mount('#app')