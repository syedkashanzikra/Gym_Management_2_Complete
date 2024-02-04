<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="useAnnouncementStore"
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
        :no-data-label="$t('announcements.noData')"
        table-key="announcements"
      >
        <template v-slot:body-cell-date="props">
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            :to="{
              name: 'Single Announcement',
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
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useAnnouncementStore } from "stores/announcement";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "date",
        descending: true,
        page: 1,
        filter: "",
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
      useAnnouncementStore: useAnnouncementStore(),
    };
  },
  methods: {
    ...mapActions(useAnnouncementStore, ["get"]),
    onRequest(props) {
      console.func(
        "admins/announcements/AnnouncementsPage:methods.onRequest()",
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
        "admins/announcements/AnnouncementsPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "admins/announcements/AnnouncementsPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "admins/announcements/AnnouncementsPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Announcement",
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
    ...mapState(useAnnouncementStore, [
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
  },
};
</script>
