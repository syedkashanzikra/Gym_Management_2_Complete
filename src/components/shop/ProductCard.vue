<template>
  <q-card class="product-card cursor-pointer" v-ripple:teal-3 flat>
    <div v-if="list">
      <q-item class="q-pa-none">
        <q-item-section avatar>
          <product-thumbnail
            class="rounded-border"
            :ratio="1"
            style="width: 70px"
            :media="thumbnail"
          />
        </q-item-section>
        <q-item-section>
          <q-item-label lines="1">
            {{ title }}
          </q-item-label>
          <q-item-label class="text-subtitle2">
            {{ price }}
          </q-item-label>
        </q-item-section>
      </q-item>
    </div>
    <template v-else>
      <q-item-section>
        <product-thumbnail class="rounded-border" :media="thumbnail" />
      </q-item-section>
      <div class="ellipsis-2-lines text-weight-medium">{{ title }}</div>
      <div class="text-subtitle2">
        {{ price }}
      </div>
    </template>
  </q-card>
</template>

<script>
import ProductThumbnail from "src/components/ProductThumbnail.vue";
export default {
  components: { ProductThumbnail },
  props: {
    title: String,
    price: String,
    thumbnail: Object,
    list: Boolean,
  },
};
</script>
