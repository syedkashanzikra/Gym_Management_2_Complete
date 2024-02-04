<template>
  <q-card flat bordered class="webcam-qr-code-scanner">
    <div :id="id" :width="width"></div>
    <q-card-section :class="{ 'text-center': true, 'q-pt-none': isScanning }">
      <img
        v-show="!isScanning"
        class="qr-icon"
        src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzNzEuNjQzIDM3MS42NDMiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDM3MS42NDMgMzcxLjY0MyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTEwNS4wODQgMzguMjcxaDE2My43Njh2MjBIMTA1LjA4NHoiLz48cGF0aCBkPSJNMzExLjU5NiAxOTAuMTg5Yy03LjQ0MS05LjM0Ny0xOC40MDMtMTYuMjA2LTMyLjc0My0yMC41MjJWMzBjMC0xNi41NDItMTMuNDU4LTMwLTMwLTMwSDEyNS4wODRjLTE2LjU0MiAwLTMwIDEzLjQ1OC0zMCAzMHYxMjAuMTQzaC04LjI5NmMtMTYuNTQyIDAtMzAgMTMuNDU4LTMwIDMwdjEuMzMzYTI5LjgwNCAyOS44MDQgMCAwIDAgNC42MDMgMTUuOTM5Yy03LjM0IDUuNDc0LTEyLjEwMyAxNC4yMjEtMTIuMTAzIDI0LjA2MXYxLjMzM2MwIDkuODQgNC43NjMgMTguNTg3IDEyLjEwMyAyNC4wNjJhMjkuODEgMjkuODEgMCAwIDAtNC42MDMgMTUuOTM4djEuMzMzYzAgMTYuNTQyIDEzLjQ1OCAzMCAzMCAzMGg4LjMyNGMuNDI3IDExLjYzMSA3LjUwMyAyMS41ODcgMTcuNTM0IDI2LjE3Ny45MzEgMTAuNTAzIDQuMDg0IDMwLjE4NyAxNC43NjggNDUuNTM3YTkuOTg4IDkuOTg4IDAgMCAwIDguMjE2IDQuMjg4IDkuOTU4IDkuOTU4IDAgMCAwIDUuNzA0LTEuNzkzYzQuNTMzLTMuMTU1IDUuNjUtOS4zODggMi40OTUtMTMuOTIxLTYuNzk4LTkuNzY3LTkuNjAyLTIyLjYwOC0xMC43Ni0zMS40aDgyLjY4NWMuMjcyLjQxNC41NDUuODE4LjgxNSAxLjIxIDMuMTQyIDQuNTQxIDkuMzcyIDUuNjc5IDEzLjkxMyAyLjUzNCA0LjU0Mi0zLjE0MiA1LjY3Ny05LjM3MSAyLjUzNS0xMy45MTMtMTEuOTE5LTE3LjIyOS04Ljc4Ny0zNS44ODQgOS41ODEtNTcuMDEyIDMuMDY3LTIuNjUyIDEyLjMwNy0xMS43MzIgMTEuMjE3LTI0LjAzMy0uODI4LTkuMzQzLTcuMTA5LTE3LjE5NC0xOC42NjktMjMuMzM3YTkuODU3IDkuODU3IDAgMCAwLTEuMDYxLS40ODZjLS40NjYtLjE4Mi0xMS40MDMtNC41NzktOS43NDEtMTUuNzA2IDEuMDA3LTYuNzM3IDE0Ljc2OC04LjI3MyAyMy43NjYtNy42NjYgMjMuMTU2IDEuNTY5IDM5LjY5OCA3LjgwMyA0Ny44MzYgMTguMDI2IDUuNzUyIDcuMjI1IDcuNjA3IDE2LjYyMyA1LjY3MyAyOC43MzMtLjQxMyAyLjU4NS0uODI0IDUuMjQxLTEuMjQ1IDcuOTU5LTUuNzU2IDM3LjE5NC0xMi45MTkgODMuNDgzLTQ5Ljg3IDExNC42NjEtNC4yMjEgMy41NjEtNC43NTYgOS44Ny0xLjE5NCAxNC4wOTJhOS45OCA5Ljk4IDAgMCAwIDcuNjQ4IDMuNTUxIDkuOTU1IDkuOTU1IDAgMCAwIDYuNDQ0LTIuMzU4YzQyLjY3Mi0zNi4wMDUgNTAuODAyLTg4LjUzMyA1Ni43MzctMTI2Ljg4OC40MTUtMi42ODQuODIxLTUuMzA5IDEuMjI5LTcuODYzIDIuODM0LTE3LjcyMS0uNDU1LTMyLjY0MS05Ljc3Mi00NC4zNDV6bS0yMzIuMzA4IDQyLjYyYy01LjUxNCAwLTEwLTQuNDg2LTEwLTEwdi0xLjMzM2MwLTUuNTE0IDQuNDg2LTEwIDEwLTEwaDE1djIxLjMzM2gtMTV6bS0yLjUtNTIuNjY2YzAtNS41MTQgNC40ODYtMTAgMTAtMTBoNy41djIxLjMzM2gtNy41Yy01LjUxNCAwLTEwLTQuNDg2LTEwLTEwdi0xLjMzM3ptMTcuNSA5My45OTloLTcuNWMtNS41MTQgMC0xMC00LjQ4Ni0xMC0xMHYtMS4zMzNjMC01LjUxNCA0LjQ4Ni0xMCAxMC0xMGg3LjV2MjEuMzMzem0zMC43OTYgMjguODg3Yy01LjUxNCAwLTEwLTQuNDg2LTEwLTEwdi04LjI3MWg5MS40NTdjLS44NTEgNi42NjgtLjQzNyAxMi43ODcuNzMxIDE4LjI3MWgtODIuMTg4em03OS40ODItMTEzLjY5OGMtMy4xMjQgMjAuOTA2IDEyLjQyNyAzMy4xODQgMjEuNjI1IDM3LjA0IDUuNDQxIDIuOTY4IDcuNTUxIDUuNjQ3IDcuNzAxIDcuMTg4LjIxIDIuMTUtMi41NTMgNS42ODQtNC40NzcgNy4yNTEtLjQ4Mi4zNzgtLjkyOS44LTEuMzM1IDEuMjYxLTYuOTg3IDcuOTM2LTExLjk4MiAxNS41Mi0xNS40MzIgMjIuNjg4aC05Ny41NjRWMzBjMC01LjUxNCA0LjQ4Ni0xMCAxMC0xMGgxMjMuNzY5YzUuNTE0IDAgMTAgNC40ODYgMTAgMTB2MTM1LjU3OWMtMy4wMzItLjM4MS02LjE1LS42OTQtOS4zODktLjkxNC0yNS4xNTktMS42OTQtNDIuMzcgNy43NDgtNDQuODk4IDI0LjY2NnoiLz48cGF0aCBkPSJNMTc5LjEyOSA4My4xNjdoLTI0LjA2YTUgNSAwIDAgMC01IDV2MjQuMDYxYTUgNSAwIDAgMCA1IDVoMjQuMDZhNSA1IDAgMCAwIDUtNVY4OC4xNjdhNSA1IDAgMCAwLTUtNXpNMTcyLjYyOSAxNDIuODZoLTEyLjU2VjEzMC44YTUgNSAwIDEgMC0xMCAwdjE3LjA2MWE1IDUgMCAwIDAgNSA1aDE3LjU2YTUgNSAwIDEgMCAwLTEwLjAwMXpNMjE2LjU2OCA4My4xNjdoLTI0LjA2YTUgNSAwIDAgMC01IDV2MjQuMDYxYTUgNSAwIDAgMCA1IDVoMjQuMDZhNSA1IDAgMCAwIDUtNVY4OC4xNjdhNSA1IDAgMCAwLTUtNXptLTUgMjQuMDYxaC0xNC4wNlY5My4xNjdoMTQuMDZ2MTQuMDYxek0yMTEuNjY5IDEyNS45MzZIMTk3LjQxYTUgNSAwIDAgMC01IDV2MTQuMjU3YTUgNSAwIDAgMCA1IDVoMTQuMjU5YTUgNSAwIDAgMCA1LTV2LTE0LjI1N2E1IDUgMCAwIDAtNS01eiIvPjwvc3ZnPg=="
      />
      <div class="camera-action">
        <div class="cameras">
          <base-select
            v-model="cameraId"
            :options="devices"
            map-options
            option-value="id"
            emit-value
            borderless
            dense
            :prefix="$t('prefix.camera')"
            :disable="isScanning"
            class="inline"
          />
        </div>
        <base-btn
          v-if="isScanning"
          color="negative"
          icon="fas fa-qrcode"
          :label="$t('stopScanning')"
          @click="stopScan"
          outline
        />
        <base-btn
          v-else
          color="primary"
          icon="fas fa-qrcode"
          :label="$t('startScanning')"
          @click="startScan"
          outline
        />
      </div>
    </q-card-section>
    <q-inner-loading :showing="loading">
      <q-spinner-tail size="50px" color="primary" />
    </q-inner-loading>
  </q-card>
