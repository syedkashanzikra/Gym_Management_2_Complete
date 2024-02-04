import { defineStore } from "pinia";
import Api from "services/api";

export const useClassScheduleStore = defineStore("class-schedule", {
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("class-schedules/weekly", payload)
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
        Api.post(`class-schedules/weekly/update`, payload)
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
