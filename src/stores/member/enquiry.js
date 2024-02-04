import { defineStore } from "pinia";
import Api from "services/api";

const statusOptions = (store) => [
  {
    label: store.$t("label.all"),
    value: null,
  },
  {
    label: store.$t("label.pending"),
    value: "Pending",
  },
  {
    label: store.$t("label.deactive"),
    value: "Deactive",
  },
  {
    label: store.$t("label.hold"),
    value: "Hold",
  },
  {
    label: store.$t("label.lost"),
    value: "Lost",
  },
];

const createdBy = (val) => {
  if (val && val.options && val.options.ref) {
    return val.options.ref;
  }
  return val && val.admin ? `${val.admin.name}` : "Direct";
};

const lastUpdate = (val) => {
  if (val && val.admin) {
    return `${val.admin.name} on ${val.date_time}`;
  } else if (val) {
    return `System on ${val.date_time}`;
  }
  return null;
};

export const useEnquiryStore = defineStore("member/enquiry", {
  getters: {
    module: (store) => ({
      name: "Members",
      label: store.$t("modules.members"),
      singular: store.$t("modules.singular.enquiry"),
      plural: store.$t("modules.plural.enquiries"),
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
        name: "created_at",
        align: "left",
        label: store.$t("label.date"),
        field: "created_at",
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
        name: "email",
        align: "left",
        label: store.$t("label.email"),
        field: "email",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "phone_number",
        align: "left",
        label: store.$t("label.contact"),
        field: "phone_number",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "last_update",
        align: "left",
        label: store.$t("label.lastUpdate"),
        field: "last_update",
        format: (val) => lastUpdate(val),
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "status",
        align: "center",
        label: store.$t("label.status"),
        field: "status",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "created_by",
        align: "center",
        label: store.$t("label.createdBy"),
        field: "created_by",
        format: (val) => createdBy(val),
        style: "width: 40px",
        sortable: true,
      },
      { name: "actions", align: "right", sortable: false },
    ],
    toolbar: (store) => [
      {
        label: store.$t("label.trashed"),
        action: "request",
        component: "base-toggle",
        dense: true,
        key: "deleted",

        checkedIcon: "delete",
        guard: ["admins"],
        deleted: "all",
      },
      {
        title: store.$t("label.status"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "status",
        placeholder: store.$t("placeholder.select"),
        optionsDense: true,
        style: "width: 180px",
        mapOptions: true,
        emitValue: true,
        options: statusOptions(store),
        deleted: "all",
        prefix: store.$t("prefix.status"),
      },
      {
        title: store.$t("label.rag"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "rag",
        placeholder: store.$t("placeholder.rag"),
        optionsDense: true,
        style: "width: 110px",
        mapOptions: true,
        emitValue: true,
        options: [null, "red", "green", "amber", "white"].map((item) => ({
          label: item
            ? `<div class="text-center"><i class="q-icon fas fa-circle rag-${item}" style="font-size: 13px;" aria-hidden="true" role="presentation" ></i></div>`
            : `<div class="text-center">${store.$t("label.all")}</div>`,
          value: item,
        })),
        optionsHtml: true,
        prefix: store.$t("prefix.rag"),
        deleted: "all",
      },
      {
        tooltip: store.$t("tooltip.exportAsCsv"),
        icon: "fas fa-file-csv",
        action: "export",
        color: "primary",
        deleted: "all",
        type: "csv",
        method: store.get,
      },
      {
        icon: "fad fa-plus-circle",
        label: store.$t("label.addEnquiry"),
        permission: "New",
        action: "route",
        params: { id: "add" },
        route: "Single Member",
        color: "primary",
        deleted: "all",
      },
    ],
    filters: (store) => [],
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("users/enquiry", payload)
          .then((response) => {
            this.setRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
  },
});
