<template>
  <base-dialog
    content-style="width: 600px; max-width: 95vw"
    title="Mark as paid"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div class="row q-col-gutter-md">
        <div class="col-sm-12 col-xs-12">
          <div class="">
            Mark this order as paid if you received
            <strong>{{ $core.money(order.due_amount) }}</strong> outside. This
            payment won't be captured and you won't be able to refund it using
            app.
          </div>
        </div>
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">Received payment method</div>
          <q-select
            style="width: 250px"
            dense
            v-model="payment_method"
            :options="options"
            outlined
            map-options
            option-label="name"
            option-value="id"
            emit-value
            :error="payment_method_error"
            @update:model-value="payment_method_error = false"
          >
            <template v-slot:error>
              <div>
                Payment method is required. Please select a received payment
                method.
              </div>
            </template>
          </q-select>
        </div>
      </div>
    </template>

    <template v-slot:footer>
      <q-card-section class="row flex">
        <q-space />
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn
            no-caps
            :label="submit_button_label"
            color="primary"
            @click="onDone"
          />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import { usePaymentMethodStore } from "src/stores/payment-method";

export default {
  emits: ["ok", "hide"],
  props: {
    modelValue: {
      required: true,
      type: Object,
    },
  },
  data() {
    return {
      order: cloneDeep(this.modelValue),
      options: [],
      payment_method: null,
      payment_method_error: false,
    };
  },
  methods: {
    ...mapActions(usePaymentMethodStore, ["get"]),
    async show() {
      console.func("components/order/ManualPayment:methods.show()", arguments);
      await this.get({
        manual: true,
      })
        .then((data) => {
          this.options = data;
          this.payment_method = data[0]?.id;
          this.$refs.dialog.show();
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    hide() {
      console.func("components/order/ManualPayment:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/order/ManualPayment:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/order/ManualPayment:methods.onDone()",
        arguments
      );

      if (!this.payment_method) {
        this.payment_method_error = true;
        return false;
      }

      this.$emit("ok", this.payment_method);
      this.hide();
    },
  },
  computed: {
    submit_button_label() {
      if (this.order.id) {
        return "Mark as paid";
      } else {
        return "Create order";
      }
    },
  },
};
</script>
