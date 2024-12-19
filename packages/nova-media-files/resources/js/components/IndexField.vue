<template>
  <div>{{ count }} file(s)</div>
</template>

<script>
import get from "lodash/get";
import size from "lodash/size";
export default {
  props: ["resourceName", "field"],
  computed: {
    count() {
      return size(this.field.displayedAs || get(this.field, "value", []));
    },
  },
};
</script>
