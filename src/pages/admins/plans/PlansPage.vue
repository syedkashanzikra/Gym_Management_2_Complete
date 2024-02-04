<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :store="usePlanStore"
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
        :no-data-label="$t('plans.noData')"
        table-key="plans"
      >
        <template v-slot:body-cell-label="props">
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            :to="{
              name: 'Single Plan',
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
        <template v-slot:body-cell-prices="props">
          <span class="text-grey-7" v-if="props.row.is_custom">
            {{ $core.money(props.row.custom_fee) }} every
            {{ props.row.interval_count }} {{ props.row.interval }}s
          </span>
          <base-btn
            v-else
            class="text-grey-7"
            @click.stop
            :label="$t('prices', { count: 2 })"
            link
          >
            <q-menu>
              <q-list dense style="min-width: 100px">
                <q-item class="text-bold bg-grey">
                  <q-item-section>Prices</q-item-section>
                </q-item>
                <q-separator />
                <q-item>
                  <q-item-section>
                    {{ $core.money(props.row.monthly_fee) }}/month
                  </q-item-section>
                </q-item>
                <q-separator />
                <q-item>
                  <q-item-section>
                    {{ $core.money(props.row.yearly_fee) }}/year
                  </q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </base-btn>
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
import { usePlanStore } from "stores/plan";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "label",
        descending: false,
        page: 1,
        filter: "",
        active: true,
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
      },
      usePlanStore: usePlanStore(),
    };
  },
  methods: {
    ...mapActions(usePlanStore, ["get", "changeActive"]),
    onRequest(props) {
      console.func("admins/plans/PlansPage:methods.onRequest()", arguments);
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
      console.func("admins/plans/PlansPage:methods.actionClicked()", arguments);
    },
    async toolbarClicked(action, row) {
      console.func(
        "admins/plans/PlansPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func("admins/plans/PlansPage:methods.rowClicked()", arguments);
      this.$router.push({
        name: "Single Plan",
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
    ...mapState(usePlanStore, [
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
