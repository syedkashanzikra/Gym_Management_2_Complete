<template>
  <q-page padding>
    <base-form
      v-if="loaded"
      @submit="onSubmit"
      @cancel="onCancel"
      @reset="onReset"
      :resetable="resetable"
      :disable="disable"
      :submited="submited"
    >
      <div class="row q-col-gutter-md">
        <div class="col-xs-12 col-sm-8">
          <div class="q-gutter-md">
            <base-section>
              <div class="col-sm-8">
                <div class="text-h6">Options</div>
                <div class="q-pt-sm q-gutter-y-md">
                  <div v-for="option in variant.options" :key="option.id">
                    <base-label>{{ option.name }}</base-label>
                    <q-select
                      dense
                      options-dense
                      outlined
                      v-model="option.value"
                      :options="values"
                      map-options
                      option-label="name"
                      option-value="name"
                      emit-value
                      use-input
                      input-debounce="0"
                      new-value-mode="add-unique"
                      hide-selected
                      fill-input
                      @input-value="(val) => (option.value = val)"
                      @filter="
                        (val, update, abort) =>
                          onFilterValues(val, update, abort, option)
                      "
                    />
                  </div>
                </div>
              </div>
              <div class="col-sm-4">
                <thumbnail-selector
                  :label="$t('label.thumbnail')"
                  :dialog-label="$t('label.uploadthumbnail')"
                  hint="You can only choose images as variant media"
                  icon="fad fa-image"
                  :related-files="product.media"
                  v-model="variant.thumbnail"
                />
              </div>
            </base-section>
            <pricing-section v-model="variant" />
            <inventory-section v-model="variant" />
          </div>
        </div>
        <div class="col-xs-12 col-sm-4">
          <div class="q-gutter-md">
            <base-section contentClass="bg-green-1" no-row>
              <div v-if="product">
                <q-item class="q-pa-none">
                  <q-item-section avatar>
                    <base-thumbnail :size="80" :thumbnail="product.thumbnail" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label lines="2" class="text-h6">
                      {{ product.title }}
                    </q-item-label>
                    <q-item-label class="q-mt-xs">
                      <base-status class="q-ma-none" :type="product.status" />
                    </q-item-label>
                    <q-item-label class="q-mt-xs" caption>
                      {{ product.variants_count }} variants
                    </q-item-label>
                    <q-item-label>
                      <base-btn
                        link
                        size="12px"
                        padding="0"
                        tag="a"
                        :label="$t('label.bankToProduct')"
                        :to="{
                          name: 'Single Product',
                          params: {
                            id: product.id,
                          },
                          query: {
                            action: 'edit',
                          },
                        }"
                      />
                    </q-item-label>
                  </q-item-section>
                </q-item>
              </div>
            </base-section>
            <base-section title="Variants" no-row bodyClass="q-pa-none">
              <q-separator />
              <q-list v-if="product" class="bordered">
                <q-item
                  :to="{
                    name: 'Products Variant',
                    params: {
                      product_id: variant.product_id,
                      variant_id: variant.id,
                    },
                  }"
                  clickable
                  v-for="(variant, index) in product.variants"
                  :key="index"
                >
                  <q-item-section avatar>
                    <base-thumbnail :size="50" :media="variant.thumbnail" />
                  </q-item-section>
                  <q-item-section class="text-bold">
                    <q-item-label>{{ variant.title }}</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </base-section>
          </div>
        </div>
      </div>
    </base-form>
    <SkeletonSinglePage v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import InventorySection from "components/product/InventorySection.vue";
import PricingSection from "components/product/PricingSection.vue";
import ThumbnailSelector from "components/FileSelector.vue";
import { useVariantStore } from "src/stores/product/variant";
import { useProductStore } from "src/stores/product";
import SkeletonSinglePage from "src/components/skeleton/SkeletonSinglePage.vue";

