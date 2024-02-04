<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="useGuestPassStore"
        :module="module"
        :columns="getColumns"
        :rows="rows"
        :actions="actions"
        :toolbar="getToolbar"
        :filters="filters"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        @action-clicked="actionClicked"
        @toolbar-clicked="toolbarClicked"
        @row-clicked="rowClicked"
        :no-data-label="$t('guestPasses.noData')"
        no-permissions
      >
        <template v-slot:body-cell-name="props">
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            :to="{
              name: 'Single Guest Pass',
              params: {
                id: props.row.id,
                title: props.row.name,
              },
              query: {
                action: 'edit',
              },
            }"
          >
            {{ props.value }}
          </base-btn>
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useGuestPassStore } from "stores/guest-pass";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "name",
        descending: false,
        page: 1,
        filter: "",
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        active: true,
        loaded: false,
      },
      useGuestPassStore: useGuestPassStore(),
    };
  },
  methods: {
    ...mapActions(useGuestPassStore, ["get"]),
    onRequest(props) {
      console.func(
        "admins/guest-passes/GuestPassesPage:methods.onRequest()",
        arguments
      );
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      this.loading = true;

      this.get({
        ...props.pagination,
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
        "admins/guest-passes/GuestPassesPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "admins/guest-passes/GuestPassesPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "admins/guest-passes/GuestPassesPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Guest Pass",
        params: {
          id: row.id,
          title: row.name,
        },
        query: {
          action: "edit",
        },
      });
    },
  },
  computed: {
    ...mapState(useGuestPassStore, [
      "actions",
      "rows",
      "columns",
      "module",
      "toolbar",
      "filters",
    ]),
    permissions() {
      return [];
    },
    getColumns() {
      return this.columns.filter((item) => item.guard.includes(this.guard));
    },
    guard() {
      return this.$route.meta.guard;
    },
    getToolbar() {
      return this.toolbar.filter((item) => item.guard.includes(this.guard));
    },
  },
};
</script>
