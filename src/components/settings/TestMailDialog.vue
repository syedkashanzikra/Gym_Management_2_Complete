<template>
  <base-dialog
    title="Send Test Email"
    body-class="q-pa-none scroll"
    content-style="width: 450px; max-width: 95vw"
    ref="dialog"
    @hide="onDialogHide"
    @ok="onDone"
    use-separator
    persistent
    done-label="Send"
  >
    <template v-slot:body>
      <base-section no-row flat dense>
        <base-label>Sent to</base-label>
        <base-input v-model="toEmail" />
      </base-section>
    </template>
  </base-dialog>
</template>

<script>
import { mapState, mapActions } from "pinia";
import { useAppStore } from "src/stores/app";

export default {
  props: {
    mailConfig: Object,
  },
  data() {
    return {
      toEmail: null,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    ...mapActions(useAppStore, ["testMailConfig"]),
    async show() {
      console.func(
        "components/settings/TestMailDialog:methods.show()",
        arguments
      );
      this.toEmail = this.config?.email;
      this.$refs.dialog.show();
    },
    hide() {
      console.func(
        "components/settings/TestMailDialog:methods.close()",
        arguments
      );
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func(
        "components/settings/TestMailDialog:methods.onDialogHide()",
        arguments
      );
      this.$emit("hide");
    },
    onDone() {
      console.func(
        "components/settings/TestMailDialog:methods.onDone()",
        arguments
      );
      this.testMailConfig({
        ...this.mailConfig,
        to: this.toEmail,
      })
        .then(({ message }) => {
          this.$core.success(message);
          this.$emit("ok");
          this.hide();
        })
        .catch((error) => {
          this.$core.error(error);
        });
    },
  },
  computed: {
    ...mapState(useAppStore, ["config"]),
  },
};
</script>
