<template>
  <swiper
    class="swiper-slider"
    :slides-per-view="slidesPerView"
    :breakpoints="breakpoints"
    :space-between="spaceBetween"
    :navigation="true"
    :modules="modules"
  >
    <swiper-slide v-for="(slide, index) in slides" :key="slide">
      <q-img
        @click="onShowLightBox(slide, index)"
        :ratio="16 / 9"
        :src="slide"
        spinner-color="primary"
        spinner-size="82px"
      />
    </swiper-slide>
  </swiper>
</template>
<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// import required modules
import { Navigation } from "swiper";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";

import LightBox from "./LightBox.vue";

export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  data() {
    return {
      modules: [Navigation],
    };
  },
  props: {
    breakpoints: {
      // breakpoints are mobile first
      // any settings not specified will fallback to the swiper settings
      type: Object,
      default: () => ({
        // 400px and up
        400: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        // 1440px and up
        1440: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
      }),
    },
    spaceBetween: {
      type: Number,
      default: 20,
    },
    slidesPerView: {
      type: Number,
      default: 2,
    },
    slides: {
      type: Array,
      required: true,
    },
  },
  methods: {
    onShowLightBox(value, index) {
      console.func(
        "components/SwiperSlider:methods.onShowLightBox()",
        arguments
      );
      const slides = this.slides.filter((item, id) => id !== index);
      this.$q.dialog({
        component: LightBox,
        componentProps: {
          imgs: [value, ...slides],
        },
      });
    },
  },
};
</script>

<style lang="scss">
.swiper-slider {
  .swiper-wrapper {
    .swiper-slide > div {
      overflow: hidden;
      // border-radius: 12px;
    }
  }
  .swiper-button-prev,
  .swiper-button-next {
    background: #ffffff;
    color: $primary;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    padding: 15px;
  }
  .swiper-button-next:after,
  .swiper-button-prev:after {
    font-size: 14px;
    font-weight: 800;
  }
}
</style>
