import { useToast as usePrimeToast } from 'primevue/usetoast';

export interface ToastOptions {
  severity?: 'success' | 'info' | 'warn' | 'error' | 'secondary' | 'contrast';
  summary?: string;
  detail?: string;
  life?: number;
  closable?: boolean;
  group?: string;
}

export function useToast() {
  const toast = usePrimeToast();

  const showToast = (options: ToastOptions) => {
    toast.add({
      severity: options.severity || 'info',
      summary: options.summary || '',
      detail: options.detail || '',
      life: options.life || 3000,
      closable: options.closable !== false,
      group: options.group || 'default'
    });
  };

  const success = (summary: string, detail?: string, options?: Omit<ToastOptions, 'severity' | 'summary' | 'detail'>) => {
    showToast({ ...options, severity: 'success', summary, detail });
  };

  const error = (summary: string, detail?: string, options?: Omit<ToastOptions, 'severity' | 'summary' | 'detail'>) => {
    showToast({ ...options, severity: 'error', summary, detail });
  };

  const info = (summary: string, detail?: string, options?: Omit<ToastOptions, 'severity' | 'summary' | 'detail'>) => {
    showToast({ ...options, severity: 'info', summary, detail });
  };

  const warning = (summary: string, detail?: string, options?: Omit<ToastOptions, 'severity' | 'summary' | 'detail'>) => {
    showToast({ ...options, severity: 'warn', summary, detail });
  };

  const clear = (group?: string) => {
    toast.removeGroup(group || 'default');
  };

  const clearAll = () => {
    toast.removeAllGroups();
  };

  return {
    showToast,
    success,
    error,
    info,
    warning,
    clear,
    clearAll
  };
}