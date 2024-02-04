<template>
  <base-select
    class="enquiry-status"
    :options="defaultStatusOptions"
    :model-value="modelValue"
    outlined
    dense
    options-html
    map-options
    emit-value
    @update:model-value="onChange"
  />
</template>

<script>
import { mapState } from "pinia";
import { useEnquiryStore } from "stores/enquiry";
export default {
  props: {
    modelValue: String,
  },
  methods: {
    onChange(value) {
      console.func("components/EnquiryStatus:methods.onChange", arguments);
      this.$emit("update:model-value", value);
    },
  },
  computed: {
    ...mapState(useEnquiryStore, ["defaultStatusOptions"]),
  },
};
</script>
