<template>
  <base-dialog
    content-style="width: 450px; max-width: 95vw"
    :title="$t('addPaymentMethod')"
    ref="dialog"
    class="addPaymentMethod"
    persistent
    @hide="onDialogHide"
    hide-footer
    use-separator
  >
    <template v-slot:body>
      <stripe-card
        flat
        dense
        ref="stripe"
        id="add-card-element"
        @success="onSuccess"
        @failed="onFailed"
        :billingDetails="billingDetails"
      >
        <div class="q-mt-md">
          <q-checkbox dense v-model="isDefault" :label="$t('default')" />
        </div>
        <div class="q-mt-md text-right">
          <base-btn
            color="negative"
            outline
            :disable="loading"
            :label="$t('cancel')"
            v-close-popup
            class="q-mr-sm"
          />
          <base-btn
            color="primary"
            :label="$t('addNew')"
            :loading="loading"
            @click="onAdd"
          />
        </div>
      </stripe-card>
    </template>
  </base-dialog>
</template>

<script>
import { mapState } from "pinia";
import { useAppStore } from "stores/app";
import StripeCard from "./StripeCard.vue";

export default {
  name: "AddPaymentMethod",
  components: {
    StripeCard,
  },
  data() {
    return {
      isDefault: false,
      loading: false,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func(
        "components/payment-methods/AddPaymentMethod:methods.show()",
        arguments
      );
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/payment-methods/AddPaymentMethod:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/payment-methods/AddPaymentMethod:methods.onDialogHide()",
        arguments
      );
      this.loading = false;
      this.$emit("hide");
    },
    onAdd() {
      console.func(
        "components/payment-methods/AddPaymentMethod:methods.onAdd()",
        arguments
      );
      this.loading = true;
      this.$refs.stripe.submit();
    },
    onSuccess(args) {
      console.func(
        "components/payment-methods/AddPaymentMethod:methods.onSuccess()",
        arguments
      );
      const { id, card } = args;
      this.$emit(
        "ok",
        Object.assign(this, {
          payment_method: id,
          card: card,
          isDefault: this.isDefault,
        })
      );
    },
    onFailed(error) {
      console.func(
        "components/payment-methods/AddPaymentMethod:methods.onFailed()",
        arguments
      );
      this.loading = false;
      this.$core.error(error, { title: this.$t("dialog.title.error") });
    },
    setLoading(loading = false) {
      console.func(
        "components/payment-methods/AddPaymentMethod:methods.setLoading()",
        arguments
      );
      this.loading = loading;
    },
  },
  computed: {
    ...mapState(useAppStore, ["billingDetails"]),
  },
};
</script>
<style lang="scss">
.addPaymentMethod .q-card__section {
  padding: 25px;
  padding-top: 15px;
}
</style>
