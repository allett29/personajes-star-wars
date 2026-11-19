import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = 'Archivo Star Wars';

createInertiaApp({
    title: (title) => (title ? `${title} | ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    // Barra de carga superior mientras se navega entre páginas.
    progress: { color: '#2563EB' },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
