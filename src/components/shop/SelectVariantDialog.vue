<template>
  <base-dialog
    title="Select Variant"
    body-class="q-pa-none scroll"
    ref="dialog"
    @hide="onDialogHide"
    use-separator
    hide-footer
  >
    <template v-slot:body>
      <base-section flat bordered>
        <div class="col-xs-12 col-sm-6">
          <product-thumbnail :media="product.thumbnail" />
        </div>
        <div class="col-xs-12 col-sm-6">
          <div class="q-gutter-y-sm">
            <div class="text-h5 ellipsis title">{{ product.title }}</div>
            <div class="text-h6 price">
              {{ product.price_formated }}
              <q-btn
                v-if="!product.in_stock"
                size="sm"
                square
                outline
                color="negative"
                label="SOLD OUT"
              />
            </div>
            <template v-if="product.has_variant">
              <div class="q-py-md variants row q-col-gutter-md">
                <div
                  class="variant col-sm-6 col-xs-12"
                  v-for="(item, index) in product.options"
                  :key="index"
                >
                  <base-label>{{ item.name }}</base-label>
                  <base-select
                    :ref="'variant-' + item.slug"
                    dense
                    outlined
                    square
                    :options="item.values"
                    map-options
                    option-label="name"
                    option-value="name"
                    emit-value
                    v-model="item.value"
                    @update:model-value="onChangeOption"
                  />
                </div>
              </div>
            </template>
            <div class="cart">
              <base-btn
                square
                size="md"
                padding="9px 22px"
                icon="fad fa-cart-plus"
                class="add-to-cart full-width"
                :label="$t('addToCart')"
                :disable="!product.in_stock"
                @click="addToCart"
              />
            </div>
          </div>
        </div>
      </base-section>
    </template>
  </base-dialog>
</template>

<script>
import ProductThumbnail from "components/ProductThumbnail.vue";

export default {
  components: { ProductThumbnail },
  props: {
    title: String,
    modelValue: {
      type: Object,
      default: () => ({
        variants: [],
      }),
    },
  },
  data() {
    return {
      product: this.modelValue,
      variant: null,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    async show() {
      console.func("components/shop/VariantDialog:methods.show()", arguments);
      const { options, variants } = this.modelValue;
      const variant = variants.find((variant) => variant.in_stock);
      this.variant = variant;
      const response = {
        ...this.modelValue,
        options: options.map((option) => {
          const variantOption = variant.options.find(
            (vOption) => vOption.name === option.name
          );
          return {
            ...option,
            value: variantOption?.value,
          };
        }),
        price_formated: variant.price_formated,
        variant_id: variant.id,
        price: variant.price,
        in_stock: variant.in_stock,
        variants: variants.map((v) => ({
          compare_at_price: v.compare_at_price,
          id: v.id,
          in_stock: v.in_stock,
          media_id: v.media_id,
          price: v.price,
          price_formated: v.price_formated,
          thumbnail: v.thumbnail,
          title: v.title,
        })),
      };
      this.product = response;
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
    addToCart() {
      console.func(
        "components/shop/VariantDialog:methods.addToCart()",
        arguments
      );
      this.$emit("ok", {
        ...this.product,
        default_variant: this.variant,
      });
      this.hide();
    },
    async onChangeOption(val) {
      console.func(
        "components/shop/VariantDialog:methods.onChangeOption()",
        arguments
      );
      const { options, variants } = this.product;
      const variantTitle = options.map((item) => item.value).join(" / ");
      const variant = variants.find((item) => item.title === variantTitle);
      this.variant = variant;
      Object.assign(this.product, {
        price_formated: variant.price_formated,
        price: variant.price,
        variant_id: variant.id,
        in_stock: variant.in_stock,
      });
    },
  },
};
</script>
