<template>
  <span>{{ fieldValue }}</span>
</template>

<script>
import { computed } from "vue";
export default {
  props: ["resourceName", "field"],
  setup(props) {
    const fieldValue = computed(() =>
      (props.field.displayedAs || props.field.value)
        .map((permission) => props.field.options[permission])
        .join(", ")
    );

    return { fieldValue };
  },
};
</script>
