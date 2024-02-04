<template>
  <base-dialog
    class="add-customer-dialog"
    title="Add Customer"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <address-fields all v-model="address" />
    </template>

    <template v-slot:footer>
      <q-card-section class="text-right">
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn no-caps :label="saveLabel" color="primary" @click="onDone" />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
import { cloneDeep } from "lodash";
import AddressFields from "src/components/address/AddressFields.vue";

export default {
  components: { AddressFields },
  props: {
    modelValue: {
      type: [Object],
      required: true,
    },
  },
  data() {
    return {
      address: cloneDeep(this.modelValue),
      loading: false,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func(
        "components/customer/AddCustomerDialog:methods.show()",
        arguments
      );
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/customer/AddCustomerDialog:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/customer/AddCustomerDialog:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/customer/AddCustomerDialog:methods.onDone()",
        arguments
      );
      this.$emit("ok", this.address);
      this.hide();
    },
  },
  computed: {
    saveLabel() {
      return "Create";
    },
  },
};
</script>
