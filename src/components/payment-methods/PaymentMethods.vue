<template>
  <base-section
    flat
    bordered
    :title="$t(title)"
    dense
    class="payment-methods"
    no-row
  >
    <div class="row q-col-gutter-sm">
      <div
        v-for="item in paymentMethods"
        :key="item.id"
        class="col-xs-12 col-sm-6"
      >
        <payment-method-card
          v-bind="item"
          :pay-mode="payMode"
          :selected="modelValue"
          @update:selected="$emit('update:model-value', item)"
        />
      </div>
      <div class="col-xs-12 col-sm-6" v-if="payMode">
        <payment-method-card
          v-bind="offlinePayment"
          :pay-mode="payMode"
          :selected="modelValue"
          @update:selected="$emit('update:model-value', offlinePayment)"
        />
      </div>
      <div class="col-xs-12 col-sm-6" v-else-if="noPaymentMethods">
        <payment-method-placeholder animation="none" />
      </div>
    </div>
    <slot></slot>
    <template v-if="hasPaymentStripe" #bottom>
      <div class="text-right">
        <base-btn
          @click="onAddPaymentMethod"
          color="primary"
          :label="$t('addPaymentMethod')"
          link
        />
      </div>
    </template>
    <template v-if="loading" #skeleton>
      <base-section-skeleton flat bordered />
    </template>
  </base-section>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useSubscriptionStore } from "stores/subscription";
import AddPaymentMethod from "./AddPaymentMethod.vue";
import PaymentMethodCard from "./PaymentMethodCard.vue";
import BaseSectionSkeleton from "../skeleton/BaseSectionSkeleton.vue";
import PaymentMethodPlaceholder from "../skeleton/PaymentMethodPlaceholder.vue";
import { useAppStore } from "src/stores/app";

const offlinePayment = {
  id: "manual",
  icon: "fas fa-money-check-dollar",
  card_number: "cash",
  exp_date: "payCash",
  card: {},
};

export default {
  components: {
    PaymentMethodCard,
    BaseSectionSkeleton,
    PaymentMethodPlaceholder,
  },
  props: {
    modelValue: {
      type: Object,
      default: () => ({}),
    },
    payMode: Boolean,
    title: {
      type: String,
      default: "paymentMethods",
    },
  },
  emits: ["update:model-value"],
  data() {
    return {
      offlinePayment,
      loading: true,
    };
  },
  methods: {
    ...mapActions(useSubscriptionStore, [
      "getPaymentMethods",
      "addPaymentMethod",
    ]),
    onAddPaymentMethod() {
      console.func(
        "components/payment-methods/PaymentMethods:methods.onAddPaymentMethod()",
        arguments
      );
      this.$q
        .dialog({
          component: AddPaymentMethod,
        })
        .onOk(({ payment_method, isDefault, card, hide, setLoading }) => {
          const { last4 } = card;
          this.addPaymentMethod({
            payment_method,
            last_four_digit: last4,
            is_default: isDefault,
          })
            .then(({ message }) => {
              hide();
              this.$core.success(message, {
                title: this.$t("dialog.title.success"),
              });
            })
            .catch((error) => {
              setLoading();
              this.$core.error(error, { title: this.$t("dialog.title.error") });
            });
        });
    },
  },
  async mounted() {
    await this.getPaymentMethods();
    if (!this.defaultPaymentMethod?.id) {
      const methods = [...this.paymentMethods, offlinePayment];
      this.$emit("update:model-value", { ...methods[0] });
    }
    this.loading = false;
  },
  computed: {
    ...mapState(useAppStore, ["hasPaymentStripe"]),
    ...mapState(useSubscriptionStore, [
      "paymentMethods",
      "subscription",
      "currentPlan",
      "noPaymentMethods",
      "defaultPaymentMethod",
      "isSubscribed",
      "hasCancelled",
    ]),
  },
};
</script>
