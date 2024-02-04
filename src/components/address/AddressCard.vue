<template>
  <q-item class="address-card q-pa-none" dense>
    <q-item-section>
      <div>{{ first_name }} {{ last_name }}</div>
      <div>{{ line1 }}</div>
      <div>{{ city }}</div>
      <div>{{ state }} {{ postcode }}</div>
      <div>{{ country }}</div>
    </q-item-section>
  </q-item>
</template>

<script>
export default {
  props: {
    first_name: [String],
    last_name: [String],
    line1: [String],
    city: [String],
    state: [String],
    postcode: [String],
    country: [String],
  },
};
</script>

<style lang="sass">
.address-card
  .action-btn
    visibility: hidden
  &:hover .action-btn, .active
      visibility: visible
</style>
