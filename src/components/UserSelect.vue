<template>
  <base-select
    ref="user"
    :placeholder="$t('placeholder.selectMember')"
    :model-value="modelValue"
    :filter-method="get"
    map-options
    :hide-no-option="hideNoOption"
    :option-label="optionLabel"
    :query="
      (val) => ({
        filter: val,
        rowsPerPage: 10,
        option: true,
        blocked: true,
      })
    "
    @update:model-value="onChange"
    @new-value="onNewValue"
  >
    <template v-for="slot in scopedSlots" v-slot:[slot]="props">
      <slot :name="slot" v-bind="props" v-bind:props="props"></slot>
    </template>
  </base-select>
</template>

<script>
import { mapActions } from "pinia";
import { useMemberStore } from "src/stores/member";

const excludes = [
  "prepend",
  "selected",
  "before-options",
  "after-options",
  "no-option",
];

export default {
  props: {
    modelValue: {},
    hideNoOption: Boolean,
    onNewValue: Function,
    optionLabel: [Function, String],
  },
  emits: ["update:model-value"],
  methods: {
    ...mapActions(useMemberStore, ["get"]),
    onChange(val) {
      console.func("components/UserSelect:methods.onChange()", arguments);
      this.$emit("update:model-value", val);
    },
    clear() {
      console.func("components/UserSelect:methods.clear()", arguments);
      this.$refs.user.clear();
    },
  },
  computed: {
    scopedSlots() {
      return Object.keys(this.$slots).filter((item) => excludes.includes(item));
    },
  },
};
</script>
