<template>
  <base-section :flat="flat" :title="title" :description="description">
    <div class="col-xs-12 col-sm-6">
      <base-label>{{ $t("label.skuStockKeepingUnit") }}</base-label>
      <q-input dense outlined v-model="variant.sku" />
    </div>
    <div class="col-xs-12 col-sm-6">
      <base-label>{{ $t("label.barcodeIsbnUpcGtinEtc") }}</base-label>
      <q-input dense outlined v-model="variant.barcode" />
    </div>
    <div class="col-xs-12 col-sm-12">
      <q-list class="q-gutter-y-sm">
        <div>
          <q-checkbox
            size="sm"
            dense
            v-model="variant.track_inventory"
            :label="$t('label.trackQuantity')"
          />
        </div>
        <div v-if="variant.track_inventory">
          <q-checkbox
            size="sm"
            dense
            v-model="variant.out_of_stock_track_inventory"
            :label="$t('label.continueSellingWhenOutOfInventory')"
          />
        </div>
      </q-list>
    </div>
    <div class="col-xs-12 col-sm-12">
      <div class="text-label flex">
        Quantity
        <q-space />
        <base-btn
          v-if="hasMultipleLocation"
          link
          padding="0"
          color="primary"
          :label="$t('label.editLocations')"
          @click="onEditLocation(variant.inventories)"
        />
      </div>
      <q-table
        class="list-table"
        :separator="hasMultipleLocation ? 'horizontal' : 'none'"
        :dense="!hasMultipleLocation"
        :rows="variant.inventories.filter((item) => item.active === true)"
        :columns="inventory_columns"
        :rows-per-page-options="[0]"
        hide-bottom
        flat
        row-key="name"
      >
        <template v-slot:body-cell-available="props">
          <q-td :props="props">
            <a
              v-if="variant.track_inventory"
              href="javascript:void(0);"
              class="text-primary"
            >
              {{ props.row.available }}
              <quantity-edit-popup v-model="props.row.available" />
            </a>
            <span class="text-grey" v-else>Not tracked</span>
          </q-td>
        </template>
      </q-table>
    </div>
  </base-section>
</template>

<script>
import QuantityEditPopup from "src/components/product/QuantityEditPopup.vue";
import EditLocation from "src/components/product/EditLocation.vue";

export default {
  components: { QuantityEditPopup },
  props: {
    modelValue: {
      required: true,
      type: Object,
      default: () => ({}),
    },
    flat: {
      type: Boolean,
      default: false,
    },
    creating: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: "Inventory",
    },
    description: String,
  },
  emits: ["update:model-value"],
  data() {
    return {
      inventory_columns_default: [
        {
          name: "available",
          creating: true,
          label: "Available",
          field: "available",
          style: "width: 100px",
          align: "left",
        },
      ],
      inventory_columns_multiple: [
        {
          name: "location",
          creating: true,
          label: "Location name",
          field: (row) => row.location.name,
          align: "left",
        },
        {
          name: "available",
          creating: true,
          label: "Available",
          field: "available",
          style: "width: 100px",
        },
      ],
      locations: [],
      variant: this.modelValue,
    };
  },
  computed: {
    hasMultipleLocation() {
      return this.variant.inventories.length > 1;
    },
    inventory_columns() {
      return this.hasMultipleLocation
        ? this.inventory_columns_multiple.filter(
            (item) => (this.creating && item.creating) || !this.creating
          )
        : this.inventory_columns_default.filter(
            (item) => (this.creating && item.creating) || !this.creating
          );
    },
  },
  methods: {
    onEditLocation(inventories) {
      console.func(
        "components/product/InventorySection:method.onEditLocation()",
        arguments
      );
      this.$q
        .dialog({
          component: EditLocation,
          componentProps: {
            modelValue: inventories,
          },
        })
        .onOk((payload) => {
          console.func(
            "components/product/InventorySection:methods.onEditLocation.onOk()",
            arguments
          );
          this.variant.inventories = payload;
        });
    },
  },
  watch: {
    modelValue: {
      deep: true,
      handler(val) {
        this.variant = val;
      },
    },
  },
};
</script>
<style lang="sass">
.list-table .q-table th, .list-table .q-table td
  padding-left: 0 !important
  padding-right: 0 !important
.base-popup-edit
  .q-popup-edit__buttons.row.justify-center.no-wrap
    justify-content: flex-end
</style>
