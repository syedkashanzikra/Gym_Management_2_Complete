import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";

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

export const useClassScheduleStore = defineStore("member-class-schedule", {
  getters: {
    module: (store) => ({
      name: "Class schedules",
      label: store.$t("modules.classSchedules"),
      singular: store.$t("modules.singular.classSchedule"),
      plural: store.$t("modules.plural.classSchedules"),
    }),
    columns: (store) => [
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
        name: "instructor",
        align: "left",
        label: store.$t("label.instructor"),
        field: "instructor",
        format: (val) => (val ? val.name : ""),
        style: "width: 40px",
        sortable: false,
      },
      { name: "actions", align: "right", sortable: false },
    ],
    toolbar: (store) => [
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
    ],
    filters: (store) => [],
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("member/class-schedules", payload)
          .then((response) => {
            this.setRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    show(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`member/class-schedules/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    book(payload) {
      return new Promise((resolve, reject) => {
        core
          .confirm(
            `${payload.class.name} on ${payload.day} (${payload.date_at_formated}) at ${payload.time}`
          )
          .then(() => {
            Api.post(`member/class-schedules/${payload.id}`)
              .then((response) => {
                payload.is_active = !payload.is_active;
                const { message } = response;
                core.success(message);
                resolve(response);
              })
              .catch((error) => {
                core.error(error);
                reject(error);
              });
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    cancel(payload) {
      return new Promise((resolve, reject) => {
        const isLate = payload.booking.cancelable === "late-cancellation";
        const lateMessage = isLate ? this.$t("dialog.info.lateCancel") : "";
        core
          .confirm(`${lateMessage}${this.$t("dialog.info.cancel")}`)
          .then(() => {
            Api.post(`member/bookings/${payload.booking.id}/cancel`)
              .then((response) => {
                payload.booking.cancelable = false;
                const { message } = response;
                core.success(message);
                resolve(response);
              })
              .catch((error) => {
                core.error(error);
                reject(error);
              });
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
  },
});
