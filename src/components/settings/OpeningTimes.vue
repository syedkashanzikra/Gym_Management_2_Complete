<template>
  <base-table
    class="opening-times"
    hide-top
    hide-pagination
    :rows-per-page-options="[0]"
    :columns="columns"
    :rows="options"
    :loaded="loaded"
  >
    <template v-slot:body-cell-open_at="props">
      <base-time-input v-model="props.row.open_at" />
    </template>
    <template v-slot:body-cell-close_at="props">
      <base-time-input v-model="props.row.close_at" />
    </template>
  </base-table>
</template>

<script>
export default {
  props: {
    options: Array,
    loaded: Boolean,
  },
  computed: {
    columns() {
      return [
        {
          name: "name",
          align: "left",
          label: this.$t("label.day"),
          field: "name",
        },
        {
          name: "open_at",
          align: "left",
          label: this.$t("label.openAt"),
          field: "open_at",
        },
        {
          name: "close_at",
          align: "left",
          label: this.$t("label.closeAt"),
          field: "close_at",
        },
      ];
    },
  },
};
</script>
