<template>
  <div class="url-list">
    <div class="q-mb-md flex items-center">
      <div class="text-bold">{{ $t("urls") }}</div>
      <q-space />
      <base-btn
        v-if="!view"
        color="primary"
        icon="fad fa-circle-plus"
        :label="$t('addNew')"
        flat
        @click="onAdd"
      />
    </div>
    <div v-if="noLinks" class="text-grey">
      {{ $t("noUrlsAdded") }}
    </div>
    <q-list :dense="view" class="q-gutter-y-sm">
      <q-item class="q-pa-none" v-for="(item, index) in lists" :key="index">
        <template v-if="view">
          <q-item-section>
            <q-item-label>
              <a :href="item.url">{{ item.label || item.url }}</a>
            </q-item-label>
          </q-item-section>
        </template>
        <template v-else>
          <q-item-section>
            <base-input
              v-model="item.url"
              :placeholder="$t('placeholder.url')"
            />
          </q-item-section>
          <q-item-section>
            <base-input
              v-model="item.label"
              :placeholder="$t('placeholder.label')"
            />
          </q-item-section>
          <q-item-section side>
            <base-btn
              color="grey"
              size="md"
              flat
              padding="14px"
              icon="fas fa-trash-can"
              @click="onRemove(index)"
            />
          </q-item-section>
        </template>
      </q-item>
    </q-list>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: Array,
    view: Boolean,
  },
  emits: ["update:model-value"],
  data() {
    return {
      lists: this.modelValue || [],
    };
  },
  methods: {
    onRemove(index) {
      console.func("components/UrlList:methods.onRemove()", arguments);
      this.lists.splice(index, 1);
      this.$emit("update:model-value", this.lists);
    },
    onAdd(index) {
      console.func("components/UrlList:methods.onAdd()", arguments);
      this.lists.push({});
      this.$emit("update:model-value", this.lists);
    },
  },
  computed: {
    noLinks() {
      return !this.lists || (this.lists && this.lists.length < 1);
    },
  },
};
</script>
