<template>
  <base-dialog
    content-style="width: 800px; max-width: 95vw"
    title="Cancel order"
    body-class="scroll"
    ref="dialog"
    persistent
    use-separator
    @hide="onDialogHide"
  >
    <template v-slot:body>
      <div class="row q-col-gutter-md">
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">REASON</div>
          <q-select
            dense
            outlined
            v-model="cancel_order.reason"
            map-options
            emit-value
            options-dense
            :options="[
              {
                label: 'Customer changed/cancelled order',
                value: 'CUSTOMER',
              },
              {
                label: 'Fraudulent order',
                value: 'FRAUD',
              },
              {
                label: 'Items unavailable',
                value: 'INVENTORY',
              },
              {
                label: 'Payment declined',
                value: 'DECLINED',
              },
              {
                label: 'Other',
                value: 'OTHER',
              },
            ]"
          />
        </div>
        <div v-if="order.has_payment" class="col-sm-12 col-xs-12">
          <div class="text-label">REFUND PAYMENT</div>
          <div>
            <base-radio
              v-model="cancel_order.refund"
              :no-hint="!cancel_order.refund"
              :val="true"
            >
              <template v-slot:label>
                <div class="text-weight-medium">
                  Refund {{ $core.money(order.paid_total) }}
                </div>
              </template>
              <template v-slot:hint>
                <q-item
                  class="q-pa-none"
                  dense
                  v-for="item in order.payments"
                  :key="item.id"
                >
                  <q-item-section style="min-width: auto" avatar>
                    <q-icon size="25px" :name="item.payment_method.logo" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>{{ item.payment_method.name }}</q-item-label>
                    <q-item-label caption>
                      {{ $core.money(item.amount) }} available to refund
                    </q-item-label>
                  </q-item-section>
                </q-item>
              </template>
            </base-radio>
          </div>
          <div>
            <base-radio
              v-model="cancel_order.refund"
              :no-hint="cancel_order.refund"
              :val="false"
            >
              <template v-slot:label>
                <div class="text-weight-medium">Refund later</div>
              </template>
              <template v-slot:hint>
                <div class="q-item__label--caption text-caption">
                  Your customer won’t be refunded. Refund the amount owed at a
                  later point.
                </div>
              </template>
            </base-radio>
          </div>
        </div>
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">INVENTORY</div>
          <q-checkbox
            dense
            v-model="cancel_order.restock"
            label="Restock items"
          />
        </div>
        <div class="col-sm-12 col-xs-12">
          <base-checkbox
            dense
            v-model="cancel_order.send_customer_notification"
          >
            <div class="text-weight-bold">NOTIFY CUSTOMER OF CANCELATION</div>
            <div>Send cancelation details to your customer now</div>
          </base-checkbox>
        </div>
      </div>
    </template>

    <template v-slot:footer>
      <q-card-section class="row flex">
        <q-space />
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn no-caps label="Done" color="primary" @click="onDone" />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import { useOrderStore } from "src/stores/order";

export default {
  props: {
    modelValue: Object,
  },
  data() {
    return {
      order: cloneDeep(this.modelValue),
      cancel_order: {
        moduleId: this.modelValue.id,
        reason: "CUSTOMER",
        restock: true,
        refund: true,
        send_customer_notification: true,
      },
    };
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useOrderStore, ["cancel"]),
    show() {
      console.func("components/order/CancelOrder:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/order/CancelOrder:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/order/CancelOrder:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/order/CancelOrder:methods.onDone()", arguments);
      this.cancel(this.cancel_order)
        .then(({ data, message }) => {
          this.$q.notify(message);
          this.$emit("ok", data);
          this.hide();
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
  },
};
</script>
