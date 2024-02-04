<template>
  <q-card>
    <q-list class="q-pa-sm">
      <template v-if="items.length">
        <q-item
          dense
          v-for="(item, index) in items"
          :key="index"
          @click="selectItem(item)"
          clickable
          v-ripple
          :class="{
            'rounded-borders': true,
            'text-primary bg-teal-1': index === selectedIndex,
          }"
        >
          <q-item-section>
            {{ item.name }}
          </q-item-section>
        </q-item>
      </template>
      <q-item v-else clickable v-ripple>
        <q-item-section>No results!</q-item-section>
      </q-item>
    </q-list>
  </q-card>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      required: true,
    },

    command: {
      type: Function,
      required: true,
    },
  },

  data() {
    return {
      selectedIndex: 0,
    };
  },

  watch: {
    items() {
      this.selectedIndex = 0;
    },
  },

  methods: {
    onKeyDown({ event }) {
      if (event.key === "ArrowUp") {
        this.upHandler();
        return true;
      }

      if (event.key === "ArrowDown") {
        this.downHandler();
        return true;
      }

      if (event.key === "Enter") {
        this.enterHandler();
        return true;
      }

      return false;
    },

    upHandler() {
      this.selectedIndex =
        (this.selectedIndex + this.items.length - 1) % this.items.length;
    },

    downHandler() {
      this.selectedIndex = (this.selectedIndex + 1) % this.items.length;
    },

    enterHandler() {
      this.selectItem(this.selectedIndex);
    },

    selectItem(item) {
      if (item) {
        this.command({
          id: item.id,
          rel: "staff",
          label: item.name,
        });
      }
    },
  },
};
</script>
