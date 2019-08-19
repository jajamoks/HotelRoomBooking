<template>
  <div id="app">
    <modals-container />
    <v-dialog />
    <div class="calendar-controls">
      <div v-if="message" class="notification is-success">{{ message }}</div>

      <div class="box">
        <h4 class="title is-5">
          <router-link :to="{ name: 'booking_create' }" class="btn btn-primary">Book A Room</router-link>
        </h4>

        <div class="field">
          <label class="label">Filter By</label>
          <div class="control">
            <div class="select">
              <select v-model="displayPeriodUom">
                <option>month</option>
                <option>year</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="calendar-parent">
      <calendar-view
        :events="events"
        :show-date="showDate"
        :time-format-options="{hour: 'numeric', minute:'2-digit'}"
        :enable-drag-drop="true"
        :disable-past="disablePast"
        :disable-future="disableFuture"
        :show-event-times="showEventTimes"
        :display-period-uom="displayPeriodUom"
        :display-period-count="displayPeriodCount"
        :starting-day-of-week="startingDayOfWeek"
        :class="themeClasses"
        :period-changed-callback="periodChanged"
        :current-period-label="useTodayIcons ? 'icons' : ''"
        @drop-on-date="onDrop"
        @click-date="onClickDay"
        @click-event="onClickEvent"
      >
        <calendar-view-header
          slot="header"
          slot-scope="{ headerProps }"
          :header-props="headerProps"
          @input="setShowDate"
        />
      </calendar-view>
    </div>
  </div>
</template>
<script>
// Load CSS from the published version
import "vue-simple-calendar/static/css/default.css";
import "vue-simple-calendar/static/css/holidays-us.css";

import {
  CalendarView,
  CalendarViewHeader,
  CalendarMathMixin
} from "vue-simple-calendar"; // published version

