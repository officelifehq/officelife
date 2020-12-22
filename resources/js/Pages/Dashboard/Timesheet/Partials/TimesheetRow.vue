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
          <inertia-link :href="localRow.project_url" class="dib">
            {{ localRow.project_name }}
          </inertia-link>
        </div>
        <span class="f7 fw5">
          {{ total }}
        </span>
      </div>
    </div>

    <!-- monday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days[0].hours"
        :minutes="localRow.days[0].minutes"
        :total="localRow.days[0].total_of_minutes"
        @update="updateDayInformation($event, 0)"
      />
    </div>

    <!-- tuesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days[1].hours"
        :minutes="localRow.days[1].minutes"
        :total="localRow.days[1].total_of_minutes"
        @update="updateDayInformation($event, 1)"
      />
    </div>

    <!-- wednesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days[2].hours"
        :minutes="localRow.days[2].minutes"
        :total="localRow.days[2].total_of_minutes"
        @update="updateDayInformation($event, 2)"
      />
    </div>

    <!-- thursday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days[3].hours"
        :minutes="localRow.days[3].minutes"
        :total="localRow.days[3].total_of_minutes"
        @update="updateDayInformation($event, 3)"
      />
    </div>

    <!-- friday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid">
      <text-duration
        :hours="localRow.days[4].hours"
        :minutes="localRow.days[4].minutes"
        :total="localRow.days[4].total_of_minutes"
        @update="updateDayInformation($event, 4)"
      />
    </div>

    <!-- saturday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days">
      <text-duration
        :hours="localRow.days[5].hours"
        :minutes="localRow.days[5].minutes"
        :total="localRow.days[5].total_of_minutes"
        @update="updateDayInformation($event, 5)"
      />
    </div>

    <!-- sunday -->
    <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days">
      <text-duration
        :hours="localRow.days[6].hours"
        :minutes="localRow.days[6].minutes"
        :total="localRow.days[6].total_of_minutes"
        @update="updateDayInformation($event, 6)"
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
    rowComingFromBackend: Object,
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

      var hours = Math.floor(totalDurationInMinutes / 60);
      var minutes = totalDurationInMinutes % 60;

      // this adds leading zero to minutes, if needed
      const zeroPad = (num, places) => String(num).padStart(places, '0');
      this.total = hours + 'h' + zeroPad(minutes, 2);

      this.$emit('update-weekly-total', { id: this.localRow.task_id, value: totalDurationInMinutes});
    },

    saveInDB(day, duration) {
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
