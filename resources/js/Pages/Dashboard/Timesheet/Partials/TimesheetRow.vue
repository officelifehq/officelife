<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}

.grid {
  display: grid;
  grid-template-columns: 3fr repeat(7, 1fr);
  align-items: center;
}

.project {
  width: 280px;
  max-width: 400px;
}

.off-days {
  color: #4b7682;
  background-color: #e6f5f9;
}

.add-new-entry {
  background-color: #f3ffee;
}
</style>

<template>
  <div class="dt-row">
    <div class="f6 ph2 pv3 dtc bl bb bb-gray project v-mid">
      <div class="flex justify-between items-center">
        <div>
          <span class="db pb1 fw5">
            {{ localRow.task_title }}
          </span>
          <span class="db gray">
            {{ localRow.project_name }}
          </span>
        </div>
        <span class="f7 fw5">
          {{ total }}
        </span>
      </div>
    </div>

    <!-- monday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days.monday.hours"
        :minutes="localRow.days.monday.minutes"
        :total="localRow.days.monday.total_of_minutes"
        @update="updateDay($event, 0)"
      />
    </div>

    <!-- tuesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days.tuesday.hours"
        :minutes="localRow.days.tuesday.minutes"
        :total="localRow.days.tuesday.total_of_minutes"
        @update="updateDay($event, 1)"
      />
    </div>

    <!-- wednesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days.wednesday.hours"
        :minutes="localRow.days.wednesday.minutes"
        :total="localRow.days.wednesday.total_of_minutes"
        @update="updateDay($event, 2)"
      />
    </div>

    <!-- thursday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days.thursday.hours"
        :minutes="localRow.days.thursday.minutes"
        :total="localRow.days.thursday.total_of_minutes"
        @update="updateDay($event, 3)"
      />
    </div>

    <!-- friday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days.friday.hours"
        :minutes="localRow.days.friday.minutes"
        :total="localRow.days.friday.total_of_minutes"
        @update="updateDay($event, 4)"
      />
    </div>

    <!-- saturday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days">
      <text-duration
        :hours="localRow.days.saturday.hours"
        :minutes="localRow.days.saturday.minutes"
        :total="localRow.days.saturday.total_of_minutes"
        @update="updateDay($event, 5)"
      />
    </div>

    <!-- sunday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days">
      <text-duration
        :hours="localRow.days.sunday.hours"
        :minutes="localRow.days.sunday.minutes"
        :total="localRow.days.sunday.total_of_minutes"
        @update="updateDay($event, 6)"
      />
    </div>
  </div>
</template>

<script>
import TextDuration from '@/Shared/TextDuration';

