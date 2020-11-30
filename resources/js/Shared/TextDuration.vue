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
      <the-mask v-model="hours"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
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
      <the-mask v-model="minutes"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
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
    type: {
      type: String,
      default: 'text',
    },
    value: {
      type: [String, Number],
      default: '',
    },
    customRef: {
      type: String,
      default: 'input',
    },
    name: {
      type: String,
      default: 'input',
    },
    errors: {
      type: String,
      default: '',
    },
    datacy: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      hours: 0,
      minutes: 0,
      durationInMinutes: 0,
      localErrors: '',
    };
  },

  computed: {
    hasError() {
      return this.localErrors.length > 0 && this.required;
    }
  },

  watch: {
    errors(value) {
      this.localErrors = value;
    },
  },

  mounted() {
    this.localErrors = this.errors;
  },

  methods: {
    broadcastTotal() {
      var localHours = 0;
      var localMinutes = 0;

      if (this.hours) {
        localHours = parseInt(this.hours) * 60;
      }

      if (this.minutes) {
        localMinutes = parseInt(this.minutes);
      }

      this.durationInMinutes = localHours + localMinutes;
      this.$emit('update', this.durationInMinutes);
    },
  },
};
</script>
