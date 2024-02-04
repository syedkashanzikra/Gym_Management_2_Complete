<template>
  <base-select
    class="state-select"
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
    :cache-key="cacheKey"
    option-label="name"
    :option-value="optionValue"
    auto-complete
    :no-auto-complete-emit="hasCountryStates"
    :filter-method="states"
    :placeholder="!modelValue ? 'Select or type...' : ''"
    name="state"
    input-debounc="500"
    :query="
      (val) => ({
        filter: val,
        rowsPerPage: 50,
        code: code,
      })
    "
    :required-query-keys="['code']"
  />
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "src/stores/app";
import { useCountryStore } from "src/stores/country";

export default {
  props: {
    errorMessage: String,
    error: Boolean,
    hasCountryStates: Boolean,
    borderless: Boolean,
    countryCode: String,
    modelValue: String,
    optionValue: { type: String, default: "name" },
    placeholder: {
      type: String,
      default: "placeholder.select",
    },
  },
  methods: {
    ...mapActions(useCountryStore, ["states"]),
    onChange(value) {
      console.func("components/StateSelect:methods.onChange", arguments);
      if (value === this.modelValue) return false;
      this.$emit("update:model-value", value);
    },
    onComplete(value) {
      console.func("components/StateSelect:methods.onComplete", arguments);
      this.$emit("complete", value);
    },
  },
  computed: {
    ...mapState(useAppStore, ["location"]),
    code() {
      return this.countryCode;
    },
    cacheKey() {
      const code = this.$core.slug(this.code ?? "all");
      return `state-` + code;
    },
  },
};
</script>
