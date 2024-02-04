import core from "./core";
import { router } from "../router";
import { LoadingBar } from "quasar";

const setLoadingProgress = (event) => {
  const progress = parseInt(Math.round((event.loaded * 100) / event.total));
  // Update state here
  LoadingBar.increment(progress);
};

export default {
  cache: {},
  init() {
    console.log("api.init()", arguments);
  },
  async call(method, endpoint, data = {}, o) {
    console.func("services/api:request.call()", arguments);
    LoadingBar.start();

    if (core.$appStore.token) {
      core.$axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${core.$appStore.token}`;
    }

    return new Promise((resolve, reject) => {
      var payload = {
        url: endpoint,
        method: method,
        onUploadProgress: (event) => {
          setLoadingProgress(event);
        },
        onDownloadProgress: (event) => {
          setLoadingProgress(event);
        },
      };

      if (method === "get") {
        payload.params = data;
      } else {
        payload.data = data;
      }

      if (o && o.responseType) {
        payload.responseType = o.responseType;
      }

      core
        .$axios(payload)
        .then((response) => {
          resolve(response.data);
          LoadingBar.stop();
        })
        .catch((error) => {
          LoadingBar.stop();
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx

            if (["419", "401"].includes(error.response.status)) {
              reject(error);
              router.push({
                name: "Login",
              });
            }

            if (error.response.data) {
              reject(
                error.response.data.data
                  ? error.response.data.data
                  : error.response.data
              );
            } else {
              reject(error);
            }
            return;
          }
          reject(error);
        });
    });
  },
  get(endpoint, data, o) {
    console.func("services/core:request.get()", arguments);
    return new Promise((resolve, reject) => {
      this.call("get", endpoint, data, o)
        .then((response) => {
          resolve(response);
        })
        .catch((error) => {
          reject(error);
        });
    });
  },
  post(endpoint, data, o) {
    console.func("services/core:request.post()", arguments);
    return new Promise((resolve, reject) => {
      this.call("post", endpoint, data, o)
        .then((response) => {
          resolve(response);
        })
        .catch((error) => {
          reject(error);
        });
    });
  },
  put(endpoint, data, o) {
    console.func("services/core:request.put()", arguments);
    return new Promise((resolve, reject) => {
      this.call("put", endpoint, data, o)
        .then((response) => {
          resolve(response);
        })
        .catch((error) => {
          reject(error);
        });
    });
  },
  delete(endpoint, data, o) {
    console.func("services/core:request.delete()", arguments);
    return new Promise((resolve, reject) => {
      this.call("delete", endpoint, data, o)
        .then((response) => {
          resolve(response);
        })
        .catch((error) => {
          reject(error);
        });
    });
  },
  download(endpoint, data, o = {}) {
    console.func("services/core:request.download()", arguments);
    Object.assign(o, {
      responseType: "blob",
    });
    return new Promise((resolve, reject) => {
      this.call(o?.method || "get", endpoint, data, o)
        .then((response) => {
          resolve(response);
        })
        .catch((error) => {
          reject(error);
        });
    });
  },
};
