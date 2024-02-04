<template>
  <base-dialog
    title="Edit SKUs "
    ref="dialog"
    body-class="scroll"
    use-separator
    persistent
    @hide="onDialogHide"
  >
    <template v-slot:body>
      <q-list class="bordered">
        <q-item
          class="q-pl-none q-pr-none"
          v-for="(variant, index) in variants"
          :key="index"
        >
          <q-item-section class="text-bold">
            <q-item-label>{{ variant.title }}</q-item-label>
          </q-item-section>
          <q-item-section side class="align-right">
            <base-input dense outlined v-model="variant.sku" />
          </q-item-section>
        </q-item>
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
      variants: cloneDeep(this.modelValue),
      default: cloneDeep(this.modelValue),
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func("components/product/EditSkus:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/product/EditSkus:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/EditSkus:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/product/EditSkus:methods.onDone()", arguments);
      this.$emit("ok", this.variants);
      this.hide();
    },
  },
  computed: {
    disable() {
      return JSON.stringify(this.variants) === JSON.stringify(this.default);
    },
  },
};
</script>
