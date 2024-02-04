<template>
  <q-page padding>
    <base-info rounded class="q-mb-md">
      {{ $t("shopNotice") }}
    </base-info>
    <base-section flat bordered v-if="loaded">
      <div class="col-xs-12 col-sm-6">
        <div class="images" v-if="slides.length">
          <carousel-slider v-bind:slides="slides" />
        </div>
        <div v-else class="no-images">
          <product-thumbnail />
        </div>
      </div>
      <div class="col-xs-12 col-sm-6">
        <div class="q-gutter-y-sm">
          <div class="text-h5 title">{{ product.title }}</div>
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
          <div class="description" v-html="product.description"></div>
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
              class="addToCart full-width"
              :label="$t('addToCart')"
              :disable="!product.in_stock"
              @click="addToCart(product)"
            />
          </div>
        </div>
      </div>
    </base-section>
    <product-skeleton v-else />
  </q-page>
</template>

<script>
import { mapActions } from "pinia";
import { useShopStore } from "stores/shop";
import CarouselSlider from "components/SwiperSlider.vue";
import ProductSkeleton from "components/skeleton/ProductSkeleton.vue";
import ProductThumbnail from "components/ProductThumbnail.vue";

export default {
  components: { CarouselSlider, ProductSkeleton, ProductThumbnail },
  data() {
    return {
      product: {
        media: [],
      },
      loaded: false,
      breakpoints: {
        400: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
      },
    };
  },
  methods: {
    ...mapActions(useShopStore, {
      get: "product",
      addToCart: "addToCart",
    }),
    async onLoad(evt, row) {
      this.loaded = false;
      console.func("pages/users/shop/ProductPage:methods.onLoad()", arguments);
      this.get(this.$route.params.slug)
        .then((product) => {
          this.$app.setTitle(product.title);
          this.product = product;
          this.loaded = true;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
          this.$router.back();
        });
    },
    async onChangeOption(val) {
      console.func(
        "pages/users/shop/ProductPage:methods.onChangeOption()",
        arguments
      );
      const { options, variants } = this.product;
      const variantTitle = options.map((item) => item.value).join(" / ");
      const variant = variants.find((item) => item.title === variantTitle);
      Object.assign(this.product, {
        price_formated: variant.price_formated,
        price: variant.price,
        variant_id: variant.id,
        in_stock: variant.in_stock,
      });
    },
  },
  mounted() {
    this.onLoad();
  },
  computed: {
    slides() {
      return this.product.media.map((item) => item.url);
    },
  },
};
</script>
