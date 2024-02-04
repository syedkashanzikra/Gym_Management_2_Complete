<template>
  <q-item
    class="rounded-borders"
    :disable="isDisable ? isDisable() : false"
    :active="isActive ? isActive() : false"
    active-class="text-teal-9 bg-teal-1"
    @click="action ? action() : false"
    clickable
    v-close-popup
  >
    <q-item-section v-if="icon" avatar style="min-width: auto"><q-icon style="font-size: 15px" :name="icon" /></q-item-section>
    <q-item-section :class="contentClass">
      <span class="q-pt-sm q-pb-sm">{{ title }}</span>
    </q-item-section>
  </q-item>
</template>

<script>
export default {
  props: {
    isDisable: {
      type: [Function, Boolean],
      default: () => false,
    },
    isActive: {
      type: [Function, Boolean],
      default: false,
    },
    action: {
      type: [Function, Boolean],
      default: false,
    },
    contentClass: {
      type: [String],
    },
    title: {
      type: [String],
    },
    icon: {
      type: [String, Boolean],
      default: false,
    },
  },
}
</script>
