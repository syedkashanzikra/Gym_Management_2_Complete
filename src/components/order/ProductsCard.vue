<template>
  <base-section title="Products" no-row>
    <template v-slot:action>
      <base-btn
        v-if="!view"
        link
        size="14px"
        padding="0"
        label="Add custom product"
        @click="onAddCustomProduct"
      />
      <slot name="action"></slot>
    </template>
    <div v-if="!view" class="q-mb-md row">
      <div class="col">
        <q-input
          dense
          outlined
          type="text"
          placeholder="Search products"
          debounce="500"
          @update:model-value="onBrowseProduct"
        >
          <template v-slot:after>
            <q-btn
              @click="onBrowseProduct('')"
              no-caps
              outline
              padding="8px"
              :style="{
                width: '100px',
              }"
              color="primary"
              label="Browse"
            />
          </template>
        </q-input>
      </div>
    </div>
    <slot name="top"></slot>
    <div v-if="products.length">
      <q-table
        ref="product"
        :class="{
          'main-table': !view,
          'no-hover': view,
        }"
        square
        flat
        :dense="view"
        :hide-header="view"
        :rows="products"
        :columns="columns"
        hide-pagination
        :rows-per-page-options="[0]"
        :visible-columns="visibleColumns"
        :separator="view ? 'none' : 'horizontal'"
      >
        <template v-slot:body-cell-product="props">
          <q-td :props="props">
            <q-item class="q-pa-none" dense>
              <q-item-section avatar>
                <base-thumbnail :size="40" :media="props.row.thumbnail">
                  <q-badge
                    v-if="view"
                    rounded
                    floating
                    color="orange-3"
                    text-color="black"
                    :label="props.row.remaining_quantity || props.row.quantity"
                  />
                </base-thumbnail>
              </q-item-section>
              <q-item-section avatar>
                <product-title v-bind="props.row" />
                <div v-if="props.row.variant_title">
                  {{ props.row.variant_title }}
                </div>
                <div v-if="props.row.reason">
                  Reason: {{ props.row.reason }}
                </div>
              </q-item-section>
            </q-item>
          </q-td>
        </template>
        <template v-slot:body-cell-quantity="props">
          <q-td :props="props">
            <q-input
              dense
              outlined
              v-model.number="props.row.quantity"
              input-style="text-align: center"
              type="number"
              min="1"
              @update:model-value="onChangeQuantity"
            />
          </q-td>
        </template>
        <template v-slot:body-cell-price="props">
          <q-td :props="props">
            <template v-if="view">
              <base-btn
                link
                v-if="props.row.has_discount"
                class="text-strike"
                :label="props.row.price"
              >
                <!-- TODO: Need to show the actual discount amount per item -->
                <!-- <q-menu anchor="bottom middle" self="top middle">
                    <q-card class="my-card">
                      <q-card-section>
                        {{ props.row.discount.value }}
                      </q-card-section>
                    </q-card>
                  </q-menu> -->
              </base-btn>
              {{ props.value }} x
              {{ props.row.remaining_quantity || props.row.quantity }}
            </template>
            <template v-else>
              <base-btn
                link
                color="primary"
                size="0.72rem"
                @click="addDiscount(props.row)"
                :label="props.value"
              />
              <span
                class="q-ml-sm text-strike text-grey"
                v-if="props.row.has_discount"
              >
                {{ $core.money(props.row.price) }}
              </span>
            </template>
          </q-td>
        </template>
        <template v-slot:body-cell-action="props">
          <q-td :props="props">
            <q-btn
              round
              dense
              flat
              size="sm"
              color="primary"
              icon="fal fa-times"
              @click="onRemoveItem(props)"
            />
          </q-td>
        </template>
      </q-table>
    </div>
    <div v-else></div>
    <template v-if="!!$slots.bottom" v-slot:bottom>
      <slot name="bottom"></slot>
    </template>
  </base-section>
</template>

<script>
import { cloneDeep } from "lodash";
import InventorySelector from "components/product/InventorySelector.vue";
import CustomProduct from "components/order/CustomProduct.vue";
import ProductTitle from "components/order/ProductTitle.vue";
import AddDiscount from "components/order/AddDiscount.vue";

