import { defineStore } from "pinia";
import Api from "services/api";
import { useVendorStore } from "stores/product/vendor";
import { useCategoryStore } from "./category";
import { useTagStore } from "./tag";
import { useCollectionStore } from "./collection";

const vendor = useVendorStore();
const category = useCategoryStore();
const tag = useTagStore();
const collection = useCollectionStore();

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
    label: store.$t("label.draft"),
    value: "Draft",
  },
];

const sortOptions = (store) => [
  {
    label: store.$t("label.bestSelling"),
    value: "BEST_SELLING",
  },
  {
    label: store.$t("label.productTitleAZ"),
    value: "TITLE_ASC",
  },
  {
    label: store.$t("label.productTitleZA"),
    value: "TITLE_DESC",
  },
  {
    label: store.$t("label.highestPrice"),
    value: "PRICE_DESC",
  },
  {
    label: store.$t("label.lowestPrice"),
    value: "PRICE_ASC",
  },
  {
    label: store.$t("label.newest"),
    value: "CREATED_AT_DESC",
  },
  {
    label: store.$t("label.oldest"),
    value: "CREATED_AT",
  },
];

export const useProductStore = defineStore("product", {
  getters: {
    module: (store) => ({
      name: "Products",
      label: store.$t("modules.products"),
      singular: store.$t("modules.singular.product"),
      plural: store.$t("modules.plural.products"),
    }),
    columns: (store) => [
      {
        name: "title",
        align: "left",
        label: store.$t("label.product"),
        field: "title",
        style: "width: 250px; white-space: normal;",
        sortable: true,
      },
      {
        name: "inventory_html",
        align: "left",
        label: store.$t("label.inventory"),
        field: "inventory_html",
        sortable: false,
      },
      {
        name: "category",
        align: "left",
        label: store.$t("label.category"),
        field: "category",
        format: (val) => val?.name,
        sortable: false,
      },
      {
        name: "vendor",
        align: "left",
        label: store.$t("label.vendor"),
        field: "vendor",
        format: (val) => val?.name,
        sortable: false,
      },
      {
        name: "price",
        align: "left",
        label: store.$t("label.price"),
        field: "price",
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "status",
        align: "left",
        label: store.$t("label.status"),
        field: "status",
        sortable: false,
      },
      { name: "actions", align: "right", sortable: false },
    ],
    actions: (store) => [
      {
        label: store.$t("label.edit"),
        permission: "Edit",
        action: "route",
        route: "Single Product",
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
        title: store.$t("label.sortBy"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "sortBy",
        "options-dense": true,
        "map-options": true,
        "emit-value": true,
        icon: "fas fa-sort",
        style: "min-width: 200px",
        options: sortOptions(store),
        deleted: "all",
      },
      {
        label: store.$t("label.filters"),
        icon: "fas fa-sort-alt",
        action: "filter",
        color: "grey-6",
        outline: true,
        deleted: "all",
      },
      {
        icon: "fad fa-plus-circle",
        label: store.$t("label.addProduct"),
        permission: "New",
        action: "route",
        params: { id: "add" },
        route: "Single Product",
        color: "primary",
        deleted: "all",
      },
    ],
    filters: (store) => [
      {
        title: store.$t("label.vendor"),
        component: "base-select",
        filterMethod: vendor.get,
        mapOptions: true,
        optionLabel: "name",
        optionvalue: "id",
        outlined: true,
        dense: true,
        placeholder: "Select vendor",
        clearable: true,
        emitValue: true,
        key: "vendor",
      },
      {
        title: store.$t("label.category"),
        component: "base-select",
        placeholder: "Select category",
        filterMethod: category.get,
        mapOptions: true,
        optionLabel: "name",
        optionvalue: "id",
        outlined: true,
        dense: true,
        clearable: true,
        emitValue: true,
        key: "category",
      },
      {
        title: store.$t("label.taggedWith"),
        component: "base-select",
        placeholder: "Select tag",
        filterMethod: tag.get,
        mapOptions: true,
        optionLabel: "name",
        optionvalue: "id",
        outlined: true,
        dense: true,
        clearable: true,
        emitValue: true,
        key: "tag",
      },
      {
        title: store.$t("label.collection"),
        component: "base-select",
        placeholder: "Select collection",
        filterMethod: collection.get,
        mapOptions: true,
        optionLabel: "name",
        optionvalue: "id",
        outlined: true,
        dense: true,
        clearable: true,
        emitValue: true,
        key: "collection",
      },
      {
        title: store.$t("label.status"),
        component: "base-select",
        dense: true,
        outlined: true,
        key: "status",
        placeholder: "Select status",
        optionsDense: true,
        emitValue: true,
        options: statusOptions(store),
      },
      {
        title: store.$t("label.sortBy"),
        component: "base-select",
        dense: true,
        outlined: true,
        key: "sortBy",
        optionsDense: true,
        mapOptions: true,
        emitValue: true,
        style: "min-width: 200px",
        options: sortOptions(store),
      },
      {
        label: store.$t("label.trashed"),
        component: "q-checkbox",
        dense: true,
        key: "deleted",
      },
    ],
    sortOptions,
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.post("products", payload)
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
        Api.post("products/store", payload)
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
        Api.get(`products/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    barcodeProduct(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`products/bar-code/${payload}`)
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
        Api.put(`products/${payload.id}`, payload)
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
        Api.delete(`products/${payload}/destroy`)
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
        Api.delete("products/destroy", {
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
        Api.post(`products/${payload}/restore`)
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
        Api.post("products/restore", {
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
