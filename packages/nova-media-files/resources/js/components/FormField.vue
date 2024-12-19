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
        :multiple="multiple"
      />
    </template>
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import { MediaLibraryCollection } from "media-library-pro-vue3-collection";
import { ref } from "vue";
import forEach from "lodash/forEach";
import get from "lodash/get";
export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ["resourceName", "resourceId", "field"],
  setup(props) {
    const multiple = get(props.field, "multiple", false);

    const value = ref(props.field.value);

    const routePrefix = "nova-vendor/media-files/media-library-pro";

    const rules = {
      accept: props.field.accept ?? ["application/pdf"],
      maxSizeInKB: props.field.maxSize ?? 4096,
    };

    const imageChange = (image) => (value.value = image);

    return {
      value,
      multiple,
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
