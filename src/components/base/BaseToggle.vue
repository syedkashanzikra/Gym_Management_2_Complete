<template>
  <q-toggle
    class="base-toggle"
    :model-value="modelValue"
    @update:model-value="onChange"
  />
</template>

<script>
export default {
  props: {
    modelValue: {
      required: true,
    },
  },
  emits: ["update:model-value"],
  methods: {
    onChange(val) {
      console.func("components/base/BaseToggle:methods.onChange()", arguments);
      this.$emit("update:model-value", val);
    },
  },
};
</script>
