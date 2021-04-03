<style scoped>
.project {
  width: 280px;
  max-width: 400px;
}

.blank {
  background-color: rgb(232, 232, 232);
  color: #959595;
  font-size: 10px;
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
          <span class="db pb1 fw5 lh-copy">
            {{ localRow.task_title }}

            <!-- destroy row -->
            <a v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'" class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete f7" href="#" @click.prevent="destroy()">[x]</a>
          </span>

          <!-- project name -->
          <inertia-link v-if="localRow.project_url" :href="localRow.project_url" class="dib">
            {{ localRow.project_name }}
          </inertia-link>
          <span v-else class="dib">
            {{ localRow.project_name }}
          </span>
        </div>
        <span class="f7 fw5">
          {{ total }}
        </span>
      </div>
    </div>

    <!-- monday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc" :class="isEmpty(localRow.days[0].total_of_minutes)">
      <text-duration
        v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'"
        :hours="localRow.days[0].hours"
        :minutes="localRow.days[0].minutes"
        :total="localRow.days[0].total_of_minutes"
        :datacy="'timesheet-' + timesheet.id + '-day-0'"
        @update="updateDayInformation($event, 0)"
      />
      <span v-else>
        {{ formatTime(localRow.days[0].total_of_minutes) }}
      </span>
    </div>

    <!-- tuesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc" :class="isEmpty(localRow.days[1].total_of_minutes)">
      <text-duration
        v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'"
        :hours="localRow.days[1].hours"
        :minutes="localRow.days[1].minutes"
        :total="localRow.days[1].total_of_minutes"
        :datacy="'timesheet-' + timesheet.id + '-day-1'"
        @update="updateDayInformation($event, 1)"
      />
      <span v-else class="">
        {{ formatTime(localRow.days[1].total_of_minutes) }}
      </span>
    </div>

    <!-- wednesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc" :class="isEmpty(localRow.days[2].total_of_minutes)">
      <text-duration
        v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'"
        :hours="localRow.days[2].hours"
        :minutes="localRow.days[2].minutes"
        :total="localRow.days[2].total_of_minutes"
        :datacy="'timesheet-' + timesheet.id + '-day-2'"
        @update="updateDayInformation($event, 2)"
      />
      <span v-else class="">
        {{ formatTime(localRow.days[2].total_of_minutes) }}
      </span>
    </div>

    <!-- thursday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc" :class="isEmpty(localRow.days[3].total_of_minutes)">
      <text-duration
        v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'"
        :hours="localRow.days[3].hours"
        :minutes="localRow.days[3].minutes"
        :total="localRow.days[3].total_of_minutes"
        :datacy="'timesheet-' + timesheet.id + '-day-3'"
        @update="updateDayInformation($event, 3)"
      />
      <span v-else class="">
        {{ formatTime(localRow.days[3].total_of_minutes) }}
      </span>
    </div>

    <!-- friday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc" :class="isEmpty(localRow.days[4].total_of_minutes)">
      <text-duration
        v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'"
        :hours="localRow.days[4].hours"
        :minutes="localRow.days[4].minutes"
        :total="localRow.days[4].total_of_minutes"
        :datacy="'timesheet-' + timesheet.id + '-day-4'"
        @update="updateDayInformation($event, 4)"
      />
      <span v-else>
        {{ formatTime(localRow.days[4].total_of_minutes) }}
      </span>
    </div>

    <!-- saturday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days tc">
      <text-duration
        v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'"
        :hours="localRow.days[5].hours"
        :minutes="localRow.days[5].minutes"
        :total="localRow.days[5].total_of_minutes"
        :datacy="'timesheet-' + timesheet.id + '-day-5'"
        @update="updateDayInformation($event, 5)"
      />
      <span v-else class="">
        {{ formatTime(localRow.days[5].total_of_minutes) }}
      </span>
    </div>

    <!-- sunday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days tc">
      <text-duration
        v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'"
        :hours="localRow.days[6].hours"
        :minutes="localRow.days[6].minutes"
        :total="localRow.days[6].total_of_minutes"
        :datacy="'timesheet-' + timesheet.id + '-day-6'"
        @update="updateDayInformation($event, 6)"
      />
      <span v-else class="">
        {{ formatTime(localRow.days[6].total_of_minutes) }}
      </span>
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
    rowComingFromBackend: {
      type: Object,
      default: null,
    },
    timesheet: {
      type: Object,
      default: null,
    },
    timesheetStatus: {
      type: String,
      default: '',
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
        project_url: '',
        task_id: 0,
        task_title: 0,
        total_this_week: 0,
        days: [
          {
            day_of_week: 1,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          {
            day_of_week: 2,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          {
            day_of_week: 3,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          {
            day_of_week: 4,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          {
            day_of_week: 5,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          {
            day_of_week: 6,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
          {
            day_of_week: 7,
            total_of_minutes: 0,
            hours: 0,
            minutes: 0,
          },
        ],
      },
      total: '00h00',
    };
  },

  mounted() {
    this.localRow = this.rowComingFromBackend;
    this.refreshTotalHoursInRow();
  },

  methods: {
    isEmpty(timeInMinutes) {
      return (timeInMinutes == 0) ? 'blank' : '';
    },

    updateDayInformation(payload, day) {
      var duration = parseInt(payload);
      this.saveInDB(day, duration);

      this.localRow.days[day].total_of_minutes = duration;
      this.$emit('day-updated', { id: this.localRow.task_id, day: day, value: duration});

      this.refreshTotalHoursInRow();
    },

    refreshTotalHoursInRow() {
      var totalDurationInMinutes = 0;

      for(var i = 0; i < 7; i++){
        if (i == 0) {
          totalDurationInMinutes = parseInt(this.localRow.days[0].total_of_minutes);
        } else {
          totalDurationInMinutes = totalDurationInMinutes + parseInt(this.localRow.days[i].total_of_minutes);
        }
      }

      this.total = this.formatTime(totalDurationInMinutes);

      this.$emit('update-weekly-total', { id: this.localRow.task_id, value: totalDurationInMinutes});
    },

    destroy() {
      this.form.project_id = this.localRow.project_id;
      this.form.project_task_id = this.localRow.task_id;

      axios.put(`${this.$page.props.auth.company.id}/dashboard/timesheet/${this.timesheet.id}/row`, this.form)
        .then(response => {
          this.$emit('row-deleted', { id: this.localRow.task_id});
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    saveInDB(day, duration) {
      this.form.project_id = this.localRow.project_id;
      this.form.project_task_id = this.localRow.task_id;
      this.form.day = day;
      this.form.durationInMinutes = duration;

      axios.post(`${this.$page.props.auth.company.id}/dashboard/timesheet/${this.timesheet.id}/store`, this.form)
        .then(response => {
          this.tasks = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    formatTime(timeInMinutes) {
      var hours = Math.floor(timeInMinutes / 60);
      var minutes = timeInMinutes % 60;

      // this adds leading zero to minutes, if needed
      const zeroPad = (num, places) => String(num).padStart(places, '0');
      return hours + 'h' + zeroPad(minutes, 2);
    }
  },
};
</script>
