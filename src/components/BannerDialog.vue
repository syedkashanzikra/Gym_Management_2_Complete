<template>
  <base-dialog
    title="Update banner"
    body-class="q-pa-none scroll"
    content-style="width: 950px; max-width: 95vw"
    ref="dialog"
    @hide="onDialogHide"
    @ok="onDone"
    use-separator
    persistent
  >
    <template v-slot:body>
      <base-section gutter="md" flat dense>
        <div class="col-xs-12 col-sm-12">
          <div class="q-gutter-md">
            <base-section flat bordered :title="$t('generalInformation')">
              <div class="col-xs-12 col-sm-4">
                <base-label>{{ $t("tagLine") }}</base-label>
                <base-input
                  :placeholder="$t('placeholder.bannerTitle')"
                  v-model="banner.tag_line"
                  name="tag_line"
                >
                  <template #append>
                    <base-color-input
                      borderless
                      v-model="banner.options.tag_line"
                    />
                  </template>
                </base-input>
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label required>{{ $t("titleLine1") }}</base-label>
                <base-input
                  :placeholder="$t('placeholder.bannerTag')"
                  v-model="banner.title_line_1"
                  name="title_line_1"
                >
                  <template #append>
                    <base-color-input
                      borderless
                      v-model="banner.options.title_line"
                    />
                  </template>
                </base-input>
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>{{ $t("titleLine2") }}</base-label>
                <base-input
                  :placeholder="$t('placeholder.bannerTag1')"
                  v-model="banner.title_line_2"
                  name="title_line_2"
                >
                  <template #append>
                    <base-color-input
                      borderless
                      v-model="banner.options.title_line"
                    />
                  </template>
                </base-input>
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>{{ $t("button") }}</base-label>
                <base-input
                  :placeholder="$t('placeholder.bannerBtn')"
                  v-model="banner.button"
                  name="button"
                >
                  <template #append>
                    <base-color-input
                      borderless
                      v-model="banner.options.button"
                    />
                  </template>
                </base-input>
              </div>
              <div class="col-xs-12 col-sm-4">
                <base-label>{{ $t("link") }}</base-label>
                <base-input
                  placeholder="https://"
                  v-model="banner.link"
                  type="url"
                  name="link"
                />
              </div>
            </base-section>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12">
          <base-section flat bordered :title="$t('thumbnail')">
            <div class="col-xs-12">
              <file-selector
                accept="image/*"
                dialog-label="uploadThumbnail"
                :hint="$t('hint.images', { type: 'thumbnail' })"
                load-from-server
                v-model="banner.thumbnail"
                :query="
                  (args) => ({
                    ...args,
                    type: 'image',
                  })
                "
              />
            </div>
            <div class="col-xs-12">
              <q-checkbox
                dense
                v-model="banner.is_active"
                :label="$t('active')"
              />
            </div>
          </base-section>
        </div>
      </base-section>
    </template>
  </base-dialog>
</template>

<script>
import { cloneDeep } from "lodash";
import FileSelector from "components/FileSelector.vue";

const defaultValue = {
  thumbnail: null,
  is_active: true,
  options: {},
};

const processData = (data) => {
  if (Array.isArray(data.options) || !data.options) {
    data.options = {};
  }
  return data;
};

export default {
  components: {
    FileSelector,
  },
  props: {
    modelValue: {
      type: Object,
      default: () => defaultValue,
    },
  },
  data() {
    return {
      banner: cloneDeep(this.modelValue),
    };
  },
  emits: ["ok", "hide"],
  methods: {
    async show() {
      console.func("components/MemberDialog:methods.show()", arguments);
      this.banner = processData(cloneDeep(this.modelValue));
      this.$refs.dialog.show();
    },
    hide() {
      console.func("components/MemberDialog:methods.close()", arguments);
      this.$refs.dialog.hide();
    },
    onDialogHide() {
      console.func("components/MemberDialog:methods.onDialogHide()", arguments);
      this.$emit("hide");
    },
    onDone() {
      console.func("components/MemberDialog:methods.onDone()", arguments);
      this.$emit("ok", this.banner);
      this.hide();
    },
  },
};
</script>
