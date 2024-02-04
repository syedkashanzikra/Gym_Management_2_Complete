<template>
  <router-view
    :class="{
      'is-loading': isLoading,
    }"
  />
</template>
<script>
import { createMetaMixin } from "quasar";
import { mapActions, mapState } from "pinia";
import { useAppStore } from "./stores/app";
import QSpinnerLogo from "src/components/spinners/QSpinnerLogo.vue";
const getTitle = (route) => {
  const { meta, query } = route;
  return query.title || meta.title || meta.module || route.name;
};

export default {
  mixins: [
    createMetaMixin(function () {
      // "this" here refers to your component
      return {
        // sets document title
        title: this.title,
        // optional; sets final title as "Index Page - My Website", useful for multiple level meta
        titleTemplate: (title) => `${title} | ${process.env.APP_NAME}`,

        // <noscript> tags
        noscript: {
          default: "This is content for browsers with no JS (or disabled JS)",
        },
      };
    }),
  ],
  data() {
    return {
      isLoading: true,
    };
  },
  watch: {
    $route(to, from) {
      this.setTitle(getTitle(to));
      if (this.isGuard("admins")) {
        this.$app.getStats();
      }
    },
  },
  methods: {
    ...mapActions(useAppStore, ["setTitle"]),
  },
  computed: {
    ...mapState(useAppStore, ["title", "isGuard"]),
  },
  mounted() {
    this.$q.loading.show({
      customClass: "base-loading",
      spinnerColor: "primary",
      spinner: QSpinnerLogo,
      spinnerSize: 150,
    });
    this.setTitle(getTitle(this.$route));
    this.$app.getConfig().then((config) => {
      this.$i18n.locale = config?.lang;
      this.$q.loading.hide();
      this.isLoading = false;
    });
  },
};
</script>
