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
        <div class="col-xs-12 col-sm-9">
          <div class="q-gutter-md">
            <base-section
              title="General information"
              description="A great product title and description has the power to turn a casual shopper into a revenue-generating buyer."
            >
              <div class="col-xs-12">
                <base-label>{{ $t("label.title") }}</base-label>
                <base-input dense outlined v-model="product.title" />
              </div>
              <div class="col-xs-12">
                <base-label>{{ $t("label.description") }}</base-label>
                <base-editor v-model="product.description" min-height="10rem" />
                <!-- <tiptap-editor v-model="product.description" /> -->
              </div>
            </base-section>
            <base-section no-row>
              <base-dropzone
                header
                editor
                hint="Product media can include images, 3D models and videos. Using media like 3D models for your products provides your customer with a better understanding of the function and size of an item, and increases your customers' confidence in the quality of your products."
                accept="image/*"
                v-model="product.media"
              />
            </base-section>
            <template v-if="!product.has_variant || creating">
              <pricing-section v-model="product.default_variant" />
              <inventory-section
                :creating="creating"
                v-model="product.default_variant"
              />
            </template>
            <base-section title="Variants">
              <div class="col-xs-12">
                <q-checkbox
                  size="sm"
                  dense
                  v-model="product.has_variant"
                  @update:model-value="onChangeVariant"
                  :label="
                    $t(
                      'label.thisProductHasMultipleOptionsLikeDifferentSizesOrColors'
                    )
                  "
                />
              </div>
              <template v-if="product.has_variant">
                <div class="col-xs-12">
                  <option-section
                    :creating="creating"
                    v-model="product.options"
                    :product-id="product.id"
                    @update:variants="onGenerateVariant"
                  />
                </div>
                <div class="col-xs-12">
                  <variants-section
                    :creating="creating"
                    :editing="resetable"
                    ref="variants"
                    :product="product"
                    v-model="product.variants"
                  />
                </div>
              </template>
            </base-section>
            <seo-section v-model="product" />
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-section no-row>
            <div class="q-gutter-y-md">
              <div>
                <base-label>{{ $t("label.productStatus") }}</base-label>
                <base-select
                  v-model="product.status"
                  :options="['Active', 'Draft']"
                  dense
                  outlined
                />
              </div>
              <div>
                <base-label>{{ $t("label.vendor") }}</base-label>
                <vendor-select v-model="product.vendor" />
              </div>
              <div>
                <base-label>{{ $t("label.category") }}</base-label>
                <category-select v-model="product.category" />
              </div>
              <div>
                <base-label>{{ $t("label.tags") }}</base-label>
                <tags-select v-model="product.tags" />
              </div>
              <div>
                <base-label>{{ $t("label.collections") }}</base-label>
                <collections-select v-model="product.collections" />
              </div>
            </div>
          </base-section>
        </div>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import { useProductStore } from "src/stores/product";
import { useVariantStore } from "src/stores/product/variant";
import { useShopLocationStore } from "src/stores/shop/location";
import CategorySelect from "src/components/product/CategorySelect.vue";
import CollectionsSelect from "src/components/product/CollectionsSelect.vue";
import InventorySection from "src/components/product/InventorySection.vue";
import OptionSection from "src/components/product/OptionSection.vue";
import PricingSection from "src/components/product/PricingSection.vue";
import TagsSelect from "src/components/product/TagsSelect.vue";
import VariantsSection from "src/components/product/VariantsSection.vue";
import VendorSelect from "src/components/product/VendorSelect.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
// import TiptapEditor from "components/tiptap/TiptapEditor.vue";
import SeoSection from "src/components/product/SeoSection.vue";

const product = {
  description: "",
  status: "Draft",
  inventories_available: "0 available",
  default_variant: {
    taxable: false,
    track_inventory: false,
    out_of_stock_track_inventory: false,
    inventories: [],
    price: "0.00",
    compare_at_price: "0.00",
    cost_per_item: "0.00",
  },
  has_variant: false,
  media: [],
  options: [
    {
      id: false,
      name: "Size",
      is_custom: true,
      attribute_id: false,
      values: [],
    },
  ],
};

export default {
  components: {
    OptionSection,
    VariantsSection,
    InventorySection,
    PricingSection,
    VendorSelect,
    CategorySelect,
    TagsSelect,
    CollectionsSelect,
    SeoSection,
    SkeletonSinglePage,
    // TiptapEditor,
  },
  data() {
    return {
      loaded: false,
      submited: false,
      default: cloneDeep(product),
      product: cloneDeep(product),
      options: [],
    };
  },
  methods: {
    ...mapActions(useProductStore, ["show", "store", "update"]),
    ...mapActions(useVariantStore, ["clear", "generate"]),
    ...mapActions(useShopLocationStore, {
      location: "get",
    }),
    onSubmit(props) {
      console.func(
        "pages/admins/products/ProductPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.product)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.product = data;
          this.default = cloneDeep(data);
          this.clear(data.variants);
          this.$router.push({
            name: "Single Product",
            params: {
              id: data.id,
            },
            query: {
              action: "edit",
            },
          });
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: "Error" });
        });
    },
    onReset(props) {
      console.func(
        "pages/admins/products/ProductPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      setTimeout(() => {
        this.product = cloneDeep(this.default);
        this.loaded = true;
      }, 1);
    },
    onCancel(props) {
      console.func(
        "pages/admins/products/ProductPage:methods.onCancel()",
        arguments
      );
      this.$router.back();
    },
    onGenerateVariant(options) {
      console.func(
        "pages/admins/products/ProductPage:methods.onGenerateVariant()",
        arguments
      );
      this.generate({
        options: options,
        default_variant: this.product.default_variant,
      });
    },
    onChangeVariant(args) {
      console.func(
        "pages/admins/products/ProductPage:methods.onChangeVariant()",
        arguments
      );
      this.clear([]);
      this.product.options = [];
      this.product.variants = [];
    },
    onLoadLoaction() {
      this.location()
        .then(({ data }) => {
          data.forEach((location) => {
            this.product.default_variant.inventories.push({
              id: false,
              available: 0,
              incoming: 0,
              active: true,
              location: {
                id: location.id,
                name: location.name,
              },
            });
          });
        })
        .then(() => {
          this.default = cloneDeep(this.product);
          this.loaded = true;
        })
        .catch((error) => {
          this.loaded = true;
          this.$core.error(error, { title: "Error" });
        });
    },
  },
  mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((product) => {
          this.product = product;
          this.default = cloneDeep(product);
          this.loaded = true;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    } else {
      this.onLoadLoaction();
    }
  },
  computed: {
    creating() {
      return this.id === "add";
    },
    id() {
      return this.$route.params.id;
    },
    action() {
      return this.$route.query.action;
    },
    slot() {
      return this.options.length ? "before-options" : "no-option";
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.product) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.product) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
