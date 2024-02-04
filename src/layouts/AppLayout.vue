<template>
  <q-layout view="hHh LpR lFr" class="bg-main">
    <layout-header
      :use-cart="config?.shop"
      @update-left-drawer="$refs.layoutDrawer.toggle()"
    />

    <layout-drawer
      class="admin-side-links"
      ref="layoutDrawer"
      :side-menus="menus"
    >
      <div class="card q-pa-md">
        <member-card v-bind="user" />
      </div>
    </layout-drawer>

    <q-page-container>
      <router-view :key="$router.fullPath" />
    </q-page-container>
  </q-layout>
</template>

<script>
import LayoutHeader from "components/LayoutHeader.vue";
import LayoutDrawer from "components/LayoutDrawer.vue";
import MemberCard from "src/components/MemberCard.vue";
import { mapState, mapActions } from "pinia";
import { useAppStore } from "stores/app";
import ParqDialog from "components/ParqDialog.vue";

export default {
  components: { LayoutHeader, LayoutDrawer, MemberCard },
  computed: {
    ...mapState(useAppStore, ["user", "config"]),
    sideMenus() {
      return [
        {
          title: this.$t("menus.dashboard"),
          icon: "fas fa-tachometer-alt",
          route: "Dashboard",
        },
        {
          title: this.$t("menus.messages"),
          icon: "fas fa-message-quote",
          route: "Enquiries",
          notification: this.user.unread_enquiries,
        },
        {
          title: this.$t("menus.bookClass"),
          icon: "fas fa-calendar-circle-plus",
          route: "Bookable",
        },
        {
          title: this.$t("menus.classesBooked"),
          icon: "fas fa-calendar-circle-user",
          route: "Classes",
        },
        {
          title: this.$t("menus.guestPasses"),
          route: "Guest Passes",
          icon: "fas fa-user-tag",
        },
        {
          title: this.$t("menus.shop"),
          route: "Shop",
          icon: "fas fa-gifts",
          show: this.config?.shop,
        },
        {
          title: this.$t("menus.documents"),
          route: "Documents",
          icon: "fas fa-rectangle-history",
        },
        {
          title: this.$t("menus.billing"),
          route: "Billing",
          icon: "fas fa-credit-card",
        },
      ];
    },
    menus() {
      return this.sideMenus.filter((item) => item?.show !== false);
    },
  },
  methods: {
    ...mapActions(useAppStore, ["documents", "currentUser"]),
    showParq() {
      console.func("layouts/AppLayout:methods.showParq()", arguments);
      this.$q.dialog({
        component: ParqDialog,
        componentProps: {
          requestParq: true,
        },
      });
    },
  },
  mounted() {
    this.documents().then((files) => {
      const unread = files.filter((item) => !item.has_read);
      if (unread.length) {
        this.$core
          .warning(this.$t("documentDialogMessage"), {
            title: this.$t("newDocument"),
            ok: this.$t("readNow"),
          })
          .onOk(() => {
            this.$router.push({
              name: "Documents",
            });
          });
      }
    });
    this.currentUser().then(() => {
      if (this.user.request_parq) {
        this.showParq().onOk(() => {
          this.currentUser();
        });
      } else if (this.user.request_avatar) {
        this.$core
          .warning(this.$t("avatarDialogMessage"), {
            title: this.$t("updatePIC"),
            ok: this.$t("uploadNow"),
          })
          .onOk(() => {
            this.$router.push({
              name: "My Account",
              query: {
                avatar: true,
              },
            });
          });
      }
    });
  },
};
</script>