</template>

<script>
import { Html5Qrcode } from "html5-qrcode";
import { LocalStorage } from "quasar";

const defaultCamera = { id: null, label: "Auto Detect" };

export default {
  props: {
    width: {
      type: String,
      default: "600px",
    },
    id: {
      type: String,
      default: "qr-reader",
    },
    verbose: Boolean,
    options: {
      type: Object,
      default: () => ({ fps: 10, qrbox: { width: 180, height: 180 } }),
    },
  },

  data() {
    return {
      qrCode: null,
      qrcode: null,
      cameraId: null,
      devices: [defaultCamera],
      isScanning: false,
      loading: true,
      pause: false,
    };
  },

  emits: ["detect"],

  methods: {
    stopScan() {
      this.qrCode
        ?.stop()
        .then((ignore) => {
          this.isScanning = false;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    async onScanSuccess(decodedText, decodedResult) {
      try {
        if (this.pause) return false;
        this.pause = true;
        this.$emit("detect", decodedText, "webcam");
        this.playSound();
        setTimeout(() => {
          this.pause = false;
        }, 1000);
      } catch (error) {
        console.error(error);
      }
    },
    startScan() {
      const qrCode = new Html5Qrcode(this.id);
      this.qrCode = qrCode;
      let cameraConfig = { facingMode: "environment" };

      if (this.cameraId) {
        cameraConfig = { deviceId: { exact: this.cameraId } };
      }

      qrCode
        .start(cameraConfig, this.options, this.onScanSuccess)
        .then(() => {
          this.isScanning = true;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    playSound() {
      const audio = new Audio(
        "data:audio/mpeg;base64,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"
      );
      setTimeout(() => {
        audio.play();
      }, 1);
    },
  },

  mounted() {
    Html5Qrcode.getCameras()
      .then((devices) => {
        if (devices && devices.length) {
          this.devices = [defaultCamera, ...devices];
          this.cameraId = this.history?.lastUsedCameraId || null;
        }
        this.loading = false;
      })
      .catch((err) => {
        console.error(err);
      });
  },

  computed: {
    history() {
      if (LocalStorage.has("HTML5_QRCODE_DATA")) {
        return JSON.parse(localStorage.getItem("HTML5_QRCODE_DATA"));
      }
      return {};
    },
  },

  beforeUnmount() {
    this.stopScan();
  },
};
</script>
<style lang="scss">
.webcam-qr-code-scanner {
  overflow: hidden;
  & > div > div {
    overflow: hidden;
  }
}
.qr-icon {
  width: 70px;
}
</style>
