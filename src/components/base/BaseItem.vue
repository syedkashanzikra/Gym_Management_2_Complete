<template>
  <q-item
    clickable
    v-close-popup
    :class="{
      'base-item': true,
      link: link,
      'no-hover': noHover,
      'rounded-borders': !squared,
    }"
  >
    <slot>
      <q-item-section v-if="icon || iconSpace" style="min-width: auto" avatar>
        <q-icon :color="iconColor" size="15px" :name="icon" />
      </q-item-section>
      <q-item-section>
        {{ label }}
      </q-item-section>
      <q-item-section v-if="notification" side>
        <q-badge
          rounded
          color="warning"
          text-color="black"
          class="text-bold"
          :label="notification"
        />
      </q-item-section>
    </slot>
  </q-item>
</template>

<script>
export default {
  props: {
    label: [Number, String],
    icon: String,
    iconColor: String,
    notification: [String, Number],
    link: Boolean,
    squared: Boolean,
    noHover: Boolean,
    iconSpace: Boolean,
  },
};
</script>
