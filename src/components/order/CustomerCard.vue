<template>
  <base-section title="Customer" no-row>
    <template v-slot:action>
      <q-btn
        v-if="hasCustomer && edit"
        @click="$refs.customer.clear()"
        round
        dense
        flat
        color="primary"
        icon="close"
      />
    </template>
    <div class="q-gutter-y-md">
      <user-select
        v-show="!hasCustomer && edit"
        ref="customer"
        placeholder="Select or create customer"
        v-model="order.customer"
        prevent-defaults
        dense
        outlined
        use-input
        hide-no-option
        option-label="name"
        new-value-mode="add-unique"
        @new-value="onCreateCustomer"
        no-option-message="No customer found."
        icon="far fa-search"
        @update:model-value="onSelectCustomer"
      >
        <template v-slot:button="{ onCreate }">
          <q-separator class="q-my-sm" />
          <q-item dense class="q-pa-none">
            <q-item-section>
              <q-btn
                flat
                no-caps
                class="add-button"
                icon="fad fa-plus-circle"
                label="Create new customer"
                align="left"
                @click="onCreate"
              />
            </q-item-section>
          </q-item>
        </template>
      </user-select>
      <template v-if="hasCustomer">
        <div>
          <base-btn
            link
            size="15px"
            padding="0"
            tag="a"
            :to="{
              name: 'Customer',
              params: {
                id: order.customer.id,
              },
            }"
          >
            {{ order.customer.name }}
          </base-btn>
          <div v-if="order.customer.created_at" class="text-grey">
            Created at: {{ order.customer.created_at }}
          </div>
          <div v-if="order.customer.note" class="">
            {{ order.customer.note }}
          </div>
        </div>
      </template>

      <div>
        <div class="row flex-center">
          <div class="text-bold">CONTACT INFORMATION</div>
          <q-space />
          <base-btn v-if="edit" link label="Edit" @click="onEditContact" />
        </div>
        <div v-if="hasContact">
          <div class="row flex-start">
            <base-btn link padding="0" :label="order.contact.email" />
            <q-btn
              @click="$core.copyToClipboard(order.contact.email)"
              flat
              dense
              round
              padding="0"
              size="xs"
              icon="fas fa-clipboard"
            >
              <base-tooltip>Copy email</base-tooltip>
            </q-btn>
          </div>
          <div>
            {{ order.contact.phone_number }}
          </div>
        </div>
        <div v-else class="text-grey">No contact info provided</div>
      </div>
      <template v-if="hasBillingAddress">
        <div class="billing-address">
          <div class="row flex-center">
            <div class="text-bold">BILLING ADDRESS</div>
            <q-space />
            <q-btn
              @click="$core.copyToClipboard(order.billing_address)"
              flat
              dense
              padding="0"
              size="xs"
              icon="fas fa-clipboard"
              class="q-mr-xs"
            >
              <base-tooltip>Copy address</base-tooltip>
            </q-btn>
            <base-btn
              v-if="edit"
              link
              padding="0"
              label="Edit"
              @click="
                onEditAddress({
                  key: 'billing_address',
                  title: 'Edit billing address',
                })
              "
            />
          </div>
          <address-card
            class="q-mt-sm"
            ref="billing"
            v-bind="order.billing_address"
          />
        </div>
      </template>
    </div>
  </base-section>
</template>

<script>
import { cloneDeep } from "lodash";
import ChangeCustomerContact from "components/order/ChangeCustomerContact.vue";
import AddressEdit from "src/components/address/AddressEdit.vue";
import UserSelect from "components/UserSelect.vue";
import AddressCard from "src/components/address/AddressCard.vue";
import AddCustomerDialog from "../customer/AddCustomerDialog.vue";

export default {
  components: { UserSelect, AddressCard },
  props: {
    modelValue: {
      required: true,
      type: Object,
    },
    edit: Boolean,
    view: Boolean,
  },
  data() {
    return {
      order: cloneDeep(this.modelValue),
      default: cloneDeep(this.modelValue),
    };
  },
  emits: ["update:model-value", "update:customer", "update:address"],
  methods: {
    onCreateCustomer(val, done) {
      console.func(
        "components/order/CustomerCard:methods.onCreateCustomer()",
        arguments
      );
      const name = val.trim().split(" ");
      this.$q
        .dialog({
          component: AddCustomerDialog,
          componentProps: {
            modelValue: {
              first_name: name[0],
              last_name: name[1],
            },
          },
        })
        .onOk((payload) => {
          done({
            first_name: payload.first_name,
            last_name: payload.last_name,
            phone_number: payload.phone_number,
            email: payload.email,
            address: { ...payload },
            collect_tax: true,
            email_marketing: true,
          });
        });
    },
    onEditContact(props) {
      console.func(
        "components/order/CustomerCard:methods.onEditContact()",
        arguments
      );
      this.$q
        .dialog({
          component: ChangeCustomerContact,
          componentProps: {
            modelValue: Object.assign(this.order.contact || {}, {
              update_customer_profile: Boolean(
                this.order.contact && this.order.contact.update_customer_profile
              ),
            }),
            has_customer: this.hasCustomer,
          },
        })
        .onOk((payload) => {
          console.func(
            "components/order/CustomerCard:methods.onEditContact.onOk()",
            payload
          );
          this.order.contact = payload;
          this.$emit("update:model-value", this.order);
        });
    },
    onSelectCustomer(customer) {
      console.func(
        "components/order/CustomerCard:methods.onSelectCustomer()",
        arguments
      );
      if (customer) {
        this.order.collect_tax = customer.collect_tax;
        const { email, phone_number } = customer;
        if (!this.default.contact && (email || phone_number)) {
          this.order.contact = {
            email,
            phone_number,
          };
        }
        if (!this.default.billing_address) {
          const { first_name, last_name, phone_number, address } = customer;
          this.order.billing_address = {
            ...address,
            first_name,
            last_name,
            phone_number,
          };
        }
      } else {
        this.order.collect_tax = this.default.collect_tax;
      }
      this.$emit("update:model-value", this.order);
      this.$emit("update:customer", customer);
    },
    onEditAddress({ title, key }) {
      console.func(
        "components/order/CustomerCard:methods.onEditAddress()",
        arguments
      );
      this.$q
        .dialog({
          component: AddressEdit,
          componentProps: {
            modelValue: this.order[key] ?? {},
            title: title,
          },
        })
        .onOk(({ address }) => {
          this.order[key] = address;
          this.$emit("update:model-value", this.order);
          this.$emit("update:address", address);
        });
    },
  },
  computed: {
    hasCustomer() {
      return Boolean(this.order.customer);
    },
    hasBillingAddress() {
      return Boolean(this.order.billing_address);
    },
    hasContact() {
      return Boolean(this.order.contact);
    },
  },
  watch: {
    modelValue: {
      deep: true,
      handler(modelValue) {
        this.order = cloneDeep(modelValue);
      },
    },
  },
};
</script>
