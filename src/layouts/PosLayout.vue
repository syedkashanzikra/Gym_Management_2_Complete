<template>
  <q-layout view="hHh LpR lFr" class="bg-main">
    <layout-header no-menu>
      <template #menus>
        <base-btn
          class="q-mr-sm items-stretch"
          :icon="fullscreenIcon"
          rounded
          flat
          dense
          size="16px"
          color="grey"
          @click="onFullScreen"
        />
        <base-btn
          class="q-mr-sm items-stretch"
          icon="fad fa-square-arrow-up-right"
          :to="{ name: 'Orders' }"
          rounded
          flat
          dense
          size="16px"
          color="negative"
        />
      </template>
    </layout-header>

    <q-page-container>
      <router-view :key="$router.fullPath" />
    </q-page-container>
  </q-layout>
</template>

<script>
import LayoutHeader from "components/LayoutHeader.vue";
export default {
  components: { LayoutHeader },
  methods: {
    onFullScreen() {
      this.$q.fullscreen
        .toggle()
        .then(() => {
          //
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  computed: {
    fullscreenIcon() {
      return this.$q.fullscreen.isActive
        ? "fad fa-compress-wide"
        : "fad fa-expand-wide";
    },
  },
};
</script>
