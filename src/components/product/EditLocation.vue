<template>
  <base-dialog
    title="Edit locations"
    body-class="scroll"
    ref="dialog"
    persistent
    use-separator
    @hide="onDialogHide"
  >
    <template v-slot:body>
      <info v-if="!hasInventory" class="q-mb-md">
        You must select at least one location for the variant.
      </info>
      <div class="q-mb-md text-body2">
        Select locations that inventory the selected variants.
      </div>
      <q-list class="q-gutter-sm">
        <div v-for="(inventory, index) in inventories" :key="index">
          <q-checkbox
            size="sm"
            dense
            v-model="inventory.active"
            :label="inventory.location.name"
          />
        </div>
      </q-list>
    </template>
    <template v-slot:footer>
      <q-card-section class="text-right">
        <div class="q-gutter-sm">
          <q-btn
            no-caps
            outline
            :label="$t('label.cancel')"
            color="grey"
            v-close-popup
          />
          <q-btn
            :disable="disable"
            no-caps
            :label="$t('label.done')"
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
  props: {
    modelValue: {
      required: true,
      type: Array,
    },
  },
  data() {
    return {
      inventories: cloneDeep(this.modelValue),
      default: cloneDeep(this.modelValue),
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func("components/product/EditLocation:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/product/EditLocation:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/EditLocation:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      this.$emit("ok", this.inventories);
      this.hide();
    },
  },
  computed: {
    hasInventory() {
      return this.inventories.filter((item) => item.active).length > 0;
    },
    disable() {
      return (
        JSON.stringify(this.inventories) === JSON.stringify(this.default) ||
        !this.hasInventory
      );
    },
  },
};
</script>
