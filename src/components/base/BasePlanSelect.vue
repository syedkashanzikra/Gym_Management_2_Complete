<template>
  <base-select
    class="base-plan-select"
    bg-color="white"
    :placeholder="$t('placeholder.select')"
    dense
    outlined
    :model-value="modelValue"
    :filter-method="get"
    map-options
    use-filter
    options-html
    :option-label="
      (opt) =>
        `${opt.label} - ${$core.money(opt.monthly_fee)} | ${$core.money(
          opt.yearly_fee
        )}`
    "
    option-value="id"
    @update:model-value="onChange"
  />
</template>

<script>
import { mapActions } from "pinia";
import { usePlanStore } from "stores/plan";
export default {
  props: {
    modelValue: {},
  },
  emits: ["update:model-value"],
  methods: {
    ...mapActions(usePlanStore, ["get"]),
    onChange(value) {
      this.$emit("update:model-value", value);
    },
  },
};
</script>
