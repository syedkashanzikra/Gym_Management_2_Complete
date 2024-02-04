<template>
  <base-table
    class="documents-list"
    hide-top
    hide-pagination
    :rows-per-page-options="[0]"
    :columns="columns"
    :rows="options"
    :loaded="loaded"
  >
    <template v-slot:body-cell-name="props">
      <q-item class="q-pa-none">
        <q-item-section avatar>
          <file-selector
            mini
            accept="application/pdf"
            dialog-label="uploadFile"
            :hint="$t('hint.pdfs', { type: 'document' })"
            load-from-server
            v-model="props.row"
            @update:model-value="(val) => onChange(val, props)"
            :ref="`docs-${props.rowIndex}`"
          />
        </q-item-section>
        <q-item-section>
          <q-item-label>
            <base-btn
              @click="$refs[`docs-${props.rowIndex}`].onDetails()"
              :label="props.row.name"
              link
            />
          </q-item-label>
        </q-item-section>
      </q-item>
    </template>
    <template v-slot:body-cell-member="props">
      <q-toggle dense v-model="props.row.member" />
    </template>
    <template v-slot:body-cell-is_active="props">
      <q-toggle dense v-model="props.row.is_active" />
    </template>
    <template v-slot:actions="props">
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
import FileSelector from "components/FileSelector.vue";

export default {
  components: { FileSelector },
  props: {
    options: Array,
    loaded: Boolean,
  },
  emits: ["remove", "update"],
  methods: {
    onRemove({ rowIndex }) {
      console.func(
        "components/base/settings/DocumentsList:methods.onRemove()",
        arguments
      );
      this.$emit("remove", rowIndex);
    },
    onChange(value, { rowIndex }) {
      console.func(
        "components/base/settings/DocumentsList:methods.onChange()",
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
          name: "name",
          align: "left",
          label: this.$t("label.name"),
          field: "name",
        },
        {
          name: "is_active",
          align: "center",
          label: this.$t("label.active"),
          field: "is_active",
        },
        {
          name: "member",
          align: "center",
          label: this.$t("label.memberOnly"),
          field: "member",
        },
        {
          name: "actions",
          align: "right",
          label: "",
          field: "actions",
          style: "width: 30px",
        },
      ];
    },
  },
};
</script>
