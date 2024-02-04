<template>
  <base-dialog
    :title="title"
    body-class="q-pa-none"
    body-style=""
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <q-card-section class="q-col-gutter-md row">
        <div class="col">
          <q-input
            dense
            outlined
            type="text"
            v-model="filter"
            clearable
            debounce="500"
            placeholder="Search products"
            @update:model-value="(val) => onBrowseProduct(val, 'filter')"
          />
        </div>
        <div class="col-auto">
          <q-select
            :style="{
              width: '200px',
            }"
            prefix="Sort: "
            map-options
            options-dense
            emit-value
            v-model="sortBy"
            :options="sortOptions"
            dense
            outlined
            @update:model-value="(val) => onBrowseProduct(val, 'sortBy')"
          />
        </div>
      </q-card-section>
      <q-separator />
      <q-table
        flat
        square
        style="max-height: 50vh; min-height: 50vh"
        :rows="products"
        :columns="product_columns"
        row-key="id"
        hide-bottom
        class="sticky-header"
        :visible-columns="visibleColumns"
      >
        <template v-slot:header="props">
          <q-tr :props="props">
            <q-th v-for="col in props.cols" :key="col.name" :props="props">
              {{ col.label }}
            </q-th>
          </q-tr>
        </template>

        <template v-slot:body="props">
          <q-tr
            @click="onSelected(props.row)"
            class="cursor-pointer"
            :props="props"
          >
            <q-td v-for="col in props.cols" :key="col.name" :props="props">
              <template v-if="col.name === 'thumbnail'">
                <q-checkbox
                  size="sm"
                  dense
                  :model-value="selectedProducts"
                  @update:model-value="onSelected(props.row)"
                  :val="props.row.id"
                >
                  <div style="width: 280px" class="flex items-center">
                    <div>
                      <base-thumbnail :size="40" :media="props.row.thumbnail" />
                    </div>
                    <div
                      style="width: 200px; white-space: normal"
                      class="q-ml-md"
                    >
                      {{ props.row.title }}
                    </div>
                  </div>
                </q-checkbox>
              </template>
              <template v-else-if="col.name === 'source_stock'">
                <span v-html="col.value"></span>
              </template>
              <template v-else>
                <span v-html="col.value"></span>
              </template>
            </q-td>
          </q-tr>
          <q-tr
            v-for="(variant, index) in props.row.variants"
            :key="index"
            v-show="props.row.has_variant"
            :props="props"
            class="cursor-pointer"
            @click="onVariantSelected(variant, props.row)"
          >
            <q-td colspan="1" style="width: 50px">
              <q-checkbox
                class="q-ml-xl"
                size="sm"
                dense
                :model-value="selectedVariants"
                @update:model-value="onVariantSelected(variant, props.row)"
                :val="variant.id"
              >
                <div>{{ variant.title }}</div>
              </q-checkbox>
            </q-td>
            <q-td colspan="1" class="text-right">
              <span v-html="getVariantStocks(variant, source)"></span>
            </q-td>
            <q-td colspan="1" class="text-right">
              <span v-html="variant.price_formated"></span>
            </q-td>
          </q-tr>
        </template>
      </q-table>
      <q-inner-loading :showing="loading">
        <q-spinner-oval size="50px" color="primary" />
      </q-inner-loading>
    </template>

    <template v-slot:footer>
      <q-card-section class="flex">
        <template v-if="loadFromServer">
          <q-btn
            :disable="current_page <= 1 || loading"
            @click="
              onLoadProduct({
                page: current_page - 1 >= 1 ? current_page - 1 : 1,
              })
            "
            dense
            round
            flat
            color="primary"
            icon="fal fa-angle-left"
          />
          <q-btn
            :disable="current_page == last_page || loading"
            @click="
              onLoadProduct({
                page: current_page + 1 <= last_page ? current_page + 1 : 1,
              })
            "
            dense
            round
            flat
            color="primary"
            icon="fal fa-angle-right"
          />
        </template>
        <q-space />
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
import { mapActions, mapState } from "pinia";
import { uniq } from "lodash";
import { useProductStore } from "src/stores/product";

