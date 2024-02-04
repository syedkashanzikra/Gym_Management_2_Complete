import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";

const APP_CURRENCY = process.env.APP_CURRENCY;

const dialog = async (type, response) => {
  await core.viewPdf(response, `reports-${type}`);
};

const pdf = (payload) => {
  return new Promise((resolve, reject) => {
    Api.download(`users/reports/pdf`, payload, { method: "post" })
      .then((response) => {
        const blob = new Blob([response], { type: "application/pdf" });
        if (payload.download) {
          core.downloadBlob(`reports-${payload.type}`, blob);
        } else {
          dialog(payload.type, response);
        }
        resolve(response);
      })
      .catch((error) => {
        core.error(error, { title: store.$t("dialog.title.error") });
        reject(error);
      });
  });
};

export const useMemberReportStore = defineStore("member-report", {
  getters: {
    module: (store) => ({
      name: store.$t("modules.memberReports"),
      singular: store.$t("modules.singular.memberReport"),
      plural: store.$t("modules.plural.memberReports"),
    }),
    columns: (store) => [
      {
        name: "name",
        align: "left",
        label: store.$t("label.name"),
        field: "name",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "phone",
        align: "left",
        label: store.$t("label.phone"),
        field: "phone",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "email",
        align: "left",
        label: store.$t("label.email"),
        field: "email",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "status",
        align: "left",
        label: store.$t("label.status"),
        field: "status",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "hourspw",
        align: "left",
        label: store.$t("label.hours"),
        field: "hourspw",
        format: (val) => core.money(val),
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "rentpw",
        align: "left",
        label: store.$t("label.rent"),
        field: "rentpw",
        format: (val) => core.money(val),
        style: "width: 40px",
        sortable: true,
      },
      { name: "actions", align: "right", sortable: false },
    ],
    reportColumns: (store) => [
      {
        name: "total",
        field: "total",
        label: store.$t("label.total"),
        sortable: false,
        align: "center",
        stats: true,
        load: true,
      },
      {
        name: "rolling_total",
        field: "rolling_total",
        label: store.$t("label.rolling") + "(" + APP_CURRENCY + ")",
        format: (val) => (val !== undefined ? core.money(val) : null),
        sortable: false,
        align: "center",
        stats: false,
      },
      {
        name: "rolling",
        field: "rolling",
        label: store.$t("label.rolling"),
        sortable: false,
        align: "center",
        stats: true,
        load: true,
      },
      {
        name: "end_date_total",
        field: "end_date_total",
        label: store.$t("label.endDate") + "(" + APP_CURRENCY + ")",
        format: (val) => (val !== undefined ? core.money(val) : null),
        sortable: false,
        align: "center",
        stats: false,
      },
      {
        name: "end_date",
        field: "end_date",
        label: store.$t("label.endDate"),
        sortable: false,
        align: "center",
        stats: true,
        load: true,
      },
      {
        name: "free",
        field: "free",
        label: store.$t("label.free"),
        sortable: false,
        align: "center",
        stats: true,
        load: true,
      },
      {
        name: "cancelled_total",
        field: "cancelled_total",
        label: store.$t("label.cancelled") + "(" + APP_CURRENCY + ")",
        format: (val) => (val !== undefined ? core.money(val) : null),
        sortable: false,
        align: "center",
        stats: false,
      },
      {
        name: "cancelled",
        field: "cancelled",
        label: store.$t("label.cancelled"),
        sortable: false,
        align: "center",
        stats: false,
        load: true,
      },
    ],
    dailyReportColumns: (store) => [
      {
        name: "id",
        field: "id",
        label: store.$t("label.day"),
        sortable: false,
        style: "width: 15px",
        align: "left",
        stats: false,
      },
      ...store.reportColumns,
    ],
    monthlyReportColumns: (store) => [
      {
        name: "id",
        field: "id",
        label: store.$t("label.month"),
        sortable: false,
        style: "width: 15px",
        align: "left",
        stats: false,
      },
      ...store.reportColumns,
    ],
    yearlyReportColumns: (store) => [
      {
        name: "id",
        field: "label",
        label: store.$t("label.year"),
        sortable: false,
        style: "width: 15px",
        align: "left",
        stats: false,
      },
      ...store.reportColumns,
    ],
    toolbar: (store) => [
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
        width: "6",
      },
      {
        tooltip: store.$t("tooltip.exportAsCsv"),
        icon: "fas fa-file-csv",
        action: "export",
        color: "primary",
        deleted: "all",
        type: "csv",
        name: "daily_reports",
        padding: "14px",
      },
      {
        tooltip: store.$t("tooltip.exportAsPdf"),
        icon: "fas fa-file-pdf",
        action: ({ columns, rows }) =>
          pdf({
            type: "daily",
            download: true,
            columns,
            rows,
          }),
        color: "primary",
        deleted: "all",
        type: "pdf",
        padding: "14px",
      },
      {
        tooltip: store.$t("tooltip.print"),
        icon: "fas fa-print",
        action: ({ columns, rows }) =>
          pdf({
            type: "daily",
            columns,
            rows,
          }),
        color: "primary",
        deleted: "all",
        padding: "14px",
      },
    ],
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("users/reports", payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    reportsDaily(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`users/reports/daily`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    reportsMonthly(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`users/reports/monthly`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    reportsYearly(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`users/reports/yearly`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
  },
});
