<template>
  <q-item-label>
    <template v-if="track_inventory">
      {{ available }} available at {{ activeLocation.length }} locations
    </template>
    <template v-else>Stocked at {{ activeLocation.length }} locations</template>
  </q-item-label>
</template>

<script>
export default {
  props: {
    inventories: {
      required: true,
      type: Array,
    },
    track_inventory: {
      required: true,
      type: Boolean,
      default: false,
    },
  },
  computed: {
    available() {
      return this.activeLocation.length
        ? this.activeLocation
            .map((item) => parseInt(item.available))
            .reduce((total, available) => total + available)
        : 0;
    },
    activeLocation() {
      return this.inventories.filter((item) => item.active);
    },
  },
};
</script>
