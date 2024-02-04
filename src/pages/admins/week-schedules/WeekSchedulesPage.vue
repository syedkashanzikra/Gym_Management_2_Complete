<template>
  <q-page padding>
    <base-page-header
      class="q-mb-md"
      :title="$t('menus.weekSchedules')"
      :hint="$t('manageAdminClassSchedules')"
      :tabs="['Calendar', 'List']"
      v-model="tab"
    />
    <q-tab-panels class="base-tab-panels" v-model="tab">
      <q-tab-panel name="calendar">
        <base-section flat bordered no-row>
          <class-schedules-calendar />
        </base-section>
      </q-tab-panel>
      <q-tab-panel name="list">
        <base-section flat bordered no-row>
          <q-toolbar class="q-pa-none">
            <q-toolbar-title class="text-body1 text-bold">
              Week: {{ $core.formatDate(pagination.startOfWeek) }}
              <span v-if="totalCost">({{ $core.money(totalCost) }})</span>
            </q-toolbar-title>
            <q-btn
              color="primary"
              flat
              round
              dense
              icon="fas fa-angle-left"
              @click="onChangeDate(pagination.previousOfWeek)"
            >
              <base-tooltip>
                Previous Week ({{
                  $core.formatDate(pagination.previousOfWeek)
                }})
              </base-tooltip>
            </q-btn>
            <q-btn
              color="primary"
              flat
              round
              dense
              icon="fas fa-circle"
              @click="onChangeDate(new Date())"
            >
              <base-tooltip>
                Current Week({{ $core.formatDate(new Date()) }})
              </base-tooltip>
            </q-btn>
            <q-btn
              color="primary"
              flat
              round
              dense
              icon="fas fa-angle-right"
              @click="onChangeDate(pagination.nextOfWeek)"
            >
              <base-tooltip>
                Next Week ({{ $core.formatDate(pagination.nextOfWeek) }})
              </base-tooltip>
            </q-btn>
          </q-toolbar>
          <base-table
            :store="useWeekScheduleStore"
            :module="module"
            :columns="columns"
            :rows="rows"
            :actions="actions"
            :toolbar="toolbar"
            :filters="filters"
            :loading="loading"
            v-model:pagination="pagination"
            @request="onRequest"
            @action-clicked="actionClicked"
            @toolbar-clicked="toolbarClicked"
            @row-clicked="rowClicked"
            :no-data-label="$t('registrations.noData')"
            table-key="registrations"
          >
            <template v-slot:body-cell-day_index="props">
              <base-btn
                v-show="!props.row.disable"
                @click.stop
                link
                size="12px"
                tag="a"
                :to="{
                  name: 'Single Week Schedule',
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
            <template v-slot:body-cell-has_sign_off="props">
              <q-checkbox
                v-show="!props.row.disable"
                @update:model-value="changeSignOff(props.row)"
                size="sm"
                dense
                :model-value="props.row.has_sign_off"
                color="green"
              />
            </template>
            <template v-slot:body-cell-at="props">
              <class-at
                v-show="!props.row.disable"
                :has-sign-off="props.row.has_sign_off"
                :value="props.value"
              />
            </template>
          </base-table>
        </base-section>
      </q-tab-panel>
    </q-tab-panels>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useWeekScheduleStore } from "stores/week-schedule";
import { date } from "quasar";
import ClassAt from "components/ClassAt.vue";
import ClassSchedulesCalendar from "src/components/ClassSchedulesCalendar.vue";

const { formatDate, subtractFromDate, getDayOfWeek } = date;

const startOfWeek = (newDate = null) =>
  formatDate(
    subtractFromDate(newDate, { days: getDayOfWeek(newDate) - 1 }),
    "YYYY-MM-DD"
  );

export default {
  components: { ClassAt, ClassSchedulesCalendar },
  data() {
    return {
      loading: false,
      tab: "calendar",
      pagination: {
        sortBy: "day_index",
        otherSortBy: "start_at",
        descending: false,
        startOfWeek: startOfWeek(new Date()),
        previousOfWeek: null,
        nextOfWeek: null,
        descending: false,
        page: 1,
        filter: "",
        active: null,
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
        override: ["startOfWeek"],
      },
      totalCost: null,
      useWeekScheduleStore: useWeekScheduleStore(),
    };
  },
  methods: {
    ...mapActions(useWeekScheduleStore, ["get", "changeSignOff", "listPdf"]),
    onRequest(props) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.onRequest()",
        arguments
      );
      const {
        page,
        rowsPerPage,
        sortBy,
        descending,
        date,
        classlist,
        instructor,
      } = props.pagination;
      this.pagination = props.pagination;
      this.loading = true;

      if (typeof date === "object" && date) {
        props.pagination.date_from = date.from;
        props.pagination.date_to = date.to;
      } else {
        delete props.pagination.date_from;
        delete props.pagination.date_to;
      }

      this.get({
        ...props.pagination,
        class: classlist ? classlist.id : null,
        instructor: instructor ? instructor.id : null,
        direction: descending ? "desc" : "asc",
      })
        .then(
          ({ previousOfWeek, startOfWeek, nextOfWeek, meta, totalCost }) => {
            // clear out existing data and add new
            // this.rows = this.tableData;
            // update rowsCount with appropriate value
            this.pagination.rowsNumber = meta.total;

            this.totalCost = totalCost;

            // updated the pagination
            this.pagination.page = page;
            this.pagination.rowsPerPage = rowsPerPage;
            this.pagination.sortBy = sortBy;
            this.pagination.descending = descending;
            this.pagination.loaded = true;
            this.pagination.previousOfWeek = previousOfWeek;
            this.pagination.startOfWeek = startOfWeek;
            this.pagination.nextOfWeek = nextOfWeek;

            // ...and turn of loading indicator
            this.loading = false;
          }
        )
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    async actionClicked(action, row) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulePage:methods.actionClicked()",
        arguments
      );
    },
    async toolbarClicked({ action }, row) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulesPage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulesPage:methods.rowClicked()",
        arguments
      );

      if (row.disable) return false;

      this.$router.push({
        name: "Single Week Schedule",
        params: {
          id: row.id,
        },
        query: {
          action: "edit",
        },
      });
    },
    onChangeDate(date) {
      console.func(
        "pages/admins/week-schedules/WeekSchedulesPage:methods.onChangeDate()",
        arguments
      );
      this.onRequest({
        pagination: {
          ...this.pagination,
          startOfWeek: date,
        },
      });
    },
  },
  computed: {
    ...mapState(useWeekScheduleStore, [
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
