<template>
  <q-page padding>
    <base-page-header
      class="q-mb-md"
      :title="$route.meta.title"
      hint="Manage the places you stock inventory, fulfill orders, and sell products."
      :tabs="['Locations', 'Taxes', 'Payments']"
      use-route
    />
    <div v-if="loaded" class="payment-methods">
      <q-toolbar>
        <base-btn
          icon="fas fa-arrow-left"
          link
          label="Go to settings"
          :to="{ name: 'Settings' }"
        />
      </q-toolbar>
      <q-scroll-area style="height: calc(100vh - 300px)">
        <div class="bg-white payments-list rounded-border">
          <payment-method
            v-for="(item, index) in rows"
            :key="index"
            class="payments-card q-py-md"
            v-bind="item"
            @edit="onEdit(item)"
          />
        </div>
      </q-scroll-area>
    </div>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import PaymentMethodDialog from "components/settings/PaymentMethodDialog.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { usePaymentMethodStore } from "src/stores/payment-method";
import PaymentMethod from "src/components/settings/PaymentMethod.vue";

export default {
  components: { SkeletonSinglePage, PaymentMethod },
  data() {
    return {
      loaded: false,
    };
  },
  methods: {
    ...mapActions(usePaymentMethodStore, ["get"]),
    onEdit(args) {
      console.func(
        "components/settings/ShopLocationsDialog:methods.onEdit()",
        arguments
      );
      this.$q.dialog({
        component: PaymentMethodDialog,
        componentProps: {
          modelValue: args,
          title: `Set up ${args?.name}`,
        },
      });
    },
  },
  computed: {
    ...mapState(usePaymentMethodStore, ["rows"]),
  },
  mounted() {
    this.get()
      .then(() => {
        this.loaded = true;
      })
      .catch((error) => {
        this.$core.error(error);
      });
  },
};
</script>

<style lang="scss">
.payments-list {
  border: 1px solid $separator-color;
}

.payments-list > *:not(:last-child) {
  border-bottom: 1px solid $separator-color;
}
</style>
