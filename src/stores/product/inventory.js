import { defineStore } from "pinia";
import Api from "services/api";
import { useVendorStore } from "stores/product/vendor";
import { useCategoryStore } from "./category";
import { useTagStore } from "./tag";
import { useCollectionStore } from "./collection";
import { useShopLocationStore } from "../shop/location";

const vendor = useVendorStore();
const category = useCategoryStore();
const tag = useTagStore();
const collection = useCollectionStore();
const location = useShopLocationStore();

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
    label: store.$t("label.lowestAvailable"),
    value: "AVAILABLE_ASC",
  },
  {
    label: store.$t("label.highestAvailable"),
    value: "AVAILABLE_DESC",
  },
  {
    label: store.$t("label.skuAZ"),
    value: "SKU_ASC",
  },
  {
    label: store.$t("label.skuZA"),
    value: "SKU_DESC",
  },
];

export const useInventoryStore = defineStore("product/inventory", {
  getters: {
    module: (store) => ({
      name: "Inventories",
      label: store.$t("modules.inventories"),
      singular: store.$t("modules.singular.inventory"),
      plural: store.$t("modules.plural.inventories"),
    }),
    columns: (store) => [
      {
        name: "product",
        align: "left",
        label: store.$t("label.product"),
        field: "product_title",
        style: "width: 40px",
        sortable: false,
      },
      {
        name: "sku",
        align: "left",
        label: store.$t("label.sku"),
        field: (row) => (row.sku ? row.sku : `No SKU`),
        sortable: false,
      },
      {
        name: "out_of_stock_track_inventory",
        align: "left",
        label: store.$t("label.whenSoldOut"),
        field: (row) =>
          row.out_of_stock_track_inventory
            ? `Continue selling`
            : `Stop selling`,
        sortable: false,
      },
      {
        name: "available",
        align: "center",
        label: store.$t("label.available"),
        field: (row) =>
          row.available <= 0
            ? `<span class="no-inventory">${row.available}</span>`
            : row.available,
        sortable: false,
      },
      {
        name: "edit_quantity",
        align: "left",
        label: store.$t("label.editQuantity"),
        field: "edit_quantity",
        style: "width: 250px",
        sortable: false,
      },
    ],
    toolbar: (store) => [
      {
        title: store.$t("label.location"),
        placeholder: "Select location",
        component: "base-select",
        filterMethod: location.get,
        action: "request",
        icon: "fas fa-map-marker-alt",
        key: "location",
        emitValue: true,
        dense: true,
        outlined: true,
        mapOptions: true,
        optionLabel: "name",
        optionvalue: "id",
        style: "min-width: 200px",
        deleted: "all",
      },
      {
        title: store.$t("label.sortBy"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "sortBy",
        optionsDense: true,
        mapOptions: true,
        emitValue: true,
        icon: "fas fa-sort",
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
        label: store.$t("label.viewProducts"),
        action: "route",
        route: "Products",
        color: "primary",
        deleted: "all",
      },
    ],
    filters: (store) => [
      {
        title: store.$t("label.vendor"),
        component: "base-select",
        filterMethod: vendor.get,
        placeholder: "Select vendor",
        mapOptions: true,
        optionLabel: "name",
        optionvalue: "id",
        outlined: true,
        dense: true,
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
        title: store.$t("label.location"),
        placeholder: "Select location",
        component: "base-select",
        filterMethod: location.get,
        mapOptions: true,
        optionLabel: "name",
        optionvalue: "id",
        outlined: true,
        dense: true,
        clearable: true,
        emitValue: true,
        key: "location",
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
        options: sortOptions(store),
      },
    ],
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("inventories", payload)
          .then((response) => {
            this.setRows(
              response.data.map((item) => ({
                ...item,
                adjust_type: "add",
                adjust_value: 0,
                has_changed: false,
              }))
            );
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    update(payload) {
      return new Promise((resolve, reject) => {
        Api.put(`inventories/${payload.id}`, payload)
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
