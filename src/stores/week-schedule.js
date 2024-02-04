import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";
import { Dialog, openURL } from "quasar";
import { groupBy, uniqueId, cloneDeep } from "lodash";
import { useClassListStore } from "stores/class-list";
import { useInstructorStore } from "stores/instructor";
import BulkPrintDialog from "components/BulkPrintDialog.vue";

const classStore = useClassListStore();
const instructorStore = useInstructorStore();

const sortOptions = (store) => [
  {
    label: store.$t("label.default"),
    value: "start_at",
  },
  {
    label: store.$t("label.class"),
    value: "class_id",
  },
  {
    label: store.$t("label.instructor"),
    value: "instructor_id",
  },
];

const calCapacity = (row) => {
  if (row.disable) return "";
  let retval = 0;
  const capacity = row.capacity;
  const space = capacity - row.total_active_bookings;
  const standby = 5 - row.total_stand_by_bookings;
  if (capacity) {
    retval = 100 - (space / capacity) * 100;
  }
  return `${capacity}(${space})(${standby}) \n` + parseFloat(retval).toFixed(2);
};

const calAttendance = (row) => {
  if (row.disable) return "";
  if (!row.has_sign_off) return 0;
  return parseFloat(
    ((row.total_active_bookings - row.no_show) * 100) / row.capacity
  ).toFixed(2);
};

const dialog = async (schedule, response) => {
  await core.viewPdf(response, `class-schedule-${schedule.label}`);
};

const download = (schedule, blob) => {
  const url = URL.createObjectURL(blob);

  const downloadLink = document.createElement("a");
  downloadLink.href = url;
  downloadLink.download = `class-schedule-${schedule.label}.pdf`;
  downloadLink.click();

  setTimeout(() => {
    URL.revokeObjectURL(url);
  }, 5000);
};

const open = (blob) => {
  const url = URL.createObjectURL(blob);
  openURL(url);
};

const pdf = (payload) => {
  return new Promise((resolve, reject) => {
    Api.download(`class-schedules/${payload.id}/pdf`)
      .then((response) => {
        const blob = new Blob([response], { type: "application/pdf" });
        if (payload.download) {
          download(payload, blob);
        } else if (payload.open) {
          open(blob);
        } else {
          dialog(payload, response);
        }
        resolve(response);
      })
      .catch((error) => {
        core.error(error, { title: store.$t("dialog.title.error") });
        reject(error);
      });
  });
};

const listPdf = (payload) => {
  return new Promise((resolve, reject) => {
    Api.download(`class-schedules/list-pdf`, payload)
      .then(async (response) => {
        await core.viewPdf(response, "schedules");
        resolve(response);
      })
      .catch((error) => {
        reject(error);
      });
  });
};

const bulkPrint = (columns, rows) => {
  Dialog.create({
    component: BulkPrintDialog,
    componentProps: {
      modelValue: rows,
      action: pdf,
      columns: columns,
    },
  });
};

