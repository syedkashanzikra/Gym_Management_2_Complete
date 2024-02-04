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
                :year="props.row.id"
              />
            </q-td>
          </q-tr>
        </template>

        <template v-slot:bottom-row>
          <q-tr no-hover>
            <q-td class="text-bold" colspan="2">
              <q-btn
                flat
                color="primary"
                icon="fad fa-circle-plus"
                dense
                round
                @click="onAdd"
              />
            </q-td>
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
import MembersTable from "components/MembersTable.vue";
import { useMemberReportStore } from "stores/member-report";
import { useReportMixin } from "services/mixins";

const year = new Date().getFullYear();
const rows = range(year, year - 5).map((item) => ({
  id: item.toString(),
  label: item === year ? "Current Year" : item.toString(),
  checked: false,
  type: null,
}));

export default {
  components: { MembersTable },
  mixins: [useReportMixin],
  data() {
    return {
      loaded: false,
      visiable: true,
      rows,
      year,
    };
  },
  methods: {
    ...mapActions(useMemberReportStore, ["reportsYearly"]),
    onRequest(load, props) {
      console.func(
        "pages/admins/members/ReportsPage:methods.onRequest()",
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
      this.reportsYearly({
        year: props.row.id,
      }).then((data) => {
        Object.assign(props.row, data);
      });
    },
    onLoad(props, col) {
      console.func(
        "pages/admins/members/ReportsPage:methods.onLoad()",
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
    onAdd() {
      console.func(
        "pages/admins/members/ReportsPage:methods.onAdd()",
        arguments
      );
      const last = this.rows[this.rows.length - 1].id - 1;
      this.rows.push({
        id: last.toString(),
        label: last.toString(),
        checked: false,
        type: null,
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
      columns: "yearlyReportColumns",
    }),
    years() {
      return range(2015, new Date().getFullYear() + 1, 1);
    },
  },
};
</script>
