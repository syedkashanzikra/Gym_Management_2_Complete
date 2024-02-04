<template>
  <base-dialog
    :title="title"
    body-class="q-pa-none scroll"
    ref="dialog"
    @hide="onDialogHide"
    @ok="onDone"
    use-separator
    persistent
    :loading="loading"
  >
    <template v-slot:body>
      <base-section flat>
        <div class="col-xs-12 col-sm-4">
          <base-label required>Country</base-label>
          <country-select
            :readonly="tax.removeable"
            v-model="tax.country"
            style="width: 100%"
            @complete="(val) => (tax.code = val.iso2)"
          />
        </div>
        <div class="col-xs-12 col-sm-4">
          <base-label required>State</base-label>
          <state-select
            :readonly="tax.removeable"
            v-model="tax.state"
            :country="tax.country"
            option-value="state_code"
            style="width: 100%"
          />
        </div>
        <div class="col-xs-12 col-sm-4">
          <base-label required>Label</base-label>
          <base-input debounce="500" v-model="tax.label" style="width: 100%" />
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-label required>Rate</base-label>
          <base-input
            v-model="tax.rate"
            type="number"
            min="0"
            debounce="500"
            style="width: 100%"
          />
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-label>Priority</base-label>
          <base-input
            debounce="500"
            v-model="tax.priority"
            type="number"
            min="0"
          />
        </div>
        <div class="col-xs-12 col-sm-12">
          <q-checkbox
            dense
            v-model="tax.compounded"
            color="green"
            label="Compounded"
          />
        </div>
      </base-section>
    </template>
  </base-dialog>
</template>

<script>
import CountrySelect from "src/components/address/CountrySelect.vue";
import StateSelect from "src/components/address/StateSelect.vue";

export default {
  components: { CountrySelect, StateSelect },
  props: {
    title: String,
    modelValue: {
      type: Object,
      default: () => ({}),
    },
    method: Function,
  },
  data() {
    return {
      tax: this.modelValue,
      loading: false,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    async show() {
      console.func("components/shop/VariantDialog:methods.show()", arguments);
      this.tax = this.modelValue;
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/shop/VariantDialog:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/shop/VariantDialog:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func("components/shop/VariantDialog:methods.onDone()", arguments);
      this.loading = true;
      this.method(this.tax)
        .then(({ data }) => {
          this.$emit("ok", data);
          this.hide();
          this.loading = false;
        })
        .catch((error) => {
          this.$core.error(error);
          this.loading = false;
        });
    },
  },
};
</script>