export default {
  data() {
    return {
      selected: this.modelValue,
      products: [],
      current_page: 1,
      last_page: 1,
      loading: false,
      filter: this.query,
      sortBy: "TITLE_ASC",
      product_columns: [
        {
          name: "thumbnail",
          label: "Product",
          field: "thumbnail",
          align: "left",
          sortable: false,
        },
        {
          name: "source_stock",
          label: "Total Available",
          field: (row) =>
            !row.has_variant ? this.getStocks(row, this.source) : "",
          align: "right",
          sortable: false,
          style: "width: 150px; white-space: normal",
        },
      ],
    };
  },
  props: {
    modelValue: {
      required: false,
      type: [Array],
      default: () => [],
    },
    source: {
      required: false,
      type: [Object, Boolean],
      default: false,
    },
    columns: {
      required: false,
      type: [Array],
      default: () => [],
    },
    hint: [String],
    query: [String],
    title: {
      type: [String],
      default: "Select products",
    },
    loadFromServer: {
      type: [Boolean],
      required: false,
      default: true,
    },
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useProductStore, ["get"]),
    onLoadProduct(args) {
      console.func(
        "components/product/InventorySelector:methods.onLoadProduct()",
        arguments
      );
      this.loading = true;
      this.current_page = args.page;
      this.get({
        ...args,
        filter: this.filter,
        sortBy: this.sortBy,
        rowsPerPage: 10,
        location: this.source?.id,
        includes: ["variants", "default_variant"],
      })
        .then(({ data, meta }) => {
          this.products = data;
          this.current_page = meta.current_page;
          this.last_page = meta.last_page;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    async show() {
      console.func(
        "components/product/InventorySelector:methods.show()",
        arguments
      );
      if (this.loadFromServer) {
        await this.onLoadProduct({
          page: this.current_page,
        });
      }
      this.columns.forEach((column) => {
        this.product_columns.push(column);
      });
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/product/InventorySelector:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/InventorySelector:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/product/InventorySelector:methods.onDone()",
        arguments
      );
      this.$emit("ok", this.selected);
      this.hide();
    },
    onSelected(product) {
      console.func(
        "components/product/InventorySelector:methods.onSelected()",
        arguments
      );
      if (product.has_variant) {
        const hasSelectedVariants = this.selected.filter(
          (item) => item.product_id === product.id
        ).length;

        const hasVariants = product.variants.length;

        product.variants.forEach((variant) => {
          this.onVariantSelected(
            variant,
            product,
            hasSelectedVariants === hasVariants
          );
        });
      } else {
        this.onVariantSelected(product.default_variant, product);
      }
    },
    onVariantSelected(variant, product, clear = true) {
      console.func(
        "components/product/InventorySelector:methods.onVariantSelected()",
        arguments
      );

      const hasVariant = this.selectedVariants.filter(
        (item) => item === variant.id
      ).length;

      if (!hasVariant) {
        this.selected.push({
          product_id: variant.product_id,
          product: product,
          title: product.title,
          variant_id: variant.id,
          taxable: variant.taxable,
          variant: variant,
          variant_title: variant.title,
          price: variant.price,
          thumbnail: variant.thumbnail,
          sku: variant.sku,
          quantity: 1,
        });
      } else {
        if (clear) {
          this.selected = this.selected.filter(
            (item) => item.variant_id !== variant.id
          );
        }
      }
    },
    onBrowseProduct(value, key) {
      console.func(
        "components/product/InventorySelector:methods.onBrowseProduct()",
        arguments
      );
      this[key] = value;
      this.onLoadProduct({
        page: 1,
      });
    },
    getStocks(product, location) {
      if (!product.default_variant.inventories.length) return "-";

      const stocks = product.default_variant.inventories
        .filter(
          (item) =>
            (location && item.location_id === location.id && item.active) ||
            (!location && item.active)
        )
        .map((item) => parseInt(item.available));

      if (!stocks.length) return "-";

      const available = stocks.reduce((total, available) => total + available);
      return available <= 0
        ? `<span class="no-inventory">${available}</span>`
        : available;
    },
    getVariantStocks(variant, location) {
      if (!variant.inventories.length) return "-";

      const stocks = variant.inventories
        .filter(
          (item) =>
            (location && item.location_id === location.id && item.active) ||
            (!location && item.active)
        )
        .map((item) => parseInt(item.available));

      if (!stocks.length) return "-";

      const available = stocks.reduce((total, available) => total + available);
      return available <= 0
        ? `<span class="no-inventory">${available}</span>`
        : available;
    },
  },
  computed: {
    ...mapState(useProductStore, ["sortOptions"]),
    disable() {
      return this.selected === this.modelValue;
    },
    visibleColumns() {
      return this.product_columns.map((item) => item.name);
    },
    selectedVariants() {
      return uniq(
        this.selected
          .filter((item) => item.variant_id)
          .map((item) => item.variant_id)
      );
    },
    selectedProducts() {
      return uniq(
        this.selected
          .filter((item) => item.product_id)
          .map((item) => item.product_id)
      );
    },
  },
};
</script>

<style lang="sass">
.sticky-header
  thead tr:first-child th
    background-color: white
  thead tr th
    position: sticky
    z-index: 1
  thead tr:first-child th
    top: 0
</style>
