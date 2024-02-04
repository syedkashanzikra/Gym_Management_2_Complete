<template>
  <base-section flat bordered class="currnet-plan-card" dense no-row>
    <template #action>
      <div class="renewal-fee">
        <span class="amount text-subtitle2">
          {{ $core.money(price?.amount) }}
        </span>
        <span class="q-ml-xs interval">{{ intervalLabel }}</span>
      </div>
    </template>

    <template #title>
      <div class="text-h6">
        {{ price?.plan.label }}
        <base-status :type="planBadge" />
      </div>
    </template>

    <div v-if="subscription?.message">
      <span
        style="font-size: 13px"
        :class="{ 'text-negative': !isSubscribed }"
        v-html="subscription.message"
      ></span>
    </div>

    <div v-if="isSubscribed" class="q-py-sm usages">
      <div class="label text-h6">{{ $t("features") }}:</div>
      <q-item
        dense
        v-for="(item, index) in features"
        :key="index"
        class="feature text-grey-10 q-pa-none"
      >
        <q-item-section>
          <q-item-label>
            {{ $t("plan.features." + item.key) }}
            <q-icon size="12px" name="fal fa-info-circle">
              <base-tooltip>{{ $t("hint.features." + item.key) }}</base-tooltip>
            </q-icon>
          </q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-item-label>
            <span
              :class="{
                'text-bold': true,
                'text-negative': item.value === item.usage,
              }"
            >
              {{ item.usage }}
            </span>
            <span>/{{ item.value }}</span>
          </q-item-label>
        </q-item-section>
      </q-item>
    </div>
    <div v-else-if="price?.plan?.feature_lines" class="features">
      <ul>
        <li v-for="(feature, index) in price.plan.feature_lines" :key="index">
          <span v-html="feature"></span>
        </li>
      </ul>
    </div>

    <template v-if="!noAction" #bottom>
      <div class="text-right">
        <base-btn
          v-if="hasCancelled"
          @click="onResumePlan"
          :loading="resumeLoading"
          :label="$t('resumeNow')"
          color="warning"
          class="q-mr-sm"
          link
        />
        <base-btn
          v-else-if="isDowngrade"
          @click="onCancelDowngrade"
          :loading="cancelLoading"
          :label="$t('cancelDowngrade')"
          color="warning"
          class="q-mr-sm"
          link
        />
        <base-btn
          v-else-if="isActive"
          @click="onCancelPlan"
          :loading="cancelLoading"
          color="negative"
          :label="$t('cancelPlan')"
          class="q-mr-sm"
          link
        />
        <base-btn
          v-if="!noUpgrade"
          :to="{ name: 'Subscription' }"
          color="primary"
          :label="subscribeLabel"
          link
        />
        <base-btn
          v-else-if="!hasCancelled"
          @click="onChangePlan"
          color="primary"
          :label="subscribeLabel"
          link
        />
      </div>
    </template>

    <template v-if="isLoadingSubscription" #skeleton>
      <current-plan-skeleton />
    </template>
  </base-section>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useSubscriptionStore } from "stores/subscription";
import CurrentPlanSkeleton from "../skeleton/CurrentPlanSkeleton.vue";
import ChangePlan from "./ChangePlan.vue";
import { useAppStore } from "src/stores/app";

export default {
  components: { CurrentPlanSkeleton },
  props: {
    noUpgrade: Boolean,
    noAction: Boolean,
    userId: [String, Number],
  },
  emits: ["changed"],
  data() {
    return {
      cancelLoading: false,
      resumeLoading: false,
    };
  },
  methods: {
    ...mapActions(useAppStore, ["currentUser"]),
    ...mapActions(useSubscriptionStore, [
      "cancel",
      "cancelDowngrade",
      "resume",
      "getSubscribed",
      "setUser",
    ]),
    onCancelDowngrade() {
      this.cancelLoading = true;
      this.cancelDowngrade()
        .then(({ message }) => {
          this.cancelLoading = false;
          this.$core.success(message, {
            title: this.$t("dialog.title.success"),
          });
        })
        .catch((error) => {
          this.cancelLoading = false;
          this.$core.error(error);
        });
    },
    onCancelPlan() {
      this.$core
        .confirm(this.$t("dialog.info.cancel"), {
          title: this.$t("dialog.title.cancel"),
        })
        .then(() => {
          this.cancelLoading = true;
          console.func(
            "components/subscription/CurrentPlanCard:methods.onCancelPlan()",
            arguments
          );
          this.cancel()
            .then(({ message }) => {
              this.cancelLoading = false;
              this.$core.success(message, {
                title: this.$t("dialog.title.success"),
              });
            })
            .catch((error) => {
              this.cancelLoading = false;
              this.$core.error(error);
            });
        });
    },
    onResumePlan() {
      this.$core.confirm(`Are you sure you want to resume?`).then(() => {
        this.resumeLoading = true;
        console.func(
          "components/subscription/CurrentPlanCard:methods.onResumePlan()",
          arguments
        );
        this.resume()
          .then(({ message }) => {
            this.resumeLoading = false;
            this.$core.success(message, {
              title: this.$t("dialog.title.success"),
            });
          })
          .catch((error) => {
            this.resumeLoading = false;
            this.$core.error(error);
          });
      });
    },
    onChangePlan() {
      this.$q
        .dialog({
          component: ChangePlan,
          componentProps: {
            title: this.subscribeLabel,
          },
        })
        .onOk(async () => {
          this.$emit("changed");
          await this.getSubscribed();
        });
    },
  },
  computed: {
    ...mapState(useSubscriptionStore, [
      "subscription",
      "currentPlan",
      "upcomingInvoice",
      "isLoadingSubscription",
      "defaultPlan",
      "hasCancelled",
      "isSubscribed",
      "usages",
    ]),
    price() {
      return this.currentPlan || this.defaultPlan;
    },
    interval() {
      return this.$core.capitalize(this.price?.interval || "month") + "ly";
    },
    intervalLabel() {
      return this.$core.priceToInterval(this.price);
    },
    subscribeLabel() {
      return this.isSubscribed || this.hasCancelled
        ? this.$t("upgradePlan")
        : this.$t("subscribeNow");
    },
    isActive() {
      return this.subscription.is_valid;
    },
    isDowngrade() {
      return this.subscription.is_downgrade;
    },
    planBadge() {
      return this.subscription?.stripe_status || this.interval;
    },
    features() {
      return this.usages.map((item) => ({
        ...item,
        key: this.$core.toCamelCase(item.label),
      }));
    },
  },
  async mounted() {
    if (this.userId) {
      await this.setUser(this.userId);
      await this.getSubscribed();
    }
  },
};
</script>

<style lang="scss">
.currnet-plan-card {
  .amount {
    font-size: 18px;
  }
  .usages {
    .label {
      font-size: 16px;
    }
    .q-item {
      min-height: auto;
    }
  }
}
</style>
