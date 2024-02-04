<template>
  <q-banner
    class="base-info"
    :style="{
      background: bgColor,
    }"
  >
    <div class="flex row items-center">
      <div class="col-auto q-mr-md avatar">
        <slot name="avatar">
          <q-icon
            size="sm"
            name="fas fa-info-circle"
            :color="color ?? 'warning'"
          />
        </slot>
      </div>
      <div class="col message">
        <slot></slot>
      </div>
    </div>
  </q-banner>
</template>
<script>
import { colors } from "quasar";
const { getPaletteColor, changeAlpha } = colors;

export default {
  props: {
    color: {
      type: String,
      deafult: "warning",
    },
  },
  computed: {
    bgColor() {
      return changeAlpha(getPaletteColor(this.color ?? "warning"), 0.3);
    },
  },
};
</script>
