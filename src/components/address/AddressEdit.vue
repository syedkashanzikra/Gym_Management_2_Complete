<template>
  <base-dialog
    :title="title"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div class="row q-col-gutter-md">
        <div class="col-sm-12 col-xs-12">
          <address-fields v-model="address" />
        </div>
      </div>
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
    title: String,
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
      console.func("components/order/AddressEdit:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/order/AddressEdit:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/order/AddressEdit:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/order/AddressEdit:methods.onDone()", arguments);
      this.$emit("ok", this);
      this.hide();
    },
  },
  computed: {
    saveLabel() {
      return "Update";
    },
  },
};
</script>
