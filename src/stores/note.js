import { defineStore } from "pinia";
import Api from "services/api";

export const useNoteStore = defineStore("note", {
  actions: {
    delete(payload) {
      return new Promise((resolve, reject) => {
        Api.delete(`logs/${payload}/destroy`)
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
        Api.post(`logs/${payload.id}`, payload)
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
