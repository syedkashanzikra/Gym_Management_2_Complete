import { defineStore } from "pinia";
import Api from "services/api";

export const usePaymentMethodStore = defineStore("payment-method", {
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("shop/payment-methods", payload)
          .then((response) => {
            resolve(response);
            this.setRows(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    show(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`shop/payment-methods/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    store(payload) {
      return new Promise((resolve, reject) => {
        Api.post("shop/payment-methods/store", payload)
          .then((response) => {
            this.pushRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    update(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`shop/payment-methods/${payload.id}`, payload)
          .then((response) => {
            resolve(response);
            this.editRows(response.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    disable(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`shop/payment-methods/${payload}/disable`)
          .then((response) => {
            resolve(response);
            this.editRows(response.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    enable(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`shop/payment-methods/${payload}/enable`)
          .then((response) => {
            resolve(response);
            this.editRows(response.data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
  },
});
