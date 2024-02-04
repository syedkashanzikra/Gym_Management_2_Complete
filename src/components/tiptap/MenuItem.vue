<template>
  <q-btn
    :class="{
      'menu-item': true,
      'bg-primary text-white': isActive ? isActive() : false,
      'no-right-icon': !items,
    }"
    @click="onClick"
    flat
    dense
    no-caps
    size="xs"
    padding="5px"
    tag="a"
    :icon="icon"
    :disable="isDisable ? isDisable() : false"
    :label="label ? label : ''"
    :icon-right="items ? 'fas fa-caret-down' : ''"
  >
    <q-tooltip
      v-if="tooltip"
      anchor="top middle"
      self="bottom middle"
      :offset="[0, 0]"
    >
      {{ title }}
    </q-tooltip>
    <q-menu>
      <q-list dense v-if="items" class="q-pa-sm" style="min-width: 100px">
        <sub-menu v-for="(item, index) in items" v-bind="item" :key="index" />
      </q-list>
    </q-menu>
  </q-btn>
</template>

<script>
import SubMenu from "src/components/tiptap/SubMenu.vue";

export default {
  components: { SubMenu },
  props: {
    icon: {
      type: [String],
      required: true,
    },

    items: {
      type: [Array, Boolean],
      required: false,
      default: false,
    },

    title: {
      type: [String],
      required: true,
    },

    label: {
      type: [String],
      required: false,
    },

    action: {
      type: [Function, Boolean],
      required: false,
    },

    isActive: {
      type: [Function, Boolean],
      default: false,
    },

    isDisable: {
      type: [Function, Boolean],
      default: () => false,
    },

    tooltip: {
      type: [Boolean],
      default: true,
    },
  },
  methods: {
    onClick() {
      if (!this.action) return false;
      this.action();
    },
  },
};
</script>

<style lang="sass">
.menu-item
  .block
    font-size: 11px !important
.no-right-icon
  .q-icon:last-child
    display: none
.q-item--active
  [class*="text-h"]
    color: inherit
</style>
