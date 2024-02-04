<template>
  <q-page class="q-pa-md pos-page">
    <base-section gutter="md" bordered dense style="height: 100%">
      <div class="col-xs-12 col-sm-12">
        <q-toolbar class="q-px-none pos-toolbar">
          <base-btn
            dense
            padding="13px"
            class="q-mr-sm"
            outline
            @click="isGrid = !isGrid"
            :icon="isGrid ? `fas fa-grid` : `fas fa-list`"
          />
          <category-select
            class="q-mr-sm"
            no-add
            use-filter
            placeholder="All category"
            style="width: 250px"
            v-model="category"
            @update:model-value="onRefresh"
            clearable
          />
          <base-input
            class="product-search"
            v-model="filter"
            outlined
            clearable
            placeholder="Search Product"
            debounce="1000"
            @update:model-value="onRefresh"
            dense
            style="width: 100%"
          >
            <template v-slot:append>
              <q-icon v-if="!filter" name="fas fa-search" size="13px" />
            </template>
          </base-input>
          <qr-code-scanner-btn @detect="onScanned" class="q-ml-sm qr-scanner" />
        </q-toolbar>
      </div>
      <div class="col-xs-12 col-sm-12">
        <q-scroll-area style="height: calc(100vh - 257px)">
          <q-infinite-scroll ref="productList" @load="onLoad" :offset="150">
            <div class="row q-col-gutter-md">
              <div
                v-for="(item, index) in products"
                :key="index"
                :class="{
                  'col-xs-12 col-lg-2 col-md-3 col-sm-3': isGrid,
                  'col-xs-12 col-lg-4 col-md-6 col-sm-6': !isGrid,
                }"
              >
                <product-card
                  :list="!isGrid"
                  @click="$refs.products.addToCart(item)"
                  v-bind="item"
                />
              </div>
              <div
                v-if="!products.length && !loading"
                class="col-xs-12 col-sm-12"
              >
                <base-no-records
                  icon="fad fa-box-open"
                  title="Product not found"
                  message="Kindly ensure the accurate product name is entered, as it might not be available in the listing. Alternatively, consider utilizing the custom item option."
                  height="350px"
                  icon-size="75px"
                />
              </div>
            </div>
            <template v-slot:loading>
              <product-card-skeleton :rows-per-page="18" :list="!isGrid" />
            </template>
          </q-infinite-scroll>
        </q-scroll-area>
      </div>
      <template #bottom>
        <base-btn
          link
          color="primary"
          label="Add custom product"
          @click="onAddCustomProduct"
        />
        <base-btn
          link
          icon="fas fa-location-dot"
          @click="onChangeLocation"
          class="q-ml-md"
          :label="locationLabel"
        />
      </template>
    </base-section>

    <q-page-sticky
      v-if="$q.screen.lt.md"
      position="bottom-right"
      :offset="[18, 18]"
    >
      <q-btn
        @click="$refs.cartDrawer.toggle()"
        fab
        icon="fad fa-cart-plus"
        color="primary"
      >
        <q-badge
          floating
          color="orange"
          text-color="black"
          :label="totalProducts"
          rounded
          style="padding: 5px 7px; font-weight: bold"
        />
      </q-btn>
    </q-page-sticky>

    <q-drawer
      ref="cartDrawer"
      show-if-above
      side="right"
      :breakpoint="768"
      :width="drawerWidth"
      :class="{ 'q-py-md q-pr-md': smScreen }"
    >
      <div
        :class="{ 'q-pa-md bg-white': true, 'rounded-border': smScreen }"
        style="height: 100%"
      >
        <q-toolbar class="q-pb-sm q-px-none">
          <q-toolbar-title>
            <user-select
              placeholder="Select customer"
              dense
              outlined
              v-model="cart.customer"
              @update:model-value="onSelectCustomer"
              :option-label="(opt) => `${opt.first_name} ${opt.last_name}`"
              :disable="submited"
            />
          </q-toolbar-title>
          <base-btn
            dense
            padding="13px"
            color="primary"
            icon="fas fa-user-plus"
            @click="onCreateCustomer"
            :disable="submited"
          />
          <base-btn
            v-if="$q.screen.xs"
            dense
            outline
            padding="13px"
            color="grey"
            class="q-ml-sm"
            icon="fad fa-circle-xmark"
            @click="$refs.cartDrawer.toggle()"
          />
        </q-toolbar>
        <q-scroll-area style="height: calc(100% - 270px)">
          <pos-products
            ref="products"
            v-model="cart.line_items"
            @update:model-value="onCalculator"
          />
        </q-scroll-area>
        <base-section dense gutter="md" class="q-mt-sm bg-grey-2" flat no-row>
          <billing-details
            ref="billing"
            v-model="cart"
            @marked-paid="onSubmit"
            @update:model-value="onCalculator"
          />
        </base-section>
        <q-toolbar class="q-mt-sm q-px-none">
          <q-toolbar-title>
            <q-btn
              padding="10px"
              class="full-width text-black"
              color="secondary"
              label="Place Order"
              :loading="submited"
              :disable="!hasCart"
              @click="$refs.billing.onMarkAsPaid()"
            />
          </q-toolbar-title>
          <base-btn
            v-if="latestOrder"
            padding="15px"
            dense
            class="q-mr-sm"
            icon="fas fa-print"
            :disable="submited"
            :loading="ptintingReceipt"
            @click="printLatestReceipt"
          />
          <base-btn
            padding="15px"
            outline
            class="bg-negative"
            color="white"
            dense
            icon="fas fa-cart-circle-xmark"
            :disable="submited"
            @click="clearCart"
          />
        </q-toolbar>
      </div>
    </q-drawer>
  </q-page>
