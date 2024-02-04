<template>
  <base-dialog
    :title="$t('registrations.bulkPrint')"
    class="bulkPrint-dialog"
    body-class="q-pa-none"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div class="bordered scroll">
        <base-table
          style="max-height: 50vh; min-height: 50vh"
          hide-top
          :columns="columns"
          :rows="modelValue"
          loaded
          :no-data-label="$t('registrations.noData')"
          :visible-columns="['day_index', 'start_at', 'end_at', 'class']"
          selection="multiple"
          hide-pagination
          :rows-per-page-options="[0]"
          ref="registrations"
          v-model:selected="selected"
        />
      </div>
    </template>
    <template v-slot:footer>
      <q-card-section class="bg-white flex">
        <q-space />
        <div class="q-gutter-sm">
          <base-btn
            no-caps
            outline
            :label="$t('cancel')"
            color="grey"
            v-close-popup
          />
          <base-btn
            :disable="disable"
            no-caps
            icon="fas fa-print"
            :label="$t('print')"
            color="primary"
            @click="onDone"
          />
        </div>
      </q-card-section>
    </template>
    <template v-slot:loading>
      <q-inner-loading :showing="loading">
        <q-spinner-oval size="50px" color="primary" />
      </q-inner-loading>
    </template>
  </base-dialog>
</template>

<script>
export default {
  data() {
    return {
      selected: [],
      loading: false,
    };
  },
  props: {
    modelValue: {
      required: false,
      type: Array,
      default: () => [],
    },
    columns: {
      required: true,
      type: Array,
      default: () => [],
    },
    action: {
      required: true,
      type: Function,
    },
  },
  emits: ["ok", "hide"],
  methods: {
    async show() {
      console.func("components/ClassSelector:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/ClassSelector:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/ClassSelector:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    async onDone() {
      console.func("components/ClassSelector:methods.onDone()", arguments);
      await this.selected.forEach((element) => {
        this.action({
          ...element,
          open: true,
        });
      });
      this.$emit("ok", this.selected);
      this.hide();
    },
  },
  computed: {
    disable() {
      return this.selected.length <= 0;
    },
  },
};
</script>
