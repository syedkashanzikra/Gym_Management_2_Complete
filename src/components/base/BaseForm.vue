<template>
  <q-form class="base-form" ref="baseForm">
    <q-card
      flat
      :square="square"
      :bordered="bordered"
      :class="`bg-transparent ${contentClass}`"
    >
      <q-card-section class="q-pa-none form-body">
        <slot></slot>
      </q-card-section>
      <slot v-if="!noAction" name="actions">
        <q-card-section class="q-px-none text-right form-actions">
          <q-btn
            no-caps
            v-if="resetable && !submited"
            @click="onReset"
            :label="$t('reset')"
            color="grey"
            class="main-btn q-mr-sm"
          />
          <q-btn
            :disable="submited"
            no-caps
            v-if="cancelable"
            @click="onCancel"
            :label="$t('cancel')"
            color="negative"
            class="main-btn q-mr-sm"
          />
          <q-btn
            :disable="disable"
            :loading="submited"
            no-caps
            v-show="submit"
            :label="saveLabel ?? $t('save')"
            type="submit"
            color="primary"
            class="main-btn"
          />
          <slot name="after-actions"></slot>
        </q-card-section>
      </slot>
    </q-card>
  </q-form>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";

export default {
  props: {
    submited: {
      required: false,
      type: Boolean,
      default: () => false,
    },
    resetable: {
      required: false,
      type: Boolean,
      default: () => false,
    },
    disable: {
      required: false,
      type: Boolean,
      default: () => false,
    },
    cancelable: {
      required: false,
      type: Boolean,
      default: () => true,
    },
    square: {
      required: false,
      type: Boolean,
      default: () => false,
    },
    bordered: {
      required: false,
      type: Boolean,
      default: () => false,
    },
    submit: {
      required: false,
      type: Boolean,
      default: () => true,
    },
    saveLabel: String,
    contentClass: {
      required: false,
      type: String,
      default: () => "",
    },
    noAction: Boolean,
    noEvents: Boolean,
  },
  emits: ["cancel", "reset"],
  methods: {
    ...mapActions(useAppStore, ["setIsDirt", "setIsLoading"]),
    onCancel() {
      console.func("components/base/base-form:methods.onCancel()", arguments);
      this.$emit("cancel");
    },
    onReset() {
      console.func("components/base/base-form:methods.onReset()", arguments);
      this.$core
        .confirm(this.$t("dialog.info.discard"), {
          title: this.$t("dialog.title.discard"),
          ok: this.$t("dialog.ok.discard"),
          cancel: this.$t("dialog.cancel.discard"),
          okColor: "negative",
        })
        .then(() => {
          this.setIsDirt(false);
          this.$emit("reset");
        });
    },
    addHandler() {
      if (this.noEvents) return false;
      window.addEventListener("form:discard", this.onDiscard);
      window.addEventListener("form:save", this.onSave);
    },
    removeHandler() {
      if (this.noEvents) return false;
      window.removeEventListener("form:discard", this.onDiscard);
      window.removeEventListener("form:save", this.onSave);
    },
    onDiscard() {
      console.func("components/base/base-form:methods.onDiscard()", arguments);
      this.onReset();
    },
    onSave() {
      console.func("components/base/base-form:methods.onSave()", arguments);
      this.$refs.baseForm.submit();
      this.setIsLoading(true);
    },
  },
  watch: {
    resetable: {
      deep: true,
      immediate: true,
      handler(val) {
        this.setIsDirt(val);
      },
    },
    submited: {
      deep: true,
      immediate: true,
      handler(val) {
        this.setIsLoading(val);
      },
    },
  },
  computed: {
    ...mapState(useAppStore, ["isDirt"]),
  },
  created() {
    this.addHandler();
  },
  beforeUnmount() {
    this.removeHandler();
  },
};
</script>
