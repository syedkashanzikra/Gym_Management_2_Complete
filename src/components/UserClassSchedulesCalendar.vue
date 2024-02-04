<template>
  <FullCalendar
    ref="element"
    class="user-class-schedules-calendar"
    :options="calendarOptions"
  />
</template>

<script>
import tippy from "tippy.js";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";
import { useMemberStore } from "src/stores/member";
import { mapActions } from "pinia";

import "tippy.js/dist/tippy.css";
import { useClassScheduleStore } from "stores/member/class-schedule";

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
    ...mapActions(useMemberStore, ["schedulesCalendar"]),
    ...mapActions(useClassScheduleStore, ["cancel"]),
    onLoad({ start, end }, resolve, reject) {
      console.func("components/FullCalendar:methods.onLoad", arguments);
      this.schedulesCalendar({
        start,
        end,
        moduleId: this.moduleId,
        hasClass: true,
        activeOnly: true,
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
    onEventClick({ event, el }) {
      console.func("components/FullCalendar:methods.onEventClick", arguments);
      const booking = event?.extendedProps?.booking;
      if (booking?.cancelable) {
        this.cancel({
          ...event,
          ...event?.extendedProps,
        }).then(() => {
          event.remove();
        });
      }
    },
    onEventDidMount({ event, el, timeText }) {
      const instructor = event.extendedProps?.instructor;
      const location = event.extendedProps?.location;
      const className = event.extendedProps?.class;
      const title = () => {
        if (timeText) {
          return className + " - " + timeText;
        }
        return className;
      };
      tippy(el, {
        content: `<div style="min-width:250px" class="calendar-popover fc-more-popover fc-day fc-day-mon fc-day-today"><div class="fc-popover-header"><span class="fc-popover-title text-bold">${title()}</span></div><div class="fc-popover-body"><div class="fc-daygrid-event-harness"><i class="q-icon q-mr-xs fad fa-location-dot" style="font-size:12px" aria-hidden="true" role="presentation"></i>${location}</div><div class="fc-daygrid-event-harness"><i class="q-icon q-mr-xs fad fa-user" style="font-size:12px" aria-hidden="true" role="presentation"></i>${instructor}</div></div></div>`,
        allowHTML: true,
        zIndex: 99999,
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
        eventTimeFormat: {
          hour: "numeric",
          minute: "2-digit",
        },
        eventDidMount: this.onEventDidMount,
        eventClick: this.onEventClick,
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
.calendar-popover {
  .fc-popover-header {
    background: transparent !important;
    border-bottom: 1px solid rgba(2, 2, 2, 0.245);
  }
  .q-icon {
    margin-top: -3px;
  }
}
</style>
