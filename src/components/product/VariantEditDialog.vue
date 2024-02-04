<template>
  <base-dialog
    :title="`Edit ${variant.title}`"
    content-style="width: 800px; max-width: 95vw"
    body-class="scroll q-pa-none"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <q-item
        class="q-py-md"
        v-if="creating"
        tag="label"
        style="min-height: auto"
      >
        <q-item-section style="min-width: auto" top avatar>
          <q-checkbox dense v-model="variant.create_on_save" />
        </q-item-section>
        <q-item-section top>
          <q-item-label class="text-subtitle2">
            Create this variant
          </q-item-label>
          <q-item-label v-if="!variant.create_on_save" caption lines="2">
            This variant will not be created when you save this product.
          </q-item-label>
        </q-item-section>
      </q-item>
      <template v-if="(creating && variant.create_on_save) || !creating">
        <pricing-card flat v-model="variant" title />
        <q-separator />
        <inventory-card flat v-model="variant" />
      </template>
    </template>
    <template v-slot:footer>
      <q-card-section class="text-right">
        <div class="q-gutter-sm">
          <q-btn
            no-caps
            outline
            :label="$t('label.cancel')"
            color="grey"
            @click="onCancel"
          />
          <q-btn
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
import InventoryCard from "src/components/product/InventorySection.vue";
import PricingCard from "src/components/product/PricingSection.vue";

export default {
  components: {
    PricingCard,
    InventoryCard,
  },
  props: {
    modelValue: {
      required: true,
      type: Object,
    },
    creating: {
      required: false,
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      variant: cloneDeep(this.modelValue),
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func(
        "components/product/VariantEditDialog:methods.show()",
        arguments
      );
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/product/VariantEditDialog:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/VariantEditDialog:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      this.$emit("ok", this.variant);
      this.hide();
    },
    onCancel() {
      this.hide();
    },
  },
};
</script>
