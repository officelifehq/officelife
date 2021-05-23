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
      <the-mask :value="localHours"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
                :data-cy="datacy + '-hours'"
                @input="updateHours"
      />

      <!-- separator -->
      <span class="di">
        :
      </span>

      <!-- minutes -->
      <span class="legend gray tc absolute f7">
        min.
      </span>

      <the-mask :value="localMinutes"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
                :data-cy="datacy + '-minutes'"
                @input="updateMinutes"
      />
    </div>
  </div>
</template>

<script>
import TheMask from 'vue-the-mask/src/component';

export default {

  components: {
    TheMask
  },

  model: {
    prop: 'modelValue',
    event: 'update:modelValue'
  },

  props: {
    id: {
      type: String,
      default: 'text-input-',
    },
    errors: {
      type: String,
      default: '',
    },
    modelValue: {
      type: Number,
      default: 0,
    },
    hours: {
      type: Number,
      default: undefined,
    },
    minutes: {
      type: Number,
      default: undefined,
    },
    datacy: {
      type: String,
      default: '',
    },
  },

  emits: [
    'update:modelValue', 'update:hours', 'update:minutes'
  ],

  data() {
    return {
      localHours: undefined,
      localMinutes: undefined,
    };
  },

  computed: {
    realId() {
      return this.id + this._.uid;
    },
  },

  watch: {
    hours(value) {
      this.localHours = value;
    },
    minutes(value) {
      this.localMinutes = value;
    },
  },

  mounted() {
    this.$nextTick(() => {
      this.localHours = this.hours;
      this.localMinutes = this.minutes;
    });
  },

  methods: {
    updateHours(e) {
      if (e.isTrusted) {
        let value = parseInt(e.target.value);
        this.localHours = value === undefined || isNaN(value) ? '0' : e.target.value;
        this.$emit('update:hours', value);
        this.updateModelValue();
      }
    },

    updateMinutes(e) {
      if (e.isTrusted) {
        let value = parseInt(e.target.value);
        this.localMinutes = value === undefined || isNaN(value) ? '0' : e.target.value;
        this.$emit('update:minutes', value);
        this.updateModelValue();
      }
    },

    updateModelValue() {
      let value = 0;
      if (this.localHours !== undefined) {
        value += parseInt(this.localHours) * 60;
      }
      if (this.localMinutes !== undefined) {
        value += parseInt(this.localMinutes);
      }
      this.$emit('update:modelValue', value);
    }
  }
};
</script>
