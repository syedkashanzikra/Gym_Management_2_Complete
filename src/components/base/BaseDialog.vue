<template>
  <q-dialog
    transition-show="jump-up"
    transition-hide="jump-down"
    class="base-dialog"
    ref="dialog"
    @hide="onDialogHide"
  >
    <q-card :style="contentStyle" v-bind:class="contentClass">
      <slot>
        <template v-if="!hideHeader">
          <slot name="header">
            <q-toolbar
              style="padding: 0 27px; min-height: 60px"
              class="bg-transparent"
            >
              <q-toolbar-title class="text-h6 text-weight-medium">
                {{ title }}
              </q-toolbar-title>
              <q-btn
                flat
                size="11px"
                round
                dense
                icon="fal fa-times"
                v-close-popup
              />
            </q-toolbar>
            <q-separator v-show="useSeparator" />
          </slot>
        </template>
        <q-card-section
          :style="bodyStyle"
          v-bind:class="'q-pa-md ' + bodyClass"
        >
          <slot name="body">
            <div class="q-py-xl">Base dialog body content.</div>
          </slot>
        </q-card-section>
        <template v-if="!hideFooter">
          <q-separator v-show="useSeparator" />
          <slot name="footer">
            <q-card-section class="text-right">
              <div class="q-gutter-sm">
                <q-btn
                  no-caps
                  :label="$t('cancel')"
                  color="grey"
                  outline
                  v-close-popup
                />
                <q-btn
                  :disable="disable"
                  :loading="loading"
                  no-caps
                  :label="doneLabel ?? $t('done')"
                  color="primary"
                  @click="onDone"
                />
              </div>
            </q-card-section>
          </slot>
        </template>
        <slot name="loading"></slot>
      </slot>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: "Base dialog title",
    },
    contentStyle: {
      type: [String, Object],
      default: "width: 800px; max-width: 95vw",
    },
    bodyStyle: {
      type: [String, Object],
      default: "max-height: 60vh",
    },
    bodyClass: {
      type: [String],
      default: "",
    },
    contentClass: {
      type: [String],
      default: "",
    },
    doneLabel: String,
    hideFooter: Boolean,
    hideHeader: Boolean,
    useSeparator: Boolean,
    disable: Boolean,
    loading: Boolean,
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      // console.func('components/base/base-dialog:methods.show()', arguments);
      this.$refs.dialog.show();
    },
    hide() {
      // console.func('components/base/base-dialog:methods.close()', arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      // console.func('components/base/base-dialog:methods.onDialogHide()', arguments);
      this.$emit("hide");
    },
    onDone() {
      // console.func('components/base/base-dialog:methods.onDone()', arguments);
      this.$emit("ok");
    },
  },
};
</script>
