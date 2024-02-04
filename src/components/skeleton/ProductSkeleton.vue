<template>
  <base-section flat bordered>
    <div class="col-xs-12 col-sm-6">
      <div class="no-images">
        <q-skeleton height="350px" square animation="fade" />
      </div>
    </div>
    <div class="col-xs-12 col-sm-6">
      <div class="q-gutter-y-sm">
        <div>
          <q-skeleton height="50px" width="80%" type="text" />
          <q-skeleton height="50px" width="100px" type="text" />
          <q-skeleton type="text" />
          <q-skeleton type="text" />
          <q-skeleton width="90%" type="text" />
          <q-skeleton type="text" />
          <q-skeleton type="text" />
          <q-skeleton width="90%" type="text" />
          <q-skeleton type="text" />
          <q-skeleton type="text" />
          <q-skeleton width="90%" type="text" />
        </div>
        <div class="cat">
          <q-skeleton width="200px" class="main-btn" type="QBtn" />
        </div>
      </div>
    </div>
  </base-section>
</template>
