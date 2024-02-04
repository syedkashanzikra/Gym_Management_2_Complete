<template>
  <base-section no-row title="Last order placed">
    <template v-if="hasOrder">
      <q-item dense class="q-px-none">
        <q-item-section>
          <q-item-label class="q-gutter-y-xs">
            <div>
              Order {{ order.formated_id }}
              <base-status
                v-for="status in order.status"
                :key="status.id"
                :type="status.label"
              />
            </div>
            <div>
              {{ $core.money(order.grand_total) }} from {{ order.source }}
            </div>
            <div class="row q-col-gutter-md">
              <div
                v-for="item in order.line_items"
                :key="item"
                class="col-sm-3 col-xs-12"
              >
                <q-item class="q-pa-none">
                  <q-item-section avatar>
                    <base-thumbnail :media="item.thumbnail" size="40" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label caption lines="1">
                      {{ item.title }}
                    </q-item-label>
                  </q-item-section>
                </q-item>
              </div>
            </div>
          </q-item-label>
        </q-item-section>
        <q-item-section side top>
          <q-item-label caption>{{ order.created_at }}</q-item-label>
        </q-item-section>
      </q-item>
    </template>
    <template v-else>
      <div class="row flex-center" style="height: 250px">
        <div class="text-center">
          <div class="q-mb-sm">
            <q-icon color="grey" name="fad fa-shopping-cart" size="100px" />
          </div>
          <div class="q-my-sm text-h6">
            This customer hasn’t placed any orders.
          </div>
          <q-btn
            :to="{
              name: 'Single Order',
              params: { id: 'add' },
              query: {
                customer: customer,
              },
            }"
            no-caps
            color="primary"
            label="Create order"
          />
        </div>
      </div>
    </template>
    <template v-if="hasOrder" v-slot:bottom>
      <div class="text-right">
        <div class="q-gutter-md">
          <base-btn
            :to="{
              name: 'Orders',
              query: {
                customer: customer,
              },
            }"
            link
            vertical-middel
            label="View all orders"
          />
          <q-btn
            :to="{
              name: 'Single Order',
              params: { id: 'add' },
              query: {
                customer: customer,
              },
            }"
            color="primary"
            label="Create order"
            no-caps
          />
        </div>
      </div>
    </template>
  </base-section>
</template>

<script>
export default {
  props: {
    order: {
      type: Object,
      required: false,
    },
    customer: {
      type: Number,
      required: true,
    },
  },
  computed: {
    hasOrder() {
      return Boolean(this.order);
    },
  },
};
</script>
