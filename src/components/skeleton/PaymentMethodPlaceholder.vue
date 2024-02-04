<template>
  <q-card class="payment-method-card" bordered flat>
    <q-card-section>
      <q-item>
        <q-item-section class="card-brand" top avatar>
          <q-skeleton :animation="animation" type="QToggle" />
        </q-item-section>
        <q-item-section>
          <q-item-label>
            <q-skeleton :animation="animation" width="80%" type="rect" />
          </q-item-label>
          <q-item-label caption>
            <q-skeleton :animation="animation" width="50%" type="text" />
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  props: {
    animation: String,
  },
};
</script>
