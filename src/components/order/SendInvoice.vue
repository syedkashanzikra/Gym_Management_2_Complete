<template>
  <base-dialog
    content-style="width: 700px; max-width: 95vw"
    title="Send invoice"
    body-class="scroll"
    class="send-invoice"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div v-if="review" class="row q-col-gutter-md">
        <div class="col-sm-12 col-xs-12">
          <q-list class="bordered" dense>
            <q-item>
              <q-item-section avatar>
                <q-item-label class="text-bold">To</q-item-label>
              </q-item-section>
              <q-item-section>
                <q-item-label caption>{{ invoice.to }}</q-item-label>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section avatar>
                <q-item-label class="text-bold">From</q-item-label>
              </q-item-section>
              <q-item-section>
                <q-item-label caption>{{ invoice.from.label }}</q-item-label>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section avatar>
                <q-item-label class="text-bold">Subject</q-item-label>
              </q-item-section>
              <q-item-section>
                <q-item-label caption>{{ invoice.subject }}</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </div>
        <div class="col-sm-12 col-xs-12">
          <!-- TODO: dynamic email template -->
          {{ invoice.message }}
        </div>
      </div>
      <div v-else class="row q-col-gutter-md">
        <div class="col-sm-6 col-xs-12">
          <div class="text-label">To</div>
          <q-input dense outlined v-model="invoice.to" type="email" />
        </div>
        <div class="col-sm-6 col-xs-12">
          <div class="text-label">From</div>
          <q-select
            dense
            v-model="invoice.from"
            :options="options"
            outlined
            map-options
            option-label="label"
            option-value="email"
          />
        </div>
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">Subject</div>
          <q-input dense outlined v-model="invoice.subject" />
        </div>
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">Custom message (optional)</div>
          <q-input
            bottom-slots
            outlined
            v-model="invoice.message"
            type="textarea"
          >
            <template v-slot:hint>
              <div>
                This template can be edited in
                <a href="javascript:void()">notifications</a>
              </div>
            </template>
          </q-input>
        </div>
      </div>
    </template>

    <template v-slot:footer>
      <q-card-section class="row flex">
        <q-btn
          v-if="review"
          @click="review = !review"
          no-caps
          outline
          label="Back"
          color="grey"
        />
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
      invoice: {
        to: this.modelValue.customer.email,
        from: {
          label: '"Shoparket" <hello@shoparket.com>',
          email: "hello@shoparket.com",
        },
        subject: "Invoice {{label}}",
        message: null,
        order: this.modelValue.id,
      },
      options: [],
      review: false,
    };
  },
  methods: {
    async show() {
      console.func("components/order/SendInvoice:methods.show()", arguments);
      // TODO: Get email options from server
      this.options = [
        {
          label: '"Shoparket" <hello@shoparket.com>',
          email: "hello@shoparket.com",
        },
      ];
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/order/SendInvoice:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/order/SendInvoice:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/order/SendInvoice:methods.onDone()", arguments);
      if (this.review) {
        this.$emit("ok", this.payment_method);
        this.hide();
      } else {
        this.review = true;
      }
    },
  },
  computed: {
    submit_button_label() {
      if (this.review) {
        return "Send invoice";
      } else {
        return "Review invoice";
      }
    },
  },
};
</script>
