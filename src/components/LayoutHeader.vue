<template>
  <q-header bordered class="base-header bg-white text-black">
    <q-toolbar>
      <base-btn
        flat
        round
        dense
        size="18px"
        color="primary"
        class="q-mr-sm"
        icon="menu"
        :aria-label="$t('menu')"
        @click="onUpdateLeftDrawer"
        v-if="!noMenu"
        padding="0"
      />
      <template v-if="isDirt">
        <q-toolbar-title>{{ $t("unsavedChanges") }}</q-toolbar-title>
        <q-btn
          no-caps
          @click="onDiscard"
          :label="$t('discard')"
          color="negative"
          class="q-mr-sm"
        />
        <q-btn
          no-caps
          :label="$t('save')"
          @click="onSave"
          type="submit"
          color="primary"
          :loading="isLoading"
        />
      </template>
      <template v-else>
        <img width="120" src="~assets/logo.png" />
        <q-space />
        <base-btn
          class="q-mr-sm"
          icon="fad fa-cart-arrow-down"
          :to="{ name: 'Pos' }"
          v-show="usePos"
          rounded
          flat
          dense
          size="16px"
        />
        <slot name="menus"></slot>
        <shop-cart class="q-mr-sm" v-show="useCart" dense />
        <base-current-user flat v-if="user" />
      </template>
    </q-toolbar>
  </q-header>
</template>

<script>
import { mapState } from "pinia";
import { useAppStore } from "stores/app";
import ShopCart from "./ShopCart.vue";

export default {
  components: { ShopCart },
  props: {
    useCart: Boolean,
    usePos: Boolean,
    noMenu: Boolean,
  },
  emits: ["update-left-drawer"],
  methods: {
    onUpdateLeftDrawer() {
      this.$emit("update-left-drawer");
    },
    onSave() {
      console.func("layouts/Dashboard:methods.onSave()", arguments);
      window.dispatchEvent(new Event("form:save"));
    },
    onDiscard() {
      console.func("layouts/Dashboard:methods.onDiscard()", arguments);
      window.dispatchEvent(new Event("form:discard"));
    },
  },
  computed: {
    ...mapState(useAppStore, ["user", "isDirt", "isLoading"]),
  },
};
</script>