export default {
  components: { ProductTitle },
  props: {
    modelValue: {
      type: [Array],
      required: true,
    },
    view: Boolean,
    canNotRemove: Boolean,
  },
  emits: ["update:model-value"],
  data() {
    return {
      products: cloneDeep(this.modelValue),
      columns: [
        {
          name: "product",
          align: "left",
          label: "Product",
          field: "title",
          sortable: false,
          view: true,
        },
        {
          name: "price",
          align: "right",
          label: "Price",
          style: "width: 150px;",
          field: "discounted_price",
          format: (val) => this.$core.money(val),
          sortable: false,
          view: true,
        },
        {
          name: "quantity",
          align: "left",
          label: "Quantity",
          style: "width: 150px;",
          field: "quantity",
          sortable: false,
          view: false,
        },
        {
          name: "total",
          align: this.view ? "right" : "left",
          label: "Total",
          style: "width: 90px;",
          field: "total",
          format: (val) => this.$core.money(val),
          sortable: false,
          view: true,
        },
        {
          name: "action",
          align: "right",
          label: "",
          style: "width: 10px; padding-left: 0",
          field: "action",
          sortable: false,
          view: false,
        },
      ],
    };
  },
  methods: {
    onBrowseProduct(value) {
      console.func(
        "components/order/ProductsCard:methods.onBrowseProduct()",
        arguments
      );
      this.$q
        .dialog({
          component: InventorySelector,
          componentProps: {
            query: value,
            columns: [
              {
                name: "price",
                label: "Price",
                field: (row) => (!row.has_variant ? row.price : ""),
                align: "right",
                sortable: false,
                style: "width: 150px; white-space: normal",
              },
            ],
            modelValue: cloneDeep(this.products),
          },
        })
        .onOk((payload) => {
          console.func(
            "components/order/ProductsCard:methods.onBrowseProduct.onOk()",
            payload
          );
          this.products = cloneDeep(payload);
          this.$emit("update:model-value", this.products);
        });
    },
    onRemoveItem({ rowIndex }) {
      console.func(
        "components/order/ProductsCard:methods.onRemoveItem()",
        arguments
      );
      this.products.splice(rowIndex, 1);
      this.$emit("update:model-value", this.products);
    },
    onAddCustomProduct() {
      console.func(
        "components/order/ProductsCard:methods.onAddCustomProduct()",
        arguments
      );
      this.$q
        .dialog({
          component: CustomProduct,
        })
        .onOk((payload) => {
          console.func(
            "components/order/ProductsCard:methods.onAddCustomProduct.onOk()",
            payload
          );
          this.products.push(payload);
          this.$emit("update:model-value", this.products);
        });
    },
    onChangeQuantity() {
      console.func(
        "components/order/ProductsCard:methods.onChangeQuantity()",
        arguments
      );
      this.$core.debounce(() => {
        this.$emit("update:model-value", this.products);
      }, 500);
    },
    addDiscount(props) {
      console.func(
        "components/order/ProductsCard:methods.addDiscount()",
        arguments
      );
      this.$q
        .dialog({
          component: AddDiscount,
          componentProps: {
            modelValue: cloneDeep(props.discount),
            update: Boolean(props.discount),
          },
        })
        .onOk((payload) => {
          console.func(
            "components/order/ProductsCard:methods.addDiscount.onOk()",
            payload
          );
          if (!payload) {
            props.discount_removed = true;
          }
          props.discount = payload;
          this.$emit("update:model-value", this.products);
        });
    },
  },
  watch: {
    modelValue: {
      deep: true,
      handler(modelValue) {
        this.products = cloneDeep(modelValue);
      },
    },
  },
  computed: {
    visibleColumns() {
      return this.columns
        .filter((item) => (this.view && item.view) || !this.view)
        .filter(
          (item) =>
            (this.canNotRemove && item.name !== "action") || !this.canNotRemove
        )
        .map((item) => item.name);
    },
  },
};
</script>
