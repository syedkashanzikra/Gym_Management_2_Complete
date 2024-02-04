<template>
  <q-avatar
    :rounded="!square"
    :square="square"
    :size="`${width}px`"
    :font-size="`${width / 2}px`"
    :class="{
      'base-thumbnail': true,
      flat: flat,
    }"
  >
    <template v-if="media">
      <q-img v-if="media.is_image" loading="lazy" :src="media.url" contain>
        <template v-slot:loading>
          <q-spinner-orbit color="white" />
        </template>
      </q-img>
      <q-icon v-else-if="media.icon" :name="`fad fa-file-${media.icon}`" />
      <q-icon v-else name="fad fa-image" />
    </template>
    <template v-else>
      <q-icon name="fad fa-image" />
    </template>
    <slot></slot>
  </q-avatar>
</template>

<script>
export default {
  props: {
    media: Object,
    size: {
      type: [Number, String],
      default: 50,
    },
    square: Boolean,
    rounded: {
      type: [Boolean],
      default: true,
    },
    flat: Boolean,
    fullWidth: Boolean,
    padding: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      maxWidth: this.size,
    };
  },
  computed: {
    width() {
      return this.fullWidth ? this.maxWidth - this.padding : this.size;
    },
  },
  mounted() {
    window.addEventListener("resize", this.updateParentWidth);
    this.$nextTick(() => {
      this.maxWidth = this.$el.parentElement.offsetWidth;
    });
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.updateParentWidth);
  },
  methods: {
    updateParentWidth() {
      this.maxWidth = this.$el.parentElement.offsetWidth;
    },
  },
};
</script>
<style lang="sass" scoped>
.base-thumbnail
  &.flat
    border: none
  border: 1px solid $separator-color
</style>
