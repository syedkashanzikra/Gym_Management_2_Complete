<template>
  <base-input
    readonly
    :class="{
      'base-color-input': true,
      'use-picker': usePicker,
    }"
    dense
  >
    <template #prepend>
      <q-icon name="fas fa-square" :style="{ color: modelValue }">
        <q-popup-proxy transition-show="scale" transition-hide="scale">
          <q-color :model="modelValue" @update:model-value="onChange" />
        </q-popup-proxy>
      </q-icon>
    </template>
    <template v-if="usePicker" #append>
      <q-icon name="colorize" class="cursor-pointer">
        <q-popup-proxy transition-show="scale" transition-hide="scale">
          <q-color :model="modelValue" @update:model-value="onChange" />
        </q-popup-proxy>
      </q-icon>
    </template>
  </base-input>
</template>

<script>
export default {
  props: {
    modelValue: {},
    usePicker: Boolean,
  },
  methods: {
    onChange(value) {
      console.func(
        "components/base/BaseColorInput:methods.onChange",
        arguments
      );
      this.$emit("update:model-value", value);
    },
  },
};
</script>

<style lang="scss">
.base-color-input {
  &:not(.use-picker) {
    .q-field__prepend {
      padding-right: 0;
    }
    .q-field__control-container {
      display: none;
    }
  }
  &.q-field--borderless .q-field__control {
    &:before,
    &:after {
      border: none;
    }
  }
}
</style>
