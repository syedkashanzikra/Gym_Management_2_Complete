<template>
  <q-avatar
    :class="{ 'base-avatar': true, overlapping: isOverlapping }"
    :rounded="rounded"
    :square="square"
    :size="size"
    :style="styles"
    text-color="white"
  >
    <template v-if="user.avatar">
      <img :src="user.avatar.url" />
      <q-badge
        v-if="['online', 'offline'].includes(user.status)"
        color="transparent"
        floating
      >
        <q-icon
          dense
          round
          name="brightness_1"
          :color="user.status == 'online' ? 'positive' : 'grey'"
          size="10px"
        />
      </q-badge>
    </template>
    <template v-else-if="user.name || user.company_name">
      <span>{{ letters }}</span>
      <q-badge
        v-if="['online', 'offline'].includes(user.status)"
        color="transparent"
        floating
      >
        <q-icon
          dense
          round
          name="brightness_1"
          :color="user.status == 'online' ? 'positive' : 'grey'"
          size="10px"
        />
      </q-badge>
    </template>
    <base-tooltip v-if="tooltip" position="top">
      {{ user.name }}
    </base-tooltip>
  </q-avatar>
</template>

<script>
export default {
  props: {
    user: {
      required: true,
      type: Object,
    },
    size: {
      required: false,
      type: String,
      default: "60px",
    },
    tooltip: Boolean,
    rounded: Boolean,
    square: Boolean,
    overlapping: Number,
  },
  computed: {
    styles() {
      const styles = {
        background: this.color,
      };
      if (this.isOverlapping) {
        styles.left = this.overlapping * 25 + "px";
      }
      return styles;
    },
    isOverlapping() {
      return !isNaN(this.overlapping);
    },
    letters() {
      return (this.user.name || this.user.company_name)
        .split(" ")
        .slice(-2)
        .map((item) => item.charAt(0))
        .join("");
    },
    color() {
      return this.$core.stringToHslColor(
        this.user.name || this.user.company_name
      );
    },
  },
};
</script>
