<template>
  <div
    v-if="editor"
    :class="{
      'q-editor q-editor--default': true,
      'not-empty': !editor.isEmpty && noHeader,
    }"
  >
    <menu-bar
      v-if="!noHeader"
      class="q-editor__toolbars-container"
      :editor="editor"
    />
    <editor-content
      :style="contentStyle"
      class="q-editor__content"
      :editor="editor"
    />
    <slot name="actions" v-bind:editor="editor"></slot>
  </div>
</template>

<script>
import { Editor, EditorContent } from "@tiptap/vue-3";
import { mergeAttributes } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import Table from "@tiptap/extension-table";
import TableRow from "@tiptap/extension-table-row";
import TableCell from "@tiptap/extension-table-cell";
import TableHeader from "@tiptap/extension-table-header";
import Link from "@tiptap/extension-link";
import Underline from "@tiptap/extension-underline";
import TextAlign from "@tiptap/extension-text-align";
import Highlight from "@tiptap/extension-highlight";
import Placeholder from "@tiptap/extension-placeholder";
import Paragraph from "@tiptap/extension-paragraph";
import Image from "@tiptap/extension-image";
import MenuBar from "src/components/tiptap/MenuBar.vue";
import Mention from "./lib/mention";
import suggestion from "./lib/suggestion";

export default {
  components: {
    EditorContent,
    MenuBar,
  },

  props: {
    modelValue: [String],
    placeholder: {
      type: [String],
      required: false,
      default: "Write something ...",
    },
    noHeader: {
      type: [Boolean],
      required: false,
      default: false,
    },
    hasMention: {
      type: [Boolean],
      required: false,
      default: false,
    },
    contentStyle: {
      type: [Object, String],
      required: false,
      default: "",
    },
  },

  emits: ["update:model-value"],

  data() {
    return {
      editor: null,
    };
  },

  watch: {
    modelValue(value) {
      // HTML
      const isSame = this.editor.getHTML() === value;

      // JSON
      // const isSame = JSON.stringify(this.editor.getJSON()) === JSON.stringify(value)

      if (isSame) {
        return;
      }

      this.editor.commands.setContent(value, false);
    },
  },

  mounted() {
    const extensions = [
      StarterKit.configure({
        paragraph: false,
        // mention: false,
      }),
      Paragraph.extend({
        parseHTML() {
          return [{ tag: "div" }];
        },
        renderHTML({ HTMLAttributes }) {
          return [
            "div",
            mergeAttributes(this.options.HTMLAttributes, HTMLAttributes),
            0,
          ];
        },
      }),
      Placeholder.configure({
        placeholder: this.placeholder,
      }),
      Highlight,
      Table.configure({
        resizable: true,
      }),
      Link.configure({
        autolink: false,
        openOnClick: false,
      }),
      TableRow,
      TableHeader,
      TableCell,
      Underline,
      Image,
      TextAlign.configure({
        types: ["heading", "paragraph"],
      }),
    ];

    if (this.hasMention) {
      extensions.push(
        Mention.configure({
          HTMLAttributes: {
            class:
              "bg-cyan-1 inline items-center no-wrap q-badge q-badge--single-line text-black",
          },
          suggestion,
        })
      );
    }

    this.editor = new Editor({
      content: this.modelValue,
      onUpdate: () => {
        // HTML
        this.$emit("update:model-value", this.editor.getHTML());

        // JSON
        // this.$emit('update:modelValue', this.editor.getJSON())
      },
      extensions,
    });
  },

  beforeUnmount() {
    this.editor.destroy();
  },
};
</script>

<style lang="scss">
.q-editor {
  position: relative;
  &.not-empty {
    padding-bottom: 40px;
  }
}
/* Basic editor styles */
.ProseMirror {
  line-height: inherit;
  min-height: 10em;
  margin: 0;
  &:focus {
    outline: none;
  }
  :first-child {
    margin: 0;
  }
  > * + * {
    margin: 0.75em 0 0 0;
  }
  img {
    max-width: 100%;
    height: auto;

    &.ProseMirror-selectednode {
      outline: 3px solid #68cef8;
    }
  }
  ul,
  ol {
    padding: 0 1rem;
  }
  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    line-height: 1.1;
  }
  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }
  pre {
    background: #0d0d0d;
    color: #fff;
    font-family: "JetBrainsMono", monospace;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;

    code {
      color: inherit;
      padding: 0;
      background: none;
      font-size: 0.8rem;
    }
  }
  img {
    max-width: 100%;
    height: auto;
  }
  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0d0d0d, 0.1);
  }
  hr {
    border: none;
    border-top: 2px solid rgba(#0d0d0d, 0.1);
    margin: 2rem 0;
  }
}

/* Table-specific styling */
.ProseMirror {
  table {
    border-collapse: collapse;
    table-layout: fixed;
    width: 100%;
    margin: 0;
    overflow: hidden;

    td,
    th {
      min-width: 1em;
      border: 2px solid #ced4da;
      padding: 3px 5px;
      vertical-align: top;
      box-sizing: border-box;
      position: relative;

      > * {
        margin-bottom: 0;
      }
    }

    th {
      font-weight: bold;
      text-align: left;
      background-color: #f1f3f5;
    }

    .selectedCell:after {
      z-index: 2;
      position: absolute;
      content: "";
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      background: rgba(200, 200, 255, 0.4);
      pointer-events: none;
    }

    .column-resize-handle {
      position: absolute;
      right: -2px;
      top: 0;
      bottom: -2px;
      width: 4px;
      background-color: #adf;
      pointer-events: none;
    }

    p {
      margin: 0;
    }
  }
}
.tableWrapper {
  overflow-x: auto;
}
.resize-cursor {
  cursor: ew-resize;
  cursor: col-resize;
}

/* Placeholder (at the top) */
.ProseMirror .is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: $grey-7;
  pointer-events: none;
  height: 0;
}
</style>
