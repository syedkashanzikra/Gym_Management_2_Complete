import { defineStore } from "pinia";
import axios from "axios";

const Api = axios.create({
  baseURL: "https://api.coderstm.com",
});

export const useCountryStore = defineStore("country", {
  state: () => ({
    country: null,
  }),
  getters: {
    hasStates: (store) => store.country?.states_count > 0,
  },
  actions: {
    get(payload) {
      return new Promise((resolve, reject) => {
        Api.get("countries", { params: payload })
          .then(({ data }) => {
            resolve(data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    states(payload) {
      return new Promise((resolve, reject) => {
        Api.get("countries/states", { params: payload })
          .then(({ data }) => {
            resolve(data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    cities(payload) {
      return new Promise((resolve, reject) => {
        Api.get("countries/cities", { params: payload })
          .then(({ data }) => {
            resolve(data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    find(payload) {
      return new Promise((resolve, reject) => {
        Api.get("countries/find", { params: payload })
          .then(({ data }) => {
            resolve(data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    setCountry(payload) {
      this.country = payload;
    },
  },
});
