<template>
  <q-page padding>
    <div class="row q-col-gutter-lg">
      <div class="col-xs-12 col-sm-12 order-none">
        <base-page-header :title="$t('billing')" :hint="$t('hint.billing')" />
      </div>
      <div class="col-xs-12 col-sm-8 order-last">
        <div class="row q-col-gutter-lg">
          <div class="col-xs-12">
            <payment-methods />
          </div>
          <div class="col-xs-12">
            <invoice-manager />
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4">
        <current-plan-card />
      </div>
    </div>
  </q-page>
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
  data() {
    return {
      loaded: false,
    };
  },
  methods: {
    ...mapActions(useAppStore, ["currentUser"]),
    ...mapActions(useSubscriptionStore, ["getSubscribed", "getPlans"]),
  },
  async mounted() {
    await this.currentUser();
    await this.getSubscribed();
  },
  computed: {
    ...mapState(useAppStore, ["hasCancelled"]),
  },
};
</script>
