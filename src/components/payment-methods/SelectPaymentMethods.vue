<template>
  <base-select
    dense
    outlined
    :options="[...paymentMethods, offlinePayment]"
    :option-label="(opt) => $t(opt?.card_number)"
    :model-value="modelValue"
    @update:model-value="$emit('update:model-value', $event)"
  />
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useSubscriptionStore } from "stores/subscription";
import AddPaymentMethod from "./AddPaymentMethod.vue";

const offlinePayment = {
  id: "manual",
  icon: "fas fa-money-check-dollar",
  card_number: "cash",
  exp_date: "payCash",
  card: {},
};

export default {
  props: {
    modelValue: {},
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
        .onOk(({ payment_method, isDefault, card, hide }) => {
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
              this.$core.error(error, { title: this.$t("dialog.title.error") });
            });
        });
    },
  },
  async mounted() {
    await this.getPaymentMethods();
    if (!this.defaultPaymentMethod?.id) {
      this.$emit("update:model-value", offlinePayment);
    }
    this.loading = false;
  },
  computed: {
    ...mapState(useSubscriptionStore, [
      "paymentMethods",
      "defaultPaymentMethod",
    ]),
  },
};
</script>
