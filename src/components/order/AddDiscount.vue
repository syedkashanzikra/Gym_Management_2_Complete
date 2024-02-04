<template>
  <base-dialog
    class="add-discount"
    content-style="width: 600px; max-width: 95vw"
    :title="title"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div class="row q-col-gutter-md">
        <div class="col-sm-7 col-xs-12">
          <div class="text-label">Discount type</div>
          <q-select
            dense
            outlined
            v-model="discount.type"
            map-options
            emit-value
            :options="[
              {
                label: 'Amount',
                value: 'fixed_amount',
              },
              {
                label: 'Percentage',
                value: 'percentage',
              },
            ]"
          />
        </div>
        <div class="col-sm-5 col-xs-6">
          <div class="text-label">Discount value</div>
          <base-price-input
            v-if="discount.type === 'fixed_amount'"
            dense
            outlined
            v-model="discount.value"
          />
          <base-percentage-input
            v-else
            dense
            outlined
            v-model="discount.value"
          />
        </div>
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">Comment</div>
          <q-input
            hint="Your customers can see this comment"
            dense
            outlined
            v-model="discount.description"
            type="text"
          />
        </div>
      </div>
    </template>

    <template v-slot:footer>
      <q-card-section class="row flex">
        <q-btn
          v-if="update"
          no-caps
          color="negative"
          label="Remove"
          @click="onRemove"
        />
        <q-space />
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn no-caps label="Apply" color="primary" @click="onDone" />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
export default {
  props: {
    modelValue: {
      required: false,
      default: () => ({
        type: "fixed_amount",
      }),
    },
    update: {
      required: false,
      type: [Boolean],
      default: false,
    },
  },
  data() {
    return {
      discount: this.modelValue || {
        type: "fixed_amount",
      },
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func("components/order/AddDiscount:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/order/AddDiscount:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/order/AddDiscount:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/order/AddDiscount:methods.onDone()", arguments);
      this.$emit("ok", this.discount);
      this.hide();
    },
    onRemove() {
      console.func(
        "components/order/AddDiscount:methods.onRemove()",
        arguments
      );
      this.$emit("ok", null);
      this.hide();
    },
  },
  computed: {
    title() {
      return this.update ? "Edit discount" : "Add discount";
    },
  },
};
</script>
