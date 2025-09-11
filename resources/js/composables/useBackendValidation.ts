import { reactive } from 'vue';

export type BackendValidationErrors<T> = {
    [K in keyof T]?: string | string[];
}


export function useBackendValidation<T extends Record<string, any>>() {

    const backendErrors = reactive<BackendValidationErrors<T>>({});


    const mapBackendErrors = (
        errorsFromBackend: BackendValidationErrors<T>,
        setFieldError: (field: keyof T, message: string) => void
    ) => {
        console.log('Backend errors received:', errorsFromBackend);
        Object.entries(errorsFromBackend).forEach(([field, messages]) => {
            if (messages) {
                // Handle both string and string[] formats from backend
                const errorMessage = Array.isArray(messages) ? messages[0] : messages;
                console.log(`Setting error for field ${field}:`, errorMessage);
                setFieldError(field as keyof T, errorMessage);
                (backendErrors as any)[field] = messages;
            }
        });
    };


    const getBackendError = (field: keyof T): string => {
        const error = (backendErrors as any)[field];
        if (!error) return '';
        return Array.isArray(error) ? error[0] : error;
    };


    const clearBackendErrors = () => {
        Object.keys(backendErrors).forEach(key => {
            delete (backendErrors as any)[key];
        });
    };


    const clearBackendError = (field: keyof T) => {
        if ((backendErrors as any)[field]) {
            delete (backendErrors as any)[field];
        }
    };


    const hasBackendError = (field: keyof T): boolean => {
        return !!(backendErrors as any)[field];
    };

    return {
        backendErrors,
        mapBackendErrors,
        getBackendError,
        clearBackendErrors,
        clearBackendError,
        hasBackendError,
    };
}
