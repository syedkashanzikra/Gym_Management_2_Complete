import { defineStore } from "pinia";
import { Dialog } from "quasar";
import Api from "services/api";
import TaxDialog from "src/components/settings/TaxDialog.vue";
import core from "src/services/core";

export const useTaxStore = defineStore("taxes", {
  getters: {
    columns: (store) => [
      {
        name: "country",
        align: "left",
        label: "Country",
        field: "country",
        style: "width: 100px;",
        sortable: true,
      },
      {
        name: "state",
        align: "left",
        label: "State",
        field: "state",
        style: "width: 50px;",
        sortable: true,
      },
      {
        name: "label",
        align: "left",
        label: "Label",
        field: "label",
        style: "width: 100px;",
        sortable: true,
      },
      {
        name: "rate",
        align: "center",
        label: "Rate %",
        field: "rate",
        style: "width: 100px;",
        sortable: true,
      },
      {
        name: "compounded",
        align: "center",
        label: "Compounded",
        field: "compounded",
        format: (val) => (val ? "Yes" : "No"),
        style: "width: 50px;",
        sortable: true,
      },
      {
        name: "priority",
        align: "center",
        label: "Priority",
        field: "priority",
        style: "width: 100px;",
        sortable: true,
      },
      { name: "actions", align: "right", sortable: false },
    ],
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("taxes", payload)
          .then((response) => {
            const rows = response.map((item) => ({
              ...item,
              removeable: item.code === "*",
            }));
            this.setRows(rows);
            resolve(rows);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    store(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`taxes/store`, payload)
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
        Api.put(`taxes/${payload.id}`, payload)
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
        Api.delete(`taxes/${payload}/destroy`)
          .then((response) => {
            this.deleteRows({ id: payload });
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    add() {
      Dialog.create({
        component: TaxDialog,
        componentProps: {
          title: "Add tax",
          method: this.store,
          modelValue: {
            country: null,
            code: null,
            state: "*",
            label: "VAT",
            rate: 0,
            compounded: false,
            priority: 0,
          },
        },
      }).onOk((payload) => {
        this.pushRows(payload);
      });
    },
    edit({ row, pageIndex }) {
      Dialog.create({
        component: TaxDialog,
        componentProps: {
          title: "Update tax",
          method: this.update,
          modelValue: { ...row },
        },
      }).onOk((payload) => {
        this.editRows(payload);
      });
    },
  },
});
