import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";
import { cloneDeep, uniqueId, uniqBy } from "lodash";

export const useVariantStore = defineStore("product/variant", {
  state: () => ({
    default: [],
  }),
  getters: {
    module: (store) => ({
      name: "Variants",
      label: store.$t("modules.variants"),
      singular: store.$t("modules.singular.variant"),
      plural: store.$t("modules.plural.variants"),
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
        format: price,
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
        key: "active",
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
        storeAction: "vendor/List",
        placeholder: "Select vendor",
        clearable: true,
        emitValue: true,
        key: "vendor",
      },
      {
        title: store.$t("label.category"),
        component: "base-select",
        placeholder: "Select category",
        storeAction: "category/List",
        clearable: true,
        emitValue: true,
        key: "category",
      },
      {
        title: store.$t("label.taggedWith"),
        component: "base-select",
        placeholder: "Select tag",
        storeAction: "tag/List",
        clearable: true,
        emitValue: true,
        key: "tag",
      },
      {
        title: store.$t("label.collection"),
        component: "base-select",
        placeholder: "Select collection",
        storeAction: "collection/List",
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
        clearable: true,
        emitValue: true,
        options: ["Active", "Draft"],
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
    hasVariant(store) {
      return (position, value) => {
        if (store.rows) {
          if (
            store.rows.filter((item) =>
              item.options.find(
                (opt) => opt.value === value && opt.position === position
              )
            ).length
          ) {
            return true;
          }
          return false;
        }
        return false;
      };
    },
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`products/${payload.product_id}/variants`, payload)
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
        Api.post(`products/${payload.product_id}/variants`, payload)
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
        Api.get(`variants/${payload.variant_id}`)
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
        Api.post(`variants/${payload.id}`, payload)
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
        Api.delete(`variants/${payload.variant_id}`)
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
        Api.post(`variants/${payload.variant_id}/restore`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    changeList(payload) {
      return new Promise(async (resolve, reject) => {
        try {
          this.rows = payload;
          resolve(this.rows);
        } catch (error) {
          reject(error);
        }
      });
    },
    generate(payload) {
      return new Promise((resolve, reject) => {
        try {
          const { is_gift_card } = payload;
          const deafultVariant = cloneDeep(payload.default_variant);
          deafultVariant.inventories = deafultVariant.inventories.map(
            (item) => {
              delete item.id;
              delete item.variant_id;
              return item;
            }
          );
          const options = payload.options
            .map((item) => {
              return item.values && item.values.length
                ? item.values
                    .filter((item) => item.name)
                    .map((value) => value.name)
                : [];
            })
            .filter((item) => item.length);

          // check variant exist and map them
          if (this.rows.length) {
            this.firstOrUpdate(payload.options);
          }

          // create new variaint
          if (options.length) {
            const combinations = core.combinations(...options);
            combinations
              .filter(
                (combination) =>
                  !this.rows.find(
                    (variant) => variant.title === combination.join(" / ")
                  )
              )
              .forEach((combination) => {
                var variant = cloneDeep(deafultVariant);
                variant.id = uniqueId("v");
                if (is_gift_card) {
                  variant.price =
                    parseFloat(combination.join("").substring(1)) || 0;
                }
                variant.title = combination.join(" / ");
                variant.create_on_save = true;
                variant.options = combination.map((value, index) => {
                  return {
                    id: false,
                    position: index,
                    name: payload.options[index].name,
                    value: value,
                  };
                });
                this.pushRows(variant);
              });
          }
          resolve(cloneDeep(this.rows));
        } catch (error) {
          reject(error);
        }
      });
    },
    removeOption(payload) {
      return new Promise((resolve, reject) => {
        try {
          this.rows.forEach((variant) => {
            if (
              variant.options.find((item) => item.position === payload.position)
            ) {
              variant.options = variant.options
                .filter((item) => item.position !== payload.position)
                .map((item, index) => Object.assign(item, { position: index }));
              const title = variant.options
                .map((item) => item.value)
                .join(" / ");
              variant.title = title;
              variant.id = variant.id ? variant.id : uniqueId("v");
            }
          });
          this.rows = uniqBy(this.rows, "title");
          resolve(this.rows);
        } catch (error) {
          reject(error);
        }
      });
    },
    removeOptionValue(payload) {
      return new Promise((resolve, reject) => {
        try {
          this.rows = this.rows.filter(
            (item) =>
              !item.options.find(
                (option) =>
                  option.position === payload.position &&
                  option.value === payload.value
              )
          );
          resolve(this.rows);
        } catch (error) {
          reject(error);
        }
      });
    },
    firstOrUpdate(payload) {
      this.rows.forEach((variant) => {
        payload.forEach((option, index) => {
          if (
            option.values &&
            option.values.length &&
            !variant.options.find((item) => item.position === index)
          ) {
            variant.options.push({
              id: false,
              position: index,
              name: option.name,
              value: option.values[0].name,
            });
            const title = variant.options.map((item) => item.value).join(" / ");
            variant.title = title;
            variant.id = variant.id ? variant.id : uniqueId("v");
          }
        });
      });
    },
    clear(payload = []) {
      this.setRows(payload);
      this.default = cloneDeep(payload);
    },
    edit(payload) {
      return new Promise((resolve, reject) => {
        try {
          const item = this.rows.find((item) => item.id === payload.id);
          Object.assign(item, payload);
          resolve(item);
        } catch (error) {
          reject(error);
        }
      });
    },
    remove(payload) {
      this.rows = this.rows.filter((item) => item.id !== payload);
    },
  },
});
