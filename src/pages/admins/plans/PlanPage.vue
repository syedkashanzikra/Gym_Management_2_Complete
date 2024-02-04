<template>
  <q-page padding>
    <base-form
      v-if="loaded"
      @submit="onSubmit"
      @cancel="onCancel"
      @reset="onReset"
      :resetable="resetable"
      :disable="disable"
      :submited="submited"
    >
      <div class="q-gutter-md">
        <base-section
          flat
          bordered
          :title="$t('generalInformation')"
          :description="$t('plans.generalDesc')"
        >
          <div class="col-xs-12 col-sm-12">
            <base-label required>{{ $t("label.label") }}</base-label>
            <base-input v-model="plan.label" type="text" />
          </div>
          <div class="col-xs-12 col-sm-12">
            <q-checkbox
              dense
              v-model="plan.is_custom"
              :label="$t('customBillingPeriod')"
              color="green"
              :disable="!creating"
            />
          </div>
          <div class="col-xs-12 col-sm-12">
            <div class="row q-col-gutter-md">
              <template v-if="plan.is_custom">
                <div class="col-xs-12 col-sm-4">
                  <base-label required>{{ $t("renewalFee") }}</base-label>
                  <base-price-input
                    :readonly="!creating"
                    dense
                    outlined
                    v-model="plan.custom_fee"
                    type="text"
                    :hint="$t('hint.plan', { field: 'renewal fee' })"
                  />
                </div>
                <div class="col-xs-12 col-sm-8">
                  <base-label required>{{ $t("billingPeriod") }}</base-label>
                  <div class="row q-col-gutter-sm">
                    <div class="col-xs-12 col-sm-6">
                      <base-select
                        :readonly="!creating"
                        dense
                        outlined
                        v-model="plan.default_interval"
                        :options="intervalOptions"
                        @update:model-value="onChangeInterval"
                        emit-value
                        map-options
                        :hint="$t('hint.plan', { field: 'billing period' })"
                      />
                    </div>
                    <div
                      v-if="plan.default_interval === 'custom'"
                      class="col-xs-12 col-sm-6"
                    >
                      <div class="row q-col-gutter-sm">
                        <div class="col-sm-6 col-xs-6">
                          <base-input
                            :readonly="!creating"
                            dense
                            outlined
                            v-model="plan.interval_count"
                            :prefix="$t('prefix.every')"
                            type="number"
                            min="1"
                            step="1"
                            style="width: 100%"
                          />
                        </div>
                        <div class="col-sm-6 col-xs-6">
                          <base-select
                            :readonly="!creating"
                            dense
                            outlined
                            v-model="plan.interval"
                            :options="[
                              { label: 'days', value: 'day' },
                              { label: 'weeks', value: 'week' },
                              { label: 'months', value: 'month' },
                            ]"
                            emit-value
                            map-options
                            style="width: 100%"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
              <template v-else>
                <div class="col-xs-12 col-sm-4">
                  <base-label required>{{ $t("monthlyFee") }}</base-label>
                  <base-price-input
                    :readonly="!creating"
                    dense
                    outlined
                    v-model="plan.monthly_fee"
                    type="text"
                    :hint="$t('hint.plan', { field: 'monthly fee' })"
                  />
                </div>
                <div class="col-xs-12 col-sm-4">
                  <base-label required>{{ $t("yearlyFee") }}</base-label>
                  <base-price-input
                    :readonly="!creating"
                    dense
                    outlined
                    v-model="plan.yearly_fee"
                    type="text"
                    :hint="$t('hint.plan', { field: 'yearly fee' })"
                  />
                </div>
              </template>
            </div>
          </div>
          <div class="col-xs-12 col-sm-6">
            <base-label>{{ $t("description") }}</base-label>
            <base-input v-model="plan.description" type="textarea" />
          </div>
          <div class="col-xs-12 col-sm-6">
            <base-label>{{ $t("note") }}</base-label>
            <base-input v-model="plan.note" type="textarea" />
          </div>
          <div class="col-xs-12">
            <q-checkbox
              dense
              v-model="plan.is_active"
              :label="$t('active')"
              color="green"
            />
          </div>
        </base-section>
        <base-section
          flat
          bordered
          :title="$t('features')"
          :description="$t('plans.featureDesc')"
        >
          <div
            v-for="(feature, index) in features"
            :key="index"
            class="col-xs-12 col-sm-4"
          >
            <base-label required>
              {{ $t("plan.features." + feature.key) }}
            </base-label>
            <base-input
              v-model.number="plan.features[index].value"
              type="number"
              min="-1"
              :hint="$t('hint.features.' + feature.key)"
            />
          </div>
        </base-section>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions, mapState } from "pinia";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { usePlanStore } from "stores/plan";

const plan = {
  is_custom: false,
  default_interval: "month",
  interval: "month",
  interval_count: 1,
  custom_fee: 0,
  monthly_fee: 0,
  yearly_fee: 0,
  is_active: true,
  description: `Max <strong>1/mo</strong> Guest pass
Max <strong>5/mo</strong> Classes
Full Access to the Facilities
Classes and Online Portal`,
  features: [
    {
      label: "Classes",
      key: "classes",
      description: "Maximum classes can be booked and join.",
      value: 5,
    },
    {
      label: "Guest pass",
      key: "guestPass",
      description: "Allows non-members to try out the gym or studio facilities",
      value: 1,
    },
  ],
};

export default {
  components: {
    SkeletonSinglePage,
  },
  data() {
    return {
      default: cloneDeep(plan),
      plan: cloneDeep(plan),
      loaded: false,
      submited: false,
    };
  },
  methods: {
    ...mapActions(usePlanStore, ["store", "show", "update"]),
    onSubmit(props) {
      console.func("pages/admins/plans/PlanPage:methods.onSubmit()", arguments);
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.plan)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.plan = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Plan",
            params: {
              id: data.id,
            },
            query: {
              action: "edit",
            },
          });
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onReset(props) {
      console.func("pages/admins/plans/PlanPage:methods.onReset()", arguments);
      this.loaded = false;
      this.$nextTick(() => {
        this.plan = cloneDeep(this.default);
        this.loaded = true;
      });
    },
    onCancel(props) {
      console.func("pages/admins/plans/PlanPage:methods.onCancel()", arguments);
      this.$router.go(-1);
    },
    onChangeInterval(value) {
      console.func(
        "pages/admins/plans/PlanPage:methods.onChangeInterval()",
        arguments
      );
      if (value === "custom") {
        this.plan.interval = "month";
      } else {
        this.plan.interval = value;
      }
    },
  },
  async mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((data) => {
          this.plan = data;
          this.default = cloneDeep(data);
          this.loaded = true;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    } else {
      this.loaded = true;
    }
  },
  computed: {
    ...mapState(usePlanStore, ["intervalOptions"]),
    edit() {
      return ["add", "edit"].includes(this.action) || this.id === "add";
    },
    creating() {
      return this.id === "add";
    },
    id() {
      return this.$route.params.id;
    },
    action() {
      return this.$route.query.action;
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.plan) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.plan) !== JSON.stringify(this.default)
      );
    },
    features() {
      return this.plan.features.map((item) => ({
        ...item,
        key: this.$core.toCamelCase(item.label),
      }));
    },
  },
};
</script>
