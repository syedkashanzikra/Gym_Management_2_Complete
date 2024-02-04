<template>
  <base-section-skeleton :loading="loading" flat bordered>
    <q-skeleton :animation="animation" width="50%" type="text" />
    <q-skeleton :animation="animation" width="55%" type="text" />
    <q-skeleton :animation="animation" width="50%" type="text" />
  </base-section-skeleton>
</template>

<script>
import BaseSectionSkeleton from "./BaseSectionSkeleton.vue";
export default {
  components: { BaseSectionSkeleton },
  props: {
    loading: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    animation() {
      return this.loading ? "wave" : "none";
    },
  },
};
</script>
