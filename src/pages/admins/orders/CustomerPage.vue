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
            <recent-order
              :customer="customer.id"
              :order="customer.latest_order"
            />
            <base-section
              title="General information"
              description="Customer information forms provide key data on customer that can be used to keep track of who worked for the company, when, and in what positions."
            >
              <div class="col-xs-12 col-sm-4">
                <base-label>First Name</base-label>
                <base-input v-model="customer.first_name" />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>Last name</base-label>
                <base-input v-model="customer.last_name" />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>Email Address</base-label>
                <base-input dense outlined v-model="customer.email" />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>Phone Number</base-label>
                <base-input v-model="customer.phone_number" />
              </div>
              <div class="col-xs-12 col-sm-12">
                <base-label>Customer Note</base-label>
                <base-input dense outlined v-model="customer.note" />
              </div>
            </base-section>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <div class="q-gutter-md">
            <base-section title="Customer overview" contentClass="bg-green-1">
              <div class="col-sm-12 col-xs-12">
                <div class="row flex-start">
                  <base-btn link padding="0" :label="customer.email" />
                  <q-btn
                    @click="$core.copyToClipboard(customer.email)"
                    flat
                    dense
                    padding="0"
                    size="xs"
                    icon="fas fa-clipboard"
                  >
                    <base-tooltip>Copy email</base-tooltip>
                  </q-btn>
                </div>
                <div class="text-grey">
                  Created at: {{ customer.created_at }}
                </div>
              </div>
              <div class="col-sm-12 col-xs-12">
                <base-label>DEFAULT ADDRESS</base-label>
                <address-card v-bind="customer.address" />
              </div>
            </base-section>
            <base-section title="Activity">
              <div class="col-sm-12 col-xs-12">
                <q-item dense class="q-px-none">
                  <q-item-section class="text-center">
                    Total spent in {{ customer.total_orders }} orders
                    <div class="text-h6">
                      {{ $core.money(customer.total_spent) }}
                    </div>
                  </q-item-section>
                  <q-item-section class="text-center">
                    Average order amount
                    <div class="text-h6">
                      {{ $core.money(customer.average_order_amount) }}
                    </div>
                  </q-item-section>
                </q-item>
              </div>
            </base-section>
            <base-section title="Tax exemption">
              <div class="col-sm-12 col-xs-12">
                <q-checkbox
                  dense
                  v-model="customer.collect_tax"
                  label="Collect tax"
                />
              </div>
            </base-section>
          </div>
        </div>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import { useCustomerStore } from "stores/customer";
import AddressCard from "src/components/address/AddressCard.vue";
import RecentOrder from "components/customer/RecentOrder.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";

const customer = {
  email_marketing: false,
  collect_tax: true,
};

export default {
  components: { AddressCard, RecentOrder, SkeletonSinglePage },
  data() {
    return {
      default: cloneDeep(customer),
      customer: cloneDeep(customer),
      loaded: false,
      submited: false,
    };
  },
  methods: {
    ...mapActions(useCustomerStore, ["show", "update"]),
    onSubmit(props) {
      console.func(
        "pages/admins/orders/CustomerPage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      this.update(this.customer)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.customer = data;
          this.default = cloneDeep(data);
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: "Error" });
        });
    },
    onReset(props) {
      console.func(
        "pages/admins/orders/CustomerPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      setTimeout(() => {
        Object.assign(this.customer, cloneDeep(this.default));
        this.loaded = true;
      }, 1);
    },
    onCancel(props) {
      console.func(
        "pages/admins/orders/CustomerPage:methods.onCancel()",
        arguments
      );
      this.$router.back();
    },
  },
  async mounted() {
    this.show(this.id)
      .then((customer) => {
        this.customer = customer;
        this.default = cloneDeep(customer);
        this.loaded = true;
      })
      .catch((error) => {
        this.$core.error(error, { title: "Error" });
      });
  },
  computed: {
    id() {
      return this.$route.params.id;
    },
    action() {
      return this.$route.query.action;
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.customer) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.customer) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
