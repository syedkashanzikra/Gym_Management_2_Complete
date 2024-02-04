import { defineStore } from "pinia";
import Api from "services/api";
import core from "services/core";
import { LocalStorage } from "quasar";
import { map, orderBy } from "lodash";
import app from "../../package.json";
import { Device } from "@capacitor/device";

const user = LocalStorage.getItem("current_user") ?? {};
const location = LocalStorage.getItem("location") ?? {};
const token = LocalStorage.getItem("token");
const authenticated = LocalStorage.has("current_user");
const defaultConfig = {
  lang: "en-US",
  name: process.env.APP_NAME,
  timezone: "Asia/Calcutta",
  phone: "+9733014543",
  email: "hello@coderstm.com",
  country: "India",
  currency: "usd",
  shop: true,
};
const HOME_ITEMS = ["Tasks", "Enquiries"];
const STRIPE_PAYMENT = "stripe";

export const useAppStore = defineStore("app", {
  state: () => ({
    user: user,
    location: location,
    token,
    authenticated,
    version: app.version,
    isDirt: false,
    isLoading: false,
    paymentMethods: [],
    stats: {},
    config: defaultConfig,
    title: null,
    currency: {
      symbol: process.env.APP_CURRENCY ?? "$",
      decimal: ".",
    },
  }),
  getters: {
    hasPermission(store) {
      return (module) => {
        if (store.user.modules) {
          if (store.user.modules.find((item) => item.name === module)) {
            return true;
          }
          return false;
        }
        return false;
      };
    },
    hasModule(store) {
      return (module) => {
        if (store.user.modules) {
          if (
            store.user.modules.find((item) => core.slug(item.name) === module)
          ) {
            return true;
          }
          return false;
        }
        return false;
      };
    },
    hasModulePermission(store) {
      return (module, permission = false) => {
        if (store.user.modules) {
          const userModule = store.user.modules.find(
            (item) => item.name === module
          );
          if (!permission && userModule) {
            return true;
          } else if (Array.isArray(permission) && userModule) {
            return userModule.permissions.find((item) =>
              permission.includes(item.action)
            );
          } else if (
            permission &&
            userModule &&
            userModule.permissions.find((item) => item.action === permission)
          ) {
            return true;
          }
          return false;
        }
        return false;
      };
    },
    getPermissions(store) {
      return (module) => {
        if (store.user.modules) {
          var item = store.user.modules.find((item) => item.name === module);
          if (!item) return [];
          item = map(item.permissions, "action");
          return item;
        }
        return [];
      };
    },
    isAuthenticated(store) {
      return store.authenticated;
    },
    guard(store) {
      return store.user.guard;
    },
    isGuard(store) {
      return (guard) => {
        return this.guard === guard;
      };
    },
    modules(store) {
      return orderBy(store.user.modules, ["sort_order"], ["asc"]).map(
        (item) => ({
          ...item,
          notification: store.stats["unread_" + item.url],
        })
      );
    },
    homeItems(store) {
      return store.modules.filter((item) => HOME_ITEMS.includes(item.name));
    },
    adminSideMenus: (store) => [
      {
        title: store.$t("menus.dashboard"),
        icon: "fas fa-tachometer-alt",
        route: "Dashboard",
      },
      {
        title: store.$t("menus.enquiries"),
        icon: "fas fa-message-quote",
        route: "Enquiries",
        module: "Enquiries",
        notification: store.stats.unread_support,
      },
      {
        title: store.$t("menus.tasks"),
        icon: "fas fa-list-check",
        route: "Tasks",
        module: "Tasks",
        notification: store.stats.unread_tasks,
      },
      {
        title: store.$t("menus.members"),
        icon: "fas fa-user-tag",
        route: "Enquiry",
        module: "Members",
        subLinks: [
          {
            title: store.$t("menus.enquiry"),
            route: "Enquiry",
            permission: "Enquiry",
          },
          {
            title: store.$t("menus.members"),
            route: "Members",
          },
        ],
      },
      {
        title: store.$t("menus.reports"),
        icon: "fas fa-chart-user",
        route: "Daily Reports",
        module: "Reports",
        permission: ["Daily Reports", "Monthly Reports", "Yearly Reports"],
        subLinks: [
          {
            title: store.$t("menus.dailyReports"),
            route: "Daily Reports",
            permission: "Daily Reports",
          },
          {
            title: store.$t("menus.monthlyReports"),
            route: "Monthly Reports",
            permission: "Monthly Reports",
          },
          {
            title: store.$t("menus.yearlyReports"),
            route: "Yearly Reports",
            permission: "Yearly Reports",
          },
        ],
      },
      {
        title: store.$t("menus.finance"),
        icon: "fas fa-coins",
        route: "Finance Membership",
        module: "Finance",
        subLinks: [
          {
            title: store.$t("menus.membership"),
            route: "Finance Membership",
          },
          {
            title: store.$t("menus.plans"),
            route: "Plans",
          },
        ],
      },
      {
        title: store.$t("menus.classes"),
        icon: "fas fa-book-sparkles",
        route: "Classes",
        module: "Classes",
        subLinks: [
          {
            title: store.$t("menus.allClasses"),
            route: "Classes",
          },
          {
            title: store.$t("menus.templates"),
            route: "Templates",
          },
          {
            title: store.$t("menus.weekSchedules"),
            route: "Class Schedules",
          },
          {
            title: store.$t("menus.locations"),
            route: "Locations",
          },
        ],
      },
      {
        title: store.$t("menus.products"),
        icon: "fas fa-box-open-full",
        route: "Products",
        module: "Products",
        subLinks: [
          {
            title: store.$t("menus.allProducts"),
            route: "Products",
            exact: true,
          },
          {
            title: store.$t("label.inventory"),
            route: "Inventory",
            permission: "Inventory",
          },
          {
            title: store.$t("label.collections"),
            route: "Collections",
            permission: "Collections",
          },
          {
            title: store.$t("label.categories"),
            route: "Categories",
            permission: "Categories",
          },
          {
            title: store.$t("label.attributes"),
            route: "Attributes",
            permission: "Attributes",
          },
        ],
      },
      {
        title: store.$t("menus.instructors"),
        icon: "fas fa-chalkboard-user",
        route: "Instructors",
        module: "Instructors",
      },
      {
        title: store.$t("menus.weekSchedules"),
        icon: "fas fa-calendar-days",
        route: "Week Schedules",
        module: "Week Schedules",
      },
      {
        title: store.$t("menus.orders"),
        icon: "fas fa-cart-arrow-down",
        route: "Orders",
        module: "Orders",
      },
      {
        title: store.$t("menus.announcements"),
        icon: "fas fa-bullhorn",
        route: "Announcements",
        module: "Announcements",
      },
      {
        title: store.$t("menus.staff"),
        icon: "fas fa-user-shield",
        route: "Staff",
        module: "Staff",
        subLinks: [
          {
            title: store.$t("menus.allStaff"),
            route: "Staff",
          },
          {
            title: store.$t("menus.groups"),
            route: "Groups",
          },
        ],
      },
    ],
    greeting(store) {
      const currentTime = new Date();
      const currentHour = currentTime.getHours();
      if (currentHour >= 5 && currentHour < 12) {
        return store.$t("goodMorning", { name: store?.user?.name });
      } else if (currentHour >= 12 && currentHour < 17) {
        return store.$t("goodAfternoon", { name: store?.user?.name });
      } else {
        return store.$t("goodEvening", { name: store?.user?.name });
      }
    },
    stripePayment(store) {
      return store.paymentMethods.find(
        ({ provider }) => provider === STRIPE_PAYMENT
      );
    },
    hasPaymentStripe(store) {
      return Boolean(store.stripePayment?.API_KEY);
    },
  },
  actions: {
    async login(payload) {
      const { identifier } = await Device.getId();
      payload.device_id = identifier;
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload.guard}/login`, payload)
          .then((response) => {
            this.updateCurrentUser(response);
            resolve(response);
          })
          .catch((error) => {
            this.updateCurrentUser(false);
            reject(error);
          });
      });
    },
    async signUp(payload) {
      const { identifier } = await Device.getId();
      payload.device_id = identifier;
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload.guard}/signup`, payload)
          .then((response) => {
            this.updateCurrentUser(response);
            resolve(response);
          })
          .catch((error) => {
            this.updateCurrentUser(false);
            reject(error);
          });
      });
    },
    currentUser(payload = "users") {
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload}/me`)
          .then((response) => {
            const user = response;
            if (!response.address) {
              Object.assign(user, {
                address: {},
              });
            }
            this.updateCurrentUser(user);
            resolve(user);
          })
          .catch((error) => {
            this.updateCurrentUser(false);
            reject(error);
          });
      });
    },
    updateCurrentUser(payload) {
      if (payload) {
        this.authenticated = true;
        if ("token" in payload) {
          const { user, token } = payload;
          this.user = user;
          this.token = token;
          LocalStorage.set("current_user", user);
          LocalStorage.set("token", token);
        } else {
          this.user = payload;
          LocalStorage.set("current_user", payload);
        }
      } else {
        this.authenticated = false;
        this.token = null;
        this.user = {};
        LocalStorage.remove("current_user");
        LocalStorage.remove("token");
      }
    },
    update(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload.guard}/update`, payload)
          .then((response) => {
            this.updateCurrentUser(response);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getModules(payload) {
      return new Promise((resolve, reject) => {
        Api.get("admins/modules", payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getStats(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`application/stats`, payload)
          .then((response) => {
            this.stats = response;
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getSettings(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`application/settings/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getConfig() {
      return new Promise((resolve, reject) => {
        Api.get(`application/config`)
          .then((response) => {
            this.currentLocation();
            this.getPaymentMethods();
            this.config = Object.assign(this.config, response);
            this.currency = core.currencyInfo(this.config?.currency);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    getPaymentMethods() {
      return new Promise((resolve, reject) => {
        Api.get(`application/payment-methods`)
          .then((response) => {
            this.paymentMethods = response.map((item) => {
              if (item.provider === "stripe") {
                return {
                  ...item,
                  brand: "visa",
                  card_number: "Stripe",
                  exp_date: "Automatic",
                  card: {},
                };
              } else if (item.provider === "manual") {
                return {
                  ...item,
                  icon: "fas fa-money-check-dollar",
                  card_number: "Cash",
                  exp_date: "payCash",
                  card: {},
                };
              }
            });
            resolve(this.paymentMethods);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    updateSettings(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`application/settings`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    changePassword(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload.guard}/change-password`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    async requestPassword(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload.guard}/request-password`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    async resetPassword(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload.guard}/reset-password`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    logout(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`auth/${payload}/logout`)
          .then((response) => {
            this.updateCurrentUser(false);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    documents() {
      return new Promise((resolve, reject) => {
        Api.get(`member/documents`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    readDcument(payload) {
      return new Promise((resolve, reject) => {
        Api.get(`member/documents/${payload}`)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    update_parq(payload) {
      return new Promise((resolve, reject) => {
        Api.post(`auth/users/update-parq`, payload)
          .then((response) => {
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    setIsDirt(payload) {
      this.isDirt = payload;
      if (!payload) {
        this.isLoading = false;
      }
    },
    setIsLoading(payload) {
      this.isLoading = payload;
    },
    setTitle(payload) {
      this.title = payload;
    },
    updateCancelled(payload) {
      this.user.has_cancelled = payload;
    },
    currentLocation() {
      return new Promise((resolve, reject) => {
        Api.get("application/location")
          .then((response) => {
            LocalStorage.set("location", response);
            resolve(response);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    testMailConfig(payload) {
      return new Promise((resolve, reject) => {
        Api.post("application/test-mail-config", payload)
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
