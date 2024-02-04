import { boot } from "quasar/wrappers";
import { Capacitor } from "@capacitor/core";
// import { StatusBar, Style } from "@capacitor/status-bar";

// "async" is optional;
// more info on params: https://v2.quasar.dev/quasar-cli/boot-files
export default boot(async ({ app }) => {
  if (Capacitor.isNativePlatform()) {
    // Code to run in Capacitor mode
    console.core("Running in Capacitor mode");
    // Change the status bar style to transparent
    if (Capacitor.isPluginAvailable("StatusBar")) {
      // StatusBar.setStyle({ style: Style.Light });
      // StatusBar.setBackgroundColor({ color: "#ff000000" });
      // Display content under transparent status bar (Android only)
      // StatusBar.setOverlaysWebView({ overlay: true });
    }
  }
});
