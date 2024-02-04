<template>
  <FullCalendar
    ref="element"
    class="class-schedules-calendar"
    :options="calendarOptions"
  />
</template>

<script>
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";
import { mapActions } from "pinia";
import { useWeekScheduleStore } from "src/stores/week-schedule";

export default {
  props: {
    moduleId: [String, Number],
  },
  components: {
    FullCalendar,
  },
  data() {
    return {
      currentEvents: [],
    };
  },
  methods: {
    ...mapActions(useWeekScheduleStore, ["calendar"]),
    onLoad({ start, end }, resolve, reject) {
      console.func("components/FullCalendar:methods.onLoad", arguments);
      this.calendar({
        start,
        end,
      })
        .then((data) => {
          resolve(
            data.map((item) => ({
              ...item,
              color: this.$core.stringToHslColor(item.class, 25, 55),
              textColor: "#000000",
            }))
          );
        })
        .catch((error) => {
          reject(error);
        });
    },
    onResize(info) {
      console.func("components/FullCalendar:methods.onResize", arguments);
      this.$refs.element.calendar.changeView(
        this.$q.screen.lt.sm ? "listWeek" : "dayGridMonth"
      );
    },
  },
  computed: {
    calendarOptions() {
      const isSmall = this.$q.screen.lt.md;
      let headerToolbar = {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek",
      };
      if (isSmall) {
        headerToolbar = {
          left: "title",
          center: "prev,next today",
          right: "timeGridDay,listWeek",
        };
      }
      return {
        aspectRatio: isSmall ? 0.8 : 1.8,
        firstDay: "1",
        plugins: [
          dayGridPlugin,
          timeGridPlugin,
          listPlugin,
          interactionPlugin, // needed for dateClick
        ],
        headerToolbar: headerToolbar,
        initialView: isSmall ? "listWeek" : "dayGridMonth",
        initialEvents: [],
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        editable: true,
        eventTimeFormat: {
          hour: "numeric",
          minute: "2-digit",
        },
        events: this.onLoad,
        windowResize: this.onResize,
      };
    },
  },
};
</script>

<style lang="scss">
.fc-event {
  cursor: pointer;
}
.fc .fc-button {
  text-transform: capitalize;
}
</style>
