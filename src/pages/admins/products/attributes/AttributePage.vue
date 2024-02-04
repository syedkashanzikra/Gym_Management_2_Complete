<template>
  <q-page padding>
    <base-form
      v-if="loaded"
      @submit="onSubmit"
      @cancel="onCancel"
      @reset="onReset"
      :resetable="resetable"
      :disable="disable"
      :submited="submited"
    >
      <div class="row q-col-gutter-md">
        <div class="col-xs-12 col-sm-9">
          <div class="q-gutter-md">
            <base-section
              title="General information"
              description="Attributes let you define extra product data, such as size or colour. You can use these attributes in the shop sidebar using the `layered nav` widgets."
            >
              <div class="col-xs-12">
                <base-label>{{ $t("label.name") }}</base-label>
                <base-input v-model="attribute.name" />
              </div>
            </base-section>
            <base-section
              title="Values"
              description="Attribute values can be assigned to products and variations."
            >
              <div class="col-xs-12">
                <div
                  v-for="(value, index) in attribute.values"
                  :key="index"
                  class="q-mb-md value"
                >
                  <div class="flex items-center">
                    <base-label>
                      {{ $t("label.value") }} {{ index + 1 }}
                    </base-label>
                    <q-space />
                    <base-btn
                      v-if="attribute.values.length > 1"
                      link
                      class="q-ml-sm"
                      padding="0"
                      :label="$t('label.remove')"
                      @click="onRemoveValue(index)"
                    />
                  </div>
                  <q-item class="q-pa-none">
                    <q-item-section avatar>
                      <template v-if="value.has_thumbnail">
                        <thumbnail-selector
                          mini
                          load-from-server
                          :dialog-label="$t('label.uploadthumbnail')"
                          icon="fad fa-image"
                          hint="You can only choose images as value media"
                          :model-value="value.thumbnail"
                          @update:model-value="(val) => (value.thumbnail = val)"
                        >
                          <base-tooltip position="top">
                            Select an image
                          </base-tooltip>
                        </thumbnail-selector>
                      </template>
                      <template v-else>
                        <q-avatar
                          rounded
                          size="60px"
                          :style="{
                            background: value.color || `var(--q-primary)`,
                          }"
                          icon="fas fa-eye-dropper"
                          text-color="white"
                        >
                          <q-popup-proxy
                            cover
                            transition-show="scale"
                            transition-hide="scale"
                          >
                            <q-color v-model="value.color" />
                          </q-popup-proxy>
                          <base-tooltip position="top">
                            Select color
                          </base-tooltip>
                        </q-avatar>
                      </template>
                    </q-item-section>
                    <q-item-section>
                      <q-item-label>
                        <base-input v-model="value.name" placeholder="Name" />
                        <q-checkbox
                          class="q-mt-sm"
                          dense
                          size="xs"
                          :label="$t('label.imagePreviewForThisValue')"
                          v-model="value.has_thumbnail"
                          color="green"
                        />
                      </q-item-label>
                    </q-item-section>
                  </q-item>
                  <q-separator class="q-mt-md" />
                </div>
                <q-btn
                  no-caps
                  outline
                  :label="
                    $t('label.AddNewAttributeName', {
                      attribute: attribute.name,
                    })
                  "
                  @click="
                    onAddValue({
                      id: false,
                      name: null,
                      has_thumbnail: false,
                      color: null,
                    })
                  "
                />
              </div>
            </base-section>
          </div>
        </div>
        <div class="col-xs-12 col-sm-3">
          <base-section no-row>
            <div class="q-gutter-md">
              <div>
                <base-label>{{
                  $t("label.attributeVisibilityMode")
                }}</base-label>
                <q-toggle
                  :label="$t('label.isButton')"
                  dense
                  size="sm"
                  v-model="attribute.is_button"
                  color="green"
                />
                <q-item-label class="q-mt-sm">
                  Enable this if you want to show this attribute as button on
                  products in your store.
                </q-item-label>
              </div>
            </div>
          </base-section>
        </div>
      </div>
    </base-form>
    <skeleton-single-page v-else />
  </q-page>
</template>

<script>
import { cloneDeep } from "lodash";
import { mapActions } from "pinia";
import ThumbnailSelector from "components/FileSelector.vue";
import SkeletonSinglePage from "components/skeleton/SkeletonSinglePage.vue";
import { useAttributeStore } from "stores/product/attribute";

const attribute = {
  name: "",
  is_button: false,
  values: [],
};

export default {
  components: {
    ThumbnailSelector,
    SkeletonSinglePage,
  },
  data() {
    return {
      loaded: false,
      submited: false,
      default: cloneDeep(attribute),
      attribute: cloneDeep(attribute),
      options: [],
    };
  },
  methods: {
    ...mapActions(useAttributeStore, ["show", "store", "update"]),
    onSubmit(props) {
      console.func(
        "pages/admins/attributes/AttributePage:methods.onSubmit()",
        arguments
      );
      this.submited = true;
      const method = this.creating ? this.store : this.update;
      method(this.attribute)
        .then(({ data, message }) => {
          this.submited = false;
          this.$q.notify(message);
          this.attribute = data;
          this.default = cloneDeep(data);
          this.$router.push({
            name: "Single Attribute",
            params: {
              id: data.id,
            },
            query: {
              action: "edit",
            },
          });
        })
        .catch((error) => {
          this.submited = false;
          this.$core.error(error, { title: this.$t("dialog.title.error") });
        });
    },
    onReset(props) {
      console.func(
        "pages/admins/attributes/AttributePage:methods.onReset()",
        arguments
      );
      this.loaded = false;
      setTimeout(() => {
        this.attribute = cloneDeep(this.default);
        this.loaded = true;
      }, 1);
    },
    onCancel(props) {
      console.func(
        "pages/admins/attributes/AttributePage:methods.onCancel()",
        arguments
      );
      this.$router.back();
    },
    onAddValue(value) {
      console.func(
        "pages/admins/attributes/AttributePage:method.onAddValue()",
        arguments
      );
      this.attribute.values.push(value);
    },
    onRemoveValue(index) {
      console.func(
        "pages/admins/attributes/AttributePage:method.onRemoveValue()",
        arguments
      );
      this.attribute.values.splice(index, 1);
    },
  },
  mounted() {
    if (!this.creating) {
      this.show(this.id)
        .then((attribute) => {
          this.attribute = attribute;
          this.default = cloneDeep(attribute);
          this.loaded = true;
        })
        .catch((error) => {
          this.$emit("error", error);
        });
    } else {
      this.loaded = true;
    }
  },
  computed: {
    edit() {
      return ["add", "edit"].includes(this.action) || this.creating;
    },
    creating() {
      return this.id === "add";
    },
    id() {
      return this.$route.params.id;
    },
    action() {
      return this.$route.query.action;
    },
    disable() {
      return (
        this.default &&
        JSON.stringify(this.attribute) === JSON.stringify(this.default)
      );
    },
    resetable() {
      return (
        this.default &&
        JSON.stringify(this.attribute) !== JSON.stringify(this.default)
      );
    },
  },
};
</script>
