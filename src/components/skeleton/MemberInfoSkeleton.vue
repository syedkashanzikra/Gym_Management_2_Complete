<template>
  <base-section bordered flat class="user-details">
    <div class="col-xs-12 col-sm-4">
      <base-thumbnail :padding="24" full-width />
    </div>
    <div class="col-xs-12 col-sm-8">
      <div class="row q-col-gutter-md">
        <div class="col-xs-6 col-sm-6">
          <base-label>{{ $t("firstName") }}</base-label>
          <q-skeleton :animation="animation" width="100%" type="QBtn" />
        </div>
        <div class="col-xs-6 col-sm-6">
          <base-label>{{ $t("surname") }}</base-label>
          <q-skeleton :animation="animation" width="100%" type="QBtn" />
        </div>
        <div class="col-xs-12 col-sm-4">
          <base-label>{{ $t("startsAt") }}</base-label>
          <q-skeleton :animation="animation" width="100%" type="QBtn" />
        </div>
        <div class="col-xs-12 col-sm-4">
          <base-label>{{ $t("endsAt") }}</base-label>
          <q-skeleton :animation="animation" width="100%" type="QBtn" />
        </div>
        <div class="col-xs-12 col-sm-4">
          <base-label>{{ $t("label.status") }}</base-label>
          <q-skeleton :animation="animation" width="100%" type="QBtn" />
        </div>
        <div class="col-xs-12">
          <base-label>{{ $t("menus.membership") }}</base-label>
          <current-plan-skeleton :loading="loading" />
        </div>
      </div>
    </div>
  </base-section>
</template>
<script>
import CurrentPlanSkeleton from "./CurrentPlanSkeleton.vue";

export default {
  components: { CurrentPlanSkeleton },
  props: {
    loading: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    animation() {
      return this.loading ? "wave" : "none";
    },
  },
};
</script>
