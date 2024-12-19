<template>
  <PanelItem :index="index" :field="field">
    <template v-slot:value>
      <div v-for="permission in fieldValue" :key="permission">
        {{ permission }}
      </div>
    </template>
  </PanelItem>
</template>

<script>
import { computed } from "vue";
export default {
  props: ["index", "resource", "resourceName", "resourceId", "field"],
  setup(props) {
    const fieldValue = computed(() =>
      (props.field.displayedAs || props.field.value).map(
        (permission) => props.field.options[permission]
      )
    );

    return { fieldValue };
  },
};
</script>
