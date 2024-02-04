<template>
  <q-toolbar class="q-px-none page-toolbar">
    <q-toolbar-title>
      <slot name="title"></slot>
    </q-toolbar-title>
    <base-btn-dropdown v-if="!noAction" size="13px" class="q-ml-sm" label="Actions">
      <q-list class="q-pa-sm" dense style="min-width: 150px">
        <slot name="actions"></slot>
      </q-list>
    </base-btn-dropdown>
  </q-toolbar>
</template>

<script>
export default {
  props: {
    noAction: Boolean,
  },
};
</script>
