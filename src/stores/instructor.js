import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";
import { useClassListStore } from "stores/class-list";

const classList = useClassListStore();

const statusOptions = (store) => [
  {
    label: store.$t("label.all"),
    value: null,
  },
  {
    label: store.$t("label.active"),
    value: "Active",
  },
  {
    label: store.$t("label.deactive"),
    value: "Deactive",
  },
  {
    label: store.$t("label.hold"),
    value: "Hold",
  },
];

const typeOptions = (store) => [
  {
    label: store.$t("label.all"),
    value: null,
  },
  {
    label: store.$t("label.instructors"),
    value: 0,
  },
  {
    label: store.$t("label.personalTrainer"),
    value: 1,
  },
];

export const useInstructorStore = defineStore("instructor", {
  getters: {
    module: (store) => ({
      name: "Instructors",
      label: store.$t("modules.instructors"),
      singular: store.$t("modules.singular.instructor"),
      plural: store.$t("modules.plural.instructors"),
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
        name: "phone_number",
        align: "left",
        label: store.$t("label.phone"),
        field: "phone_number",
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
    actions: (store) => [
      {
        label: store.$t("label.edit"),
        permission: "Edit",
        action: "route",
        route: "Single Instructor",
        params: (row) => ({ id: row.id }),
        query: (row) => ({ action: "edit" }),
        icon: "fas fa-edit",
        deleted: false,
      },
      {
        label: store.$t("label.delete"),
        permission: "Delete",
        action: "delete",
        icon: "fas fa-trash-alt",
        deleted: false,
      },
      {
        label: store.$t("label.restore"),
        permission: "Delete",
        action: "restore",
        icon: "fas fa-trash-undo",
        deleted: true,
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
        filterMethod: classList.get,
        optionLabel: "name",
        optionValue: "id",
        prefix: store.$t("prefix.class"),
        deleted: "all",
      },
      {
        title: store.$t("label.type"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "is_pt",
        placeholder: store.$t("placeholder.type"),
        optionsDense: true,
        style: "width: 180px",
        mapOptions: true,
        emitValue: true,
        options: typeOptions(store),
        prefix: store.$t("prefix.type"),
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
        style: "width: 150px",
        mapOptions: true,
        emitValue: true,
        options: statusOptions(store),
        prefix: store.$t("prefix.status"),
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
        icon: "fad fa-plus-circle",
        label: store.$t("label.addInstructor"),
        permission: "New",
        action: "route",
        params: { id: "add" },
        route: "Single Instructor",
        color: "primary",
        deleted: "all",
      },
    ],
    filters: (store) => [],
    statusOptions,
    typeOptions,
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("instructors", payload)
          .then((response) => {
            this.setRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    options(payload) {
      return new Promise((resolve, reject) => {
        Api.get("instructors/options", payload)
          .then((response) => {
            this.setRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    store(payload) {
      return new Promise((resolve, reject) => {
        Api.post("instructors/store", payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    show(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`instructors/${payload}`)
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
        Api.put(`instructors/${payload.id}`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    delete(payload) {
      return new Promise((resolve, reject) => {
        Api.delete(`instructors/${payload}/destroy`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    deleteSelected(payload) {
      return new Promise((resolve, reject) => {
        Api.delete("instructors/destroy", {
          items: payload,
        })
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    restore(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`instructors/${payload}/restore`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    restoreSelected(payload) {
      return new Promise((resolve, reject) => {
        Api.post("instructors/restore", {
          items: payload,
        })
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
