<template>
  <q-card
    :class="{
      'plan-card cursor-pointer': true,
      'current bg-secondary': isCurrent && (!noRadio || !dense),
      selected: isSelected,
    }"
    @click="onChange"
  >
    <q-card-section>
      <q-item class="q-pa-none">
        <q-item-section>
          <q-item-label :class="{ flex: !dense }">
            <div class="name text-subtitle2">
              {{ label }}
            </div>
            <q-space />
            <div class="text-grey price">
              <span class="text-subtitle2">
                {{ $core.money(price?.amount) }}
              </span>
              <span>{{ intervalLabel }}</span>
            </div>
          </q-item-label>
          <q-item-label v-if="!dense">
            <div class="features">
              <ul>
                <li v-for="(feature, index) in features" :key="index">
                  <span v-html="feature"></span>
                </li>
              </ul>
            </div>
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-card-section>
  </q-card>
</template>

<script>
import { mapState } from "pinia";
import { useSubscriptionStore } from "stores/subscription";
export default {
  emits: ["update:current"],
  props: {
    id: [Number, String],
    current: [Number, String],
    label: String,
    features: {
      type: Array,
      default: () => [],
    },
    prices: {
      type: Array,
      default: () => [],
    },
    interval: String,
    isCustom: Boolean,
    noRadio: Boolean,
    dense: Boolean,
  },
  methods: {
    onChange() {
      if (this.noRadio) return false;
      let mode = "create";
      if (this.currentPlan?.id) {
        mode = "change";
      }
      this.$emit("update:current", this.id, mode);
    },
  },
  computed: {
    ...mapState(useSubscriptionStore, ["currentPlan", "isSubscribed"]),
    price() {
      if (this.isCustom) {
        return this.prices[0];
      }
      return this.prices.find((item) => item.interval === this.interval);
    },
    isCurrent() {
      return this.price?.stripe_id === this.currentPlan?.stripe_id;
    },
    isSelected() {
      return this.current === this.id;
    },
    intervalLabel() {
      return this.$core.priceToInterval(this.price, false, true);
    },
  },
  watch: {
    interval() {
      if (this.current === this.id) {
        this.onChange();
      }
    },
  },
};
</script>

<style lang="scss">
.plan-card.selected {
  border-color: $primary;
  background-color: rgba($primary, 0.2);
}
</style>
