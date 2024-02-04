<template>
  <div class="base-no-records flex flex-center" :style="{ height: height }">
    <div class="container text-center" :style="containerStyle">
      <q-icon :color="iconColor" :size="iconSize" :name="icon" />
      <div class="q-mt-md text-subtitle2">{{ title }}</div>
      <div class="message">{{ message }}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    height: {
      type: String,
      default: "calc(100vh - 220px)",
    },
    containerStyle: {
      type: String,
      default: "width: 400px; max-width: calc(100vw - 50px)",
    },
    icon: {
      type: String,
      default: "fad fa-earth-americas",
    },
    iconColor: {
      type: String,
      default: "teal-2",
    },
    iconSize: {
      type: String,
      default: "100px",
    },
    title: String,
    message: String,
  },
};
</script>
