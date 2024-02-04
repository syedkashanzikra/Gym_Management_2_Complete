<template>
  <q-field
    class="base-number-input"
    dense
    outlined
    :model-value="modelValue"
    @update:model-value="onChange"
  >
    <template v-slot:prepend>
      <slot name="prepend">
        <!-- <span style="font-size: 12px">$</span> -->
      </slot>
    </template>
    <template v-slot:control="{ id, modelValue, emitValue }">
      <vue-number
        :id="id"
        class="q-field__input"
        :model-value="modelValue"
        @update:model-value="emitValue"
        v-bind="{
          decimal: '.',
          separator: ',',
          prefix: '',
          suffix: '',
          precision: precision,
          minimumFractionDigits: minimumFractionDigits,
          masked: false,
          nullValue: '',
          max: max,
          min: min,
        }"
        :placeholder="placeholder"
      />
    </template>
  </q-field>
</template>

<script>
export default {
  props: {
    modelValue: {
      required: true,
    },
    placeholder: {
      required: false,
      type: [String],
      default: "0.00",
    },
    precision: {
      required: false,
      type: [Number],
      default: 2,
    },
    minimumFractionDigits: {
      required: false,
      type: [Number],
      default: 2,
    },
    max: [Number, String, Boolean],
    min: [Number, String, Boolean],
  },
  emits: ["update:model-value"],
  methods: {
    onChange(val) {
      console.func(
        "components/base/input/base-number:methods.onChange()",
        arguments
      );
      this.$emit("update:model-value", val);
    },
  },
};
</script>
