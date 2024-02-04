<template>
  <div class="billing-details">
    <q-markup-table class="no-hover" wrap-cells flat dense separator="none">
      <tbody>
        <tr>
          <td class="text-left">Subtotal</td>
          <td class="text-left">
            <div v-if="view">{{ order.total_products }}</div>
          </td>
          <td class="text-right">{{ $core.money(order.sub_total) }}</td>
        </tr>
        <tr>
          <td class="text-left">
            <base-btn
              v-if="!view"
              @click="addDiscount(order)"
              :label="order.discount ? 'Edit discount' : 'Add discount'"
              link
            />
            <div v-else>Discount</div>
          </td>
          <template v-if="order.discount">
            <td class="text-left">
              {{ order.discount.description || "Custom discount" }}
            </td>
            <td class="text-right">-{{ $core.money(order.discount_total) }}</td>
          </template>
          <template v-else>
            <td class="text-left">—</td>
            <td class="text-right">{{ $core.money(0) }}</td>
          </template>
        </tr>
        <tr>
          <td class="text-left">
            <base-btn v-if="!view" label="Tax" link>
              <collect-tax
                transition-show="jump-up"
                transition-hide="jump-down"
                v-model="order.collect_tax"
                @update:model-value="onChangeTax"
              />
            </base-btn>
            <div v-else>Tax</div>
          </td>
          <template v-if="hasTaxes">
            <td class="text-left">
              <template v-if="order.tax_lines.length > 1">
                <base-btn padding="0" label="All tax rates" link>
                  <q-menu transition-show="jump-down" transition-hide="jump-up">
                    <q-list class="q-pa-sm" style="min-width: 200px">
                      <q-item
                        dense
                        class="q-px-sm q-pa-none"
                        v-for="(tax_line, index) in order.tax_lines"
                        :key="index"
                      >
                        <q-item-section avatar>
                          {{ `${tax_line.label} ${tax_line.rate}%` }}
                        </q-item-section>
                        <q-item-section class="text-right q-pa-none">
                          {{ $core.money(tax_line.amount) }}
                        </q-item-section>
                      </q-item>
                      <q-separator />
                      <q-item dense class="q-px-sm q-pa-none">
                        <q-item-section class="text-weight-bold" avatar>
                          Total tax
                        </q-item-section>
                        <q-item-section
                          class="text-right text-weight-bold q-pa-none"
                        >
                          {{ $core.money(order.tax_total) }}
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </base-btn>
              </template>
              <template v-else>
                <div v-for="(tax_line, index) in order.tax_lines" :key="index">
                  {{ `${tax_line.label} ${tax_line.rate}%` }}
                </div>
              </template>
            </td>
            <td class="text-right">
              {{ $core.money(order.tax_total) }}
            </td>
          </template>
          <template v-else>
            <td class="text-left">Not collected</td>
            <td class="text-right">
              {{ $core.money(order.tax_total) }}
            </td>
          </template>
        </tr>
        <tr>
          <td class="text-left text-weight-medium">Total</td>
          <td class="text-left"></td>
          <td class="text-right text-weight-medium">
            {{ $core.money(order.grand_total) }}
          </td>
        </tr>
        <template v-if="hasPayment">
          <tr v-if="order.paid_total || hasDue">
            <td colspan="100%">
              <q-separator />
            </td>
          </tr>
          <tr class="text-primary" v-if="order.paid_total">
            <td class="text-left text-weight-medium">Paid by customer</td>
            <td class="text-left"></td>
            <td class="text-right text-weight-medium">
              {{ $core.money(order.paid_total) }}
            </td>
          </tr>
          <tr class="text-orange-9" v-if="hasDue">
            <td class="text-left text-weight-medium">
              Balance (customer owes you)
            </td>
            <td class="text-left"></td>
            <td class="text-right text-weight-medium">
              {{ $core.money(order.due_amount) }}
            </td>
          </tr>
        </template>
      </tbody>
    </q-markup-table>
    <template v-if="canCollectPayment">
      <q-separator class="q-my-md" />
      <div class="row flex">
        <q-space />
        <div class="q-gutter-md">
          <!-- <base-btn
            icon="fas fa-file-invoice"
            outline
            color="grey"
            size="md"
            label="Send invoice"
            @click="onSendInvoice"
          /> -->
          <base-btn
            @click="onMarkAsPaid"
            icon="fas fa-credit-card"
            size="md"
            label="Mark as paid"
          />
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import { cloneDeep } from "lodash";
import AddDiscount from "components/order/AddDiscount.vue";
import CollectTax from "components/order/CollectTax.vue";
import ManualPayment from "components/order/ManualPayment.vue";
import SendInvoice from "components/order/SendInvoice.vue";

export default {
  components: { CollectTax },
  props: {
    modelValue: {
      type: [Object],
      required: true,
    },
    view: Boolean,
    hasDue: Boolean,
    hasPayment: Boolean,
  },
  emits: ["update:model-value", "marked-paid"],
  data() {
    return {
      order: cloneDeep(this.modelValue),
    };
  },
  methods: {
    addDiscount(props) {
      console.func(
        "components/order/BillingDetails:methods.addDiscount()",
        arguments
      );
      this.$q
        .dialog({
          component: AddDiscount,
          componentProps: {
            modelValue: cloneDeep(props.discount),
            update: Boolean(props.discount),
          },
        })
        .onOk((payload) => {
          console.func(
            "components/order/BillingDetails:methods.addDiscount.onOk()",
            payload
          );
          if (!payload) {
            props.discount_removed = true;
          }
          props.discount = payload;
          this.$emit("update:model-value", this.order);
        });
    },
    addShipping(props) {
      console.func(
        "components/order/BillingDetails:methods.addShipping()",
        arguments
      );
      this.$q
        .dialog({
          component: AddShipping,
          componentProps: {
            modelValue: cloneDeep(props.shipping_line),
            update: Boolean(props.shipping_line),
          },
        })
        .onOk((payload) => {
          console.func(
            "components/order/BillingDetails:methods.addShipping.onOk()",
            payload
          );
          if (!payload) {
            props.shipping_line_removed = true;
          }
          props.shipping_line = payload;
          this.$emit("update:model-value", this.order);
        });
    },
    onChangeTax(value) {
      this.order.collect_tax = value;
      this.$emit("update:model-value", this.order);
    },
    onMarkAsPaid() {
      console.func(
        "components/order/BillingDetails:methods.onMarkAsPaid()",
        arguments
      );
      this.$q
        .dialog({
          component: ManualPayment,
          componentProps: {
            modelValue: cloneDeep(this.order),
          },
        })
        .onOk((payload) => {
          this.$emit("marked-paid", payload);
        });
    },
    onSendInvoice() {
      console.func(
        "components/order/BillingDetails:methods.onSendInvoice()",
        arguments
      );
      this.$q
        .dialog({
          component: SendInvoice,
          componentProps: {
            modelValue: cloneDeep(this.order),
          },
        })
        .onOk((payload) => {
          this.$emit("send-invoice", payload);
        });
    },
  },
  watch: {
    modelValue: {
      deep: true,
      handler(modelValue) {
        this.order = cloneDeep(modelValue);
      },
    },
  },
  computed: {
    canCollectPayment() {
      return this.hasDue && !this.order.is_cancelled;
    },
    hasTaxes() {
      return this.order.collect_tax && this.order.tax_lines.length;
    },
  },
};
</script>
