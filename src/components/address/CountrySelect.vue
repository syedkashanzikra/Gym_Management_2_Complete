<template>
  <base-select
    class="country-select"
    :error-message="errorMessage"
    :error="error"
    :hide-bottom-space="!error"
    :model-value="modelValue || undefined"
    @update:model-value="onChange"
    @complete="onComplete"
    emit-value
    dense
    :outlined="!borderless"
    :borderless="borderless"
    use-input
    option-label="name"
    option-value="name"
    :filter-method="get"
    cache-key="country"
    auto-complete
    no-auto-complete-emit
    :placeholder="!modelValue ? 'Select or type...' : ''"
    name="country"
    input-debounc="500"
    :query="
      (val) => ({
        filter: val,
        rowsPerPage: 50,
      })
    "
  />
</template>

<script>
import { mapActions } from "pinia";
import { useCountryStore } from "src/stores/country";

export default {
  props: {
    errorMessage: String,
    error: Boolean,
    borderless: Boolean,
    modelValue: String,
    placeholder: {
      type: String,
      default: "placeholder.select",
    },
  },
  emits: ["complete", "update:model-value"],
  methods: {
    ...mapActions(useCountryStore, ["get"]),
    onChange(value) {
      console.func("components/CountrySelect:methods.onChange", arguments);
      if (value === this.modelValue) return false;
      this.$emit("update:model-value", value);
    },
    onComplete(value) {
      console.func("components/CountrySelect:methods.onComplete", arguments);
      this.$emit("complete", value);
    },
  },
};
</script>
