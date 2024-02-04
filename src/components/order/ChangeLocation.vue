<template>
  <base-dialog
    title="Change location"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <q-list class="bordered q-gutter-sm">
        <div v-for="item in locations" :key="item.id">
          <q-radio
            size="sm"
            @update:model-value="location = item"
            dense
            v-model="location.id"
            :val="item.id"
            :label="item.name"
          />
        </div>
      </q-list>
    </template>
    <template v-slot:footer>
      <q-card-section class="text-right">
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn
            :disable="disable"
            no-caps
            label="Done"
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
import { mapActions } from "pinia";
import { useShopLocationStore } from "src/stores/shop/location";

export default {
  props: {
    modelValue: {
      required: true,
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      location: cloneDeep(this.modelValue || {}),
      default: cloneDeep(this.modelValue || {}),
      locations: [],
    };
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useShopLocationStore, ["get"]),
    async show() {
      console.func("components/order/ChangeLocation:methods.show()", arguments);
      await this.get()
        .then(({ data }) => {
          this.locations = data;
          this.$refs.dialog.show();
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    hide() {
      console.func(
        "components/order/ChangeLocation:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/order/ChangeLocation:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/order/ChangeLocation:methods.onDone()",
        arguments
      );
      this.$emit("ok", this.location);
      this.hide();
    },
  },
  computed: {
    disable() {
      return JSON.stringify(this.location) === JSON.stringify(this.default);
    },
  },
};
</script>
