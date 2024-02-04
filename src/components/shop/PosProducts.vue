<template>
  <div class="pos-products">
    <q-list class="q-gutter-y-sm" v-if="hasProduct">
      <q-item class="bordered" v-for="(item, index) in modelValue" :key="index">
        <q-item-section>
          <q-item-label>
            {{ item.title }}
          </q-item-label>
          <q-item-label v-if="item.variant_title !== 'Default'">
            {{ item.variant_title }}
          </q-item-label>
          <q-item-label>
            {{ $core.money(item.price) }} x {{ item.quantity }}
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-item-label>
            <quantity-input
              style="width: 120px"
              :model-value="item.quantity"
              @update:model-value="onChangeQuantity($event, index)"
            />
          </q-item-label>
          <div class="meta items-center flex">
            <div class="price text-bold q-mr-md">
              {{ $core.money(item.total) }}
            </div>
            <base-btn
              flat
              dense
              color="grey"
              @click="onRemoveItem(index)"
              icon="fas fa-trash-can"
            />
          </div>
        </q-item-section>
      </q-item>
    </q-list>
    <base-no-records
      v-else
      icon="fad fa-cart-shopping-fast"
      title="Empty Cart"
      message="Kindly choose a product from the list to add it to your cart."
      height="200px"
      container-style="width: calc(100% - 100px)"
      icon-size="35px"
    />
  </div>
</template>

<script>
import { findIndex } from "lodash";
import QuantityInput from "components/shop/QuantityInput.vue";
import SelectVariantDialog from "./SelectVariantDialog.vue";

export default {
  components: { QuantityInput },
  props: {
    modelValue: Array,
  },
  emits: ["update:model-value"],
  methods: {
    onChangeQuantity(value, index) {
      const items = this.modelValue;
      items[index].quantity = value;
      this.$emit("update:model-value", items);
    },
    onRemoveItem(index) {
      const items = this.modelValue;
      items.splice(index, 1);
      this.$emit("update:model-value", items);
    },
    clear() {
      this.$emit("update:model-value", []);
    },
    async addToCart(payload) {
      console.func(
        "pages/admins/orders/PosPage:methods.addToCart()",
        arguments
      );

      if (payload.has_variant) {
        this.$q
          .dialog({
            component: SelectVariantDialog,
            componentProps: {
              modelValue: payload,
            },
          })
          .onOk((product) => {
            this.addProduct(product);
          });
      } else {
        this.addProduct(payload);
      }
    },
    addCustomProduct(payload) {
      const items = this.modelValue;
      items.push(payload);
      this.$emit("update:model-value", items);
    },
    addProduct(payload) {
      const items = this.modelValue;

      const variantId = payload?.default_variant?.id;
      const price = payload?.default_variant?.price;
      const taxable = payload?.default_variant?.taxable;
      const title = payload?.default_variant?.title;
      let itemIndex = findIndex(items, {
        product_id: payload.id,
        variant_id: variantId,
      });

      if (itemIndex > -1) {
        const item = items[itemIndex];
        items[itemIndex] = { ...item, quantity: item.quantity + 1 };
      } else {
        items.push({
          title: payload.title,
          product_id: payload.id,
          variant_id: variantId,
          variant_title: title,
          price,
          total: price,
          taxable,
          quantity: 1,
        });
      }

      this.$core.debounce(() => {
        this.$emit("update:model-value", items);
      }, 1000);
    },
  },
  computed: {
    hasProduct() {
      return this.modelValue.length > 0;
    },
  },
};
</script>
