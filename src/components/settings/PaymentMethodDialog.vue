<template>
  <base-dialog
    :title="title"
    body-class="q-pa-none scroll"
    ref="dialog"
    @hide="onDialogHide"
    @ok="onDone"
    use-separator
    persistent
    :loading="loading"
  >
    <template v-slot:body>
      <base-section flat>
        <template v-if="manual">
          <div class="col-xs-12">
            <base-label>Additional details</base-label>
            <base-input
              type="textarea"
              hint="Displays to customers when they’re choosing a payment method."
            />
          </div>
          <div class="col-xs-12">
            <base-label>Payment instructions</base-label>
            <base-input
              type="textarea"
              hint="Displays to customers after they place an order with this payment method."
            />
          </div>
        </template>
        <template v-else>
          <div class="col-xs-12">
            <base-label>About {{ payment.name }}</base-label>
            <div class="">{{ payment.description }}</div>
            <div v-if="payment.link" class="q-mt-sm">
              Learn more about
              <a :href="payment.link" target="_blank" rel="noopener noreferrer">
                {{ payment.name }}
              </a>
            </div>
          </div>
          <div
            class="col-xs-12"
            v-for="(item, index) in payment?.credentials"
            :key="index"
          >
            <base-label>{{ item.label }}</base-label>
            <base-input v-model="item.value" />
          </div>
          <div v-if="payment.webhook" class="col-xs-12">
            <base-label>Webhook Endpoint</base-label>
            <base-input v-model="payment.webhook" readonly>
              <template #append>
                <base-btn
                  @click="$core.copyToClipboard(payment.webhook)"
                  dense
                  flat
                  icon="fas fa-clipboard"
                />
              </template>
            </base-input>
          </div>
        </template>
        <div class="col-xs-12">
          <div class="row q-col-gutter-sm">
            <div class="col-xs-12">
              <q-checkbox dense v-model="payment.active" label="Active" />
            </div>
            <div v-if="!manual" class="col-xs-12">
              <q-checkbox dense v-model="payment.test_mode" label="Test Mode" />
            </div>
          </div>
        </div>
      </base-section>
    </template>
  </base-dialog>
</template>

<script>
import { mapActions } from "pinia";
import { usePaymentMethodStore } from "src/stores/payment-method";

export default {
  props: {
    title: String,
    modelValue: Object,
  },
  data() {
    return {
      payment: this.modelValue,
      loading: false,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(usePaymentMethodStore, ["update"]),
    async show() {
      console.func("components/shop/VariantDialog:methods.show()", arguments);
      this.payment = this.modelValue;
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/shop/VariantDialog:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/shop/VariantDialog:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/shop/VariantDialog:methods.onDone()", arguments);
      this.loading = true;
      this.update(this.payment)
        .then(({ data }) => {
          this.$emit("ok", data);
          this.hide();
          this.loading = false;
        })
        .catch((error) => {
          this.$core.error(error);
          this.loading = false;
        });
    },
  },
  computed: {
    creating() {
      return !this.payment?.id;
    },
    manual() {
      return this.payment.provider == "manual";
    },
  },
};
</script>
