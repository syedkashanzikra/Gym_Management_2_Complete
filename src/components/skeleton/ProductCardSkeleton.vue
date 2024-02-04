<template>
  <div class="row q-py-md q-col-gutter-md">
    <div
      v-for="(item, index) in rowsPerPage"
      :key="index"
      :class="{
        'col-xs-12 col-lg-2 col-md-3 col-sm-3': !list,
        'col-xs-12 col-lg-4 col-md-6 col-sm-6': list,
      }"
    >
      <q-card class="product-card cursor-pointer" flat>
        <div v-if="list">
          <q-item class="q-pa-none">
            <q-item-section avatar>
              <q-skeleton height="70px" width="70px" square animation="fade" />
            </q-item-section>
            <q-item-section>
              <q-item-label lines="1">
                <q-skeleton type="text" />
                <q-skeleton width="80%" type="text" />
              </q-item-label>
              <q-item-label class="text-subtitle2">
                <q-skeleton width="150px" type="text" />
              </q-item-label>
            </q-item-section>
          </q-item>
        </div>
        <template v-else>
          <q-skeleton height="150px" square animation="fade" />
          <q-card-section>
            <q-skeleton type="text" />
            <q-skeleton width="90%" type="text" />
            <div class="text-subtitle2">
              <q-skeleton width="90px" type="text" />
            </div>
          </q-card-section>
        </template>
      </q-card>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    list: Boolean,
    rowsPerPage: {
      type: Number,
      default: 18,
    },
  },
};
</script>

<style lang="scss">
.q-infinite-scroll__loading {
  &.invisible > * {
    height: 250px;
    overflow: hidden;
  }
}
</style>
