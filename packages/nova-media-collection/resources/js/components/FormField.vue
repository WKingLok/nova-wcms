<template>
  <DefaultField
    :field="field"
    :errors="errors"
    :show-help-text="showHelpText"
    :full-width-content="fullWidthContent"
  >
    <template #field>
      <MediaLibraryCollection
        :initial-value="value"
        :max-size-for-preview-in-bytes="512000"
        :route-prefix="routePrefix"
        :validation-rules="rules"
        @change="imageChange"
      />
    </template>
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import { MediaLibraryCollection } from "media-library-pro-vue3-collection";
import { ref } from "vue";
import forEach from "lodash/forEach";
export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ["resourceName", "resourceId", "field"],
  setup(props) {
    const value = ref(props.field.value);

    const routePrefix = "nova-vendor/media-collection/media-library-pro";

    const rules = {
      accept: props.field.accept ?? [
        "image/jpeg",
        "image/png",
        "image/svg+xml",
      ],
      maxSizeInKB: props.field.maxSize ?? 2048,
    };

    const imageChange = (image) => (value.value = image);

    return {
      value,
      routePrefix,
      rules,
      imageChange,
    };
  },
  methods: {
    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      var media = [];
      forEach(this.value, (image) => media.push(image));
      formData.append(this.field.attribute, JSON.stringify(media) || "");
    },
  },
  components: { MediaLibraryCollection },
};
</script>
