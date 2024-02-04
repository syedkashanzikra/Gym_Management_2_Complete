<template>
  <q-responsive class="file-card box rounded-borders cursor-pointer" :ratio="1">
    <div v-if="is_image || is_embed" class="thumbnail">
      <q-img loading="lazy" :src="url" contain>
        <template v-slot:loading>
          <q-spinner-orbit color="white" />
        </template>
      </q-img>
      <div v-if="is_embed" class="absolute-bottom-right q-pa-sm">
        <q-btn flat dense round color="grey" icon="fad fa-play-circle" />
      </div>
    </div>
    <div v-else class="text-black flex flex-center file">
      <div class="info overflow-hidden text-center">
        <q-icon :size="mini ? '20px' : '35px'" :name="`fas fa-file-${icon}`" />
      </div>
    </div>
    <div v-if="useControler" class="absolute-full controller q-pa-sm">
      <div class="handler absolute-full flex flex-center">
        <q-btn
          class="handle"
          color="white"
          size="xs"
          flat
          round
          icon="far fa-expand-alt"
          @click.stop="$emit('details')"
        />
        <q-btn
          @click.stop="$emit('select')"
          class="handle"
          color="white"
          size="xs"
          flat
          round
          icon="far fa-upload"
        />
      </div>
    </div>
    <slot></slot>
  </q-responsive>
</template>

<script>
export default {
  emits: ["details", "select"],
  props: {
    id: [String, Number],
    name: String,
    url: String,
    icon: String,
    is_image: Boolean,
    is_embed: Boolean,
    mini: Boolean,
    useControler: Boolean,
  },
};
</script>
<style lang="sass">
.file-card
  .handler
    opacity: 0
    background: linear-gradient(180deg,rgba(33,43,54,.55),hsla(0,0%,100%,0))
    border-radius: $generic-border-radius
  &:hover
    .handler
      opacity: 1
</style>
