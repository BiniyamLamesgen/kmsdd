<template>
  <div>
    <div class="aspect-w-16 aspect-h-9 relative">
      <img
        ref="image"
        :src="imageUrl"
        alt="Cropper"
        class="max-w-full max-h-full"
      />
    </div>
    <div class="mt-2 flex justify-end space-x-2">
      <button
        class="btn btn-outline"
        @click="$emit('cancel')"
        type="button"
      >Cancel</button>
      <button
        class="btn btn-primary"
        @click="cropImage"
        type="button"
      >Crop</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import Cropper from "cropperjs";
import { ref, onMounted, onBeforeUnmount, defineProps, getCurrentInstance } from "vue";

const props = defineProps({
  imageUrl: String,
});

const image = ref<HTMLImageElement | null>(null);
let cropper: Cropper | null = null;

onMounted(() => {
  if (image.value) {
    cropper = new Cropper(image.value, {
      aspectRatio: 16 / 9,
      viewMode: 1,
      movable: true,
      zoomable: true,
      rotatable: false,
      scalable: false,
      autoCropArea: 1,
      responsive: true,
    } as any); // Cast to any to suppress type errors
  }
});

onBeforeUnmount(() => {
  if (cropper) {
    (cropper as any).destroy();
    cropper = null;
  }
});

const { emit } = getCurrentInstance()!;

function cropImage() {
  if (!cropper) return;
  (cropper as any).getCroppedCanvas().toBlob((blob: Blob | null) => {
    if (blob) {
      const file = new File([blob], "cropped-image.png", { type: "image/png" });
      emit("cropped", file);
    }
  }, "image/png");
}
</script>
