import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import { resolvePageComponent } from 'vite-plugin-laravel/inertia'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import Admin from "@/views/layouts/Admin.vue";
import { createPinia } from 'pinia'

import "./font-awesome.ts"

import '../css/app.scss';

createInertiaApp({
    resolve: (name) => {
        let page = resolvePageComponent(name, import.meta.glob('../views/pages/**/*.vue'));
        page.then((module) => {
            if (module.layout === undefined) module.layout = Admin;
        });
        return page;
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(createPinia())
            .component('Link', Link)
            .component('Head', Head)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el)
    },
    title: title => title + (title ? ' — ' : '') + 'ВсеКолёса CMS',
    progress: {
        color: '#fff'
    }
})

// Очищаем body в семантике
// document.getElementById('app').removeAttribute('data-page');
