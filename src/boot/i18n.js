import { boot } from "quasar/wrappers";
import { createI18n } from "vue-i18n";
import core from "services/core";
import messages from "src/i18n";

export const i18n = createI18n({
  locale: "en-US",
  globalInjection: true,
  messages,
});

export const $t = i18n.global.t;

export default boot(({ app }) => {
  app.use(i18n);
  core.$t = $t;
});
