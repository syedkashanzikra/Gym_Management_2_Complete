<template>
  <div class="qr-code-scanner-btn">
    <input
      v-model="qrcode"
      type="text"
      id="qrScanner"
      ref="qrScanner"
      class="scanner"
      @change="onScanSuccess"
    />
    <base-btn
      v-if="isScanning"
      color="negative"
      icon="fas fa-barcode-read"
      @click="stopScan"
      dense
      padding="13px"
      outline
    >
      <base-tooltip>{{ $t("stopScanning") }}</base-tooltip>
    </base-btn>
    <base-btn
      v-else
      dense
      padding="13px"
      outline
      color="primary"
      icon="fas fa-barcode-read"
      @click="startScan"
    >
      <base-tooltip>{{ $t("startScanning") }}</base-tooltip>
    </base-btn>
  </div>
</template>

<script>
export default {
  data() {
    return {
      qrcode: null,
      isScanning: false,
    };
  },

  emits: ["detect"],

  methods: {
    stopScan() {
      this.isScanning = false;
      document.removeEventListener("keypress", this.onInput);
      document.removeEventListener("keydown", this.onInput);
      this.$refs.qrScanner.blur();
    },
    onScanSuccess({ target }) {
      if (target?.value) {
        this.$core.debounce(() => {
          this.$emit("detect", target.value, "scanner");
          this.qrcode = null;
        }, 1000);
      }
    },
    onInput(event) {
      this.$refs.qrScanner.focus();
    },
    startScan() {
      document.addEventListener("keypress", this.onInput);
      document.addEventListener("keydown", this.onInput);
      this.isScanning = true;
    },
  },

  beforeUnmount() {
    this.stopScan();
  },
};
</script>

<style lang="scss">
.qr-code-scanner-btn {
  position: relative;
  .scanner {
    position: absolute;
    left: 2px;
    right: 2px;
    top: 1px;
    height: 0;
    color: transparent;
    border: none;
    z-index: -1;
    &:focus {
      outline: 0;
    }
  }
}
</style>
