<template>
  <q-page padding>
    <base-page-toolbar v-if="loaded && !creating" class="q-mb-md">
      <template v-slot:title>
        {{ order.formated_id }}
        <base-status
          v-for="item in order.status"
          :key="item.id"
          :type="item.label"
        />
        <div class="text-subtitle2">
          {{ order.created_at }} from {{ order.source }}
        </div>
      </template>
      <template v-slot:actions>
        <base-item
          @click="onEdit(order)"
          v-show="order.can_edit && view"
          icon="fas fa-edit"
          label="Edit"
        />
        <!-- <base-item
          v-show="order.has_due"
          @click="$refs.billing.onSendInvoice()"
          icon="fas fa-file-invoice"
          label="Send invoice"
        /> -->
        <base-item
          v-show="order.id"
          @click="receipt(order.id)"
          icon="fas fa-receipt"
          label="Print receipt"
        />
        <base-item @click="onDuplicate" icon="fas fa-clone" label="Duplicate" />
        <base-item
          @click="onCancelOrder"
          class="text-negative"
          v-show="!order.is_cancelled"
          icon="fas fa-times"
          label="Cancel order"
        />
      </template>
    </base-page-toolbar>
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
            <products-card
              :view="!edit"
              :creating="creating"
              :can-edit="order.can_edit"
              v-model="order.line_items"
              :location="order.location"
              @update:model-value="onCalculator"
              @changed-location="onChangeLocation"
            />
            <base-section title="Billing details" no-row>
              <billing-details
                :view="!edit"
                ref="billing"
                has-payment
                v-model="order"
                :has-due="order.has_due"
                @marked-paid="onMarkedPaid"
                @update:model-value="onCalculator"
              />
            </base-section>
            <base-section
              v-if="!creating"
              title="Activity"
              description="With activity, you can view detailed histories and write notes and comments for order."
              body-class="q-px-lg"
              no-row
            >
              <q-timeline class="comments" color="secondary">
                <base-note-card
                  :module-action="comment"
                  :module-id="order.id"
                  class="comment"
                  creating
                  no-rag
                  @create="onCreateComment"
                />
                <base-note-card
                  class="comment"
                  v-for="(comment, index) in order.logs"
                  :key="comment.id"
                  :date_time="comment.created_at_human"
                  @update="onUpdateComment"
                  @remove="onRemoveComment(index)"
                  :message="comment.message"
                  :comment="comment"
                  :user="comment.admin"
                  module="log/Update"
                  :module-id="order.id"
                />
              </q-timeline>
            </base-section>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <div class="q-gutter-md">
            <customer-card
              :edit="edit"
              :view="view"
              v-model="order"
              @update:address="onCalculator"
              @update:customer="onCalculator"
            />
            <base-section title="Notes" no-row>
              <div class="note">
                <q-input dense outlined v-model="order.note" type="text" />
                <div class="q-mt-sm">
                  These notes are private and won't be shared with the customer.
                </div>
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
// import CommentCard from "components/CommentCard.vue";
// import ProductsCard from "components/order/ProductsCard.vue";
import ProductsCard from "components/order/UnfulfillmentCard.vue";
import BillingDetails from "components/order/BillingDetails.vue";
import CustomerCard from "components/order/CustomerCard.vue";
import CancelOrder from "components/order/CancelOrder.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { useOrderStore } from "src/stores/order";

const order = {
  source: "Draft Order",
  customer: null,
  contact: null,
  billing_address: null,
  collect_tax: true,
  line_items: [],
  tax_lines: [],
};

