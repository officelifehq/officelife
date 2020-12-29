<style lang="scss" scoped>
input {
  width: 29px;
  border: none;
}

input[type="text"]:empty {
  background-color: transparent;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

.legend {
  top: -5px;
  font-size: .65em;
}

.container {
  width: 85px;
}
</style>

<template>
  <div class="ba bb-gray br3 pa1 pt2 container">
    <div class="relative">
      <span class="legend gray tc absolute f7">
        hours
      </span>

      <!-- hours -->
      <the-mask v-model="localHours"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
                :data-cy="datacy + '-hours'"
                @input="broadcastTotal()"
      />

      <!-- separator -->
      <span class="di">
        :
      </span>

      <!-- minutes -->
      <span class="legend gray tc absolute f7">
        min.
      </span>

      <the-mask v-model="localMinutes"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
                :data-cy="datacy + '-minutes'"
                @input="broadcastTotal()"
      />
    </div>
  </div>
</template>

<script>
import {TheMask} from 'vue-the-mask';

export default {

  components: {TheMask},
  inheritAttrs: false,

  props: {
    id: {
      type: String,
      default() {
        return `text-input-${this._uid}`;
      },
    },
    errors: {
      type: String,
      default: '',
    },
    total: {
      type: Number,
      default: 0,
    },
    hours: {
      type: Number,
      default: 0,
    },
    minutes: {
      type: Number,
      default: 0,
    },
    datacy: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      localHours: 0,
      localMinutes: 0,
      durationInMinutes: 0,
    };
  },

  mounted() {
    this.$nextTick(() => {
      this.localHours = this.hours;
      this.localMinutes = this.minutes;
      this.durationInMinutes = this.total;
    });
  },

  methods: {
    broadcastTotal() {
      var hours = 0;
      var minutes = 0;

      if (this.localHours) {
        hours = parseInt(this.localHours) * 60;
      }

      if (this.localMinutes) {
        minutes = parseInt(this.localMinutes);
      }

      this.durationInMinutes = hours + minutes;
      this.$emit('update', this.durationInMinutes);
    },
  },
};
</script>
