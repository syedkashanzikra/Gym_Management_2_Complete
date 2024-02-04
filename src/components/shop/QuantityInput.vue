<template>
  <base-input
    class="quantity-input"
    input-style="text-align: center"
    :model-value="modelValue"
    debounce="500"
    @update:model-value="onChange"
  >
    <template #prepend>
      <q-btn
        size="xs"
        color="negative"
        dense
        flat
        round
        icon="fas fa-minus"
        :disable="modelValue < 2"
        @click="onDecrease"
      />
    </template>
    <template #append>
      <q-btn
        size="xs"
        color="positive"
        dense
        flat
        round
        icon="fas fa-plus"
        @click="onIncrease"
      />
    </template>
  </base-input>
</template>

<script>
export default {
  props: {
    modelValue: [Number, String],
  },
  emits: ["update:model-value"],
  methods: {
    onChange(val) {
      this.$emit("update:model-value", val);
    },
    onIncrease() {
      this.$emit("update:model-value", parseInt(this.modelValue) + 1);
    },
    onDecrease() {
      this.$emit("update:model-value", parseInt(this.modelValue) - 1);
    },
  },
};
</script>
