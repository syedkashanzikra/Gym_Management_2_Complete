<template>
  <base-select
    dense
    outlined
    map-options
    :use-input="!useFilter"
    :use-filter="useFilter"
    :hint="hint"
    input-debounce="500"
    debounce="500"
    option-label="name"
    option-value="id"
    class="category-select"
    hide-no-option
    :placeholder="!modelValue ? 'e.g. Shirts' : ''"
    :model-value="modelValue"
    @update:model-value="onChange"
    :filter-method="get"
    new-value-mode="add-unique"
  />
</template>

<script>
import { mapActions } from "pinia";
import { useCategoryStore } from "src/stores/product/category";
export default {
  props: {
    modelValue: {},
    hint: String,
    useInput: Boolean,
    useFilter: Boolean,
  },
  emits: ["update:model-value"],
  methods: {
    ...mapActions(useCategoryStore, ["get"]),
    onChange(value) {
      this.$emit("update:model-value", value);
    },
  },
};
</script>
