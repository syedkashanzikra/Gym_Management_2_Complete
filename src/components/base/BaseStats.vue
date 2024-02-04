<template>
  <q-item>
    <q-item-section style="min-width: auto" avatar>
      <q-icon :color="color" size="10px" name="fas fa-square" />
    </q-item-section>
    <q-item-section>
      <q-item-label class="text-left">
        {{ label }} <span v-if="!left" class="q-ml-xs">({{ value }})</span>
      </q-item-label>
    </q-item-section>
    <q-item-section v-if="left" side>
      <q-item-label>
        {{ value }}
      </q-item-label>
    </q-item-section>
  </q-item>
</template>
<script>
export default {
  props: {
    value: [String, Number],
    label: String,
    color: String,
    left: Boolean,
  },
};
</script>
