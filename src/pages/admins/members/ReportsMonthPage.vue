<template>
  <q-page padding>
    <base-section flat bordered no-row v-if="visiable">
      <base-table
        :rows-per-page-options="[0]"
        :loaded="loaded"
        :rows="rows"
        :columns="columns"
        hide-pagination
        no-responsive
      >
        <template v-slot:top>
          <div class="col">
            <div class="row q-col-gutter-md">
              <div class="col-xs-12 col-sm">
                <base-select
                  options-dense
                  v-model="year"
                  :options="years"
                  dense
                  outlined
                  :prefix="$t('prefix.year')"
                  @update:model-value="onChangeYear"
                />
              </div>
              <div
                v-for="(item, index) in columns.filter((item) => item.stats)"
                :key="index"
                class="col-xs-6 col-sm"
              >
                <base-input
                  :prefix="`${item.label}:`"
                  readonly
                  v-model="stats[item.name]"
                />
              </div>
            </div>
          </div>
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
                :year="year"
                :month="props.row.id"
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
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";
import { useMemberReportStore } from "stores/member-report";
import MembersTable from "components/MembersTable.vue";
import { useReportMixin } from "services/mixins";

export default {
  components: { MembersTable },
  mixins: [useReportMixin],
  data() {
    return {
      loaded: false,
      visiable: true,
      rows: [
        { id: "1", checked: false, type: null },
        { id: "2", checked: false, type: null },
        { id: "3", checked: false, type: null },
        { id: "4", checked: false, type: null },
        { id: "5", checked: false, type: null },
        { id: "6", checked: false, type: null },
        { id: "7", checked: false, type: null },
        { id: "8", checked: false, type: null },
        { id: "9", checked: false, type: null },
        { id: "10", checked: false, type: null },
        { id: "11", checked: false, type: null },
        { id: "12", checked: false, type: null },
      ],
      year: new Date().getFullYear(),
    };
  },
  methods: {
    ...mapActions(useMemberReportStore, ["reportsMonthly"]),
    onRequest(load, props) {
      console.func(
        "pages/admins/members/ReportsMonthPage:methods.onRequest()",
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
      this.reportsMonthly({
        year: this.year,
        month: props.row.id,
      }).then((data) => {
        Object.assign(props.row, data);
      });
    },
    onLoad(props, col) {
      console.func(
        "pages/admins/members/ReportsMonthPage:methods.onLoad()",
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
    onChangeYear() {
      console.func(
        "pages/admins/members/ReportsMonthPage:methods.onChangeYear()",
        arguments
      );
      this.visiable = false;
      this.rows = this.rows.map((item) => ({
        ...item,
        total: undefined,
        rolling: undefined,
        rolling_total: undefined,
        end_date: undefined,
        end_date_total: undefined,
        monthly: undefined,
        yearly: undefined,
        yearly_total: undefined,
        free: undefined,
        cancelled_total: undefined,
        cancelled: undefined,
        type: undefined,
        checked: false,
      }));
      this.$nextTick(() => {
        this.visiable = true;
      });
    },
  },
  mounted() {
    setTimeout(() => {
      this.loaded = true;
    }, 500);
  },
  computed: {
    ...mapState(useAppStore, ["stats"]),
    ...mapState(useMemberReportStore, {
      columns: "monthlyReportColumns",
    }),
    years() {
      return range(2015, new Date().getFullYear() + 1, 1);
    },
  },
};
</script>
