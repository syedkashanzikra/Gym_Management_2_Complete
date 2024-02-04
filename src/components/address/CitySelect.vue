<template>
  <base-select
    class="city-select"
    :error-message="errorMessage"
    :error="error"
    :hide-bottom-space="!error"
    :model-value="modelValue || undefined"
    @update:model-value="onChange"
    emit-value
    dense
    outlined
    use-input
    option-label="name"
    option-value="name"
    auto-complete
    :filter-method="cities"
    :placeholder="!modelValue ? 'Select or type...' : ''"
    name="city"
    input-debounc="500"
    :query="
      (val) => ({
        filter: val,
        rowsPerPage: 50,
        state_code: state,
        country_code: country,
      })
    "
    :required-query-keys="['state_code', 'country_code']"
  />
</template>

<script>
import { mapActions } from "pinia";
import { useCountryStore } from "src/stores/country";

export default {
  props: {
    errorMessage: String,
    state: String,
    country: String,
    error: Boolean,
    modelValue: String,
    placeholder: {
      type: String,
      default: "placeholder.select",
    },
  },
  methods: {
    ...mapActions(useCountryStore, ["cities"]),
    onChange(value) {
      console.func("components/CountrySelect:methods.onChange", arguments);
      this.$emit("update:model-value", value);
    },
  },
  computed: {
    cacheKey() {
      return `city-` + this.state?.toLowerCase() ?? "all";
    },
  },
};
</script>
