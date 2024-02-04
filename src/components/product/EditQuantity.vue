<template>
  <base-dialog
    title="Edit quantities"
    ref="dialog"
    body-class="scroll"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <q-list class="bordered">
        <template v-if="!location">
          <q-item dense class="q-pt-none q-pl-none q-pr-none">
            <q-item-section>
              <q-item-label class="">
                Choose a location where you want to edit quantities.
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item
            clickable
            v-for="(item, index) in locations"
            :key="index"
            @click="onSelect(item)"
          >
            <q-item-section>
              <q-item-label>{{ item.name }}</q-item-label>
            </q-item-section>
            <q-item-section side>
              <q-icon name="fal fa-angle-right" />
            </q-item-section>
          </q-item>
        </template>
        <template v-else>
          <q-item class="q-pt-none q-pl-none q-pr-none">
            <q-item-section>
              <q-item-label class="q-mb-sm"
                >Editing quantities at
                <span class="text-weight-bold">{{ location.name }}</span
                >.</q-item-label
              >
              <q-item-label class="">
                Apply a quantity to all variants
              </q-item-label>
              <q-item-label class="row">
                <div class="col">
                  <q-input dense outlined v-model="quantity" type="number" />
                </div>
                <div class="col-auto q-ml-sm">
                  <q-btn
                    no-caps
                    outline
                    padding="8px 15px"
                    color="grey"
                    class="bg-green-1"
                    :label="$t('label.applyToAll')"
                    @click="onChange"
                  />
                </div>
              </q-item-label>
            </q-item-section>
          </q-item>
          <q-item
            class="q-pl-none q-pr-none"
            v-for="(inventory, index) in inventories"
            :key="index"
          >
            <q-item-section class="text-bold">
              <q-item-label>{{ inventory.variant.title }}</q-item-label>
            </q-item-section>
            <q-item-section side class="align-right">
              <q-input
                dense
                outlined
                v-model="inventory.location.available"
                type="number"
              >
              </q-input>
            </q-item-section>
          </q-item>
        </template>
      </q-list>
    </template>
    <template v-slot:footer>
      <q-card-section class="text-right">
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
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import { useShopLocationStore } from "src/stores/shop/location";

export default {
  props: {
    modelValue: {
      required: true,
      type: Array,
    },
  },
  data() {
    return {
      variants: cloneDeep(this.modelValue),
      default: cloneDeep(this.modelValue),
      quantity: 0,
      locations: [],
      location: null,
      inventories: [],
    };
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useShopLocationStore, ["get"]),
    show() {
      console.func("components/product/EditQuantity:methods.show()", arguments);
      this.get()
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
        "components/product/EditQuantity:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/product/EditQuantity:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/product/EditQuantity:methods.onDone()",
        arguments
      );
      this.inventories.forEach((inventory) => {
        const variant = this.variants.find(
          (variant) => variant.id === inventory.variant.id
        );
        if (variant) {
          variant.inventories.find(
            (item) => item.location.id === inventory.location.location.id
          ).available = inventory.location.available;
        }
      });
      this.$emit("ok", this.variants);
      this.hide();
    },
    onChange() {
      console.func(
        "components/product/EditQuantity:methods.onChange()",
        arguments
      );
      this.inventories.forEach((inventory) => {
        inventory.location.available = this.quantity;
      });
    },
    onSelect(location) {
      console.func(
        "components/product/EditQuantity:methods.onSelect()",
        arguments
      );
      this.variants.forEach((variant) => {
        this.inventories.push({
          variant: variant,
          location: variant.inventories.find(
            (item) => item.location.id === location.id
          ),
        });
      });
      this.location = location;
    },
  },
  computed: {
    disable() {
      return JSON.stringify(this.variants) === JSON.stringify(this.default);
    },
  },
};
</script>
