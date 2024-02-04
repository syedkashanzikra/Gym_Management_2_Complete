import { defineStore } from "pinia";
import Api from "services/api";

export const useCustomerStore = defineStore("customer", {
  actions: {
    show(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`customers/${payload}`)
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
        Api.put(`customers/${payload.id}`, payload)
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
