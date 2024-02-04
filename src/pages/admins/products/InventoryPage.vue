<template>
  <q-page padding>
    <base-table
      :store="useInventoryStore"
      :module="module"
      :columns="columns"
      :rows="rows"
      :toolbar="toolbar"
      :filters="filters"
      :loading="loading"
      :pagination="pagination"
      @request="onRequest"
      :no-data-label="$t('label.noInventoryAvaialble')"
    >
      <template v-slot:body-cell-product="props">
        <q-item class="q-pa-none" dense>
          <q-item-section avatar>
            <base-thumbnail :size="40" :media="props.row.thumbnail" />
          </q-item-section>
          <q-item-section avatar>
            <base-btn
              @click.stop
              link
              size="12px"
              tag="a"
              :to="{
                name: 'Products Variant',
                params: {
                  product_id: props.row.variant.product_id,
                  variant_id: props.row.variant_id,
                },
                query: {
                  action: 'edit',
                },
              }"
            >
              {{ props.value }}
            </base-btn>
            <div v-if="props.row.variant">{{ props.row.variant?.title }}</div>
          </q-item-section>
        </q-item>
      </template>
      <template v-slot:body-cell-edit_quantity="props">
        <div @click.stop class="edit-quantity rounded-borders row">
          <div class="qtype col-auto">
            <q-select
              dense
              borderless
              v-model="props.row.adjust_type"
              map-options
              emit-value
              :options="[
                {
                  label: 'Set',
                  value: 'set',
                },
                {
                  label: 'Add',
                  value: 'add',
                },
              ]"
            />
          </div>
          <div class="qvalue col">
            <q-input
              dense
              borderless
              v-model="props.row.adjust_value"
              @update:model-value="props.row.has_changed = true"
              type="number"
              input-style="text-align: center"
            />
          </div>
          <div class="qbutton col-auto">
            <q-btn
              flat
              :disable="!props.row.has_changed"
              :color="props.row.has_changed ? `primary` : `grey`"
              icon="fal fa-save"
              style="height: 100%"
              @click="onUpdate(props.row)"
            />
          </div>
        </div>
      </template>
      <template v-slot:body-cell-available="props">
        <span v-html="props.value"></span>
        <template v-if="props.row.available !== getAvailable(props.row)">
          <span class="q-ml-xs text-bold">></span>
          <span class="q-ml-xs no-inventory">
            {{ getAvailable(props.row) }}
          </span>
        </template>
      </template>
    </base-table>
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { useInventoryStore } from "src/stores/product/inventory";
import { mapActions, mapState } from "pinia";
export default {
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "TITLE_ASC",
        descending: false,
        page: 1,
        filter: "",
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
      useInventoryStore: useInventoryStore(),
    };
  },
  methods: {
    ...mapActions(useInventoryStore, ["get", "update"]),
    onRequest(props) {
      console.func(
        "pages/admins/products/InventoryPage:methods.onRequest()",
        arguments
      );
      const {
        page,
        rowsPerPage,
        sortBy,
        descending,
        location,
        vendor,
        category,
        tag,
        collection,
      } = props.pagination;

      this.pagination = props.pagination;
      this.loading = true;

      this.get({
        ...props.pagination,
        vendor: vendor?.id,
        category: category?.id,
        tag: tag?.id,
        collection: collection?.id,
        location: location?.id,
        has_adjust: true,
        descending: descending ? "desc" : "asc",
      })
        .then(({ meta }) => {
          // clear out existing data and add new
          // this.rows = this.tableData;
          // update rowsCount with appropriate value
          this.pagination.rowsNumber = meta.total;

          // don't forget to update local pagination object
          this.pagination.page = page;
          this.pagination.rowsPerPage = rowsPerPage;
          this.pagination.sortBy = sortBy;
          this.pagination.descending = descending;
          this.pagination.loaded = true;
          this.pagination.from = meta.from;
          this.pagination.to = meta.to;

          // ...and turn of loading indicator
          this.loading = false;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    },
    onUpdate(props) {
      console.func(
        "pages/admins/products/InventoryPage:methods.Update()",
        arguments
      );
      const inventory = cloneDeep(props);
      inventory.available =
        inventory.adjust_type === "add"
          ? parseInt(inventory.available) + parseInt(inventory.adjust_value)
          : inventory.adjust_value;
      inventory.has_adjust = true;
      this.update(inventory)
        .then(({ data, message }) => {
          Object.assign(props, data);
          this.$q.notify(message);
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    getAvailable(props) {
      return props.adjust_type === "add"
        ? parseInt(props.available) + parseInt(props.adjust_value)
        : props.adjust_value;
    },
  },
  computed: {
    ...mapState(useInventoryStore, [
      "rows",
      "module",
      "columns",
      "toolbar",
      "filters",
    ]),
  },
};
</script>

<style lang="sass">
.edit-quantity
  width: 220px
  border: 1px solid $separator-color
  .qtype
    padding: 0 10px
    border-right: 1px solid $separator-color
  .qvalue
    padding: 0 10px
    border-right: 1px solid $separator-color
</style>
