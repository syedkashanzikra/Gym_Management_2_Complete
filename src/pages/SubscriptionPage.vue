<template>
  <q-page padding>
    <div class="row q-col-gutter-lg">
      <div class="col-xs-12 col-sm-12">
        <base-page-header
          :title="$t('subscription')"
          :hint="$t('hint.subscription')"
          no-tabs
        />
      </div>
      <div class="col-xs-12 col-sm-6">
        <base-section flat bordered :title="title">
          <template #action>
            <q-toggle
              dense
              v-model="interval"
              true-value="year"
              false-value="month"
              :label="$t('yearly')"
            />
          </template>
          <div
            v-for="(item, index) in plans"
            :key="index"
            class="col-xs-12 col-sm-6"
          >
            <plan-card
              flat
              v-bind="item"
              :interval="interval"
              :is-custom="item.is_custom"
              v-model:current="plan"
              @update:current="onSelectPlan"
            />
          </div>
          <template v-if="loading" #skeleton>
            <base-section-skeleton flat bordered />
          </template>
        </base-section>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="q-gutter-y-lg">
          <payment-methods
            v-model="paymentMethod"
            pay-mode
            :title="$t('payment')"
          />
          <manage-subscription
            v-if="processPayment"
            :mode="mode"
            :interval="interval"
            :payment-method="paymentMethod"
            :plan="selectedPlan(plan)"
          />
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import { useSubscriptionStore } from "stores/subscription";
import ManageSubscription from "components/subscription/ManageSubscription.vue";
import PlanCard from "components/subscription/PlanCard.vue";
import BaseSectionSkeleton from "components/skeleton/BaseSectionSkeleton.vue";
import PaymentMethods from "components/payment-methods/PaymentMethods.vue";

export default {
  components: {
    PlanCard,
    BaseSectionSkeleton,
    PaymentMethods,
    ManageSubscription,
  },
  data() {
    return {
      mode: "create",
      interval: "month",
      plan: null,
      paymentMethod: null,
      loading: true,
    };
  },
  methods: {
    ...mapActions(useSubscriptionStore, ["getSubscribed", "getPlans"]),
    ...mapActions(useAppStore, ["currentUser"]),
    onSelectPlan(plan, mode = "create") {
      console.func("pages/SubscriptionPage:methods.onSelectPlan()", arguments);
      this.mode = mode;
    },
  },
  computed: {
    ...mapState(useAppStore, ["isSubscribed", "hasCancelled"]),
    ...mapState(useSubscriptionStore, [
      "upcomingInvoice",
      "currentPlan",
      "defaultPaymentMethod",
      "selectedPlan",
      "plans",
      "defaultPlan",
    ]),
    title() {
      return this.isSubscribed ? this.$t("changePlan") : this.$t("selectPlan");
    },
    price() {
      return this.selectedPlan(this.plan)?.prices.find(
        ({ interval }) => interval === this.interval
      );
    },
    processPayment() {
      const plan = this.currentPlan?.stripe_id;
      return this.plan && plan !== this.price?.stripe_id;
    },
  },
  async mounted() {
    await this.currentUser();
    await this.getSubscribed();
    this.interval = this.currentPlan?.interval || "month";
    this.plan = this.currentPlan?.plan.id || this.defaultPlan?.plan.id;
    this.loading = false;
  },
  watch: {
    defaultPaymentMethod(value) {
      if (value?.id) {
        this.paymentMethod = { ...value };
      }
    },
  },
};
</script>
