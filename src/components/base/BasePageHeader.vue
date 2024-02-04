<template>
  <div class="base-page-header">
    <div class="text-h5">{{ title }}</div>
    <slot name="hint">
      <div class="text-grey-8" v-html="hint"></div>
    </slot>
    <q-tabs
      v-if="tabs && !noTabs"
      :model-value="modelValue"
      @update:model-value="$emit('update:model-value', $event)"
      active-color="primary"
      class="base-tabs"
      align="left"
      dense
    >
      <template v-if="useRoute">
        <q-route-tab
          v-for="(item, index) in cTabs"
          :key="index"
          :to="'/settings/' + item.value"
          :label="item.label"
          no-caps
        />
      </template>
      <template v-else>
        <q-tab
          v-for="(item, index) in cTabs"
          :key="index"
          :name="item.value"
          :label="item.label"
          no-caps
        />
      </template>
    </q-tabs>
    <q-separator v-show="useSeparator" :class="{ 'q-mt-sm': notab }" />
  </div>
</template>

<script>
export default {
  emits: ["update:model-value"],
  props: {
    modelValue: String,
    title: String,
    hint: String,
    useSeparator: {
      type: Boolean,
      default: true,
    },
    tabs: Array,
    noTabs: Boolean,
    useRoute: Boolean,
  },
  computed: {
    cTabs() {
      return this.tabs?.map((item) => ({
        label: item,
        value: item
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, "-")
          .replace(/^-+/, "")
          .replace(/-+$/, ""),
      }));
    },
    notab() {
      return this.tabs?.length < 1 || this.noTabs;
    },
  },
};
</script>
