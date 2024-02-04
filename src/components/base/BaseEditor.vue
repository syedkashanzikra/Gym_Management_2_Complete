<template>
  <q-editor
    class="base-editor"
    ref="baseEditor"
    placeholder="Your content goes here..."
    :model-value="modelValue"
    @update:model-value="onChange"
    min-height="10rem"
    dense
    :definitions="{
      'size-2': { label: 'Small', icon: null, tip: null },
      'size-4': { label: 'Medium', icon: null, tip: null },
      'size-5': { label: 'Large', icon: null, tip: null },
      'size-6': { label: 'Very Large', icon: null, tip: null },
    }"
    debounce="500"
    :toolbar="toolbar"
    :fonts="{
      arial: 'Arial',
      arial_black: 'Arial Black',
      comic_sans: 'Comic Sans MS',
      courier_new: 'Courier New',
      impact: 'Impact',
      lucida_grande: 'Lucida Grande',
      times_new_roman: 'Times New Roman',
      verdana: 'Verdana',
    }"
  >
    <template v-slot:shortCode>
      <q-btn-dropdown
        dense
        no-caps
        ref="shortCode"
        no-wrap
        unelevated
        label="Fields"
        size="sm"
        @click.stop
      >
        <q-list class="bg-grey-9 text-white q-pa-sm" dense>
          <q-item
            v-for="(item, index) in shortCodes"
            :key="index"
            class="rounded-borders"
            tag="label"
            clickable
            @click.stop="shortCode(item.value)"
          >
            <q-item-section>{{ item.label }}</q-item-section>
          </q-item>
        </q-list>
      </q-btn-dropdown>
    </template>
    <template v-slot:hiliteColor>
      <q-btn
        dense
        no-caps
        no-wrap
        unelevated
        icon="format_color_fill"
        size="sm"
        @click.stop
      >
        <q-popup-proxy
          ref="hiliteColor"
          cover
          transition-show="scale"
          transition-hide="scale"
        >
          <q-color
            @update:model-value="hiliteColor"
            no-header
            no-footer
            default-view="palette"
          />
        </q-popup-proxy>
      </q-btn>
    </template>
  </q-editor>
</template>

<script>
import { useQuasar as q } from "quasar";

export default {
  props: {
    modelValue: String,
    toolbar: {
      type: Array,
      default: () => [
        [
          {
            label: q().lang.editor.align,
            icon: q().iconSet.editor.align,
            fixedLabel: true,
            options: ["left", "center", "right", "justify"],
          },
          "bold",
          "italic",
          "strike",
          "underline",
          "color",
          "text-color",
          "removeFormat",
          "token",
          "link",
          "hiliteColor",
          "print",
          "unordered",
          "ordered",
          {
            label: q().lang.editor.formatting,
            icon: q().iconSet.editor.formatting,
            list: "no-icons",
            options: ["p", "h1", "h2", "h3", "h4", "h5", "h6", "code"],
          },
          {
            label: q().lang.editor.fontSize,
            icon: q().iconSet.editor.fontSize,
            fixedLabel: true,
            fixedIcon: true,
            list: "no-icons",
            options: [
              "size-1",
              "size-2",
              "size-3",
              "size-4",
              "size-5",
              "size-6",
              "size-7",
            ],
          },
          "undo",
          "redo",
        ],
      ],
    },
    shortCodes: {
      type: Array,
      default: () => [
        {
          label: "First Name",
          value: "FIRST_NAME",
        },
        {
          label: "Last Name",
          value: "LAST_NAME",
        },
        {
          label: "Full Name",
          value: "FULL_NAME",
        },
      ],
    },
  },
  methods: {
    onChange(value) {
      console.func("components/base/BaseEditor:methods.onChange", arguments);
      this.$emit("update:model-value", value);
    },
    shortCode(name) {
      console.func("components/base/BaseEditor:methods.shortCode", arguments);
      const edit = this.$refs.baseEditor;
      this.$refs.shortCode.hide();
      edit.caret.restore();
      edit.runCmd("insertHTML", `{{${name}}}`);
      edit.focus();
    },
    hiliteColor(name) {
      console.func("components/base/BaseEditor:methods.hiliteColor", arguments);
      const edit = this.$refs.baseEditor;
      this.$refs.hiliteColor.hide();
      edit.caret.restore();
      edit.runCmd("foreColor", name);
      edit.focus();
    },
  },
};
</script>
<style lang="sass">
.q-editor .q-editor__toolbar.no-wrap
  flex-wrap: wrap
.q-editor__toolbar-group + .q-editor__toolbar-group
  display: none
</style>
