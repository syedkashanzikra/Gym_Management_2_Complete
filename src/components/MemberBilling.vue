<template>
  <div class="row q-col-gutter-md">
    <div class="col-xs-12 col-sm-8">
      <div class="q-gutter-md">
        <payment-methods v-if="loaded" />
        <invoice-manager ref="invoices" v-if="loaded" />
      </div>
    </div>
    <div class="col-xs-12 col-sm-4">
      <div class="q-gutter-md">
        <current-plan-card @changed="onLoad" no-upgrade />
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import { useSubscriptionStore } from "stores/subscription";
import CurrentPlanCard from "components/subscription/CurrentPlanCard.vue";
import PaymentMethods from "components/payment-methods/PaymentMethods.vue";
import InvoiceManager from "components/payment-methods/InvoiceManager.vue";

export default {
  components: { CurrentPlanCard, PaymentMethods, InvoiceManager },
  props: {
    user: Object,
  },
  data() {
    return {
      loaded: false,
    };
  },
  methods: {
    ...mapActions(useAppStore, ["currentUser"]),
    ...mapActions(useSubscriptionStore, [
      "getSubscribed",
      "getPlans",
      "setUser",
      "removeUser",
    ]),
    async onLoad() {
      this.$refs.invoices.onLoad();
    },
  },
  async mounted() {
    await this.setUser(this.user?.id);
    this.loaded = true;
    await this.getSubscribed();
  },
  computed: {
    ...mapState(useAppStore, ["hasCancelled"]),
  },
  unmounted() {
    this.removeUser();
  },
};
</script>
