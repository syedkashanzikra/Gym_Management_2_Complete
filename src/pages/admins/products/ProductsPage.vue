<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="useProductStore"
        :module="module"
        :columns="columns"
        :rows="rows"
        :actions="actions"
        :toolbar="toolbar"
        :filters="filters"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        @action-clicked="actionClicked"
        @toolbar-clicked="toolbarClicked"
        @row-clicked="rowClicked"
        :no-data-label="$t('products.noData')"
        table-key="products"
        v-model:selected="selected"
      >
        <template v-slot:body-cell-title="props">
          <q-item class="q-pa-none" dense>
            <q-item-section avatar>
              <base-thumbnail :size="40" :media="props.row.thumbnail" />
            </q-item-section>
            <q-item-section avatar>
              <base-btn
                @click.stop
                link
                :style="props.col.style"
                tag="a"
                :to="{
                  name: 'Single Product',
                  params: {
                    id: props.row.id,
                  },
                  query: {
                    action: 'edit',
                  },
                }"
                align="left"
              >
                <div class="ellipsis">
                  {{ props.value }}
                </div>
              </base-btn>
            </q-item-section>
          </q-item>
        </template>
        <template v-slot:body-cell-status="props">
          <base-status :type="props.row.status" />
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useProductStore } from "src/stores/product";
export default {
  data() {
    return {
      loading: false,
      loaded: false,
      selected: [],
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
      useProductStore: useProductStore(),
    };
  },
  methods: {
    ...mapActions(useProductStore, ["get"]),
    onRequest(props) {
      console.func(
        "pages/admins/products/ProductsPage:methods.onRequest()",
        arguments
      );
      const {
        page,
        rowsPerPage,
        sortBy,
        descending,
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
        direction: descending ? "desc" : "asc",
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
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    async actionClicked(action, row) {
      console.func(
        "pages/admins/products/ProductsPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/admins/products/ProductsPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/admins/products/ProductsPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Product",
        params: {
          id: row.id,
        },
        query: {
          action: "edit",
        },
      });
    },
  },
  computed: {
    ...mapState(useProductStore, [
      "rows",
      "actions",
      "module",
      "columns",
      "toolbar",
      "filters",
    ]),
  },
  watch: {
    $route() {
      this.onRequest({
        pagination: this.pagination,
      });
    },
  },
};
</script>
