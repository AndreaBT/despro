<template>
    <div class='demo-app'>
        <div class='demo-app-top'>
            <!--<button @click="toggleWeekends">toggle weekends</button>
            <button @click="gotoPast">go to a date in the past</button>
            (also, click a date/time to add an event)-->
        </div>
        <FullCalendar
            :locale="locale"
            class='demo-app-calendar'
            ref="fullCalendar"
            defaultView="dayGridMonth"
            :header="{
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            }"
            :plugins="calendarPlugins"
            :weekends="calendarWeekends"
            :events="calendarEvents"
            :eventLimit="true"
            @dateClick="handleDateClick"
            @eventClick="eventClick"
        />
    </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'
export default {
    components: {
        FullCalendar // make the <FullCalendar> tag available
    },
    data: function() {
        return {
            calendarPlugins: [ // plugins must be defined in the JS
                dayGridPlugin,
                timeGridPlugin,
                interactionPlugin // needed for dateClick
            ],
            calendarWeekends: true,
            calendarEvents: [ // initial event data
                { title: 'Este es el ejemplo de un nuevo evento para la cotizaion ', start: new Date() }
            ],
            locale: esLocale,
        }
    },
    methods: {
        toggleWeekends() {
            this.calendarWeekends = !this.calendarWeekends // update a property
        },
        gotoPast() {
            let calendarApi = this.$refs.fullCalendar.getApi() // from the ref="..."
            calendarApi.gotoDate('2000-01-01') // call a method on the Calendar object
        },
        handleDateClick(arg) {
            if (confirm('Esta seguro que desea ir a el gant ' + arg.dateStr + ' ?')) {
                this.calendarEvents.push({ // add new event data
                title: 'New Event',
                start: arg.date,
                allDay: arg.allDay
                })
            }
        },
        eventClick: function(calEvent, jsEvent, view) {
            //alert('id: ' + calEvent.id + 'Cliente: ' + calEvent.title + '\nTrabajador: '+calEvent.Nombre+calEvent.FechaInicio);
            list_serviciofecha(calEvent.FechaInicio);
            // change the border color just for fun
            //$(this).css('border-color', callEvent.color);
        }
    }
}
</script>

<style lang='scss'>
// you must include each plugins' css
// paths prefixed with ~ signify node_modules
@import '~@fullcalendar/core/main.css';
@import '~@fullcalendar/daygrid/main.css';
@import '~@fullcalendar/timegrid/main.css';
.demo-app {
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
  font-size: 14px;
}
.demo-app-top {
  margin: 0 0 3em;
}
.demo-app-calendar {
  margin:  auto;
  max-width: auto;
}
</style>