export const useWeekScheduleStore = defineStore("week-schedule", {
  getters: {
    module: (store) => ({
      name: "Week Schedules",
      label: store.$t("modules.schedules"),
      singular: store.$t("modules.singular.schedule"),
      plural: store.$t("modules.plural.schedules"),
    }),
    columns: (store) => [
      {
        name: "day_index",
        align: "left",
        label: store.$t("label.day"),
        field: (row) => (row.day ? `${row.day} - ${row.date_at_formated}` : ""),
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "start_at",
        align: "left",
        label: store.$t("label.time"),
        field: "time",
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "class",
        align: "left",
        label: store.$t("label.className"),
        field: "class",
        format: (val) => (val ? val.name : ""),
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "location",
        align: "left",
        label: store.$t("label.location"),
        field: "location",
        format: (val) => (val ? val.label : ""),
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "ns",
        align: "center",
        label: store.$t("label.nS"),
        field: (row) => {
          if (row.disable) return "";
          return row.has_sign_off ? row.no_show : 0;
        },
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "at",
        align: "center",
        label: store.$t("label.at"),
        field: (row) => calAttendance(row),
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "capacity",
        align: "center",
        label: store.$t("label.capacity"),
        field: (row) => calCapacity(row),
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "instructor",
        align: "left",
        label: store.$t("label.instructor"),
        field: "instructor",
        format: (val) => (val ? val.name : ""),
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "has_sign_off",
        align: "left",
        label: store.$t("label.signOff"),
        field: "has_sign_off",
        style: "width: 40px",
        format: (val) => (val ? "Yes" : "No"),
        sortable: false,
      },
      {
        name: "has_remote",
        align: "center",
        label: store.$t("label.remote"),
        field: (row) => {
          if (row.disable) return "";
          return row.has_remote ? "Yes" : "No";
        },
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "cost",
        align: "left",
        label: store.$t("label.cost"),
        field: "cost",
        format: (val) => core.money(val),
        style: "width: 40px",
        sortable: false,
      },
      { name: "actions", align: "right", sortable: false },
    ],
    actions: (store) => [
      {
        label: store.$t("label.edit"),
        permission: "Edit",
        action: "route",
        route: "Single Week Schedule",
        params: (row) => ({ id: row.id }),
        query: (row) => ({ action: "edit" }),
        icon: "fas fa-edit",
        deleted: false,
      },
      {
        label: store.$t("label.print"),
        action: (row) => pdf(row),
        icon: "fad fa-print",
        deleted: false,
      },
      {
        label: store.$t("label.pdf"),
        action: (row) =>
          pdf({
            ...row,
            download: true,
          }),
        icon: "fad fa-file-pdf",
        deleted: false,
      },
    ],
    toolbar: (store) => [
      {
        title: store.$t("label.class"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "classlist",
        placeholder: store.$t("placeholder.select"),
        optionsDense: true,
        style: "width: 190px",
        mapOptions: true,
        useFilter: true,
        clearable: true,
        filterMethod: classStore.get,
        optionLabel: "name",
        optionValue: "id",
        prefix: store.$t("prefix.class"),
        deleted: "all",
      },
      {
        title: store.$t("label.instructor"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "instructor",
        placeholder: store.$t("placeholder.select"),
        optionsDense: true,
        style: "width: 190px",
        mapOptions: true,
        useFilter: true,
        clearable: true,
        filterMethod: instructorStore.get,
        optionLabel: "name",
        optionValue: "id",
        prefix: store.$t("prefix.instructor"),
        deleted: "all",
      },
      {
        title: store.$t("label.date"),
        action: "request",
        component: "base-date-input",
        dense: true,
        outlined: true,
        key: "date",
        placeholder: store.$t("placeholder.select"),
        style: "width: 150px",
        prefix: store.$t("prefix.date"),
        deleted: "all",
      },
      {
        title: store.$t("label.sort"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "otherSortBy",
        placeholder: store.$t("placeholder.select"),
        optionsDense: true,
        style: "width: 150px",
        mapOptions: true,
        emitValue: true,
        options: sortOptions(store),
        prefix: store.$t("prefix.sort"),
        deleted: "all",
      },
      {
        tooltip: store.$t("tooltip.exportAsCsv"),
        icon: "fas fa-file-csv",
        action: "export",
        color: "primary",
        deleted: "all",
        type: "csv",
      },
      {
        tooltip: store.$t("tooltip.exportAsPdf"),
        icon: "fas fa-file-pdf",
        action: ({ pagination }) => listPdf(pagination),
        color: "primary",
        deleted: "all",
        type: "pdf",
      },
      {
        tooltip: store.$t("tooltip.bulkPrint"),
        icon: "fas fa-print",
        action: ({ columns, rows }) => bulkPrint(columns, rows),
        color: "primary",
        deleted: "all",
      },
    ],
    filters: (store) => [],
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("class-schedules", payload)
          .then((response) => {
            const { otherSortBy } = payload;
            const { data } = response;
            if (["class_id", "instructor_id"].includes(otherSortBy)) {
              let lists = Object.values(
                groupBy(
                  data.map((item) => ({
                    ...item,
                    class_id: item.class ? item.class.id : null,
                    instructor_id: item.instructor ? item.instructor.id : null,
                  })),
                  (item) => item[otherSortBy]
                )
              ).map((item) => {
                let items = cloneDeep(item);
                let total = items
                  .map((item) => item.cost || 0)
                  .reduce(
                    (previous, current) =>
                      parseFloat(previous) + parseFloat(current),
                    0
                  );
                items.push({
                  id: uniqueId("total"),
                  class: null,
                  instructor: null,
                  disable: true,
                  cost: total,
                });
                return items;
              });

              this.rows = lists.flatMap((item) => item);
            } else {
              this.rows = data;
            }
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    show(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`class-schedules/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    update(payload) {
      return new Promise((resolve, reject) => {
        Api.put(`class-schedules/${payload.id}`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    changeSignOff(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`class-schedules/${payload.id}/change-sign-off`)
          .then((response) => {
            payload.has_sign_off = !payload.has_sign_off;
            const { message } = response;
            core.success(message);
            resolve(response);
          })
          .catch((error) => {
            core.error(error);
            reject(error);
          });
      });
    },
    calendar(payload) {
      return new Promise((resolve, reject) => {
        Api.get("class-schedules/calendar", payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    logs(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`class-schedules/${payload.moduleId}/logs`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    calCapacity,
    calAttendance,
    listPdf,
    pdf,
  },
});
