import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";
import { LocalStorage } from "quasar";
import { sumBy } from "lodash";

const cart = LocalStorage.getItem("cart") || [];

export const useShopStore = defineStore("shop", {
  state: () => ({
    orders: [],
    cart,
  }),
  getters: {
    columns: (store) => [
      {
        name: "title",
        align: "left",
        label: store.$t("label.title"),
        field: "title",
        style: "width: 40px",
        sortable: true,
      },
      {
        name: "price",
        align: "left",
        label: store.$t("label.price"),
        field: "price",
        format: (val) => core.money(val),
        style: "width: 40px",
        sortable: true,
      },
    ],
    toolbar: (store) => [
      {
        title: store.$t("label.sort"),
        action: "request",
        component: "base-select",
        dense: true,
        outlined: true,
        key: "sortBy",
        placeholder: store.$t("placeholder.select"),
        optionsDense: true,
        style: "width: 150px",
        mapOptions: true,
        emitValue: true,
        options: [
          { label: store.$t("label.priceHigh"), value: "PRICE_DESC" },
          { label: store.$t("label.priceLow"), value: "PRICE_ASC" },
          { label: store.$t("label.oldest"), value: "CREATED_AT_ASC" },
          { label: store.$t("label.latest"), value: "CREATED_AT_DESC" },
        ],
        prefix: store.$t("prefix.sort"),
        deleted: "all",
      },
    ],
    cartTotal(store) {
      return sumBy(store.cart, (item) => item.price * item.quantity);
    },
    cartCount(store) {
      return sumBy(store.cart, (item) => parseInt(item.quantity));
    },
    lineItems(store) {
      return store.cart.map((item) => ({
        title: item.title,
        price: item.price,
        product_id: item.id,
        variant_id: item.variant_id,
        quantity: item.quantity,
      }));
    },
  },
  actions: {
    orders(payload) {
      return new Promise((resolve, reject) => {
        Api.get("member/shop/orders", payload)
          .then((response) => {
            this.orders = response.data;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    createOrder(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`member/shop/orders/store`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    showOrder(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`member/shop/orders/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    products(payload) {
      return new Promise((resolve, reject) => {
        Api.get("member/shop/products", payload)
          .then((response) => {
            this.setRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    product(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`member/shop/products/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    addToCart(payload) {
      return new Promise((resolve, reject) => {
        try {
          const item = this.cart.find(
            (product) =>
              product.id === payload.id &&
              product.variant_id === payload.variant_id
          );
          if (item) {
            if (item.quantity < 1) {
              this.cart = this.cart.filter(
                (product) => product.id !== payload.id
              );
            } else {
              item.quantity = payload.quantity || item.quantity++;
            }
          } else {
            this.cart.push({
              ...payload,
              quantity: 1,
            });
          }
          LocalStorage.set("cart", this.cart);
          core.success("Product has been added to cart.");
          resolve(this.cart);
        } catch (error) {
          core.error(error);
          reject(error);
        }
      });
    },
    removeFromCart({ rowIndex }) {
      return new Promise((resolve, reject) => {
        try {
          this.cart.splice(rowIndex, 1);
          LocalStorage.set("cart", this.cart);
          core.success("Product has been remove from cart.");
          resolve(this.cart);
        } catch (error) {
          core.error(error);
          reject(error);
        }
      });
    },
    updateCart({ rowIndex, row }) {
      console.func("stores/shop:actions.updateCart()", arguments);
      return new Promise((resolve, reject) => {
        try {
          this.cart[rowIndex].quantity = row.quantity;
          LocalStorage.set("cart", this.cart);
          resolve(this.cart);
        } catch (error) {
          core.error(error);
          reject(error);
        }
      });
    },
    clearCart() {
      this.cart = [];
      LocalStorage.set("cart", this.cart);
    },
  },
});
