<template>
  <base-select
    dense
    outlined
    map-options
    use-input
    input-debounce="500"
    debounce="500"
    option-label="name"
    option-value="id"
    class="vendor-select"
    hide-no-option
    :placeholder="!modelValue ? 'e.g. Lotto' : ''"
    :model-value="modelValue"
    @update:model-value="onChange"
    :filter-method="get"
    @new-value="onNewValue"
  />
</template>

<script>
import { mapActions } from "pinia";
import { useVendorStore } from "src/stores/product/vendor";
export default {
  props: {
    modelValue: {},
  },
  emits: ["update:model-value"],
  methods: {
    ...mapActions(useVendorStore, ["get"]),
    onChange(value) {
      this.$emit("update:model-value", value);
    },
    onNewValue(val, done) {
      done(
        {
          name: val,
        },
        "add-unique"
      );
    },
  },
};
</script>
