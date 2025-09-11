import { watch, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

export function useFlashToast() {
    const page = usePage();
    const toast = useToast();

    watch(
        () => page.props.flash,
        async (flash: any) => {
            if (!flash) return;

            await nextTick();

            if (flash.success) {
                toast.add({
                    severity: 'success',
                    summary: 'Success!',
                    detail: flash.success,
                    life: 5000
                });
            }

            if (flash.error) {
                toast.add({
                    severity: 'error',
                    summary: 'Error!',
                    detail: flash.error,
                    life: 7000
                });
            }

            if (flash.info) {
                toast.add({
                    severity: 'info',
                    summary: 'Info',
                    detail: flash.info,
                    life: 4000
                });
            }

            if (flash.warning) {
                toast.add({
                    severity: 'warn',
                    summary: 'Warning',
                    detail: flash.warning,
                    life: 6000
                });
            }
        },
        {
            immediate: true,
            deep: true
        }
    );
}
