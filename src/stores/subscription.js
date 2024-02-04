import { defineStore } from "pinia";
import Api from "services/api";
import { useAppStore as appStore } from "./app";
import { useMemberStore as memberStore } from "./member";

export const useSubscriptionStore = defineStore("subscription", {
  state: () => ({
    isLoadingSubscription: true,
    userId: undefined,
    user: undefined,
    subscription: {
      upcomingInvoice: null,
      defaultPaymentMethod: null,
      price: null,
    },
    paymentMethods: [],
    invoices: [],
    plans: [],
  }),
  getters: {
    defaultPaymentMethod(state) {
      return state.subscription.defaultPaymentMethod;
    },
    upcomingInvoice(state) {
      return state.subscription.upcomingInvoice;
    },
    currentPlan(state) {
      return state.subscription.price;
    },
    noPaymentMethods(state) {
      return state?.paymentMethods.length <= 0;
    },
    selectedPlan(state) {
      return (plan) => {
        return state.plans.find((item) => item.id === plan);
      };
    },
    defaultPlan(state) {
      const plan = state.plans[0] || {};
      const price =
        plan?.prices?.find(({ interval }) => interval === "month") || {};
      return {
        ...price,
        plan: {
          id: plan.id,
          label: plan.label,
          feature_lines: plan.feature_lines,
        },
      };
    },
    currentUser(state) {
      return state.user || appStore().user;
    },
    isSubscribed(state) {
      return this.currentUser?.subscribed;
    },
    hasCancelled(state) {
      return this.currentUser?.has_cancelled;
    },
    billingDetails() {
      const { line1, line2, city, state, postal_code } =
        this.currentUser.address || {};
      return {
        name: this.currentUser.name,
        email: this.currentUser.email,
        phone: this.currentUser.phone_number,
        address: {
          line1,
          line2,
          city,
          state,
          postal_code,
        },
      };
    },
    usages(state) {
      if (state.subscription?.price) {
        return state.subscription?.price?.features.map((item) => {
          let usage = state.subscription?.usages.find(
            (usage) => usage.slug === item.slug
          );
          return {
            ...item,
            usage: usage?.used ?? 0,
          };
        });
      }
      return [];
    },
  },
  actions: {
    getPlans(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`shared/plans`, payload)
          .then((response) => {
            this.plans = response.map((item) => ({
              ...item,
              features: item.feature_lines,
              value: item.id,
            }));
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    subscribe(payload) {
      return new Promise((resolve, reject) => {
        payload.user_id = this.userId;
        Api.post("subscription/subscribe", payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getSubscribed() {
      return new Promise(async (resolve, reject) => {
        await this.getPlans();
        Api.get("subscription", {
          user_id: this.userId,
        })
          .then((response) => {
            this.subscription = response;
            this.isLoadingSubscription = false;
            resolve(response);
          })
          .catch((error) => {
            this.isLoadingSubscription = false;
            reject(error);
          });
      });
    },
    confirm(payload) {
      return new Promise((resolve, reject) => {
        payload.user_id = this.userId;
        Api.post("subscription/confirm", payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    cancel() {
      return new Promise((resolve, reject) => {
        Api.post("subscription/cancel", {
          user_id: this.userId,
        })
          .then(async (response) => {
            this.updateCancelled(true);
            await this.getSubscribed();
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    cancelDowngrade() {
      return new Promise((resolve, reject) => {
        Api.post("subscription/cancel-downgrade", {
          user_id: this.userId,
        })
          .then(async (response) => {
            await this.getSubscribed();
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    resume() {
      return new Promise((resolve, reject) => {
        Api.post("subscription/resume", {
          user_id: this.userId,
        })
          .then(async (response) => {
            this.updateCancelled(false);
            await this.getSubscribed();
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getInvoices(payload) {
      return new Promise((resolve, reject) => {
        payload.user_id = this.userId;
        Api.post("subscription/invoices", payload)
          .then((response) => {
            this.invoices = response.data;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    downloadInvoice(payload) {
      return new Promise((resolve, reject) => {
        Api.download(`subscription/invoices/${payload}`, {
          user_id: this.userId,
        })
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getPaymentMethods() {
      return new Promise((resolve, reject) => {
        Api.post("payment-methods", {
          user_id: this.userId,
        })
          .then((response) => {
            this.paymentMethods = response;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    addPaymentMethod(payload) {
      return new Promise((resolve, reject) => {
        payload.user_id = this.userId;
        Api.post(`payment-methods/store`, payload)
          .then(async (response) => {
            await this.getPaymentMethods();
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    updateDefaultPaymentMethod(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`payment-methods/${payload}/update`)
          .then(async (response) => {
            await this.getPaymentMethods();
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    removePaymentMethod(payload) {
      return new Promise((resolve, reject) => {
        Api.delete(`payment-methods/${payload}`)
          .then(async (response) => {
            await this.getPaymentMethods();
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    async setUser(payload) {
      this.userId = payload;
      await memberStore()
        .show(payload)
        .then((user) => {
          this.user = user;
        });
    },
    removeUser() {
      this.user = null;
      this.userId = null;
      this.isLoadingSubscription = true;
    },
    updateCancelled(payload) {
      if (this.user?.id) {
        this.user.has_cancelled = payload;
      } else {
        appStore().updateCancelled(payload);
      }
    },
  },
});
