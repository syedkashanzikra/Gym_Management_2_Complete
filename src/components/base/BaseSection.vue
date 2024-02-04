<template>
  <q-card
    :dark="dark"
    :square="square"
    :flat="flat || bordered"
    :bordered="bordered"
    :class="getSectionClass"
  >
    <template v-if="!!$slots.skeleton">
      <slot name="skeleton"></slot>
    </template>
    <template v-else>
      <q-card-section :class="[headClass, 'section-header']" v-if="hasTop">
        <div class="flex">
          <slot name="title">
            <div v-if="title" class="text-h6">{{ title }}</div>
          </slot>
          <q-space />
          <slot name="action"></slot>
        </div>
        <q-item-label v-if="description">{{ description }}</q-item-label>
      </q-card-section>
      <q-card-section v-bind:class="[getBodyClass, 'section-body']">
        <template v-if="!noRow">
          <div :class="`row q-col-gutter-${gutter}`">
            <slot></slot>
          </div>
        </template>
        <template v-else>
          <slot></slot>
        </template>
      </q-card-section>
      <template v-if="!!$slots.bottom">
        <q-separator />
        <q-card-section class="section-bottom">
          <slot name="bottom"></slot>
        </q-card-section>
      </template>
    </template>
  </q-card>
</template>

<script>
import { Screen } from "quasar";

export default {
  props: {
    dark: Boolean,
    square: Boolean,
    flat: Boolean,
    bordered: Boolean,
    bodyClass: String,
    headClass: String,
    title: String,
    description: String,
    gutter: {
      type: String,
      default: Screen?.xs ? "md" : "lg",
    },
    noRow: Boolean,
    dense: Boolean,
  },
  computed: {
    hasTop() {
      return (
        this.title ||
        this.description ||
        !!this.$slots.action ||
        !!this.$slots.title
      );
    },
    getBodyClass() {
      const classes = [this.bodyClass];
      if (this.hasTop) {
        classes.push("q-pt-none");
      }
      if (!!this.$slots.bottom) {
        classes.push("q-py-" + this.gutter);
        classes.push("q-pt-" + this.gutter);
      } else {
        classes.push("q-pa-" + this.gutter);
      }
      return classes.join(" ");
    },
    getSectionClass() {
      const classes = ["base-section", "base-section-" + this.gutter];
      if (!!this.$slots.bottom) {
        classes.push("has-bottom");
      }
      return classes;
    },
  },
};
</script>
