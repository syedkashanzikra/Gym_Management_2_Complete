<template>
  <q-page padding>
    <base-section no-row v-if="visiable">
      <base-table
        :rows-per-page-options="[0]"
        :loaded="loaded"
        :rows="rows"
        :columns="dailyReportColumns"
        :toolbar="toolbar"
        :pagination="pagination"
        hide-pagination
        no-filter
        @request="onChangeDate"
        no-responsive
      >
        <template #top-left>
          <q-toolbar class="q-pa-none">
            <q-toolbar-title class="text-weight-medium">
              {{ $t("label.dailyReports") }}
            </q-toolbar-title>
          </q-toolbar>
        </template>
        <template v-slot:body="props">
          <q-tr :props="props">
            <q-td v-for="col in props.cols" :key="col.name" :props="props">
              <template v-if="col.name === 'id'">
                <q-checkbox
                  dense
                  v-model="props.row.checked"
                  @update:model-value="(val) => onRequest(val, props)"
                  :label="col.value"
                />
              </template>
              <template v-else>
                <base-btn
                  link
                  color="black"
                  @click="onLoad(props, col.name)"
                  v-if="col.value && col.load"
                  flat
                  dense
                  :label="col.value"
                />
                <span v-else>{{ col.value }}</span>
              </template>
            </q-td>
          </q-tr>
          <q-tr v-if="props.row.expand" :props="props">
            <q-td class="q-pa-none" colspan="100%">
              <members-table
                no-responsive
                :type="props.row.type"
                :day="props.row.id"
                :month="props.row.month"
                :year="props.row.year"
              />
            </q-td>
          </q-tr>
        </template>

        <template v-slot:bottom-row>
          <q-tr>
            <q-td align="center" class="text-bold" colspan="2"></q-td>
            <q-td align="center" class="text-bold" colspan="1">
              {{ getTotal("rolling_total") }}
            </q-td>
            <q-td align="center" class="text-bold" colspan="1"></q-td>
            <q-td align="center" class="text-bold" colspan="1">
              {{ getTotal("end_date_total") }}
            </q-td>
            <q-td align="center" class="text-bold" colspan="1"></q-td>
            <q-td align="center" class="text-bold" colspan="1"></q-td>
            <q-td align="center" class="text-bold" colspan="1">
              {{ getTotal("cancelled_total") }}
            </q-td>
            <q-td align="center" class="text-bold" colspan="1"></q-td>
          </q-tr>
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { range } from "lodash";
import { date } from "quasar";
import { mapActions, mapState } from "pinia";
import MembersTable from "components/MembersTable.vue";
import { useMemberReportStore } from "stores/member-report";
import { useReportMixin } from "services/mixins";

const { subtractFromDate, formatDate, getDateDiff, extractDate } = date;
const currentDate = new Date();

const generateRows = (startDate = currentDate, endDate) => {
  let days = 6;
  if (typeof startDate === "string") {
    startDate = extractDate(startDate, "YYYY/MM/DD");
  }
  if (endDate) {
    days = getDateDiff(startDate, endDate, "days") + 1;
  }
  return range(0, days).map((item) => {
    let newDate = subtractFromDate(startDate, { days: item });
    return {
      id: formatDate(newDate, "DD"),
      label: formatDate(newDate, "DD/MM/YYYY"),
      month: formatDate(newDate, "MM"),
      year: formatDate(newDate, "YYYY"),
      checked: false,
      total: undefined,
      rolling: undefined,
      rolling_total: undefined,
      end_date: undefined,
      end_date_total: undefined,
      free: undefined,
      cancelled_total: undefined,
      cancelled: undefined,
      type: null,
    };
  });
};

const rows = generateRows();

export default {
  components: { MembersTable },
  mixins: [useReportMixin],
  data() {
    return {
      loaded: true,
      visiable: true,
      rows,
      pagination: {
        loaded: true,
      },
    };
  },
  methods: {
    ...mapActions(useMemberReportStore, ["reportsDaily"]),
    onChangeDate(props) {
      console.func(
        "pages/admins/members/ReportsDailyPage:methods.onChangeDate()",
        arguments
      );
      const { date } = props.pagination;
      if (typeof date === "object" && date) {
        this.rows = generateRows(date.to, date.from);
      } else if (date) {
        this.rows = generateRows(date);
      } else {
        this.rows = generateRows();
      }
    },
    onRequest(load, props) {
      console.func(
        "pages/admins/members/ReportsDailyPage:methods.onRequest()",
        arguments
      );
      Object.assign(props.row, {
        total: undefined,
        rolling: undefined,
        rolling_total: undefined,
        end_date: undefined,
        end_date_total: undefined,
        free: undefined,
        cancelled_total: undefined,
        cancelled: undefined,
        type: undefined,
        expand: false,
      });
      if (!load) return;
      this.reportsDaily({
        day: props.row.id,
        month: props.row.month,
        year: props.row.year,
      })
        .then((data) => {
          Object.assign(props.row, data);
        })
        .catch((error) => {
          this.$core.error(error, { title: "Error" });
        });
    },
    onLoad(props, col) {
      console.func(
        "pages/admins/members/ReportsDailyPage:methods.onLoad()",
        arguments
      );

      if (props.row.expand && col === props.row.type) {
        props.row.expand = false;
        return;
      } else {
        props.row.expand = false;
        props.row.type = null;
      }

      this.$nextTick(() => {
        props.row.type = col;
        props.row.expand = true;
      });
    },
  },
  computed: {
    ...mapState(useMemberReportStore, ["toolbar", "dailyReportColumns"]),
  },
};
</script>
