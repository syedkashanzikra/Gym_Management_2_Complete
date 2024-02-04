<template>
  <q-drawer
    ref="drawer"
    v-model="value"
    @update:model-value="(val) => $emit('update:model-value', val)"
    show-if-above
    :breakpoint="1280"
    :width="250"
    side="left"
    class="bg-white text-black"
  >
    <q-toolbar v-if="user && $q.screen.lt.sm" class="flex-center q-pa-md">
      <img width="110" src="~assets/logo.png" />
    </q-toolbar>
    <q-list class="bg-white q-pa-md" :style="listStyle">
      <links-list
        class="link-item"
        v-for="link in sideMenus"
        :key="link.title"
        v-bind="link"
        rounded
        dense
      />
    </q-list>
    <slot></slot>
  </q-drawer>
</template>

<script>
import { mapState } from "pinia";
import LinksList from "./LinksList.vue";
import { useAppStore } from "stores/app";

export default {
  components: {
    LinksList,
  },
  props: {
    modelValue: {
      required: false,
    },
    sideMenus: {
      required: true,
      type: Array,
    },
    listStyle: [String, Object],
  },
  emits: ["update:model-value"],
  data() {
    return {
      value: this.modelValue,
    };
  },
  methods: {
    toggle() {
      this.$refs.drawer.toggle();
    },
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
  },
};
</script>