const variant = {
  taxable: false,
  track_inventory: false,
  out_of_stock_track_inventory: false,
  stocks: [],
  price: 0,
  compare_at_price: 0,
  cost_per_item: 0,
};

export default {
  components: {
    PricingSection,
    InventorySection,
    ThumbnailSelector,
    SkeletonSinglePage,
  },
  data() {
    return {
      loaded: false,
      submited: false,
      default: cloneDeep(variant),
      variant: cloneDeep(variant),
      product: false,
      uploaded: [],
      values: [],
    };
  },
  methods: {
    ...mapActions(useProductStore, { showProduct: "show" }),
    ...mapActions(useVariantStore, ["show", "update"]),
    onSubmit(props) {
      console.func(
        "pages/admins/products/VariantPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      this.update(this.variant)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.variant = cloneDeep(data);
          this.default = cloneDeep(data);
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onUploaded(props) {
      console.func(
        "pages/admins/products/VariantPage:methods.onUploaded()",
        arguments
      );
      this.uploaded.push(props);
    },
    onRemoved(props) {
      console.func(
        "pages/admins/products/VariantPage:methods.onRemoved()",
        arguments
      );
      this.uploaded.splice(
        this.uploaded.findIndex((e) => e.id === props.id),
        1
      );
    },
    onReset(props) {
      console.func(
        "pages/admins/products/VariantPage:methods.onReset()",
        arguments
      );
      Object.assign(this.variant, cloneDeep(this.default));
    },
    onCancel(props) {
      console.func(
        "pages/admins/products/VariantPage:methods.onCancel()",
        arguments
      );
      this.$router.back();
    },
    onFilterValues(val, update, abort, option) {
      console.func(
        "pages/admins/products/VariantPage:methods.onFilterValues()",
        arguments
      );
      this.values = [];
      if (Array.isArray(option.values)) {
        update(() => {
          const needle = val.toLowerCase();
          this.values = option.values.filter(
            (item) => item.name.toLowerCase().indexOf(needle) > -1
          );
        });
      } else {
        abort();
      }
    },
    onChangeVariant(args) {
      this.loaded = false;
      this.show({
        variant_id: args.id,
      })
        .then((variant) => {
          this.variant = variant;
          this.default = cloneDeep(variant);
          this.loaded = true;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
  },
  mounted() {
    this.showProduct(this.product_id)
      .then((product) => {
        this.product = product;
        if (!product.has_variant) {
          this.$router.push({
            name: "Single Product",
            params: {
              id: this.product_id,
            },
            query: {
              action: "edit",
            },
          });
          return false;
        }
        this.show({
          variant_id: this.id,
        })
          .then((variant) => {
            if (Number(this.product_id) !== variant.product_id) {
              this.$emit("error");
            } else {
              this.variant = cloneDeep(variant);
              this.default = cloneDeep(variant);
              this.loaded = true;
            }
          })
          .catch((error) => {
            this.$emit("error", error);
          });
      })
      .catch((error) => {
        this.$emit("error", error);
      });
  },
  computed: {
    edit() {
      return ["add", "edit"].includes(this.action);
    },
    id() {
      return this.$route.params.variant_id;
    },
    product_id() {
      return this.$route.params.product_id;
    },
    action() {
      return this.$route.query.action;
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.variant) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.variant) !== JSON.stringify(this.default)
      );
    },
  },
  watch: {
    $route(to, from) {
      console.func(
        "pages/admins/products/VariantPage:watch.$route()",
        arguments
      );
      if (to.name === from.name) {
        this.loaded = false;
        this.show({
          variant_id: this.id,
        })
          .then((variant) => {
            if (Number(this.product_id) !== variant.product_id) {
              this.$emit("error");
            } else {
              this.variant = cloneDeep(variant);
              this.default = cloneDeep(variant);
              this.loaded = true;
            }
          })
          .catch((error) => {
            this.$emit("error", error);
          });
      }
    },
  },
};
</script>
