<template>
  <base-btn
    rounded
    flat
    size="18px"
    icon="fal fa-cart-shopping"
    :to="{ name: 'Cart' }"
  >
    <q-badge
      rounded
      style="padding: 4px 6px; font-weight: bold"
      floating
      :label="cartCount"
    />
  </base-btn>
</template>

<script>
import { mapState } from "pinia";
import { useShopStore } from "stores/shop";
export default {
  computed: {
    ...mapState(useShopStore, ["cartTotal", "cartCount"]),
  },
};
</script>
