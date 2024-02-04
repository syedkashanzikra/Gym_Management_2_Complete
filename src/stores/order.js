import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";

const statusOptions = (store) => [
  {
    label: "Open",
    value: "OPEN",
  },
  {
    label: "Paid",
    value: "PAID",
  },
  {
    label: "Completed",
    value: "COMPLETED",
  },
  {
    label: "Cancelled",
    value: "CANCELLED",
  },
];

const sortOptions = (store) => [
  {
    label: "Date created (newest first)",
    value: "CREATED_AT_DESC",
  },
  {
    label: "Date created (oldest first)",
    value: "CREATED_AT_ASC",
  },
  {
    label: "Customer name (A-Z)",
    value: "CUSTOMER_NAME_ASC",
  },
  {
    label: "Customer name (Z-A)",
    value: "CUSTOMER_NAME_DESC",
  },
];

export const useOrderStore = defineStore("order", {
  getters: {
    module: (store) => ({
      name: "Orders",
      label: store.$t("modules.orders"),
      singular: store.$t("modules.singular.order"),
      plural: store.$t("modules.plural.orders"),
    }),
    columns: (store) => [
      {
        name: "id",
        align: "left",
        label: "Order",
        field: "formated_id",
        sortable: false,
      },
      {
        name: "created_at",
        align: "left",
        label: "Date",
        field: "created_at",
        sortable: false,
      },
      {
        name: "customer_id",
        align: "left",
        label: "Customer",
        field: "customer",
        format: (val) => val?.name,
        sortable: false,
      },
      {
        name: "grand_total",
        align: "left",
        label: "Total",
        field: "grand_total",
        format: (val) => core.money(val),
        sortable: false,
      },
      {
        name: "total_line_items",
        align: "left",
        label: "Products",
        field: "total_line_items",
        sortable: false,
      },
      {
        name: "status",
        align: "left",
        label: "Status",
        field: "status",
        format: (val) => val?.map((item) => item.label),
        sortable: false,
      },
      { name: "actions", align: "right", sortable: false },
    ],
    actions: (store) => [
      {
        label: store.$t("label.edit"),
        module: "Orders",
        action: "route",
        route: "Single Order",
        params: (row) => ({ id: row.id }),
        query: (row) => ({ action: "edit" }),
        icon: "fas fa-edit",
        deleted: false,
      },
      {
        label: store.$t("label.delete"),
        module: "Orders",
        action: "delete",
        icon: "fas fa-trash-alt",
        deleted: false,
      },
      {
        label: store.$t("label.restore"),
        module: "Orders",
        action: "restore",
        icon: "fas fa-trash-undo",
        deleted: true,
      },
    ],
    toolbar: (store) => [
      {
        title: "Status",
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "status",
        placeholder: "Status",
        optionsDense: true,
        clearable: true,
        style: "width: 150px",
        mapOptions: true,
        emitValue: true,
        options: statusOptions(store),
        deleted: "all",
      },
      {
        title: "Sort by",
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "sortBy",
        optionsDense: true,
        hideDropdownIcon: true,
        mapOptions: true,
        emitValue: true,
        icon: "fas fa-sort",
        displayValue: "Sort",
        style: "width: 80px",
        options: sortOptions(store),
        deleted: "all",
      },
      {
        icon: "fad fa-plus-circle",
        label: "Add order",
        permission: "New",
        action: "route",
        params: { id: "add" },
        route: "Single Order",
        color: "primary",
        deleted: "all",
      },
    ],
    filters: (store) => [],
    statusOptions,
    getStatus: (store) => {
      return (value) => {
        const status = store.statusOptions.find((item) => item.value === value);
        return status?.label ?? value;
      };
    },
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("orders", payload)
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
        Api.post("orders/store", payload)
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
        Api.get(`orders/${payload}`)
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
        Api.put(`orders/${payload.id}`, payload)
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
        Api.delete(`orders/${payload}/destroy`)
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
        Api.delete("orders/destroy", {
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
        Api.post(`orders/${payload}/restore`)
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
        Api.post("orders/restore", {
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
    calculator(payload) {
      return new Promise((resolve, reject) => {
        Api.post("orders/calculator", payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    comment(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`orders/${payload.moduleId}/logs`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    markAsPaid(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`orders/${payload.moduleId}/mark-as-paid`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    pay(payload) {
      return new Promise((resolve, reject) => {
        Api.post(
          `orders/${payload.order}/${payload.payment_method}/pay`,
          payload
        )
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    duplicate(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`orders/${payload.moduleId}/duplicate`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    cancel(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`orders/${payload.moduleId}/cancel`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    receipt(payload) {
      return new Promise((resolve, reject) => {
        Api.download(`orders/${payload}/receipt`)
          .then(async (response) => {
            await core.viewPdf(response, `receipt_${payload}`);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    pos(payload) {
      return new Promise((resolve, reject) => {
        Api.download(`orders/${payload}/pos`)
          .then(async (response) => {
            await core.viewPdf(response, `receipt_${payload}`);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
  },
});