export default {
  components: {
    SkeletonSinglePage,
    // CommentCard,
    ProductsCard,
    BillingDetails,
    CustomerCard,
  },
  data() {
    return {
      loaded: false,
      submited: false,
      default: cloneDeep(order),
      order: cloneDeep(order),
      options: [],
    };
  },
  methods: {
    ...mapActions(useOrderStore, [
      "show",
      "store",
      "update",
      "calculator",
      "markAsPaid",
      "duplicate",
      "comment",
      "receipt",
    ]),
    onLoad() {
      console.func("pages/admins/orders/OrderPage:methods.onLoad()", arguments);
      // this.loaded = false;
      this.show(this.id)
        .then((data) => {
          this.onAssign(data);
          this.loaded = true;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    },
    onSubmit(args) {
      console.func(
        "pages/admins/orders/OrderPage:methods.onSubmit()",
        arguments
      );
      const { payment_method } = args || {};
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method({ ...this.order, payment_method })
        .then(async ({ data, message }) => {
          this.submited = false;
          await this.onAssign(data);
          await this.$router.push({
            name: "Single Order",
            params: {
              id: data.id,
            },
            query: {
              action: "view",
            },
          });
          this.$q.notify(message);
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: "Error" });
        });
    },
    onReset() {
      console.func(
        "pages/admins/orders/OrderPage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      setTimeout(() => {
        this.onAssign(this.default);
        this.loaded = true;
      }, 300);
    },
    onAssign(data = order) {
      console.func(
        "pages/admins/orders/OrderPage:methods.onAssign()",
        arguments
      );
      this.order = cloneDeep(data);
      this.default = cloneDeep(data);
    },
    onCancel(props) {
      console.func(
        "pages/admins/orders/OrderPage:methods.onCancel()",
        arguments
      );
      this.$router.back();
    },
    onCreateComment(props) {
      console.func(
        "pages/admins/orders/OrderPage:methods.onCreateComment()",
        arguments
      );
      this.order.logs.splice(0, 0, props);
      this.default = cloneDeep(this.order);
    },
    onUpdateComment(props) {
      console.func(
        "pages/admins/orders/OrderPage:methods.onUpdateComment()",
        arguments
      );
      this.default = cloneDeep(this.order);
    },
    onRemoveComment(index) {
      console.func(
        "pages/admins/orders/OrderPage:methods.onRemoveComment()",
        arguments
      );
      this.order.logs.splice(index, 1);
      this.default = cloneDeep(this.order);
    },
    onCalculator() {
      console.func(
        "pages/admins/orders/OrderPage:methods.onCalculator()",
        arguments
      );
      this.calculator(
        Object.assign(cloneDeep(this.order), {
          logs: null,
        })
      )
        .then((order) => {
          Object.assign(this.order, order);
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    onChangeLocation(payload) {
      console.func("pages/orders/single:methods.onChangeLocation()", arguments);
      this.order.location = cloneDeep(payload);
      this.submited = true;
      this.update(this.order)
        .then(({ message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.default = cloneDeep(this.order);
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: "Error" });
        });
    },
    onMarkedPaid(payload) {
      console.func("pages/orders/single:methods.onMarkedPaid()", arguments);
      if (this.creating) {
        this.onSubmit({ payment_method: payload });
        return false;
      }
      this.markAsPaid({
        moduleId: this.order.id,
        payment_method: payload,
      })
        .then(({ message }) => {
          this.$q.notify(message);
          this.onLoad();
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    onDuplicate() {
      console.func("pages/orders/single:methods.onDuplicate()", arguments);
      this.duplicate({
        moduleId: this.order.id,
      })
        .then(({ data, message }) => {
          this.$q.notify(message);
          this.$router.push({
            name: "Single Order",
            params: {
              id: data.id,
            },
            query: {
              action: "edit",
            },
          });
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    onEdit(props) {
      console.func("pages/orders/single:methods.onEdit()", arguments);
      this.$router.push({
        name: "Single Order",
        params: {
          id: props.id,
        },
        query: {
          action: "edit",
        },
      });
    },
    onCancelOrder() {
      console.func("pages/orders/single:methods.onCancelOrder()", arguments);
      this.$q
        .dialog({
          component: CancelOrder,
          componentProps: {
            modelValue: this.order,
          },
        })
        .onOk((payload) => {
          this.onAssign(payload);
        });
    },
  },
  mounted() {
    if (!this.creating) {
      this.onLoad();
    } else {
      this.loaded = true;
    }
  },
  computed: {
    id() {
      return this.$route.params.id;
    },
    action() {
      return this.$route.query.action;
    },
    view() {
      return this.action !== "edit";
    },
    edit() {
      return ["add", "edit"].includes(this.action) || this.id === "add";
    },
    creating() {
      return this.id === "add";
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.order) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.order) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
