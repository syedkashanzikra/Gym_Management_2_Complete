<template>
  <q-expansion-item
    v-if="subLinks"
    v-model="active"
    :icon="icon"
    :label="title"
    expand-icon-class="hidden"
    class="sublinks"
    :header-class="{
      'sublink-header': true,
      'sublink-active': active,
      'rounded-borders': rounded,
    }"
    :to="{ name: route }"
    :dense="dense"
  >
    <base-item
      v-for="(item, index) in subLinks"
      :key="index"
      clickable
      tag="a"
      :to="{ name: item.route, params: item.params }"
      :exact="item.exact"
      v-ripple
      active-class="active"
      :dense="dense"
      :icon="item.icon"
      :label="item.title"
      :notification="item.notification"
      icon-space
    />
  </q-expansion-item>
  <base-item
    v-else
    clickable
    tag="a"
    :to="{ name: route, params: params }"
    v-ripple
    active-class="active"
    :dense="dense"
    :icon="icon"
    :label="title"
    :notification="notification"
  />
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    route: {
      type: String,
      default: "",
    },
    params: {
      type: Object,
      default: () => ({}),
    },
    icon: {
      type: String,
      default: "",
    },
    notification: [String, Number],
    subLinks: {
      type: [Object, Boolean],
      default: false,
    },
    exact: Boolean,
    dense: Boolean,
    rounded: Boolean,
  },
  computed: {
    active: {
      get() {
        return (
          this.$route.meta.base === this.route ||
          this.$route.name === this.route
        );
      },
      set(val) {
        return val;
      },
    },
  },
  methods: {
    isActive({ route }) {
      return [
        this.$route.meta.module,
        this.$route.meta.premission,
        this.$route.name,
      ].includes(route);
    },
  },
};
</script>
