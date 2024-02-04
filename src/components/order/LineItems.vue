<template>
  <q-table
    ref="product"
    class="no-hover"
    square
    flat
    dense
    :rows="line_items"
    :columns="columns"
    hide-pagination
    hide-header
    :rows-per-page-options="[0]"
    separator="none"
  >
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td v-for="col in props.cols" :key="col.name" :props="props">
          <template v-if="col.name === 'product'">
            <q-item class="q-pa-none" dense>
              <q-item-section avatar>
                <base-thumbnail :size="40" :media="props.row.thumbnail">
                  <q-badge
                    rounded
                    floating
                    color="orange-3"
                    text-color="black"
                    :label="props.row.remaining_quantity || props.row.quantity"
                  />
                </base-thumbnail>
              </q-item-section>
              <q-item-section avatar>
                <product-title v-bind="props.row" />
                <div v-if="props.row.variant_title">
                  {{ props.row.variant_title }}
                </div>
                <div>
                  {{ $core.money(props.row.price) }}
                </div>
              </q-item-section>
            </q-item>
          </template>
          <template v-else-if="col.name === 'quantity'">
            <q-input
              dense
              outlined
              v-model.number="props.row.new_quantity"
              input-style="text-align: center"
              type="number"
              min="0"
              :max="props.row.remaining_quantity"
              @update:model-value="onChangeQuantity"
            />
          </template>
          <template v-else>
            {{ col.value }}
          </template>
        </q-td>
      </q-tr>
      <q-tr v-if="reasons" v-show="props.row.new_quantity > 0" :props="props">
        <q-td colspan="100%">
          <div>Select a return reason</div>
          <q-select
            v-model="props.row.reason"
            dense
            outlined
            map-options
            options-dense
            emit-value
            :options="reasons"
          />
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script>
import ProductTitle from "components/product-title";
import { cloneDeep } from "lodash";

export default {
  components: { ProductTitle },
  props: {
    modelValue: Array,
    reasons: Array,
    columns: {
      type: Array,
      default: () => [
        {
          name: "product",
          align: "left",
          // label: 'PRODUCTS',
          field: "title",
          sortable: false,
        },
        {
          name: "weight",
          align: "left",
          // label: 'Weight',
          style: "width: 150px;",
          field: "weight",
          format: (weight) =>
            weight ? `${weight.value} ${weight.unit}` : "0 kg",
          sortable: false,
        },
        {
          name: "quantity",
          align: "right",
          // label: 'Quantity',
          style: "width: 150px;",
          field: "quantity",
          sortable: false,
        },
      ],
    },
  },
  emits: ["update:model-value"],
  data() {
    return {
      line_items: cloneDeep(this.modelValue).map((item) =>
        Object.assign(item, {
          new_quantity: item.new_quantity || 0,
          remaining_quantity: item.remaining_quantity || item.quantity,
        })
      ),
    };
  },
  methods: {
    onChangeQuantity() {
      console.func(
        "components/order/LineItems:methods.onChangeQuantity()",
        arguments
      );
      this.$core.debounce(() => {
        this.$emit("update:model-value", this.line_items);
      }, 500);
    },
  },
};
</script>
