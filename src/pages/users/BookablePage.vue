<template>
  <q-page padding>
    <base-section
      flat
      bordered
      :title="
        $t('dateRange', {
          from: $core.formatDate(pagination.date_from),
          to: $core.formatDate(pagination.date_to),
        })
      "
      no-row
    >
      <base-table
        :module="module"
        :columns="[
          {
            name: 'id',
            label: 'Day',
            field: 'day',
            align: 'left',
            sortable: false,
          },
        ]"
        :rows="rowsByDate"
        :toolbar="toolbar"
        :filters="filters"
        :loading="loading"
        :pagination="pagination"
        @request="onRequest"
        @toolbar-clicked="toolbarClicked"
        :no-data-label="$t('registrations.noData')"
        no-permissions
        hide-pagination
        hide-header
      >
        <template #top-bottom>
          <base-info rounded class="q-mt-md" v-if="hasBlocked">
            {{
              $t("blockedInfo", {
                release: blocked.release_at,
              })
            }}
          </base-info>
        </template>
        <template v-slot:body="props">
          <q-tr no-hover class="day-header" :props="props">
            <q-td v-for="col in props.cols" :key="col.name" :props="props">
              <span>{{ col.value }}</span>
            </q-td>
          </q-tr>
          <q-tr :props="props">
            <q-td class="q-pa-none" colspan="100%">
              <base-table
                :columns="columns"
                :rows="props.row.rows"
                hide-top
                hide-pagination
                :rows-per-page-options="[0]"
                loaded
              >
                <template v-slot:actions="props">
                  <q-btn
                    v-if="props.row?.class?.has_description"
                    icon="fad fa-circle-info"
                    @click.stop
                    flat
                    size="sm"
                    round
                  >
                    <q-popup-proxy>
                      <q-banner style="width: 350px">
                        <base-label>{{ $t("description") }}</base-label>
                        <span
                          v-html="$core.nl2br(props.row.class.description)"
                        ></span>
                        <div class="q-mt-sm" v-if="props.row.class.urls">
                          <base-label>{{ $t("urls") }}</base-label>
                          <div
                            v-for="(item, index) in props.row.class.urls"
                            :key="index"
                          >
                            <a :href="item.url">{{ item.label || item.url }}</a>
                          </div>
                        </div>
                      </q-banner>
                    </q-popup-proxy>
                  </q-btn>
                  <q-btn
                    @click.stop="rowClicked(props, props.row)"
                    size="sm"
                    round
                    flat
                    :color="getColor(props.row)"
                    :icon="`fas fa-calendar-circle-${
                      props.row.is_booked ? 'user' : 'plus'
                    }`"
                    :loading="props.row.loading"
                    v-show="props.row.bookable"
                  />
                </template>
              </base-table>
            </q-td>
          </q-tr>
        </template>
        <template v-slot:item="props">
          <div class="col-xs-12">
            <q-item class="bg-grey-4">
              <q-item-section avatar>
                <q-item-label>{{ props.row.day }}</q-item-label>
              </q-item-section>
            </q-item>
            <q-tr>
              <q-td class="q-pa-none" colspan="100%">
                <base-table
                  :columns="columns"
                  :rows="props.row.rows"
                  hide-top
                  hide-pagination
                  :rows-per-page-options="[0]"
                  loaded
                  has-actions
                >
                  <template v-slot:body-cell-class="props">
                    <base-btn
                      link
                      v-if="props.row?.class?.has_description"
                      :label="props.value"
                      @click.stop
                    >
                      <q-popup-proxy>
                        <q-banner style="width: 350px">
                          <base-label>{{ $t("description") }}</base-label>
                          <span
                            v-html="$core.nl2br(props.row.class.description)"
                          >
                          </span>
                          <div class="q-mt-sm" v-if="props.row.class.urls">
                            <base-label>{{ $t("urls") }}</base-label>
                            <div
                              v-for="(item, index) in props.row.class.urls"
                              :key="index"
                            >
                              <a :href="item.url">{{
                                item.label || item.url
                              }}</a>
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
                      @click.stop="rowClicked(props, props.row)"
                      size="sm"
                      round
                      flat
                      :color="getColor(props.row)"
                      :icon="`fas fa-calendar-circle-${
                        props.row.is_booked ? 'user' : 'plus'
                      }`"
                      :loading="props.row.loading"
                      v-show="props.row.bookable"
                    />
                  </template>
                </base-table>
              </q-td>
            </q-tr>
          </div>
        </template>
      </base-table>
    </base-section>
  </q-page>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { groupBy } from "lodash";
import { date } from "quasar";
import { useClassScheduleStore } from "stores/member/class-schedule";

const { formatDate, addToDate } = date;
const getDate = (day = 0) => {
  return formatDate(addToDate(new Date(), { days: day }), "YYYY-MM-DD");
};

export default {
  data() {
    return {
      loading: false,
      pagination: {
        sortBy: "date_at",
        otherSortBy: "start_at",
        descending: false,
        page: 1,
        filter: "",
        active: null,
        deleted: false,
        rowsPerPage: 15,
        rowsNumber: 15,
        loaded: false,
        date_from: getDate(),
        date_to: getDate(6),
      },
      rowsByDate: [],
    };
  },
  methods: {
    ...mapActions(useClassScheduleStore, ["get", "book"]),
    onRequest(props) {
      console.func(
        "pages/pages/users/BookablePage:methods.onRequest()",
        arguments
      );
      const { page, rowsPerPage, sortBy, descending } = props.pagination;
      this.pagination = props.pagination;
      this.loading = true;

      this.get({
        ...props.pagination,
        direction: descending ? "desc" : "asc",
      })
        .then(({ data }) => {
          // clear out existing data and add new
          // this.rows = this.tableData;
          // update rowsCount with appropriate value
          // this.pagination.rowsNumber = meta.total;

          const rows = groupBy(data, (item) => item.day);
          this.rowsByDate = Object.keys(rows).map((item) => ({
            id: item,
            day: `${item} (${rows[item][0].date_at_formated})`,
            rows: rows[item],
          }));

          // updated the pagination
          this.pagination.page = page;
          this.pagination.rowsPerPage = rowsPerPage;
          this.pagination.sortBy = sortBy;
          this.pagination.descending = descending;
          this.pagination.loaded = true;

          // ...and turn of loading indicator
          this.loading = false;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    async toolbarClicked(action, row) {
      console.func(
        "pages/users/BookablePage:methods.toolbarClicked()",
        arguments
      );
    },
    async rowClicked(evt, row) {
      console.func("pages/users/BookablePage:methods.rowClicked()", arguments);
      if (!row.bookable) {
        return;
      }
      if (row.has_booked) {
        this.$core.warning(this.$t("dialog.info.noSlotAvailable"));
        return;
      }
      if (row.is_booked) {
        this.$core.warning(this.$t("dialog.info.userAlreadyBooked"));
        return;
      }
      row.loading = true;
      this.book(row)
        .then(() => {
          row.loading = false;
          row.is_booked = true;
        })
        .catch(() => {
          row.loading = false;
        });
    },
    onChangeDate(date) {
      console.func(
        "pages/users/BookablePage:methods.onChangeDate()",
        arguments
      );
      this.onRequest({
        pagination: {
          ...this.pagination,
        },
      });
    },
    getColor(row) {
      if (row.has_booked) {
        return "warning";
      } else if (row.bookable) {
        return "secondary";
      } else {
        return "negative";
      }
    },
  },
  computed: {
    ...mapState(useClassScheduleStore, [
      "rows",
      "columns",
      "module",
      "toolbar",
      "filters",
    ]),
    permissions() {
      return [];
    },
    hasBlocked() {
      return this.$app.user.has_blocked;
    },
    blocked() {
      return this.$app.user.blocked;
    },
  },
};
</script>

<style lang="scss">
.day-header td {
  background: $grey-4;
}
</style>
