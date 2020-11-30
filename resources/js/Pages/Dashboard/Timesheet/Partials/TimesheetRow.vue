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
    <div class="f6 ph2 dtc bl bb bb-gray project v-mid">
      <div class="flex justify-between items-center">
        <div>
          <span class="db pb1 fw5">
            Management of the timeline
          </span>
          <span class="db gray">
            Dunder Mifflin Infinity
          </span>
        </div>
        <span class="f7 fw5">
          {{ total }}
        </span>
      </div>
    </div>
    <!-- monday -->
    <div class="ph2 pv2 dtc bl bb bb-gray">
      <text-duration @update="updateDay($event, 1)" />
    </div>
    <!-- tuesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray">
      <text-duration @update="updateDay($event, 2)" />
    </div>
    <!-- wednesday -->
    <div class="ph2 pv2 dtc bl bb bb-gray">
      <text-duration @update="updateDay($event, 3)" />
    </div>
    <!-- thursday -->
    <div class="ph2 pv2 dtc bl bb bb-gray">
      <text-duration @update="updateDay($event, 4)" />
    </div>
    <!-- friday -->
    <div class="ph2 pv2 dtc bl bb bb-gray">
      <text-duration @update="updateDay($event, 5)" />
    </div>
    <!-- saturday -->
    <div class="ph2 pv2 dtc bl bb bb-gray off-days">
      <text-duration @update="updateDay($event, 6)" />
    </div>
    <!-- sunday -->
    <div class="ph2 pv2 dtc bl bb bb-gray off-days">
      <text-duration @update="updateDay($event, 7)" />
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
  },

  data() {
    return {
      week: {
        monday: 0,
        tusday: 0,
        wednesday: 0,
        thursday: 0,
        friday: 0,
        saturday: 0,
        sunday: 0,
      },
      total: '00h00',
    };
  },

  methods: {
    updateDay(payload, day) {
      if (day == 1) {
        this.week.monday = parseInt(payload);
        this.$emit('update-day', { day: 1, value: parseInt(payload)});
      }
      if (day == 2) {
        this.week.tuesday = parseInt(payload);
        this.$emit('update-day', { day: 2, value: parseInt(payload)});
      }
      if (day == 3) {
        this.week.wednesday = parseInt(payload);
        this.$emit('update-day', { day: 3, value: parseInt(payload)});
      }
      if (day == 4) {
        this.week.thursday = parseInt(payload);
        this.$emit('update-day', { day: 4, value: parseInt(payload)});
      }
      if (day == 5) {
        this.week.friday = parseInt(payload);
        this.$emit('update-day', { day: 5, value: parseInt(payload)});
      }
      if (day == 6) {
        this.week.saturday = parseInt(payload);
        this.$emit('update-day', { day: 6, value: parseInt(payload)});
      }
      if (day == 7) {
        this.week.sunday = parseInt(payload);
        this.$emit('update-day', { day: 7, value: parseInt(payload)});
      }

      this.calculateTotal();
    },

    calculateTotal() {
      var totalDurationInMinutes = 0;

      if (this.week.monday) {
        totalDurationInMinutes = parseInt(this.week.monday);
      }
      if (this.week.tuesday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.week.tuesday);
      }
      if (this.week.wednesday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.week.wednesday);
      }
      if (this.week.thursday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.week.thursday);
      }
      if (this.week.friday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.week.friday);
      }
      if (this.week.saturday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.week.saturday);
      }
      if (this.week.sunday) {
        totalDurationInMinutes = totalDurationInMinutes + parseInt(this.week.sunday);
      }

      var hours = Math.floor(totalDurationInMinutes / 60);
      var minutes = totalDurationInMinutes % 60;

      // this adds leading zero to minutes, if needed
      const zeroPad = (num, places) => String(num).padStart(places, '0');
      this.total = hours + 'h' + zeroPad(minutes, 2);
    }
  },
};
</script>
