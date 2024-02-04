<template>
  <base-dialog
    content-style="width: 600px; max-width: 95vw"
    title="Edit contact information"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div class="row q-col-gutter-md">
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">Email</div>
          <q-input dense outlined v-model="customer.email" type="text" />
        </div>
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">Phone number</div>
          <q-input dense outlined v-model="customer.phone_number" type="text" />
        </div>
        <div v-if="has_customer" class="col-sm-12 col-xs-12">
          <q-checkbox
            dense
            v-model="customer.update_customer_profile"
            label="Update customer profile"
          />
        </div>
      </div>
    </template>

    <template v-slot:footer>
      <q-card-section class="text-right">
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn no-caps label="Update" color="primary" @click="onDone" />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
export default {
  props: {
    modelValue: {
      required: true,
      type: [Object],
    },
    has_customer: {
      required: true,
      type: [Boolean],
    },
  },
  data() {
    return {
      customer: this.modelValue,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func("components/address-edit:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/address-edit:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func("components/address-edit:methods.onDialogHide()", arguments);
      this.$emit("hide");
    },
    onDone() {
      console.func("components/address-edit:methods.onDone()", arguments);
      this.$emit("ok", this.customer);
      this.hide();
    },
  },
};
</script>
