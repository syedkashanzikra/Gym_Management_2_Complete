<template>
  <base-dialog
    title="Insert link"
    body-class="scroll"
    ref="dialog"
    persistent
    @hide="onDialogHide"
    use-separator
  >
    <template v-slot:body>
      <div class="row q-col-gutter-sm">
        <div class="col-sm-8 col-xs-12">
          <base-label>{{ $t("label.linkTo") }}</base-label>
          <q-input
            dense
            outlined
            v-model="href"
            placeholder="https://"
            hint="http:// is required for external links"
          />
        </div>
        <div class="col-sm-4 col-xs-12">
          <base-label>{{ $t("label.openThisLinkIn") }}</base-label>
          <q-select
            dense
            outlined
            v-model="target"
            map-options
            emit-value
            :options="[
              {
                label: 'the same window',
                value: '',
              },
              {
                label: 'a new window',
                value: '_blank',
              },
            ]"
          />
        </div>
      </div>
    </template>
    <template v-slot:footer>
      <q-card-section class="flex">
        <q-btn
          v-if="this.editor.getAttributes('link').href"
          no-caps
          label="Remove link"
          color="negative"
          @click="onRemove"
        />
        <q-space />
        <div class="q-gutter-sm">
          <q-btn no-caps outline label="Cancel" color="grey" v-close-popup />
          <q-btn
            no-caps
            :label="href ? 'Edit link' : 'Insert link'"
            color="primary"
            @click="onDone"
          />
        </div>
      </q-card-section>
    </template>
  </base-dialog>
</template>

<script>
export default {
  props: {
    editor: [Object],
  },
  emits: ["ok", "hide"],
  data() {
    return {
      href: this.editor.getAttributes("link").href || "",
      target: this.editor.getAttributes("link").target || "",
      title: this.editor.getAttributes("link").title || "",
    };
  },
  methods: {
    show() {
      console.func("components/tiptap/link:methods.show()", arguments);
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/tiptap/link:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func("components/tiptap/link:methods.onDialogHide()", arguments);
      this.$emit("hide");
    },
    onRemove() {
      console.func("components/tiptap/link:methods.onRemove()", arguments);
      this.editor.chain().focus().unsetLink().run();
      this.hide();
    },
    onDone() {
      console.func("components/tiptap/link:methods.onDone()", arguments);

      // cancelled
      if (this.href === null) {
        this.hide();
        return;
      }

      // empty
      if (this.href === "") {
        this.editor.chain().focus().extendMarkRange("link").unsetLink().run();
        this.hide();
        return;
      }

      // update link
      this.editor
        .chain()
        .focus()
        .extendMarkRange("link")
        .setLink({
          href: this.href,
          target: this.target,
        })
        .run();
      this.hide();
    },
  },
};
</script>
