<template>
  <q-page padding>
    <base-section flat bordered no-row>
      <base-table
        :columns="columns"
        :module="module"
        :store="useFinanceMembershipStore"
        :rows="rows"
        :toolbar="toolbar"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        @row-clicked="rowClicked"
        :no-data-label="$t('membership.noData')"
        no-permissions
        table-key="finance/membership"
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
          <q-btn
            class="q-ml-md"
            size="9px"
            dense
            round
            flat
            v-if="props.row.note"
            icon="fad fa-memo-pad"
            @click.stop
          >
            <q-popup-proxy>
              <q-banner style="width: 350px">
                <base-label>{{ $t("specialNote") }}</base-label>
                <span v-html="props.row.note.replace(/\r?\n/g, '<br />')" />
              </q-banner>
            </q-popup-proxy>
          </q-btn>
        </template>
        <template v-slot:body-cell-price="props">
          <base-btn
            @click.stop
            link
            size="12px"
            tag="a"
            :to="{
              name: 'Single Plan',
              params: {
                id: props.row?.price?.plan_id,
              },
              query: {
                action: 'edit',
              },
            }"
          >
            {{ props.value }}
          </base-btn>
        </template>
        <template v-slot:body-cell-checked="props">
          <q-checkbox
            @click.stop
            dense
            :disable="props.row.loading"
            :model-value="props.row.checked"
            @update:model-value="checked(props.row)"
            :label="props.value"
          />
        </template>
        <template v-slot:body-cell-has_parq="props">
          <q-checkbox
            @click.stop
            dense
            :model-value="props.row.has_parq"
            :label="props.value"
          />
        </template>
        <template v-slot:body-cell-has_avatar="props">
          <q-checkbox
            @click.stop
            dense
            :model-value="props.row.has_avatar"
            :label="props.value"
          />
        </template>
        <template v-slot:body-cell-request_parq="props">
          <q-checkbox
            @click.stop
            dense
            :disable="props.row.loading"
            :model-value="props.row.request_parq"
            @update:model-value="requestParq(props.row)"
            :label="props.value"
          />
        </template>
        <template v-slot:body-cell-request_avatar="props">
          <q-checkbox
            @click.stop
            dense
            :disable="props.row.loading"
            :model-value="props.row.request_avatar"
            @update:model-value="requestAvatar(props.row)"
            :label="props.value"
          />
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import MemberDialog from "components/MemberDialog.vue";
import { useFinanceMembershipStore } from "stores/finance-membership";

export default {
  data() {
    return {
      loading: false,
      loaded: false,
      stats: {},
      pagination: {
        sortBy: "id",
        descending: true,
        page: 1,
        filter: "",
        type: null,
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
        includes: ["price"],
        override: ["includes"],
      },
      useFinanceMembershipStore: useFinanceMembershipStore(),
    };
  },
  methods: {
    ...mapActions(useFinanceMembershipStore, [
      "checked",
      "get",
      "requestAvatar",
      "requestParq",
    ]),
    onRequest(props) {
      console.func(
        "pages/admins/finance/MembershipPage:methods.onRequest()",
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

          // set stats
          this.stats = meta;

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
    rowClicked(evt, row) {
      console.func(
        "pages/admins/finance/AdminPage:methods.rowClicked()",
        arguments
      );
      this.$q.dialog({
        component: MemberDialog,
        componentProps: {
          title: row.name,
          id: row.id,
        },
      });
    },
  },
  computed: {
    ...mapState(useFinanceMembershipStore, [
      "columns",
      "toolbar",
      "rows",
      "module",
    ]),
    permissions() {
      return [];
    },
  },
};
</script>
