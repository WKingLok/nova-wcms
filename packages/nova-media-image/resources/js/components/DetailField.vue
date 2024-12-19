<template>
  <PanelItem :index="index" :field="field">
    <template v-slot:value>
      <div class="mi-flex mi-flex-col mi-gap-y-3">
        <div
          class="mi-rounded-lg mi-overflow-hidden"
          v-for="(image, uuid) in get(field, 'value', [])"
          :key="uuid"
        >
          <img :src="image.preview_url" />
        </div>
      </div>
    </template>
  </PanelItem>
</template>

<script>
import get from "lodash/get";
export default {
  props: ["index", "resource", "resourceName", "resourceId", "field"],
  methods: {
    get,
  },
};
</script>
