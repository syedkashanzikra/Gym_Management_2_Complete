<template>
  <q-dialog
    transition-show="jump-up"
    transition-hide="jump-down"
    class="light-box"
    ref="dialog"
    @hide="onDialogHide"
    persistent
    maximized
  >
    <vue-easy-lightbox
      :visible="visible"
      :imgs="imgs"
      :index="currentIndex"
      @hide="hide"
    />
  </q-dialog>
</template>

<script>
export default {
  data() {
    return {
      visible: false,
    };
  },
  props: {
    imgs: [String, Array],
    currentIndex: {
      type: Number,
      default: 0,
    },
  },
  emits: ["ok", "hide"],
  methods: {
    show() {
      this.visible = true;
      this.$refs.dialog.show();
    },
    hide() {
      this.visible = false;
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      this.$emit("hide");
    },
  },
};
</script>
