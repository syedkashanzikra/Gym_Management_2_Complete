import { boot } from "quasar/wrappers";
import { useAppStore } from "stores/app";

const appStore = useAppStore();

// "async" is optional;
// more info on params: https://v2.quasar.dev/quasar-cli/boot-files
export default boot(async ({ app }) => {
  app.directive("guard", (el, binding, vnode) => {
    const { arg } = binding;
    if (!appStore.isGuard(arg)) {
      const comment = document.createComment(` guard:${arg} `);
      vnode.elm = comment;
      vnode.text = " ";
      vnode.isComment = true;
      vnode.context = undefined;
      vnode.tag = undefined;

      if (vnode.componentInstance) {
        vnode.componentInstance.$el = comment;
      }

      if (el.parentNode) {
        el.parentNode.replaceChild(comment, el);
      }
    }
  });
});
