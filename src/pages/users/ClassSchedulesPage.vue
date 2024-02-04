<template>
  <q-page padding>
    <base-page-header
      class="q-mb-md"
      :title="$t('menus.classesBooked')"
      :hint="$t('manageClassSchedules')"
      :tabs="['Calendar', 'List']"
      v-model="tab"
    />
    <q-tab-panels class="base-tab-panels" v-model="tab">
      <q-tab-panel name="calendar">
        <base-section flat bordered no-row>
          <user-class-schedules-calendar :module-id="user.id" />
        </base-section>
      </q-tab-panel>
      <q-tab-panel name="list">
        <base-section flat bordered no-row>
          <user-class-schedules v-model:status="status" :module-id="user.id" />
        </base-section>
      </q-tab-panel>
    </q-tab-panels>
  </q-page>
</template>

<script>
import { mapState } from "pinia";
import { useAppStore } from "stores/app";
import UserClassSchedules from "components/UserClassSchedules.vue";
import UserClassSchedulesCalendar from "components/UserClassSchedulesCalendar.vue";
export default {
  data() {
    return {
      status: "active",
      calendar: true,
      tab: "calendar",
    };
  },
  components: { UserClassSchedules, UserClassSchedulesCalendar },
  computed: {
    ...mapState(useAppStore, ["user"]),
  },
};
</script>
