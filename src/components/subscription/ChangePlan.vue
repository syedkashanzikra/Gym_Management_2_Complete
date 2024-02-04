<template>
  <base-dialog
    :title="title"
    ref="dialog"
    content-style="width: 450px; max-width: 95vw"
    body-class="scroll"
    class="change-plan"
    @hide="onDialogHide"
    @ok="onSuccess(paymentMethod)"
    use-separator
    :loading="loading"
    :done-label="$t('proceed')"
  >
    <template v-slot:body>
      <div class="q-px-sm">
        <div class="q-gutter-y-md">
          <base-select
            dense
            outlined
            :options="plans"
            option-label="label"
            v-model="plan"
          />
          <plan-card
            flat
            no-radio
            v-bind="plan"
            :is-custom="plan.is_custom"
            :interval="interval"
          />
          <q-checkbox
            v-model="interval"
            true-value="year"
            false-value="month"
            color="green"
            :label="$t('yearly')"
            dense
          />
          <div class="">
            <base-label>{{ $t("paymentMethod") }}</base-label>
            <select-payment-methods v-model="paymentMethod" />
            <stripe-card
              v-show="false"
              v-if="loaded"
              :amount="price?.amount"
              :processing="loading"
              @success="onSuccess"
              @confirmed="onConfirmed"
              @failed="onFailed"
              :billingDetails="billingDetails"
              ref="stripeCard"
            />
          </div>
        </div>
      </div>
    </template>
  </base-dialog>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useSubscriptionStore } from "stores/subscription";
import PlanCard from "./PlanCard.vue";
import SelectPaymentMethods from "../payment-methods/SelectPaymentMethods.vue";
import StripeCard from "../payment-methods/StripeCard.vue";

export default {
  components: { PlanCard, SelectPaymentMethods, StripeCard },
  name: "ChangePlan",
  props: {
    title: String,
  },
  emits: ["ok", "hide"],
  data() {
    return {
      plan: null,
      interval: "month",
      paymentMethod: null,
      loading: false,
      loaded: false,
    };
  },
  methods: {
    ...mapActions(useSubscriptionStore, ["subscribe", "confirm"]),
    show() {
      console.func(
        "components/subscription/ChangePlan:methods.show()",
        arguments
      );
      if (this.defaultPaymentMethod?.id) {
        this.paymentMethod = { ...this.defaultPaymentMethod };
      }
      this.$refs.dialog.show();
      setTimeout(() => {
        this.loaded = true;
      }, 100);
    },
    hide() {
      console.func(
        "components/subscription/ChangePlan:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/subscription/ChangePlan:methods.onDialogHide()",
        arguments
      );
      this.paymentMethod = null;
      this.loaded = false;
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/subscription/ChangePlan:methods.onDone()",
        arguments
      );
      this.$emit("ok");
      this.hide();
    },
    onSuccess({ id, card }) {
      console.func(
        "components/ManageSubscription:methods.onSuccess()",
        arguments
      );
      this.loading = true;
      const { last4 } = card;
      this.subscribe({
        plan: this.plan.id,
        payment_interval: this.interval,
        payment_method: id,
        last_four_digit: last4,
      })
        .then((resonse) => {
          this.loading = true;
          const { message, requiresAction, paymentIntent } = resonse;
          if (requiresAction) {
            this.$refs.stripeCard.confirmPaymentMethod(paymentIntent);
          } else {
            this.loading = false;
            this.onDone();
            this.$core.success(message, {
              title: this.$t("dialog.title.success"),
            });
          }
        })
        .catch((error) => {
          this.loading = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onFailed(error) {
      console.func(
        "components/ManageSubscription:methods.onFailed()",
        arguments
      );
      this.$core.error(error, { title: this.$t("dialog.title.paymentFailed") });
      this.loading = false;
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
          this.loading = false;
          this.onDone();
        })
        .catch((error) => {
          this.loading = false;
          this.$core.error(error, {
            title: this.$t("dialog.title.paymentAuthenticationFailure"),
          });
        });
    },
  },
  computed: {
    ...mapState(useSubscriptionStore, [
      "plans",
      "currentPlan",
      "defaultPlan",
      "selectedPlan",
      "defaultPaymentMethod",
      "billingDetails",
    ]),
    price() {
      return this.plan?.prices.find(
        ({ interval }) => interval === this.interval
      );
    },
  },
  mounted() {
    this.$nextTick(() => {
      this.plan = this.selectedPlan(
        this.currentPlan?.plan.id || this.defaultPlan?.plan.id
      );
      this.interval = this.currentPlan?.interval || "month";
    });
  },
};
</script>
