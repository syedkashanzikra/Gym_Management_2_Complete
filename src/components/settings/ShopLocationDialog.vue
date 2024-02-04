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
        <div class="col-sm-12 col-xs-12">
          <div class="text-label">Location name</div>
          <div class="text-label">
            Give this location a short name to make it easy to identify. You’ll
            see this name in areas like orders and products. If this location
            offers local pickup, it will be visible to your customers at
            checkout and in notifications.
          </div>
          <base-input v-model="location.name" />
        </div>
        <div class="col-xs-12">
          <address-fields v-model="location" />
        </div>
      </base-section>
    </template>
  </base-dialog>
</template>

<script>
import { mapActions } from "pinia";
import { useShopLocationStore } from "src/stores/shop/location";
import AddressFields from "../address/AddressFields.vue";

export default {
  components: { AddressFields },
  props: {
    title: String,
    modelValue: Object,
  },
  data() {
    return {
      location: this.modelValue,
      loading: false,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useShopLocationStore, ["store", "update"]),
    async show() {
      console.func("components/shop/VariantDialog:methods.show()", arguments);
      this.location = this.modelValue;
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
      const method = this.creating ? this.store : this.update;
      method(this.location)
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
  computed: {
    creating() {
      return !this.location?.id;
    },
  },
};
</script>
