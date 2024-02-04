<template>
  <base-table
    :module="module"
    :columns="columns"
    :rows="rows"
    :loading="loading"
    :pagination="pagination"
    @request="onRequest"
    :no-data-label="$t('members.noData')"
    no-permissions
    :no-responsive="noResponsive"
    hide-top
    ref="members"
  >
    <template v-slot:header-cell-id="props">
      {{ props.col.label }}
      <q-btn
        flat
        round
        color="primary"
        icon="fas fa-file-csv"
        @click="onExport"
        dense
        size="sm"
      />
    </template>
    <template v-slot:body-cell-name="props">
      <i
        :class="`q-mr-sm q-icon fas fa-circle rag-${props.row.rag || 'white'}`"
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
  </base-table>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useMemberStore } from "stores/member";

export default {
  props: {
    year: [String, Number],
    month: [String, Number],
    day: [String, Number],
    type: String,
    noResponsive: Boolean,
  },
  data() {
    return {
      loading: false,
      loaded: false,
      pagination: {
        sortBy: "id",
        descending: true,
        page: 1,
        filter: "",
        advancedFilter: {},
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
        year: this.year,
        month: this.month,
        day: this.day,
        type: this.type,
        includes: ["price"],
        override: ["includes"],
      },
      columns: [
        {
          name: "id",
          align: "left",
          label: this.$t("label.memberID"),
          field: "member_id_formated",
          style: "width: 40px",
          sortable: true,
        },
        {
          name: "name",
          align: "left",
          label: this.$t("label.name"),
          field: "name",
          style: "width: 40px",
          sortable: true,
        },
        {
          name: "price",
          align: "left",
          label: this.$t("label.plan"),
          field: (row) => row?.price?.label,
          style: "width: 250px; white-space: normal",
          sortable: true,
        },
        {
          name: "starts_at",
          align: "left",
          label: this.$t("label.startsAt"),
          style: "width: 40px",
          field: "starts_at",
          format: (val) => this.$core.formatDate(val, "DD MMM, YYYY"),
          sortable: true,
        },
        {
          name: "ends_at",
          align: "left",
          label: this.$t("label.endsAt"),
          style: "width: 40px",
          field: "ends_at",
          format: (val) => this.$core.formatDate(val, "DD MMM, YYYY"),
          sortable: true,
        },
        {
          name: "renewal_fee",
          align: "left",
          label: this.$t("label.renewalFee"),
          field: "price",
          format: (val) => this.$core.money(val?.amount),
          style: "width: 40px",
          sortable: false,
        },
      ],
      rows: [],
    };
  },
  methods: {
    ...mapActions(useMemberStore, ["get"]),
    onRequest(props) {
      console.func("admins/members/MembersPage:methods.onRequest()", arguments);
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.loading = true;

      this.get({
        ...props.pagination,
        direction: descending ? "desc" : "asc",
      })
        .then(({ meta, data }) => {
          // clear out existing data and add new
          this.rows = data;
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
    onExport() {
      console.func("admins/members/MembersPage:methods.onExport()", arguments);
      this.$refs.members.onToolbarClicked({
        action: "export",
        type: "csv",
        method: this.get,
      });
    },
  },
  computed: {
    ...mapState(useMemberStore, ["module"]),
  },
};
</script>
