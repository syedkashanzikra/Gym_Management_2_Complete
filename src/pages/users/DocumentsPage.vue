<template>
  <q-page padding>
    <base-page-header
      :title="$t('documents')"
      hint="Discover upcoming changes, exciting events, and more"
      no-tabs
    />
    <base-section class="q-mt-md" flat bordered>
      <template v-if="loaded">
        <div v-for="media in files" :key="media.id" class="col-sm-2 col-xs-12">
          <base-media-card
            :class="{
              unread: !media.has_read,
            }"
            @close="onRead"
            :media="media"
          />
        </div>
        <template v-if="files.length === 0">
          <div class="col-xs-12">
            <base-no-records
              height="calc(100vh - 250px)"
              icon="fad fa-file-magnifying-glass"
              title="No documents available"
              message="Please check back later for updates, or feel free to reach out to our staff for any specific documents or assistance you may need."
            />
          </div>
        </template>
      </template>
      <template v-else>
        <div v-for="media in 12" :key="media.id" class="col-sm-2 col-xs-12">
          <q-skeleton height="52px" width="100%" class="main-btn" type="QBtn" />
        </div>
      </template>
    </base-section>
  </q-page>
</template>

<script>
import { mapActions } from "pinia";
import { useAppStore } from "stores/app";
export default {
  data() {
    return {
      files: [],
      loaded: false,
    };
  },
  methods: {
    ...mapActions(useAppStore, ["documents", "readDcument"]),
    onLoad() {
      console.func("pages/users/DocumentsPage:methods.onLoad()", arguments);
      this.loaded = false;
      this.documents()
        .then((files) => {
          this.files = files;
          this.loaded = true;
        })
        .catch((error) => {
          this.$core.error(error, { title: this.$t("dialog.title.error") });
          this.$router.back();
        });
    },
    onRead(args) {
      console.func("pages/users/DocumentsPage:methods.onRead()", arguments);
      this.readDcument(args.id);
    },
  },
  mounted() {
    this.onLoad();
  },
};
</script>

<style lang="scss">
.unread {
  border-color: $warning !important;
}
</style>
