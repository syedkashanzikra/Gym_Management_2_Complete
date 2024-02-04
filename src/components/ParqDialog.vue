<template>
  <base-dialog
    :title="$t('parq')"
    body-class="q-pa-none scroll"
    content-style="width: 950px; max-width: 95vw"
    ref="dialog"
    @hide="onDialogHide"
    use-separator
    hide-footer
    persistent
  >
    <template v-slot:body>
      <base-section flat bordered>
        <div class="col-xs-12">
          <parq-form
            @done="onDone"
            :request-parq="requestParq"
            v-model:member="user"
          />
        </div>
      </base-section>
    </template>
    <template v-slot:loading>
      <q-inner-loading :showing="loading">
        <q-spinner-oval size="50px" color="primary" />
      </q-inner-loading>
    </template>
  </base-dialog>
</template>

<script>
import { mapState } from "pinia";
import { useAppStore } from "stores/app";
import ParqForm from "./ParqForm.vue";

export default {
  components: { ParqForm },
  props: {
    requestParq: Boolean,
  },
  data() {
    return {
      loading: true,
    };
  },
  emits: ["ok", "hide"],
  methods: {
    async show() {
      console.func("components/MemberDialog:methods.show()", arguments);
      this.$refs.dialog.show();
      this.loading = false;
    },
    hide() {
      console.func("components/MemberDialog:methods.close()", arguments);
      this.$refs.dialog.hide();
      this.loading = true;
    },
    onDialogHide() {
      console.func("components/MemberDialog:methods.onDialogHide()", arguments);
      this.$emit("hide");
    },
    onDone() {
      console.func("components/MemberDialog:methods.onDone()", arguments);
      this.$emit("ok");
      this.hide();
    },
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
  },
};
</script>
