import "../css/app.css";

import { createInertiaApp } from "@inertiajs/vue3";
import Aura from "@primeuix/themes/aura";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import Button from "primevue/button";
import Card from "primevue/card";
import Column from "primevue/column";
import PrimeVue from "primevue/config";
import DataTable from "primevue/datatable";
import Dropdown from "primevue/dropdown";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import MultiSelect from "primevue/multiselect";
import Paginator from "primevue/paginator";
import ProgressBar from "primevue/progressbar";
import Skeleton from "primevue/skeleton";
import Tag from "primevue/tag";
import Toast from "primevue/toast";
import ToastService from "primevue/toastservice";
import Toolbar from "primevue/toolbar";
import type { DefineComponent } from "vue";
import { createApp, h } from "vue";
import { ZiggyVue } from "ziggy-js";
import { initializeTheme } from "./composables/useAppearance";

import Editor from "primevue/editor";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        darkModeSelector: ".dark",
                    },
                },
            })
            .use(ToastService)
            .component("Toast", Toast)
            .component("DataTable", DataTable)
            .component("Column", Column)
            .component("Button", Button)
            .component("Card", Card)
            .component("Dropdown", Dropdown)
            .component("InputText", InputText)
            .component("IconField", IconField)
            .component("InputIcon", InputIcon)
            .component("MultiSelect", MultiSelect)
            .component("Paginator", Paginator)
            .component("Tag", Tag)
            .component("Toolbar", Toolbar)
            .component("ProgressBar", ProgressBar)
            .component("Skeleton", Skeleton)
            .component("Editor", Editor)
            .mount(el);
    },
    progress: {
        color: "#0369a1",
    },
});

// This will set light / dark mode on page load...
initializeTheme();
