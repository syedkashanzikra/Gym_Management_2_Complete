<template>
  <base-section
    flat
    bordered
    :title="$t('paymentSummary')"
    dense
    no-row
    class="subscription-manage"
  >
    <div class="plan-details">
      <template v-if="create">
        <p
          v-html="
            $t('plan.create', {
              plan: plan?.label,
              amount: $core.money(price?.amount),
              interval: intervalLabel,
            })
          "
        ></p>
      </template>
      <template v-else-if="change">
        <p
          class="text-body q-mb-sm"
          v-html="
            $t('plan.change', {
              currentPlan: currentPlan?.plan.label,
              plan: plan?.label,
              amount: $core.money(price?.amount),
              interval: intervalLabel,
            })
          "
        ></p>
      </template>
    </div>

    <div class="q-mt-sm payment-btn">
      <base-btn
        :loading="processing"
        :disabled="isDisable"
        :color="color"
        :label="label"
        @click="onConfirm"
        style="min-width: 150px"
        padding="10px"
      />
      <div class="q-mt-xs text-small">
        <template v-if="change">*{{ $t("hint.priceChange") }}</template>
      </div>
      <stripe-card
        v-show="false"
        :amount="price?.amount"
        :processing="processing"
        @success="onSuccess"
        @confirmed="onConfirmed"
        @failed="onFailed"
        :billingDetails="billingDetails"
        ref="stripeCard"
      />
    </div>
  </base-section>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import { useSubscriptionStore } from "stores/subscription";
import StripeCard from "../payment-methods/StripeCard.vue";

const validateMode = (v) => ["create", "change"].includes(v);

export default {
  components: { StripeCard },
  props: {
    plan: {
      required: true,
      type: Object,
    },
    mode: {
      type: String,
      validator: validateMode,
    },
    interval: String,
    paymentMethod: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      visible: true,
      processing: false,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useSubscriptionStore, [
      "getSubscribed",
      "subscribe",
      "getPaymentMethods",
      "confirm",
    ]),
    ...mapActions(useAppStore, ["currentUser"]),
    onSuccess({ id, card }) {
      console.func(
        "components/ManageSubscription:methods.onSuccess()",
        arguments
      );
      this.processing = true;
      const { last4 } = card;
      this.subscribe({
        plan: this.plan.id,
        payment_interval: this.interval,
        payment_method: id,
        last_four_digit: last4,
      })
        .then((resonse) => {
          this.processing = true;
          const { message, requiresAction, paymentIntent } = resonse;
          if (requiresAction) {
            // this.$core.error('Please confirm your payment.', { title: 'Error' });
            this.$refs.stripeCard.confirmPaymentMethod(paymentIntent);
          } else {
            this.$core.success(message, {
              title: this.$t("dialog.title.success"),
            });
            this.$router.push({ name: "Billing" });
            this.processing = false;
          }
        })
        .catch((error) => {
          this.processing = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onFailed(error) {
      console.func(
        "components/ManageSubscription:methods.onFailed()",
        arguments
      );
      this.$core.error(error, { title: this.$t("dialog.title.paymentFailed") });
      this.processing = false;
    },
    onConfirmed(args) {
      console.func(
        "components/ManageSubscription:methods.onConfirmed()",
        arguments
      );
      this.confirm({
        plan: this.plan.id,
        payment_intent: args.id,
      })
        .then(({ message }) => {
          this.$core.success(message, {
            title: this.$t("dialog.title.success"),
          });
          this.$router.push({ name: "Billing" });
          this.processing = false;
        })
        .catch((error) => {
          this.processing = false;
          this.$core.error(error, {
            title: this.$t("dialog.title.paymentAuthenticationFailure"),
          });
        });
    },
    onConfirm() {
      console.func(
        "components/ManageSubscription:methods.onConfirm()",
        arguments
      );
      this.onSuccess(this.paymentMethod);
    },
  },
  computed: {
    ...mapState(useAppStore, ["billingDetails", "isSubscribed"]),
    ...mapState(useSubscriptionStore, [
      "upcomingInvoice",
      "currentPlan",
      "subscription",
    ]),
    price() {
      if (this.plan?.is_custom) {
        return this.plan?.prices[0];
      }
      return this.plan?.prices.find(
        ({ interval }) => interval === this.interval
      );
    },
    intervalLabel() {
      return this.$core.priceToInterval(this.price);
    },
    create() {
      return this.mode === "create";
    },
    change() {
      return this.mode === "change";
    },
    label() {
      return this.$t("proceedPayment");
    },
    color() {
      return "positive";
    },
    isDisable() {
      return !this.paymentMethod?.id;
    },
  },
};
</script>
