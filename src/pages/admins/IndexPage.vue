<template>
  <q-page padding>
    <div class="q-gutter-y-lg">
      <base-section :title="greeting" flat bordered class="modules">
        <div v-for="item in menuItems" :key="item.id" class="col-xs-6 col-sm-3">
          <base-btn
            class="full-width"
            :icon="item.icon.replace('fas', 'fad')"
            :label="item.label"
            :to="item.url"
            stack
            outline
            padding="20px 10px"
            size="md"
          >
            <q-badge
              rounded
              class="text-bold"
              v-if="item.notification"
              color="negative"
              floating
            >
              {{ item.notification }}
            </q-badge>
          </base-btn>
        </div>
      </base-section>
      <base-section
        :title="$t('modules.classSchedules')"
        :description="$t('manageAdminClassSchedules')"
        no-row
        flat
        bordered
        class="class-schedules"
      >
        <class-schedules-calendar />

        <template #bottom>
          <div class="text-left">
            <base-btn
              color="primary"
              label="View all"
              link
              to="/registrations"
            />
          </div>
        </template>
      </base-section>
    </div>
  </q-page>
</template>

<script>
import { mapState } from "pinia";
import ClassSchedulesCalendar from "src/components/ClassSchedulesCalendar.vue";
import { useAppStore } from "stores/app";

export default {
  components: { ClassSchedulesCalendar },
  computed: {
    menuItems() {
      return [
        {
          id: "addMembers",
          icon: "fad fa-plus-circle",
          label: this.$t("addMember"),
          url: "members/add",
        },
        ...this.homeItems,
        {
          id: "qrcode-scanner",
          icon: "fad fa-qrcode",
          label: this.$t("scanQrCode"),
          url: "scanner",
        },
      ];
    },
    ...mapState(useAppStore, ["homeItems", "greeting"]),
  },
};
</script>

<style lang="scss">
.modules {
  .q-icon {
    font-size: 35px;
    margin-bottom: 5px;
  }
}
</style>
