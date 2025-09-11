import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { router } from '@inertiajs/vue3';
import { useBackendValidation, type BackendValidationErrors } from './useBackendValidation';
import type { ZodSchema } from 'zod';

export function useValidatedForm<T extends Record<string, any>>(
  schema: ZodSchema<T>,
  initialValues: Partial<T>,
  submitUrl: string
) {
  const {
    backendErrors,
    mapBackendErrors,
    getBackendError,
    clearBackendErrors,
    clearBackendError,
    hasBackendError
  } = useBackendValidation<T>();

  const form = useForm<T>({
    validationSchema: toTypedSchema(schema),
    initialValues: initialValues as any,
  });

  const submitForm = (
    successCallback?: () => void,
    errorCallback?: (errors: BackendValidationErrors<T>) => void,
    dataTransform?: (data: T) => T
  ) => {
    return form.handleSubmit(async () => {
      clearBackendErrors();

      // Apply data transformation if provided
      let submitData = dataTransform ? dataTransform(form.values as T) : form.values;

      // Ensure File objects are passed for file fields (e.g. image)
      // If a field is a string but a File is available, replace with File
      Object.keys(submitData).forEach(key => {
        if (submitData[key + 'File'] instanceof File) {
          (submitData as Record<string, any>)[key] = submitData[key + 'File'];
        }
      });

      // Detect if any value is a File or FileList and convert to FormData
      const hasFile = Object.values(submitData).some(
        v => v instanceof File || (v && typeof v === 'object' && 'name' in v && 'size' in v)
      );
      let payload: any = submitData;
      if (hasFile) {
        const formData = new FormData();
        Object.entries(submitData).forEach(([key, value]) => {
          if (value instanceof File) {
            formData.append(key, value);
          } else if (Array.isArray(value) && value[0] instanceof File) {
            value.forEach((file: File) => formData.append(key, file));
          } else {
            formData.append(key, value ?? '');
          }
        });
        payload = formData;
      }

      router.post(submitUrl, payload, {
        forceFormData: hasFile,
        onError: (errors) => {
          // Handle Laravel validation errors
          mapBackendErrors(errors as BackendValidationErrors<T>, (field, message) => {
            form.setFieldError(field as any, message);
          });

          errorCallback?.(errors as BackendValidationErrors<T>);
        },
        onSuccess: (page) => {
          form.resetForm();
          successCallback?.();
        },
      });
    });
  };

  return {
    // Form state and methods
    ...form,

    // Backend validation
    backendErrors,
    getBackendError,
    clearBackendError,
    hasBackendError,

    // Submit handler
    submitForm,
  };
}
