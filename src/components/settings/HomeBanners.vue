<template>
  <base-table
    class="home-banners"
    hide-top
    hide-pagination
    :rows-per-page-options="[0]"
    :columns="columns"
    :rows="options"
    :loaded="loaded"
  >
    <template v-slot:body-cell-title="props">
      <q-item class="q-pa-none" dense>
        <q-item-section avatar>
          <base-thumbnail :size="40" :media="props.row.thumbnail" />
        </q-item-section>
        <q-item-section avatar>
          <base-btn @click.stop="rowClicked(props)" link>
            {{ props.value }}
          </base-btn>
        </q-item-section>
      </q-item>
    </template>
    <template v-slot:body-cell-is_active="props">
      <q-toggle dense v-model="props.row.is_active" />
    </template>
    <template v-slot:actions="props">
      <q-btn
        @click.stop="rowClicked(props)"
        size="sm"
        round
        flat
        color="grey"
        icon="fas fa-edit"
      />
      <q-btn
        @click.stop="onRemove(props)"
        size="sm"
        round
        flat
        color="grey"
        icon="fas fa-trash-can"
      />
    </template>
  </base-table>
</template>

<script>
import BannerDialog from "../BannerDialog.vue";

export default {
  props: {
    options: Array,
    loaded: Boolean,
  },
  emits: ["remove", "update"],
  methods: {
    async rowClicked({ rowIndex, row }) {
      console.func(
        "components/base/settings/HomeBanner:methods.rowClicked()",
        arguments
      );
      this.$q
        .dialog({
          component: BannerDialog,
          componentProps: {
            modelValue: row,
          },
        })
        .onOk((value) => {
          this.$emit("update", {
            value,
            rowIndex,
          });
        });
    },
    onRemove({ rowIndex }) {
      console.func(
        "components/base/settings/HomeBanner:methods.onRemove()",
        arguments
      );
      this.$emit("remove", rowIndex);
    },
    onChange(value, { rowIndex }) {
      console.func(
        "components/base/settings/HomeBanner:methods.onChange()",
        arguments
      );
      this.$emit("update", {
        value,
        rowIndex,
      });
    },
  },
  computed: {
    columns() {
      return [
        {
          name: "title",
          align: "left",
          label: this.$t("label.title"),
          field: "title_line_1",
        },
        {
          name: "is_active",
          align: "center",
          label: this.$t("label.active"),
          field: "is_active",
        },
        { name: "actions", align: "right", sortable: false },
      ];
    },
  },
};
</script>