export default {
  name: "App",
  components: {
    CalendarView,
    CalendarViewHeader
  },
  mixins: [CalendarMathMixin],
  data() {
    return {
      bookings: [],
      /* Show the current month, and give it some fake events to show */
      showDate: this.thisMonth(1),
      message: "",
      startingDayOfWeek: 0,
      disablePast: false,
      disableFuture: false,
      displayPeriodUom: "month",
      displayPeriodCount: 1,
      showEventTimes: true,
      newEventTitle: "",
      newEventStartDate: "",
      newEventEndDate: "",
      useDefaultTheme: true,
      useHolidayTheme: false,
      useTodayIcons: false,
      events: []
    };
  },
  created() {
    let uri = "http://127.0.0.1:8000/api/booking";
    this.axios.get(uri).then(response => {
      var _vue = this;
      this.bookings = response.data.data;
      let dataObj = Object.values(this.bookings);
      dataObj.forEach(function(value, key) {
        _vue.events.push({
          startDate: value.date_start,
          endDate: value.date_end,
          title: value.name + " (" + value.phone_number + ")",
          //id: value.id
          id: key
        });
      });
    });
  },
  computed: {
    userLocale() {
      return this.getDefaultBrowserLocale;
    },
    dayNames() {
      return this.getFormattedWeekdayNames(this.userLocale, "long", 0);
    },
    themeClasses() {
      return {
        "theme-default": this.useDefaultTheme,
        "holiday-us-traditional": this.useHolidayTheme,
        "holiday-us-official": this.useHolidayTheme
      };
    }
  },
  mounted() {
    this.newEventStartDate = this.isoYearMonthDay(this.today());
    this.newEventEndDate = this.isoYearMonthDay(this.today());
  },

  methods: {
    periodChanged(range, eventSource) {
      // Demo does nothing with this information, just including the method to demonstrate how
      // you can listen for changes to the displayed range and react to them (by loading events, etc.)
      console.log(eventSource);
      console.log(range);
    },
    thisMonth(d, h, m) {
      const t = new Date();
      return new Date(t.getFullYear(), t.getMonth(), d, h || 0, m || 0);
    },
    onClickDay(d) {
      //this.message = `You clicked: ${d.toLocaleDateString()}`;
    },
    onClickEvent(e) {
      let details = this.bookings[e.id];
      let detailsHtml = "<table>";
      detailsHtml +=
        "<tr><td>Name:: &nbsp;&nbsp;</td><td>" + details.name + "</td></tr>";
      detailsHtml +=
        "<tr><td>Phone No.::</td> <td>" + details.phone_number + "</td></tr>";
      detailsHtml += "<tr><td>Email::</td> <td>" + details.email + "</td></tr>";
      detailsHtml +=
        "<tr><td>Room::</td> <td>" +
        details.room.name +
        " (" +
        details.room.room_type.type +
        ") </td></tr>";
      detailsHtml +=
        "<tr><td>Date Start::</td> <td>" + details.date_start + "</td></tr>";
      detailsHtml +=
        "<tr><td>Date End::</td> <td>" + details.date_end + "</td></tr>";
      detailsHtml +=
        "<tr><td>Total Nights::</td> <td>" +
        details.total_nights +
        "</td></tr>";
      detailsHtml +=
        "<tr><td>Total Price::</td> <td>USD " +
        details.total_price +
        "</td></tr>";
      detailsHtml += "</table>";
      this.$modal.show("dialog", {
        title: "Booking Details",
        text: detailsHtml,
        buttons: [
          {
            title: "Edit",
            handler: () => {
              //alert(details.id);

              this.$router.push({
                name: "booking_edit",
                params: { id: details.id }
              });
            }
          },
          {
            title: "Close"
          }
        ]
      });
    },
    setShowDate(d) {
      // this.message = `Changing calendar view to ${d.toLocaleDateString()}`;
      //this.showDate = d;
    },
    onDrop(event, date) {
      this.message = `You dropped ${event.id} on ${date.toLocaleDateString()}`;
      // Determine the delta between the old start date and the date chosen,
      // and apply that delta to both the start and end date to move the event.
      const eLength = this.dayDiff(event.startDate, date);
      event.originalEvent.startDate = this.addDays(event.startDate, eLength);
      event.originalEvent.endDate = this.addDays(event.endDate, eLength);
    },
    clickTestAddEvent() {
      this.events.push({
        startDate: this.newEventStartDate,
        endDate: this.newEventEndDate,
        title: this.newEventTitle,
        id:
          "e" +
          Math.random()
            .toString(36)
            .substr(2, 10)
      });
      this.message = "You added an event!";
    }
  }
};
</script>

<style>
html,
body {
  height: 100%;
  margin: 0;
  background-color: #f7fcff;
}

#app {
  display: flex;
  flex-direction: row;
  font-family: Calibri, sans-serif;
  /*width: 95vw;*/
  width: 100%;
  min-width: 30rem;
  max-width: 100rem;
  min-height: 40rem;
  margin-left: auto;
  margin-right: auto;
}

.calendar-controls {
  margin-right: 1rem;
  min-width: 14rem;
  max-width: 14rem;
}

.calendar-parent {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  overflow-x: hidden;
  overflow-y: hidden;
  max-height: 80vh;
  background-color: white;
}

/* For long calendars, ensure each week gets sufficient height. The body of the calendar will scroll if needed */
.cv-wrapper.period-month.periodCount-2 .cv-week,
.cv-wrapper.period-month.periodCount-3 .cv-week,
.cv-wrapper.period-year .cv-week {
  min-height: 6rem;
}

/* These styles are optional, to illustrate the flexbility of styling the calendar purely with CSS. */

/* Add some styling for events tagged with the "birthday" class */
.theme-default .cv-event.birthday {
  background-color: #e0f0e0;
  border-color: #d7e7d7;
}

.theme-default .cv-event.birthday::before {
  content: "\1F382"; /* Birthday cake */
  margin-right: 0.5em;
}
</style>
