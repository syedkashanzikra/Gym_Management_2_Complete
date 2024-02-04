<template>
  <q-input
    class="base-input base-date-input"
    :placeholder="mask"
    readonly
    :modelValue="selected"
  >
    <template v-slot:append>
      <q-icon
        @click.stop
        v-show="!readonly"
        name="event"
        class="cursor-pointer"
      >
        <q-popup-proxy
          @before-hide="onManualEmit"
          cover
          transition-show="scale"
          transition-hide="scale"
        >
          <q-date
            @update:model-value="onChange"
            today-btn
            :range="range"
            v-model="date"
            first-day-of-week="1"
            :options="options"
          >
            <div class="row items-center justify-end q-gutter-sm">
              <div v-if="!noRange">
                <q-toggle
                  size="sm"
                  v-model="range"
                  dense
                  :label="$t('range')"
                />
              </div>
              <q-btn
                :label="$t('clear')"
                color="primary"
                @click.stop="onClear"
                flat
                v-close-popup
              />
              <q-btn
                @click.stop="onManualEmit"
                :label="$t('ok')"
                color="primary"
                flat
                v-close-popup
              />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>

<script>
export default {
  props: {
    modelValue: [String, Object],
    readonly: Boolean,
    disable: Boolean,
    noRange: Boolean,
    manualEmit: Boolean,
    debounce: {
      type: [Number, String],
      default: "0",
    },
    mask: {
      type: String,
      default: "DD/MM/YYYY",
    },
    onlyFuture: Boolean,
  },
  data() {
    return {
      range: false,
      date: this.modelValue,
    };
  },
  emits: ["update:model-value"],
  methods: {
    onChange(value) {
      console.func("components/base/BaseDateInput:methods.onChange", arguments);
      if (this.manualEmit) return;
      const emit = () => this.$emit("update:model-value", value);
      this.$core.debounce(emit, this.debounce);
    },
    onManualEmit() {
      console.func(
        "components/base/BaseDateInput:methods.onManualEmit",
        arguments
      );
      if (!this.manualEmit || this.noChange) return;
      this.$emit("update:model-value", this.date);
    },
    onClear() {
      console.func("components/base/BaseDateInput:methods.onClear", arguments);
      if (!this.date) return;
      this.date = undefined;
      this.range = false;
      this.$emit("update:model-value", undefined);
    },
    options(date) {
      if (this.onlyFuture) {
        return date > this.$core.formatDate(new Date(), "YYYY/MM/DD");
      }
      return true;
    },
  },
  computed: {
    selected() {
      if (!this.modelValue) return "";

      if (typeof this.modelValue === "object" && "from" in this.modelValue) {
        const fromDate = this.$core.formatDate(this.modelValue.from, this.mask);
        const toDate = this.$core.formatDate(this.modelValue.to, this.mask);
        return `${fromDate} - ${toDate}`;
      }

      return this.$core.formatDate(this.modelValue, this.mask);
    },

    noChange() {
      return this.date === this.modelValue;
    },
  },
};
</script>