export default {
  components: {
    TextDuration,
  },

  props: {
    row: Object,
    timesheet: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        project_id: 0,
        project_task_id: 0,
        day: 0,
        durationInMinutes: 0,
      },
      localRow: {
        project_id: 0,
        project_name: 0,
        project_code: 0,
        task_id: 0,
        task_title: 0,
        total_this_week: 0,
        days: {
          monday: {
            day_of_week: 1,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          tuesday: {
            day_of_week: 2,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          wednesday: {
            day_of_week: 3,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          thursday: {
            day_of_week: 4,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          friday: {
            day_of_week: 5,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          saturday: {
            day_of_week: 6,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          sunday: {
            day_of_week: 7,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
        },
      },
      total: '00h00',
    };
  },

  mounted() {
    // necessary because the backend sends a row back with indexes by number on
    // the days array, and in the Vue files we need to reference days by their names
    this.localRow.project_id = this.row.project_id;
    this.localRow.project_name = this.row.project_name;
    this.localRow.project_code = this.row.project_code;
    this.localRow.task_id = this.row.task_id;
    this.localRow.task_title = this.row.task_title;
    this.localRow.total_this_week = this.row.total_this_week;
    this.localRow.days.monday.day_of_week = this.row.days[0].day_of_week;
    this.localRow.days.monday.total_of_minutes = this.row.days[0].total_of_minutes;
    this.localRow.days.monday.hours = this.row.days[0].hours;
    this.localRow.days.monday.minutes = this.row.days[0].minutes;
    this.localRow.days.tuesday.day_of_week = this.row.days[1].day_of_week;
    this.localRow.days.tuesday.total_of_minutes = this.row.days[1].total_of_minutes;
    this.localRow.days.tuesday.hours = this.row.days[1].hours;
    this.localRow.days.tuesday.minutes = this.row.days[1].minutes;
    this.localRow.days.wednesday.day_of_week = this.row.days[2].day_of_week;
    this.localRow.days.wednesday.total_of_minutes = this.row.days[2].total_of_minutes;
    this.localRow.days.wednesday.hours = this.row.days[2].hours;
    this.localRow.days.wednesday.minutes = this.row.days[2].minutes;
    this.localRow.days.thursday.day_of_week = this.row.days[3].day_of_week;
    this.localRow.days.thursday.total_of_minutes = this.row.days[3].total_of_minutes;
    this.localRow.days.thursday.hours = this.row.days[3].hours;
    this.localRow.days.thursday.minutes = this.row.days[3].minutes;
    this.localRow.days.friday.day_of_week = this.row.days[4].day_of_week;
    this.localRow.days.friday.total_of_minutes = this.row.days[4].total_of_minutes;
    this.localRow.days.friday.hours = this.row.days[4].hours;
    this.localRow.days.friday.minutes = this.row.days[4].minutes;
    this.localRow.days.saturday.day_of_week = this.row.days[5].day_of_week;
    this.localRow.days.saturday.total_of_minutes = this.row.days[5].total_of_minutes;
    this.localRow.days.saturday.hours = this.row.days[5].hours;
    this.localRow.days.saturday.minutes = this.row.days[5].minutes;
    this.localRow.days.sunday.day_of_week = this.row.days[6].day_of_week;
    this.localRow.days.sunday.total_of_minutes = this.row.days[6].total_of_minutes;
    this.localRow.days.sunday.hours = this.row.days[6].hours;
    this.localRow.days.sunday.minutes = this.row.days[6].minutes;
    this.refreshTotal();
  },

  methods: {
    updateDay(payload, day) {
      var duration = parseInt(payload);
      if (day == 0) {
        this.localRow.days.monday.total_of_minutes = duration;
        this.$emit('update-day', { id: this.localRow.task_id, day: 0, value: duration});
      }
      if (day == 1) {
        this.localRow.days.tuesday.total_of_minutes = duration;
        this.$emit('update-day', { id: this.localRow.task_id, day: 1, value: duration});
      }
      if (day == 2) {
        this.localRow.days.wednesday.total_of_minutes = duration;
        this.$emit('update-day', { id: this.localRow.task_id, day: 2, value: duration});
      }
      if (day == 3) {
        this.localRow.days.thursday.total_of_minutes = duration;
        this.$emit('update-day', { id: this.localRow.task_id, day: 3, value: duration});
      }
      if (day == 4) {
        this.localRow.days.friday.total_of_minutes = duration;
        this.$emit('update-day', { id: this.localRow.task_id, day: 4, value: duration});
      }
      if (day == 5) {
        this.localRow.days.saturday.total_of_minutes = duration;
        this.$emit('update-day', { id: this.localRow.task_id, day: 5, value: duration});
      }
      if (day == 6) {
        this.localRow.days.sunday.total_of_minutes = duration;
        this.$emit('update-day', { id: this.localRow.task_id, day: 6, value: duration});
      }


      //this.$emit('update-day', { id: this.localRow.task_id, day: day, value: duration});

      this.refreshTotal();
      this.save(day, duration);
    },

    refreshTotal() {
      var totalDurationInMinutes = 0;

      if (this.localRow.days.monday) {
        totalDurationInMinutes = parseInt(this.localRow.days.monday.total_of_minutes);
      }
      if (this.localRow.days.tuesday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.localRow.days.tuesday.total_of_minutes);
      }
      if (this.localRow.days.wednesday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.localRow.days.wednesday.total_of_minutes);
      }
      if (this.localRow.days.thursday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.localRow.days.thursday.total_of_minutes);
      }
      if (this.localRow.days.friday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.localRow.days.friday.total_of_minutes);
      }
      if (this.localRow.days.saturday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.localRow.days.saturday.total_of_minutes);
      }
      if (this.localRow.days.sunday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.localRow.days.sunday.total_of_minutes);
      }

      var hours = Math.floor(totalDurationInMinutes / 60);
      var minutes = totalDurationInMinutes % 60;

      // this adds leading zero to minutes, if needed
      const zeroPad = (num, places) => String(num).padStart(places, '0');
      this.total = hours + 'h' + zeroPad(minutes, 2);

      this.$emit('update-weekly-total', { id: this.localRow.task_id, value: totalDurationInMinutes});
    },

    save(day, duration) {
      this.form.project_id = this.localRow.project_id;
      this.form.project_task_id = this.localRow.task_id;
      this.form.day = day;
      this.form.durationInMinutes = duration;

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/timesheet/' + this.timesheet.id + '/store', this.form)
        .then(response => {
          this.tasks = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};
</script>
