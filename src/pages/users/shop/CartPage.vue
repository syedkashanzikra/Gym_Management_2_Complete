<template>
  <q-page padding>
    <base-info rounded class="q-mb-md">
      {{ $t("shopNotice") }}
    </base-info>
    <base-section class="cart-section" flat bordered :title="$t('cart')" no-row>
      <base-table
        :columns="columns"
        :rows="cart"
        hide-top
        :no-data-label="$t('products.noData')"
        loaded
        hide-pagination
        :rows-per-page-options="[0]"
      >
        <template #body-cell-title="props">
          <q-item class="q-pa-none" dense>
            <q-item-section avatar>
              <base-thumbnail :size="40" :media="props.row.thumbnail" />
            </q-item-section>
            <q-item-section avatar>
              <q-item-label>
                <base-btn
                  @click.stop
                  link
                  size="12px"
                  tag="a"
                  :to="{
                    name: 'Product',
                    params: {
                      slug: props.row.slug,
                      title: props.row.title,
                    },
                  }"
                  :label="props.value"
                />
              </q-item-label>
              <q-item-label v-if="props.row.has_variant" class="variants">
                <div
                  class="variant"
                  v-for="(item, index) in props.row.options"
                  :key="index"
                >
                  <strong>{{ item.name }}:</strong>
                  <span class="q-ml-xs">{{ item.value }}</span>
                </div>
              </q-item-label>
            </q-item-section>
          </q-item>
        </template>
        <template #body-cell-price="props">
          {{ props.value }} x {{ props.row.quantity }}
        </template>
        <template #body-cell-quantity="props">
          <quantity-input
            v-model="props.row.quantity"
            @update:model-value="updateCart(props)"
          />
        </template>
        <template #actions="props">
          <q-btn
            flat
            dense
            round
            size="sm"
            color="primary"
            icon="fas fa-trash-can"
            @click="onRemove(props)"
          />
        </template>
        <template v-if="cartCount" #bottom-row>
          <q-tr no-hover>
            <q-td class="text-right" colspan="4">
              <div class="flex">
                <q-space />
                <div class="text-uppercase q-mr-xl">
                  {{ $t("label.subtotal") }}
                </div>
                <div class="text-bold">{{ $core.money(cartTotal) }}</div>
              </div>
              <div class="text-negative">Tax included at store checkout</div>
            </q-td>
            <q-td></q-td>
          </q-tr>
        </template>
      </base-table>
      <template v-if="cartCount" #bottom>
        <div class="flex items-center">
          <div>
            <base-label>
              {{ $t("cartInfo") }}
            </base-label>
            <base-input
              placeholder="Message to this enquiry..."
              autogrow
              v-model="note"
              type="textarea"
            />
          </div>
          <q-space />
          <div class="text-right">
            <base-btn
              size="md"
              caps
              color="primary"
              :label="$t('submitCart')"
              class="main-btn q-mt-md"
              cap
              :loading="submited"
              @click="onSubmitCart"
            />
          </div>
        </div>
      </template>
    </base-section>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useShopStore } from "stores/shop";
import QuantityInput from "components/shop/QuantityInput.vue";

export default {
  components: { QuantityInput },
  data() {
    return {
      submited: false,
      note: null,
    };
  },
  methods: {
    ...mapActions(useShopStore, [
      "removeFromCart",
      "updateCart",
      "createOrder",
      "clearCart",
    ]),
    onSubmitCart(props) {
      console.func(
        "pages/users/shop/CartPage:methods.onSubmitCart()",
        arguments
      );
      this.submited = true;
      this.createOrder({
        line_items: this.lineItems,
        note: this.note,
      })
        .then(({ message, enquiry }) => {
          this.submited = false;
          this.$q.notify(message);
          this.$router
            .push({
              name: "Single Enquiry",
              params: {
                id: enquiry?.id,
              },
            })
            .then(() => {
              this.clearCart();
            });
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onRemove(props) {
      console.func("pages/users/shop/CartPage:methods.onRemove()", arguments);
      this.removeFromCart(props);
    },
  },
  computed: {
    ...mapState(useShopStore, ["cart", "cartTotal", "cartCount", "lineItems"]),
    columns() {
      return [
        {
          name: "title",
          align: "left",
          label: this.$t("label.product"),
          field: "title",
          sortable: false,
        },
        {
          name: "price",
          align: "left",
          label: this.$t("label.price"),
          field: "price",
          format: (val) => this.$core.money(val),
          style: "width: 40px",
          sortable: false,
        },
        {
          name: "quantity",
          align: "left",
          label: this.$t("label.quantity"),
          field: "quantity",
          style: "width: 140px",
          sortable: true,
        },
        {
          name: "total",
          align: "left",
          label: this.$t("label.total"),
          field: (row) => row.quantity * row.price,
          format: (val) => this.$core.money(val),
          style: "width: 40px",
          sortable: true,
        },
        {
          name: "actions",
          align: "right",
          label: "",
          field: "actions",
          style: "width: 10px",
          sortable: true,
        },
      ];
    },
  },
};
</script>

<style lang="scss">
.size.q-field--dense .q-field__control,
.size.q-field--dense .q-field__native,
.size.q-field--dense:not(.q-textarea, .tags) .q-field__control,
.size.q-field--dense:not(.q-textarea, .tags) .q-field__marginal {
  min-height: auto !important;
  height: auto;
}
.cart-section {
  .section-body {
    padding-bottom: 0;
  }
}
</style>
