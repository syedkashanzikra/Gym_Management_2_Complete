<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="useTaskStore"
        :module="module"
        :actions="actions"
        :columns="columns"
        :rows="rows"
        :toolbar="toolbar"
        :filters="filters"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        @toolbar-clicked="toolbarClicked"
        @row-clicked="rowClicked"
        :no-data-label="$t('tasks.noData')"
        table-key="tasks"
      >
        <template v-slot:body-cell-id="props">
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            align="left"
            :to="{
              name: 'Single Task',
              params: {
                id: props.row.id,
              },
              query: {
                action: 'edit',
                title: props.row.subject,
              },
            }"
            :style="props.col.style"
          >
            <div class="ellipsis">{{ props.value }}</div>
          </base-btn>
          <base-btn
            v-show="props.row.message"
            class="q-ml-xs"
            size="md"
            link
            color="black"
            icon="fal fa-align-left"
            @click.stop
          >
            <q-popup-proxy>
              <q-banner style="width: 350px">
                <base-label>{{ $t("message") }}</base-label>
                <span v-html="props.row.message"></span>
              </q-banner>
            </q-popup-proxy>
          </base-btn>
        </template>
        <template v-slot:body-cell-status="props">
          <base-status :type="props.value" />
        </template>

        <template v-slot:body-cell-users="props">
          <div class="q-gutter-xs">
            <base-avatar
              v-for="(item, index) in props.row.users"
              :key="index"
              class="cursor-pointer"
              :user="item"
              size="30px"
            />
          </div>
        </template>
        <template v-slot:body-cell-is_archived="props">
          <q-checkbox
            @update:model-value="changeArchived(props.row)"
            size="sm"
            dense
            :model-value="props.row.is_archived"
            color="green"
          />
        </template>
        <template v-slot:body-cell-last_reply="props">
          <base-btn
            v-show="props.row.last_reply"
            class="q-mr-xs"
            size="md"
            link
            color="black"
            icon="fal fa-align-left"
            @click.stop
          >
            <q-popup-proxy>
              <q-banner style="width: 350px">
                <base-label>{{ $t("lastMessage") }}</base-label>
                <span v-html="$core.nl2br(props.row.last_reply.message)"></span>
              </q-banner>
            </q-popup-proxy>
          </base-btn>
          <span>{{ props.value }}</span>
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useTaskStore } from "stores/task";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "last_reply",
        descending: true,
        page: 1,
        filter: "",
        status: this.$route.meta.status,
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
      useTaskStore: useTaskStore(),
    };
  },
  methods: {
    ...mapActions(useTaskStore, ["get", "changeArchived"]),
    onRequest(props) {
      console.func("pages/core/tasks/TasksPage:methods.onRequest()", arguments);
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
        "pages/core/tasks/TasksPage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/core/tasks/TasksPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/core/tasks/TasksPage:methods.rowClicked()",
        arguments
      );
      this.$router.push({
        name: "Single Task",
        params: {
          id: row.id,
        },
        query: {
          action: "edit",
          title: row.subject,
        },
      });
    },
  },
  computed: {
    ...mapState(useTaskStore, [
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
