<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        ref="order"
        store="order"
        :module="module"
        :columns="columns"
        :rows="rows"
        :actions="actions"
        :toolbar="toolbar"
        :filters="filters"
        :loading="loading"
        :pagination="pagination"
        v-model:selected="selected"
        @request="onRequest"
        selection="multiple"
        @action-clicked="actionClicked"
        @toolbar-clicked="toolbarClicked"
        @row-clicked="rowClicked"
      >
        <template v-slot:body-cell-name="props">
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            :to="{
              name: 'Single Order',
              params: {
                id: props.row.id,
              },
              query: {
                action: 'view',
              },
            }"
          >
            {{ props.value }}
          </base-btn>
        </template>
        <template v-slot:body-cell-status="props">
          <base-status v-for="item in props.value" :key="item" :type="item" />
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useOrderStore } from "src/stores/order";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      selected: [],
      pagination: {
        sortBy: "CREATED_AT_DESC",
        descending: false,
        page: 1,
        filter: "",
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
    };
  },
  methods: {
    ...mapActions(useOrderStore, ["get"]),
    onRequest(props) {
      console.func(
        "pages/admins/orders/OrdersPage:methods.onRequest()",
        arguments
      );
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      this.loading = true;

      this.get({
        ...props.pagination,
        customer: this.$route.query?.customer,
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
    async actionClicked(action, row) {
      console.func(
        "pages/admins/orders/OrdersPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/admins/orders/OrdersPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/admins/orders/OrdersPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Order",
        params: {
          id: row.id,
        },
        query: {
          action: "view",
        },
      });
    },
  },
  computed: {
    ...mapState(useOrderStore, [
      "actions",
      "rows",
      "columns",
      "module",
      "toolbar",
      "filters",
    ]),
  },
};
</script>
