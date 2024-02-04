<template>
  <q-popup-edit
    label-set="Save"
    class="q-pa-none"
    style="width: 300px; max-width: 95vw"
    content-class="base-popup-edit"
    v-model="collect_tax"
    v-slot="scope"
    @update:model-value="(val) => $emit('update:model-value', val)"
  >
    <div class="q-pa-md">
      <div class="q-mb-sm">Taxes are automatically calculated.</div>
      <q-checkbox dense v-model="scope.value" label="Collect taxes" />
    </div>
    <q-separator />
    <div class="q-pa-md text-right">
      <div class="q-gutter-sm">
        <q-btn size="12px" @click="scope.cancel()" no-caps color="grey" outline label="Cancel" />
        <q-btn size="12px" @click="scope.set()" no-caps color="positive" label="Apply" />
      </div>
    </div>
  </q-popup-edit>
</template>

<script>
export default {
  props: {
    modelValue: [Boolean],
  },
  emits: ['update:model-value'],
  data() {
    return {
      collect_tax: this.modelValue,
    };
  },
};
</script>
