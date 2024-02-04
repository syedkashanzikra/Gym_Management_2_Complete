<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="useMemberStore"
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
        :no-data-label="$t('members.noData')"
        :visible-columns="visibleColumns"
        table-key="members"
        selection="multiple"
        v-model:selected="selected"
      >
        <template v-slot:body-cell-name="props">
          <i
            :class="`q-mr-sm q-icon fas fa-circle rag-${
              props.row.rag || 'white'
            }`"
            style="font-size: 8px"
            aria-hidden="true"
            role="presentation"
          ></i>
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            :to="{
              name: 'Single Member',
              params: {
                id: props.row.id,
              },
              query: {
                action: 'edit',
              },
            }"
          >
            {{ props.value }}
          </base-btn>
        </template>
        <template v-slot:body-cell-subscription="props">
          <base-status v-if="props.value" :type="props.value?.stripe_status" />
        </template>
        <template v-slot:body-cell-status="props">
          <base-status :type="props.value" />
        </template>
        <template v-slot:body-cell-is_active="props">
          <q-toggle
            @update:model-value="changeActive(props.row)"
            size="sm"
            dense
            :model-value="props.row.is_active"
            color="green"
          />
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useMemberStore } from "stores/member";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "id",
        descending: true,
        page: 1,
        filter: "",
        status: true,
        rag: null,
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
        includes: [
          "lastUpdate.admin",
          "lastNsBookings",
          "lastLateCancellation",
        ],
        override: ["includes"],
      },
      selected: [],
      useMemberStore: useMemberStore(),
    };
  },
  methods: {
    ...mapActions(useMemberStore, ["get", "changeActive"]),
    onRequest(props) {
      console.func("admins/members/MembersPage:methods.onRequest()", arguments);
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
        "admins/members/MembersPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "admins/members/MembersPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "admins/members/MembersPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Member",
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
    ...mapState(useMemberStore, [
      "actions",
      "rows",
      "columns",
      "module",
      "toolbar",
      "filters",
    ]),
    visibleColumns() {
      return this.columns
        .filter(
          (item) =>
            (item.status && item.status.includes(this.pagination.status)) ||
            !item.status
        )
        .map((item) => item.name);
    },
    permissions() {
      return [];
    },
  },
};
</script>
