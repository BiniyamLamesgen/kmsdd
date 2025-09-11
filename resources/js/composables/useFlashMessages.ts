import { watch, ref, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';

/**
 * Composable to handle flash messages from Laravel controllers
 * that use ->with('success', $message) or ->with('error', $message)
 * 
 * Addresses the issue where flash messages might be lost due to multiple redirects
 * or middleware consuming the flash messages before they reach the frontend.
 */
export function useFlashMessages() {
  const page = usePage();
  const lastProcessedFlash = ref<string>('');

  // Wait for the component to be fully mounted before initializing toast
  let toast: any = null;

  const initializeToast = () => {
    try {
      toast = useToast();
      return true;
    } catch (error) {
      console.warn('Toast service not yet available, retrying...', error);
      return false;
    }
  };

  // Watch for changes in flash messages with more robust detection
  watch(
    () => {
      // Create a unique key from all flash messages to detect changes
      const flash = page.props.flash as any;
      if (!flash) return '';

      return JSON.stringify({
        success: flash.success || null,
        error: flash.error || null,
        info: flash.info || null,
        warning: flash.warning || null,
        url: page.url // Include URL to detect page changes
      });
    },
    async (currentFlashKey, previousFlashKey) => {
      // Skip if no change or if we've already processed this flash
      if (!currentFlashKey || currentFlashKey === previousFlashKey || currentFlashKey === lastProcessedFlash.value) {
        return;
      }

      // Initialize toast if not already done
      if (!toast && !initializeToast()) {
        // If toast is still not available, retry after a delay
        setTimeout(() => {
          if (!toast && !initializeToast()) {
            console.error('Failed to initialize toast service after retries');
            return;
          }
          // Process the flash messages after successful initialization
          processFlashMessages(currentFlashKey);
        }, 100);
        return;
      }

      await processFlashMessages(currentFlashKey);
    },
    {
      immediate: true,
      flush: 'post' // Run after DOM updates
    }
  );

  const processFlashMessages = async (currentFlashKey: string) => {
    // Wait for next tick to ensure DOM is ready
    await nextTick();

    const flash = page.props.flash as any;
    if (!flash || !toast) return;

    // Process success messages
    if (flash.success) {
      toast.add({
        severity: 'success',
        summary: 'Success!',
        detail: flash.success,
        life: 5000
      });
    }

    // Process error messages  
    if (flash.error) {
      toast.add({
        severity: 'error',
        summary: 'Error!',
        detail: flash.error,
        life: 7000
      });
    }

    // Process info messages
    if (flash.info) {
      toast.add({
        severity: 'info',
        summary: 'Info',
        detail: flash.info,
        life: 5000
      });
    }

    // Process warning messages
    if (flash.warning) {
      toast.add({
        severity: 'warn',
        summary: 'Warning',
        detail: flash.warning,
        life: 6000
      });
    }

    // Remember this flash to avoid reprocessing
    lastProcessedFlash.value = currentFlashKey;
  };
}
