import { defineStore } from "pinia";
import Api from "services/api";

export const useWeekTemplateStore = defineStore("week-template", {
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("week-templates", payload)
          .then((response) => {
            this.setRows(response.data);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    update(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`week-templates/store`, payload)
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
