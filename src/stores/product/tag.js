import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";

export const useTagStore = defineStore("product/tag", {
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("tags", payload)
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
        Api.post("tags/store", payload)
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
