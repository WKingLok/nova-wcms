<template>
  <div>
    {{ fieldValue }}
  </div>
</template>

<script>
import get from "lodash/get";
export default {
  props: ["resourceName", "field"],
  computed: {
    fieldValue() {
      let _value = this.field.displayedAs || this.field.value;
      return get(_value, "en", Object.values(_value)[0]);
    },
  },
};
</script>
