<template>
  <DefaultField
    :field="field"
    :errors="errors"
    :show-help-text="showHelpText"
    :full-width-content="fullWidthContent"
  >
    <template #field>
      <template v-for="(label, key) in options" :key="key">
        <div class="pp-mb-2 pp-mt-2 pp-flex pp-items-center pp-justify-between">
          <label>
            {{ label }}
          </label>
          <Checkbox
            @input="toggle(key)"
            :checked="get(modelValue, key) ? true : false"
          />
        </div>
      </template>
    </template>
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";
import { ref } from "vue";
import forEach from "lodash/forEach";
import get from "lodash/get";
export default {
  mixins: [FormField, HandlesValidationErrors],
  props: ["resourceName", "resourceId", "field"],
  setup(props) {
    const options = props.field.options;

    const modelValue = ref({});

    if (get(props.field, "value")) {
      forEach(props.field.value, function (key) {
        modelValue.value[key] = true;
      });
    }

    const toggle = (key) => {
      modelValue.value[key] = !modelValue.value[key];
    };

    return {
      options,
      modelValue,
      toggle,
      get,
    };
  },
  methods: {
    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      forEach(this.modelValue, (value, permission) => {
        if (value) {
          formData.append(`${this.field.attribute}[]`, permission || "");
        }
      });
    },
  },
};
</script>
