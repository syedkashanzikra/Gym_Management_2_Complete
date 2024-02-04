<template>
  <base-dialog
    content-style="width: 700px; max-width: 95vw"
    title="Add custom product"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div class="row q-col-gutter-md">
        <div class="col-sm-6 col-xs-12">
          <div class="text-label">Product name</div>
          <q-input dense outlined v-model="product.title" type="text" />
        </div>
        <div class="col-sm-3 col-xs-6">
          <div class="text-label">Price</div>
          <base-price-input dense outlined v-model="product.price" />
        </div>
        <div class="col-sm-3 col-xs-6">
          <div class="text-label">Quantity</div>
          <q-input
            dense
            outlined
            v-model="product.quantity"
            type="number"
            min="1"
          />
        </div>
        <div class="col-sm-12 col-xs-12">
          <div class="q-gutter-y-sm">
            <div>
              <q-checkbox
                dense
                v-model="product.taxable"
                label="Charge tax on this product"
              />
            </div>
          </div>
        </div>
      </div>
    </template>

    <template v-slot:footer>
      <q-card-section class="text-right">
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn no-caps label="Add product" color="primary" @click="onDone" />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
export default {
  data() {
    return {
      product: {
        quantity: 1,
        taxable: true,
        is_custom: true,
      },
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func("components/order/CustomProduct:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/order/CustomProduct:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/order/CustomProduct:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/order/CustomProduct:methods.onDone()",
        arguments
      );
      this.$emit("ok", this.product);
      this.hide();
    },
  },
};
</script>
