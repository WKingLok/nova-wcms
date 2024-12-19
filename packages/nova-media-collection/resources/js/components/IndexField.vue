<template>
  <div class="mc-flex mc-gap-x-2">
    <div
      class="mc-h-[35px] mc-w-[35px] mc-rounded-lg mc-overflow-hidden"
      v-for="(image, uuid) in fieldValue"
      :key="uuid"
    >
      <img :src="image.preview_url" />
    </div>
    <div v-if="count > 3" class="mc-self-end">...</div>
  </div>
</template>

<script>
import get from "lodash/get";
import filter from "lodash/filter";
import size from "lodash/size";
export default {
  props: ["resourceName", "field"],
  computed: {
    count() {
      return size(this.field.displayedAs || get(this.field, "value", []));
    },
    fieldValue() {
      let medias = this.field.displayedAs || get(this.field, "value", []);
      let index = 0;
      return filter(medias, (o) => {
        index++;
        return index <= 3;
      });
    },
  },
};
</script>
