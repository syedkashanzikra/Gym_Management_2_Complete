<template>
  <q-item>
    <q-item-section avatar>
      <img v-if="isUrlLogo" style="width: 30px" :src="logo" />
      <q-icon size="30px" v-else color="primary" :name="logo" />
    </q-item-section>
    <q-item-section>
      <q-item-label v-if="link" caption>
        <a :href="link" target="_blank" rel="noopener noreferrer">{{ name }}</a>
      </q-item-label>
      <q-item-label v-else>{{ name }}</q-item-label>
      <q-item-label caption lines="2">{{ description }}</q-item-label>
      <q-item-label v-if="active || test_mode">
        <base-status v-if="active" class="q-ma-none" type="Active" />
        <base-status v-if="test_mode" type="Test Mode" />
      </q-item-label>
    </q-item-section>
    <q-item-section side>
      <q-item-label>
        <base-btn
          @click.stop="$emit('edit')"
          color="grey"
          icon="fas fa-edit"
          flat
          dense
          round
        />
      </q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
const isUrl = (str) => {
  // Regular expression for a simple URL check
  const urlRegex = /^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$/;
  return urlRegex.test(str);
};

export default {
  props: {
    logo: String,
    link: String,
    name: String,
    description: String,
    active: Boolean,
    test_mode: Boolean,
  },
  emits: ["edit"],
  computed: {
    isUrlLogo() {
      return isUrl(this.logo);
    },
  },
};
</script>
