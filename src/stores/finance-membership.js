import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";

const typeOptions = (store) => [
  {
    label: store.$t("label.all"),
    value: null,
  },
  {
    label: store.$t("label.checked"),
    value: "checked",
  },
  {
    label: store.$t("label.notChecked"),
    value: "notchecked",
  },
  {
    label: store.$t("label.pARQYes"),
    value: "parq",
  },
  {
    label: store.$t("label.pARQNo"),
    value: "notparq",
  },
  {
    label: store.$t("label.yesPIC"),
    value: "pic",
  },
  {
    label: store.$t("label.noPIC"),
    value: "notpic",
  },
];

export const useFinanceMembershipStore = defineStore("finance-membership", {
  getters: {
    module: (store) => ({
      name: store.$t("modules.memberships"),
      singular: store.$t("modules.singular.membership"),
      plural: store.$t("modules.plural.memberships"),
    }),
    columns: (store) => [
      {
        name: "id",
        align: "left",
        label: store.$t("label.memberID"),
        field: "member_id_formated",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "name",
        align: "left",
        label: store.$t("label.name"),
        field: "name",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "starts_at",
        align: "left",
        label: store.$t("label.startsAt"),
        style: "width: 40px",
        field: "starts_at",
        format: (val) => core.formatDate(val, "DD MMM, YYYY"),
        sortable: true,
      },
      {
        name: "ends_at",
        align: "left",
        label: store.$t("label.endsAt"),
        style: "width: 40px",
        field: "ends_at",
        format: (val) => core.formatDate(val, "DD MMM, YYYY"),
        sortable: true,
      },
      {
        name: "renewal_fee",
        align: "left",
        label: store.$t("label.renewalFee"),
        field: "price",
        classes: (row) => (row.ends_at ? "text-negative text-bold" : ""),
        format: (val) => core.money(val?.amount),
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "price",
        align: "left",
        label: store.$t("label.plan"),
        field: (row) => row?.price?.label,
        style: "width: 250px; white-space: normal",
        sortable: true,
      },
      {
        name: "checked",
        align: "left",
        label: store.$t("label.checked"),
        field: "checked",
        format: (val) => (val ? "Yes" : "No"),
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "has_parq",
        align: "left",
        label: store.$t("label.pARQ"),
        field: "has_parq",
        format: (val) => (val ? "Yes" : "No"),
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "request_parq",
        align: "left",
        label: store.$t("label.m-PARQ"),
        field: "request_parq",
        format: (val) => (val ? "Yes" : "No"),
        sortable: true,
        style: "width: 40px",
      },
      {
        name: "has_avatar",
        align: "left",
        label: store.$t("label.pic"),
        field: "has_avatar",
        format: (val) => (val ? "Yes" : "No"),
        sortable: true,
        style: "width: 40px",
      },
      {
        name: "request_avatar",
        align: "left",
        label: store.$t("label.m-Pic"),
        field: "request_avatar",
        format: (val) => (val ? "Yes" : "No"),
        sortable: true,
      },
    ],
    toolbar: (store) => [
      {
        title: store.$t("label.type"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "type",
        placeholder: store.$t("placeholder.select"),
        optionsDense: true,
        style: "width: 180px",
        mapOptions: true,
        emitValue: true,
        options: typeOptions(store),
        deleted: "all",
        prefix: store.$t("prefix.type"),
      },
      {
        tooltip: store.$t("tooltip.exportAsCsv"),
        icon: "fas fa-file-csv",
        action: "export",
        color: "primary",
        deleted: "all",
        type: "csv",
      },
    ],
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("finance/memberships", payload)
          .then((response) => {
            this.setRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    checked(payload) {
      payload.loading = true;
      return new Promise((resolve, reject) => {
        Api.post(`users/${payload.id}/checked`)
          .then((response) => {
            payload.loading = false;
            payload.checked = !payload.checked;
            const { message } = response;
            core.success(message);
            resolve(response);
          })
          .catch((error) => {
            payload.loading = false;
            core.error(error);
            reject(error);
          });
      });
    },
    requestAvatar(payload) {
      payload.loading = true;
      return new Promise((resolve, reject) => {
        Api.post(`users/${payload.id}/request-avatar`)
          .then((response) => {
            payload.loading = false;
            payload.request_avatar = !payload.request_avatar;
            const { message } = response;
            core.success(message);
            resolve(response);
          })
          .catch((error) => {
            payload.loading = false;
            core.error(error);
            reject(error);
          });
      });
    },
    requestParq(payload) {
      payload.loading = true;
      return new Promise((resolve, reject) => {
        Api.post(`users/${payload.id}/request-parq`)
          .then((response) => {
            payload.loading = false;
            payload.request_parq = !payload.request_parq;
            const { message } = response;
            core.success(message);
            resolve(response);
          })
          .catch((error) => {
            payload.loading = false;
            core.error(error);
            reject(error);
          });
      });
    },
  },
});
