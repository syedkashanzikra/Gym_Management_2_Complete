<template>
  <div>
    <template v-if="is_custom">
      {{ title }}
    </template>
    <template v-else>
      <template v-if="!is_product_deleted && !is_variant_deleted">
        <base-btn
          link
          size="12px"
          tag="a"
          :to="{
            name: 'products.variant',
            params: {
              product_id: product_id,
              variant_id: variant_id,
            },
            query: {
              action: 'edit',
            },
          }"
        >
          {{ title }}
        </base-btn>
      </template>
      <template v-else-if="!is_product_deleted">
        <base-btn
          link
          size="12px"
          tag="a"
          :to="{
            name: 'Single Product',
            params: {
              id: product_id,
            },
            query: {
              action: 'edit',
            },
          }"
        >
          {{ title }}
        </base-btn>
      </template>
      <template v-else>
        <span>
          {{ title }}
          <q-icon
            class="q-ml-xs"
            name="fad fa-info-circle"
            size="13px"
            color="negative"
            v-if="is_product_deleted"
          >
            <base-tooltip>This product has been deleted.</base-tooltip>
          </q-icon>
        </span>
      </template>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    title: [String],
    product_id: [String, Number],
    variant_id: [String, Number],
    is_product_deleted: {
      type: [Boolean],
      default: false,
      required: false,
    },
    is_variant_deleted: {
      type: [Boolean],
      default: false,
      required: false,
    },
    is_custom: {
      type: [Boolean],
      default: false,
      required: false,
    },
    attributes: {},
  },
};
</script>
