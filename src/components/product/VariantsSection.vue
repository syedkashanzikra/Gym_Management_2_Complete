<template>
  <div class="variants-section">
    <q-list class="bordered">
      <q-item v-if="variants.length">
        <q-item-section style="min-width: auto" avatar>
          <q-checkbox
            size="sm"
            dense
            @update:model-value="onSelect"
            v-model="selection"
          />
        </q-item-section>
        <q-item-section>
          <q-item-label class="text-weight-bold">
            {{ selected.length ? "Selected" : "Total" }} {{ length }} variant{{
              length > 1 ? "s" : ""
            }}
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          <base-btn-dropdown :label="$t('label.actions')" link color="primary">
            <q-list dense style="min-width: 150px" class="q-pa-sm">
              <q-item
                class="rounded-borders"
                @click="onEditPrices"
                clickable
                v-close-popup
              >
                <q-item-section>
                  <q-item-label>Edit prices</q-item-label>
                </q-item-section>
              </q-item>

              <q-item
                class="rounded-borders"
                @click="onEditQuantities"
                clickable
                v-close-popup
              >
                <q-item-section>
                  <q-item-label>Edit quantities</q-item-label>
                </q-item-section>
              </q-item>

              <q-item
                class="rounded-borders"
                @click="onEditSKUs"
                clickable
                v-close-popup
              >
                <q-item-section>
                  <q-item-label>Edit SKUs</q-item-label>
                </q-item-section>
              </q-item>

              <q-item
                class="rounded-borders"
                @click="onEditBarcode"
                clickable
                v-close-popup
              >
                <q-item-section>
                  <q-item-label>Edit barcodes</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </base-btn-dropdown>
        </q-item-section>
      </q-item>
      <template v-if="loading">
        <div class="row justify-center q-my-md">
          <q-spinner-dots color="primary" size="40px" />
        </div>
      </template>
      <template v-else>
        <q-item clickable v-for="(variant, index) in modelValue" :key="index">
          <q-item-section style="min-width: auto" avatar>
            <q-checkbox size="sm" dense v-model="selected" :val="variant.id" />
          </q-item-section>
          <q-item-section avatar>
            <file-selector
              mini
              dialog-label="uploadThumbnail"
              hint="You can only choose images as variant media"
              icon="fad fa-image"
              :related-files="product.media"
              :modelValue="variant.thumbnail"
              @update:model-value="(val) => onUpdateThumbnail(val, variant)"
            />
          </q-item-section>
          <q-item-section @click="onEditVariant(variant)" class="text-bold">
            <q-item-label>
              {{ variant.title }}
              <q-chip
                size="sm"
                color="green-2"
                v-if="isNaN(variant.id)"
                :label="$t('label.new')"
              />
            </q-item-label>
          </q-item-section>
          <q-item-section
            @click="onEditVariant(variant)"
            side
            class="align-right"
          >
            <template v-if="creating && !variant.create_on_save">
              <span>This version will not be created</span>
            </template>
            <template v-else>
              <q-item-label>${{ variant.price }}</q-item-label>
              <inventory-label v-bind="variant"></inventory-label>
            </template>
          </q-item-section>
        </q-item>
      </template>
      <template v-if="hasTrackInventory">
        <q-item dense class="q-pt-none">
          <q-item-section>Total inventory at all locations</q-item-section>
          <q-item-section side class="align-right">
            <q-item-label>{{ inventoriesAvailable }}</q-item-label>
          </q-item-section>
        </q-item>
      </template>
    </q-list>
  </div>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions, mapState } from "pinia";
import VariantEditDialog from "src/components/product/VariantEditDialog.vue";
import EditPrices from "src/components/product/EditPrices.vue";
import EditQuantity from "src/components/product/EditQuantity.vue";
import EditSkus from "src/components/product/EditSkus.vue";
import EditBarcodes from "src/components/product/EditBarcodes.vue";
import InventoryLabel from "src/components/product/InventoryLabel.vue";
import FileSelector from "src/components/FileSelector.vue";
import { useVariantStore } from "src/stores/product/variant";

