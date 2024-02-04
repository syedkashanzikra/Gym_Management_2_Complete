<template>
  <q-input
    :dense="dense"
    :readonly="view || readonly"
    :bg-color="bgColor"
    :outlined="outlined && !borderless"
    :borderless="borderless"
    :modelValue="modelValue"
    :type="type"
    :label="label"
    :autogrow="isAutoGrow"
    debounce="500"
    @update:model-value="onChange"
    :class="{
      'base-input': true,
      'view-mode': view,
    }"
  >
    <template v-for="slot in scopedSlots" v-slot:[slot]="props">
      <slot :name="slot" v-bind="props" v-bind:props="props"></slot>
    </template>
  </q-input>
</template>

<script>
export default {
  props: {
    modelValue: {},
    type: String,
    label: String,
    bgColor: String,
    dense: {
      type: Boolean,
      default: true,
    },
    outlined: {
      type: Boolean,
      default: true,
    },
    borderless: Boolean,
    view: Boolean,
    readonly: Boolean,
    autogrow: Boolean,
  },
  methods: {
    onChange(value) {
      console.func("components/base/BaseInput:methods.onChange", arguments);
      this.$emit("update:model-value", value);
    },
  },
  computed: {
    scopedSlots() {
      return Object.keys(this.$slots);
    },
    isAutoGrow() {
      return (
        this.type === "textarea" &&
        (this.view || this.readonly || this.autogrow)
      );
    },
  },
};
</script>
