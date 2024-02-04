<template>
  <div>
    <div class="q-editor__toolbar row no-wrap scroll-x">
      <div
        class="q-editor__toolbar-group"
        v-for="(menu, index) in items"
        :key="index"
      >
        <menu-item
          v-for="(item, index) in menu"
          :key="index"
          v-bind="item"
          v-bind:editor="editor"
        />
      </div>
    </div>
  </div>
</template>

<script>
import MenuItem from "src/components/tiptap/MenuItem.vue";
import LinkDialog from "src/components/tiptap/LinkDialog.vue";
import FileSelector from "src/components/FileSelectorDialog.vue";

export default {
  components: {
    MenuItem,
  },

  props: {
    editor: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      items: [
        [
          {
            icon: "far fa-font",
            title: "Formatting",
            label: "Formatting",
            action: () => false,
            items: [
              {
                title: "Paragraph",
                action: () => this.editor.chain().focus().setParagraph().run(),
                isActive: () => this.editor.isActive("paragraph"),
                isDisable: () => false,
                contentClass: "text-body2",
              },
              {
                title: "Heading 1",
                action: () =>
                  this.editor.chain().focus().toggleHeading({ level: 1 }).run(),
                isActive: () => this.editor.isActive("heading", { level: 1 }),
                isDisable: () => false,
                contentClass: "text-h1",
              },
              {
                title: "Heading 2",
                action: () =>
                  this.editor.chain().focus().toggleHeading({ level: 2 }).run(),
                isActive: () => this.editor.isActive("heading", { level: 2 }),
                isDisable: () => false,
                contentClass: "text-h2",
              },
              {
                title: "Heading 3",
                action: () =>
                  this.editor.chain().focus().toggleHeading({ level: 3 }).run(),
                isActive: () => this.editor.isActive("heading", { level: 3 }),
                isDisable: () => false,
                contentClass: "text-h3",
              },
              {
                title: "Heading 4",
                action: () =>
                  this.editor.chain().focus().toggleHeading({ level: 4 }).run(),
                isActive: () => this.editor.isActive("heading", { level: 4 }),
                isDisable: () => false,
                contentClass: "text-h4",
              },
              {
                title: "Heading 5",
                action: () =>
                  this.editor.chain().focus().toggleHeading({ level: 5 }).run(),
                isActive: () => this.editor.isActive("heading", { level: 5 }),
                isDisable: () => false,
                contentClass: "text-h5",
              },
              {
                title: "Heading 6",
                action: () =>
                  this.editor.chain().focus().toggleHeading({ level: 6 }).run(),
                isActive: () => this.editor.isActive("heading", { level: 6 }),
                isDisable: () => false,
                contentClass: "text-h6",
              },
            ],
          },
          {
            icon: "far fa-align-left",
            title: "Alignment",
            action: () => false,
            iconOnly: true,
            items: [
              {
                icon: "far fa-align-left",
                title: "Left",
                action: () =>
                  this.editor.chain().focus().setTextAlign("left").run(),
                isActive: () => this.editor.isActive({ textAlign: "left" }),
              },
              {
                icon: "far fa-align-center",
                title: "Center",
                action: () =>
                  this.editor.chain().focus().setTextAlign("center").run(),
                isActive: () => this.editor.isActive({ textAlign: "center" }),
              },
              {
                icon: "far fa-align-right",
                title: "Right",
                action: () =>
                  this.editor.chain().focus().setTextAlign("right").run(),
                isActive: () => this.editor.isActive({ textAlign: "right" }),
              },
              {
                icon: "far fa-align-justify",
                title: "Justify",
                action: () =>
                  this.editor.chain().focus().setTextAlign("justify").run(),
                isActive: () => this.editor.isActive({ textAlign: "justify" }),
              },
            ],
          },
        ],
        [
          {
            icon: "far fa-bold",
            title: "Bold",
            action: () => this.editor.chain().focus().toggleBold().run(),
            isActive: () => this.editor.isActive("bold"),
          },
          {
            icon: "far fa-italic",
            title: "Italic",
            action: () => this.editor.chain().focus().toggleItalic().run(),
            isActive: () => this.editor.isActive("italic"),
          },
          {
            icon: "far fa-strikethrough",
            title: "Strike",
            action: () => this.editor.chain().focus().toggleStrike().run(),
            isActive: () => this.editor.isActive("strike"),
          },
          {
            icon: "far fa-underline",
            title: "Underline",
            action: () => this.editor.commands.toggleUnderline(),
            isActive: () => this.editor.isActive("underline"),
          },
          {
            icon: "far fa-remove-format",
            title: "Clear Format",
            action: () =>
              this.editor.chain().focus().clearNodes().unsetAllMarks().run(),
          },
        ],
        [
          {
            icon: "far fa-list",
            title: "Bullet List",
            action: () => this.editor.chain().focus().toggleBulletList().run(),
            isActive: () => this.editor.isActive("bulletList"),
          },
          {
            icon: "far fa-list-ol",
            title: "Ordered List",
            action: () => this.editor.chain().focus().toggleOrderedList().run(),
            isActive: () => this.editor.isActive("orderedList"),
          },
          {
            icon: "far fa-table",
            title: "Table",
            action: false,
            items: [
              {
                title: "Insert Table",
                action: () =>
                  this.editor
                    .chain()
                    .focus()
                    .insertTable({ rows: 3, cols: 3, withHeaderRow: true })
                    .run(),
                isDisable: () => false,
              },
              {
                title: "Add Column Before",
                action: () =>
                  this.editor.chain().focus().addColumnBefore().run(),
                isDisable: () => !this.editor.can().addColumnBefore(),
              },
              {
                title: "Add Column After",
                action: () =>
                  this.editor.chain().focus().addColumnAfter().run(),
                isDisable: () => !this.editor.can().addColumnAfter(),
              },
              {
                title: "Delete Column",
                action: () => this.editor.chain().focus().deleteColumn().run(),
                isDisable: () => !this.editor.can().deleteColumn(),
              },
              {
                title: "Add Row Before",
                action: () => this.editor.chain().focus().addRowBefore().run(),
                isDisable: () => !this.editor.can().addRowBefore(),
              },
              {
                title: "Add Row After",
                action: () => this.editor.chain().focus().addRowAfter().run(),
                isDisable: () => !this.editor.can().addRowAfter(),
              },
              {
                title: "Delete Row",
                action: () => this.editor.chain().focus().deleteRow().run(),
                isDisable: () => !this.editor.can().deleteRow(),
              },
              {
                title: "Delete Table",
                action: () => this.editor.chain().focus().deleteTable().run(),
                isDisable: () => !this.editor.can().deleteTable(),
              },
            ],
          },
          {
            icon: "far fa-quote-left",
            title: "Blockquote",
            action: () => this.editor.chain().focus().toggleBlockquote().run(),
            isActive: () => this.editor.isActive("blockquote"),
          },
          {
            icon: "far fa-link",
            title: "Link",
            action: () => this.onEditLink(),
            isActive: () => this.editor.isActive("link"),
            isDisable: () =>
              !this.editor.isActive("link") && this.isSelection(this.editor),
          },
          {
            icon: "far fa-image",
            title: "Add Image",
            action: () => this.onEditImage(),
          },
        ],
        [
          {
            icon: "undo",
            title: "Undo",
            action: () => this.editor.chain().focus().undo().run(),
          },
          {
            icon: "redo",
            title: "Redo",
            action: () => this.editor.chain().focus().redo().run(),
          },
        ],
      ],
    };
  },

  methods: {
    onEditLink() {
      this.$q
        .dialog({
          component: LinkDialog,
          componentProps: {
            editor: this.editor,
          },
        })
        .onOk((payload) => {
          console.func(
            "components/tiptap/menu-bar:methods.onEditLink.onOk()",
            payload
          );
        });
    },
    onEditImage() {
      this.$q
        .dialog({
          component: FileSelector,
          componentProps: {
            title: "Insert image",
            acceptType: "image/*",
          },
        })
        .onOk((payload) => {
          console.func(
            "components/tiptap/menu-bar:methods.onEditImage.onOk()",
            payload
          );
          this.editor.chain().focus().setImage({ src: payload.url }).run();
        });
    },
    isSelection(editor) {
      return editor.view.state.selection.empty;
    },
  },
};
</script>
