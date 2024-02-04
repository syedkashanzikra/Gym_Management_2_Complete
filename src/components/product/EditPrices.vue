<template>
  <base-dialog
    title="Edit prices"
    ref="dialog"
    body-class="scroll"
    persistent
    use-separator
    @hide="onDialogHide"
  >
    <template v-slot:body>
      <q-list class="bordered">
        <q-item class="q-pl-none q-pr-none">
          <q-item-section>
            <q-item-label class="">Apply a price to all variants</q-item-label>
            <q-item-label class="row">
              <div class="col">
                <base-price-input dense outlined v-model="price" />
              </div>
              <div class="col-auto q-ml-sm">
                <q-btn
                  no-caps
                  outline
                  padding="10px 15px"
                  color="grey"
                  class="bg-green-1"
                  :label="$t('label.applyToAll')"
                  @click="onChange"
                />
              </div>
            </q-item-label>
          </q-item-section>
        </q-item>
        <q-item
          class="q-pl-none q-pr-none"
          v-for="(variant, index) in variants"
          :key="index"
        >
          <q-item-section class="text-bold">
            <q-item-label>{{ variant.title }}</q-item-label>
          </q-item-section>
          <q-item-section side class="align-right">
            <base-price-input dense outlined v-model="variant.price" />
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
      money: {
        decimal: ".",
        separator: ",",
        prefix: "$ ",
        precision: 2,
        minimumFractionDigits: 2,
        masked: false,
        nullValue: 0,
      },
      price: 0.0,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      console.func("components/product/EditPrices:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/product/EditPrices:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/EditPrices:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/product/EditPrices:methods.onDone()", arguments);
      this.$emit("ok", this.variants);
      this.hide();
    },
    onChange() {
      console.func(
        "components/product/EditPrices:methods.onChange()",
        arguments
      );
      this.variants = this.variants.map((item) => ({
        ...item,
        price: this.price,
      }));
    },
  },
  computed: {
    disable() {
      return JSON.stringify(this.variants) === JSON.stringify(this.default);
    },
  },
};
</script>
