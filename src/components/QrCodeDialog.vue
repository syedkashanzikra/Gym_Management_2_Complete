<template>
  <q-dialog
    transition-show="jump-up"
    transition-hide="jump-down"
    class="base-dialog"
    ref="dialog"
    @hide="onDialogHide"
  >
    <q-card square flat style="width: 350px; max-width: 95vw">
      <q-card-section>
        <q-img
          :src="user?.qrcode_url"
          spinner-color="primary"
          spinner-size="35px"
        />
      </q-card-section>
      <q-card-section class="q-pt-none text-center">
        <div class="text-h5">{{ user?.name }}</div>
        <base-avatar
          square
          size="150px"
          bordered
          class="q-mt-md"
          :user="user"
        />
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script>
import { mapState } from "pinia";
import { useAppStore } from "src/stores/app";

export default {
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
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
  },
};
</script>
