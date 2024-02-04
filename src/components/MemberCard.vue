<template>
  <q-card flat bordered class="member-card">
    <q-card-section>
      <div class="text-h6 title text-uppercase">{{ name }}</div>
      <div class="logo">
        <img src="/images/logo.png" />
      </div>
      <div class="text-right id text-subtitle2">
        {{ $t("membershipNumber") }}: {{ mId }}
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  props: {
    first_name: String,
    last_name: String,
    id: [String, Number],
  },
  computed: {
    name() {
      return this.first_name + " " + this.last_name;
    },
    mId() {
      const sid = String(this.id);
      return sid.length < 4 ? sid.padStart(4, "0") : sid;
    },
  },
};
</script>

<style lang="scss">
.member-card {
  .q-card__section--vert {
    padding: 10px;
  }
  .logo {
    img {
      width: 100%;
    }
  }
  .title {
    font-size: 16px;
  }
  .id {
    font-size: 10px;
  }
}
</style>
