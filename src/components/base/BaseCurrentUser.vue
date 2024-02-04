<template>
  <q-btn-dropdown
    no-caps
    class="base-current-user base-btn-dropdown q-px-none"
    :ripple="false"
    color="primary"
  >
    <template #label>
      <div class="row items-center user-label">
        <div class="col col-auto">
          <base-avatar size="28px" :user="user" />
        </div>
        <div v-if="$q.screen.gt.xs" class="q-ml-xs col">
          <div class="ellipsis text-subtitle2">
            {{ user.name }}
          </div>
        </div>
      </div>
    </template>
    <q-list class="q-pa-sm">
      <base-item
        dense
        icon-color="primary"
        link
        :label="$t('myAccount.title')"
        :to="{ name: 'My Account' }"
        icon="fas fa-user"
      />
      <base-item
        dense
        icon-color="primary"
        link
        :label="$t('logout')"
        icon="fas fa-sign-out"
        @click="onLogout"
      />
    </q-list>
  </q-btn-dropdown>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { useAppStore } from "stores/app";

export default {
  methods: {
    ...mapActions(useAppStore, ["logout"]),
    onLogout() {
      this.logout(this.$route.meta.guard)
        .then(() => {
          this.$router.push({ name: "Login" });
        })
        .catch((error) => {
          // this.$core.error(error);
          this.$router.push({ name: "Login" });
        });
    },
  },
  computed: {
    ...mapState(useAppStore, ["user"]),
  },
};
</script>