export default {
  components: {
    InventoryLabel,
    FileSelector,
  },
  props: {
    modelValue: {
      required: true,
    },
    product: {
      required: false,
      type: Object,
    },
    creating: {
      required: false,
      type: Boolean,
      default: false,
    },
    editing: {
      required: false,
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      selected: [],
      loading: true,
      selection: false,
    };
  },
  emits: ["update:model-value"],
  methods: {
    ...mapActions(useVariantStore, ["get", "edit", "changeList", "clear"]),
    onLoad() {
      console.func(
        "components/product/VariantsSection:method.onLoad()",
        arguments
      );
      if (this.product.id) {
        this.get({
          product_id: this.product.id,
        })
          .then(() => {
            this.loading = false;
          })
          .catch((error) => {
            this.loading = false;
            this.$core.error(error, { title: "Error" });
          });
      } else {
        this.loading = false;
      }
    },
    onEditVariant(variant) {
      console.func(
        "components/product/VariantsSection:method.onEditVariant()",
        arguments
      );
      if (this.creating || this.hasNewVariant || this.editing) {
        this.$q
          .dialog({
            component: VariantEditDialog,
            componentProps: {
              modelValue: variant,
              creating: this.creating || isNaN(variant.id),
            },
          })
          .onOk((payload) => {
            console.func(
              "components/product/VariantsSection:methods.onEditVariant.onOk()",
              arguments
            );
            this.edit(payload).then(() => {
              this.$emit("update:model-value", this.variants);
            });
          });
      } else {
        this.$router.push({
          name: "Products Variant",
          params: {
            product_id: this.product.id,
            variant_id: variant.id,
          },
        });
      }
    },
    onUpdateThumbnail(value, variant) {
      console.func(
        "components/product/VariantsSection:method.onUpdateThumbnail()",
        arguments
      );
      const item = cloneDeep(variant);
      this.edit(
        Object.assign(item, {
          thumbnail: value,
        })
      ).then(() => {
        this.$emit("update:model-value", this.variants);
      });
    },
    onEditPrices() {
      console.func(
        "components/product/VariantsSection:method.onEditPrices()",
        arguments
      );
      this.$q
        .dialog({
          component: EditPrices,
          componentProps: {
            modelValue: this.modelValue,
          },
        })
        .onOk((payload) => {
          this.loading = true;
          this.changeList(payload).then((variants) => {
            this.$emit("update:model-value", cloneDeep(variants));
            this.loading = false;
          });
        });
    },
    onEditQuantities() {
      console.func(
        "components/product/VariantsSection:method.onEditQuantities()",
        arguments
      );
      this.$q
        .dialog({
          component: EditQuantity,
          componentProps: {
            modelValue: this.modelValue,
          },
        })
        .onOk((payload) => {
          this.loading = true;
          this.changeList(payload).then((variants) => {
            this.$emit("update:model-value", cloneDeep(variants));
            this.loading = false;
          });
        });
    },
    onEditSKUs() {
      console.func(
        "components/product/VariantsSection:method.onEditSKUs()",
        arguments
      );
      this.$q
        .dialog({
          component: EditSkus,
          componentProps: {
            modelValue: this.modelValue,
          },
        })
        .onOk((payload) => {
          this.loading = true;
          this.changeList(payload).then((variants) => {
            this.$emit("update:model-value", cloneDeep(variants));
            this.loading = false;
          });
        });
    },
    onEditBarcode() {
      console.func(
        "components/product/VariantsSection:method.onEditBarcode()",
        arguments
      );
      this.$q
        .dialog({
          component: EditBarcodes,
          componentProps: {
            modelValue: this.modelValue,
          },
        })
        .onOk((payload) => {
          this.loading = true;
          this.changeList(payload).then((variants) => {
            this.$emit("update:model-value", cloneDeep(variants));
            this.loading = false;
          });
        });
    },
    onSelect() {
      if (this.selected.length && this.selected.length < this.variants.length) {
        this.variants
          .filter((item) => !this.selected.includes(item.id))
          .forEach((item) => {
            this.selected.push(item.id);
          });
      } else if (this.selected.length === this.variants.length) {
        this.selected = [];
      } else {
        this.selected = this.variants.map((item) => item.id);
      }
    },
  },
  computed: {
    ...mapState(useVariantStore, {
      variants: "rows",
      default: "default",
    }),
    hasNewVariant() {
      return this.variants.filter((item) => isNaN(item.id)).length > 0;
    },
    length() {
      return this.selected.length || this.variants.length;
    },
    inventoriesAvailable() {
      const available = [];
      this.variants
        .filter((variant) => variant.track_inventory)
        .forEach((variant) => {
          available.push(
            variant.inventories
              .filter((item) => item.trackable)
              .map((item) => parseInt(item.available))
              .reduce((total, available) => total + available, 0)
          );
        });
      return `${available.reduce(
        (total, available) => total + parseInt(available),
        0
      )} available`;
    },
    hasTrackInventory() {
      return this.variants.filter((variant) => variant.track_inventory).length;
    },
  },
  watch: {
    selected(val) {
      if (val.length && val.length < this.variants.length) {
        this.selection = null;
      } else if (val.length === this.variants.length) {
        this.selection = true;
      } else {
        this.selection = false;
      }
    },
    variants(val) {
      this.$emit("update:model-value", val);
    },
  },
  mounted() {
    // this.onLoad()
    setTimeout(() => {
      this.clear(this.modelValue);
      this.loading = false;
    }, 100);
  },
  unmounted() {
    this.clear([]);
  },
};
</script>