</template>

<script>
import { mapActions } from "pinia";
import { cloneDeep, sumBy } from "lodash";
import UserSelect from "src/components/UserSelect.vue";
import CategorySelect from "src/components/product/CategorySelect.vue";
import PosProducts from "src/components/shop/PosProducts.vue";
import BillingDetails from "components/order/BillingDetails.vue";
import { useProductStore } from "src/stores/product";
import { useOrderStore } from "src/stores/order";
import AddCustomerDialog from "src/components/customer/AddCustomerDialog.vue";
import { LocalStorage } from "quasar";
import ProductCard from "src/components/shop/ProductCard.vue";
import CustomProduct from "src/components/order/CustomProduct.vue";
import ChangeLocation from "src/components/order/ChangeLocation.vue";
import ProductCardSkeleton from "src/components/skeleton/ProductCardSkeleton.vue";
import QrCodeScannerBtn from "src/components/QrCodeScannerBtn.vue";

const defaultCart = {
  customer: undefined,
  source: "POS",
  contact: null,
  billing_address: null,
  collect_tax: true,
  line_items: [],
  tax_lines: [],
};

const location = LocalStorage.getItem("location");

export default {
  components: {
    CategorySelect,
    UserSelect,
    PosProducts,
    BillingDetails,
    ProductCard,
    ProductCardSkeleton,
    QrCodeScannerBtn,
  },
  data() {
    return {
      currentPage: 0,
      lastPage: 1,
      rightDrawerOpen: true,
      loading: true,
      isGrid: true,
      submited: false,
      ptintingReceipt: false,
      category: undefined,
      filter: null,
      latestOrder: LocalStorage.has("latest-pos"),
      location: location,
      products: [],
      cart: cloneDeep(defaultCart),
    };
  },
  methods: {
    ...mapActions(useProductStore, ["get", "barcodeProduct"]),
    ...mapActions(useOrderStore, ["calculator", "store", "pos"]),
    onLoad(index, done) {
      console.func("pages/admins/orders/PosPage:methods.onLoad()", arguments);
      this.loading = true;
      setTimeout(() => {
        if (this.currentPage < this.lastPage) {
          this.get({
            page: this.currentPage + 1,
            filter: this.filter,
            category: this.category,
            location: this.location?.id,
            rowsPerPage: 18,
            includes: ["default_variant", "variants", "options"],
          }).then(({ meta, data }) => {
            const { last_page, current_page } = meta;

            if (this.currentPage === 0) {
              this.products = data;
            } else {
              data.forEach((item) => this.products.push(item));
            }

            this.currentPage = current_page;
            this.lastPage = last_page;
            this.loading = false;

            done(current_page === last_page);
          });
        } else {
          this.loading = false;
          done(true);
        }
      }, 100);
    },
    onCalculator() {
      console.func(
        "pages/admins/orders/PosPage:methods.onCalculator()",
        arguments
      );
      this.calculator({ ...this.cart, location: this.location })
        .then((cart) => {
          Object.assign(this.cart, cart);
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    onSelectCustomer(customer) {
      console.func(
        "pages/admins/orders/PosPage:methods.onSelectCustomer()",
        arguments
      );
      if (customer) {
        this.cart.collect_tax = customer.collect_tax;
        const { email, phone_number } = customer;
        this.cart.contact = {
          email,
          phone_number,
        };
        const { first_name, last_name, address } = customer;
        this.cart.billing_address = {
          ...address,
          first_name,
          last_name,
          phone_number,
        };
      } else {
        this.cart.collect_tax = true;
      }
      this.onCalculator();
    },
    onCreateCustomer() {
      console.func(
        "pages/admins/orders/PosPage:methods.onCreateCustomer()",
        arguments
      );
      this.$q
        .dialog({
          component: AddCustomerDialog,
          componentProps: {
            modelValue: {},
          },
        })
        .onOk((payload) => {
          Object.assign(this.cart, {
            customer: {
              first_name: payload.first_name,
              last_name: payload.last_name,
              name: payload.first_name + " " + payload.last_name,
              phone_number: payload.phone_number,
              email: payload.email,
              address: { ...payload },
              collect_tax: true,
              email_marketing: true,
            },
            contact: {
              phone_number: payload.phone_number,
              email: payload.email,
            },
          });
        });
    },
    onAddCustomProduct() {
      console.func(
        "pages/admins/orders/PosPage:methods.onAddCustomProduct()",
        arguments
      );
      this.$q
        .dialog({
          component: CustomProduct,
        })
        .onOk((payload) => {
          this.$refs.products.addCustomProduct(payload);
        });
    },
    onChangeLocation() {
      console.func(
        "pages/admins/orders/PosPage:methods.onChangeLocation()",
        arguments
      );
      this.$q
        .dialog({
          component: ChangeLocation,
          componentProps: {
            modelValue: this.location ?? {},
          },
        })
        .onOk((payload) => {
          this.location = cloneDeep(payload);
          LocalStorage.set("location", payload);
          this.onRefresh();
        });
    },
    clearCart() {
      console.func(
        "pages/admins/orders/PosPage:methods.clearCart()",
        arguments
      );
      this.cart = cloneDeep(defaultCart);
    },
    onRefresh() {
      console.func(
        "pages/admins/orders/PosPage:methods.onRefresh()",
        arguments
      );
      this.currentPage = 0;
      this.lastPage = 1;
      this.loading = true;
      this.$refs.productList.resume();
      this.$refs.productList.trigger();
    },
    async printLatestReceipt() {
      console.func(
        "pages/admins/orders/PosPage:methods.printLatestReceipt()",
        arguments
      );
      const { id } = LocalStorage.getItem("latest-pos");
      this.ptintingReceipt = true;
      this.pos(id)
        .then(() => {
          this.ptintingReceipt = false;
        })
        .catch((error) => {
          this.$core.error(error);
          this.ptintingReceipt = false;
        });
    },
    onSubmit(args) {
      console.func("pages/admins/orders/PosPage:methods.onSubmit()", arguments);
      this.submited = true;
      this.store({
        ...this.cart,
        payment_method: args,
        location: this.location,
      })
        .then(async ({ data }) => {
          this.submited = false;
          LocalStorage.set("latest-pos", data);
          this.latestOrder = true;
          await this.pos(data.id);
          this.clearCart();
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: "Error" });
        });
    },
    onScanned(barcode) {
      console.func(
        "pages/admins/orders/PosPage:methods.onScanned()",
        arguments
      );
      this.barcodeProduct(barcode)
        .then((variant) => {
          const { product } = variant;
          this.$refs.products.addToCart({
            ...product,
            has_variant: false,
            default_variant: variant,
          });
        })
        .catch(() => {
          this.$q.notify({
            message: "Product not found using scanned barcode!",
            position: "bottom",
            color: "negative",
          });
        });
    },
  },
  computed: {
    hasCart() {
      return this.cart.line_items.length > 0;
    },
    smScreen() {
      return this.$q.screen.gt.sm;
    },
    drawerWidth() {
      return this.$q.screen.xs ? this.$q.screen.width : 400;
    },
    locationLabel() {
      return this.location?.name ?? "Set location";
    },
    totalProducts() {
      return this.cart.line_items_quantity ?? 0;
    },
  },
};
</script>
<style lang="scss">
.pos-page {
  .q-drawer {
    background: transparent;
  }
}
</style>
