<template>
  <div
    class="mi-h-[35px] mi-w-[35px] mi-rounded-lg mi-overflow-hidden"
    v-for="(image, uuid) in fieldValue"
    :key="uuid"
  >
    <img :src="image.preview_url" />
  </div>
</template>

<script>
import get from "lodash/get";
export default {
  props: ["resourceName", "field"],
  computed: {
    fieldValue() {
      return this.field.displayedAs || get(this.field, "value", []);
    },
  },
};
</script>
