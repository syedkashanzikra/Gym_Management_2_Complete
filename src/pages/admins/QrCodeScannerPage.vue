<template>
  <q-page padding>
    <div
      :class="{
        'row q-col-gutter-md': true,
        reverse: $q.screen.xs,
      }"
    >
      <div class="col-sm-9 col-xs-12">
        <base-section v-if="loaded" class="user-details">
          <div class="col-xs-12 col-sm-4">
            <base-thumbnail :padding="24" full-width :media="member.avatar" />
          </div>
          <div class="col-xs-12 col-sm-8">
            <div class="row q-col-gutter-md">
              <div class="col-xs-6 col-sm-6">
                <base-label>{{ $t("firstName") }}</base-label>
                <base-input
                  readonly
                  v-model="member.first_name"
                  name="first_name"
                />
              </div>
              <div class="col-xs-6 col-sm-6">
                <base-label>{{ $t("surname") }}</base-label>
                <base-input
                  readonly
                  v-model="member.last_name"
                  name="last_name"
                />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>{{ $t("startsAt") }}</base-label>
                <base-date-input
                  outlined
                  dense
                  readonly
                  v-model="member.starts_at"
                  no-range
                  name="starts_at"
                />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>{{ $t("endsAt") }}</base-label>
                <base-date-input
                  outlined
                  dense
                  v-model="member.ends_at"
                  only-future
                  no-range
                  name="ends_at"
                  readonly
                  :hint="$t('hint.endsAt')"
                />
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>{{ $t("label.status") }}</base-label>
                <q-select
                  dense
                  outlined
                  v-model="member.status"
                  :options="['Active', 'Pending', 'Deactive', 'Hold', 'Lost']"
                  readonly
                  name="status"
                />
              </div>
              <div class="col-xs-12 col-sm-22">
                <base-info rounded :color="member.accessColor">
                  {{ member.accessMessage }}
                </base-info>
              </div>
              <div class="col-xs-12">
                <base-label>{{ $t("menus.membership") }}</base-label>
                <current-plan-card
                  class="q-mb-md"
                  :user-id="member?.id"
                  no-action
                />
              </div>
            </div>
          </div>
        </base-section>
        <member-info-skeleton :loading="loading" v-else />
      </div>
      <div
        :class="{ 'col-sm-3 col-xs-12 ': true, 'order-first': $q.screen.xs }"
      >
        <div class="row q-col-gutter-md">
          <div class="col-xs-12 col-sm-12 qr-code-scanner">
            <qr-code-scanner @detect="onDetect" />
          </div>
          <div class="col-xs-12 col-sm-12 web-cam-scanner">
            <webcam-qr-code-scanner @detect="onDetect" />
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import { Platform } from "quasar";
import WebcamQrCodeScanner from "components/WebcamQrCodeScanner.vue";
import CurrentPlanCard from "components/subscription/CurrentPlanCard.vue";
import MemberInfoSkeleton from "components/skeleton/MemberInfoSkeleton.vue";
import QrCodeScanner from "components/QrCodeScanner.vue";
import { mapActions } from "pinia";
import { useMemberStore } from "stores/member";

export default {
  components: {
    WebcamQrCodeScanner,
    QrCodeScanner,
    CurrentPlanCard,
    MemberInfoSkeleton,
  },
  data() {
    return {
      member: {},
      loaded: false,
      loading: false,
    };
  },

  methods: {
    ...mapActions(useMemberStore, ["showByQrcode"]),
    onDetect(val) {
      console.log("onDetect", val);
      if (this.loading) return;
      this.loaded = false;
      this.loading = true;
      this.showByQrcode(val)
        .then(async (member) => {
          this.member = member;
          this.loaded = true;
          this.loading = false;
        })
        .catch((error) => {
          this.$core.error(error);
          this.loading = false;
        });
    },
  },

  computed: {
    options() {
      const qrbox = Platform.is.mobile ? 180 : 220;
      return {
        fps: 10,
        qrbox: { width: qrbox, height: qrbox },
      };
    },
  },
};
</script>
