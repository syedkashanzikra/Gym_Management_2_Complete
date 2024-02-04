<template>
  <base-table
    :toolbar="toolbar"
    :loading="loading"
    :pagination="pagination"
    @request="onRequest"
    no-permissions
    :columns="columns"
    :rows="rows"
    :no-data-label="$t('classes.noBookings')"
    :quick-search-placeholder="$t('placeholder.quickSearchClasses')"
    :visible-columns="visibleColumns"
    has-actions
  >
    <template v-slot:body-cell-class="props">
      <base-btn
        link
        v-if="props.row?.class?.has_description"
        :label="props.value"
      >
        <q-popup-proxy>
          <q-banner style="width: 350px">
            <base-label>{{ $t("description") }}</base-label>
            <span v-html="$core.nl2br(props.row.class.description)"></span>
            <div class="q-mt-sm" v-if="props.row.class.urls">
              <base-label>{{ $t("urls") }}</base-label>
              <div v-for="(item, index) in props.row.class.urls" :key="index">
                <a :href="item.url">{{ item.label || item.url }}</a>
              </div>
            </div>
          </q-banner>
        </q-popup-proxy>
      </base-btn>
      <span v-else>
        {{ props.value }}
      </span>
    </template>
    <template v-slot:actions="props">
      <q-btn
        color="primary"
        icon="fas fa-calendar-circle-minus"
        @click="onCancel(props)"
        size="sm"
        round
        flat
        v-show="props.row.booking.cancelable"
        :loading="props.row.loading"
      >
        <base-tooltip>{{ $t("cancelBooking") }}</base-tooltip>
      </q-btn>
    </template>
  </base-table>
</template>

<script>
import { mapActions } from "pinia";
import { useMemberStore } from "stores/member";
import { useClassScheduleStore } from "stores/member/class-schedule";

const getClasses = (row) => {
  let classes = [];
  if (row?.booking?.same_day_canceled) {
    classes.push("text-red text-bold");
  } else if (row?.booking?.is_stand_by) {
    classes.push("bg-amber-1");
  }
  return classes.join(" ");
};

export default {
  props: {
    moduleId: [Number, String],
    status: {
      type: String,
      default: "active",
    },
    noAction: Boolean,
  },
  data() {
    return {
      rows: [],
      loading: false,
      pagination: {
        sortBy: "date_at",
        descending: true,
        page: 1,
        filter: "",
        status: this.status,
        deleted: false,
        rowsPerPage: 10,
        rowsNumber: 10,
        loaded: false,
      },
    };
  },
  methods: {
    ...mapActions(useMemberStore, ["schedules"]),
    ...mapActions(useClassScheduleStore, ["cancel"]),
    onRequest(props) {
      console.func(
        "components/UserClassSchedules:methods.onRequest()",
        arguments
      );

      this.pagination = { ...props.pagination };
      const { page, rowsPerPage, sortBy, descending, date } = props.pagination;
      this.loading = true;

      if (typeof date === "object" && date) {
        props.pagination.date_from = date.from;
        props.pagination.date_to = date.to;
        delete props.pagination.date;
      } else {
        delete props.pagination.date_from;
        delete props.pagination.date_to;
      }

      this.schedules({
        ...props.pagination,
        moduleId: this.moduleId,
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
    onCancel({ row }) {
      console.func(
        "components/UserClassSchedules:methods.onCancel()",
        arguments
      );
      row.loading = true;
      this.cancel(row)
        .then(() => {
          row.loading = false;
          row.booking.cancelable = false;
        })
        .catch((error) => {
          row.loading = false;
        });
    },
  },
  computed: {
    visibleColumns() {
      return this.columns
        .filter((item) => {
          if (!this.noAction && item.name === "actions") {
            return true;
          } else if (!item.status) {
            return true;
          } else if (
            item.status &&
            item.status.includes(this.pagination.status)
          ) {
            return true;
          }
          return false;
        })
        .map((item) => item.name);
    },
    columns() {
      return [
        {
          name: "date_at",
          align: "left",
          label: this.$t("label.day"),
          field: (row) => `${row.day} - ${row.date_at_formated}`,
          style: "width: 40px",
          classes: (row) => (row.booking?.is_stand_by ? "bg-amber-1" : ""),
        },
        {
          name: "start_at",
          align: "left",
          label: this.$t("label.time"),
          field: (row) => `${row.time}`,
          classes: (row) => (row.booking?.is_stand_by ? "bg-amber-1" : ""),
        },
        {
          name: "class",
          align: "left",
          label: this.$t("label.class"),
          field: "class",
          format: (val) => (val ? val.name : ""),
          classes: (row) => (row.booking?.is_stand_by ? "bg-amber-1" : ""),
        },
        {
          name: "location",
          align: "left",
          label: this.$t("label.location"),
          field: "location",
          format: (val) => (val ? val.label : ""),
          classes: (row) => (row.booking?.is_stand_by ? "bg-amber-1" : ""),
        },
        {
          name: "instructor",
          align: "left",
          label: this.$t("label.instructor"),
          field: "instructor",
          format: (val) => (val ? val.name : ""),
          classes: (row) => (row.booking?.is_stand_by ? "bg-amber-1" : ""),
        },
        {
          name: "canceled_at",
          align: "left",
          label: this.$t("label.canceledAt"),
          field: (row) => row.booking.canceled_at,
          classes: getClasses,
          status: ["cancelled", "late-cancellation"],
        },
        {
          name: "is_stand_by",
          align: "left",
          label: "",
          field: (row) => row.booking.is_stand_by,
          format: (val) =>
            val ? '<strong class="text-negative">Standby</strong>' : "",
          classes: (row) => (row.booking?.is_stand_by ? "bg-amber-1" : ""),
          status: [null, "active"],
        },
        {
          name: "actions",
          align: "right",
          label: "",
          classes: (row) => (row.booking?.is_stand_by ? "bg-amber-1" : ""),
        },
      ];
    },
    toolbar() {
      return [
        {
          title: this.$t("label.date"),
          action: "request",
          component: "base-date-input",
          dense: true,
          outlined: true,
          key: "date",
          placeholder: this.$t("placeholder.select"),
          style: "width: 150px",
          prefix: this.$t("prefix.date"),
          deleted: "all",
        },
        {
          title: this.$t("label.status"),
          action: "request",
          component: "base-select",
          dense: true,
          outlined: true,
          key: "status",
          placeholder: this.$t("placeholder.select"),
          optionsDense: true,
          style: "width: 180px",
          mapOptions: true,
          emitValue: true,
          options: [
            {
              label: this.$t("label.all"),
              value: null,
            },
            {
              label: this.$t("label.active"),
              value: "active",
            },
            {
              label: this.$t("label.attended"),
              value: "attended",
            },
            {
              label: this.$t("label.cancelled"),
              value: "cancelled",
            },
            {
              label: this.$t("label.lateCancellation"),
              value: "late-cancellation",
            },
            {
              label: this.$t("label.noShow"),
              value: "noshow",
            },
          ],
          deleted: "all",
          prefix: this.$t("prefix.status"),
        },
      ];
    },
  },
};
</script>
