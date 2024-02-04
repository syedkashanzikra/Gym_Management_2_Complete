<template>
  <base-section :flat="flat" :title="title" :description="description">
    <div class="col-xs-12 col-sm-6">
      <base-label>{{ $t("label.price") }}</base-label>
      <base-price-input
        v-model="variant.price"
        @update:model-value="$emit('update:model-value', variant)"
      />
    </div>
    <div class="col-xs-12 col-sm-6">
      <base-label>{{ $t("label.compareAtPrice") }}</base-label>
      <base-price-input
        v-model="variant.compare_at_price"
        @update:model-value="$emit('update:model-value', variant)"
      />
    </div>
    <div class="col-xs-12 col-sm-6">
      <base-label>{{ $t("label.costPerItem") }}</base-label>
      <base-price-input
        v-model="variant.cost_per_item"
        @update:model-value="$emit('update:model-value', variant)"
      />
    </div>
    <div class="col-xs-12 col-sm-12">
      <q-checkbox
        size="sm"
        dense
        v-model="variant.taxable"
        @update:model-value="$emit('update:model-value', variant)"
        :label="$t('label.chargeTaxOnThisVariant')"
      />
    </div>
  </base-section>
</template>

<script>
export default {
  props: {
    modelValue: {
      required: true,
      type: Object,
    },
    flat: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: "Pricing",
    },
    description: String,
  },
  emits: ["update:model-value"],
  data() {
    return {
      variant: this.modelValue,
      money: {
        decimal: ".",
        separator: ",",
        prefix: "$ ",
        precision: 2,
        minimumFractionDigits: 2,
        masked: false,
        nullValue: 0,
      },
    };
  },
  watch: {
    modelValue: {
      deep: true,
      handler(val) {
        this.variant = val;
      },
    },
  },
};
</script>
