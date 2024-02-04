<template>
  <q-radio dense class="base-checkbox">
    <slot name="label">
      <div class="label">{{ label }}</div>
    </slot>
    <slot v-if="!noHint" name="hint">
      <div class="hint">{{ hint }}</div>
    </slot>
  </q-radio>
</template>

<script>
export default {
  props: {
    label: {
      required: false,
      type: [String],
    },
    hint: {
      required: false,
      type: [String],
    },
    noHint: {
      required: false,
      type: [Boolean],
      default: false,
    },
  },
};
</script>
