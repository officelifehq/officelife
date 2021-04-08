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
  <div :class="classes" class="ba bb-gray br3 pa1 pt2 container">
    <div class="relative">
      <span class="legend gray tc absolute f7">
        hours
      </span>

      <!-- hours -->
      <the-mask :value="proxyHours"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
                :data-cy="datacy + '-hours'"
                @input="proxyHours = $event.target.value"
      />

      <!-- separator -->
      <span class="di">
        :
      </span>

      <!-- minutes -->
      <span class="legend gray tc absolute f7">
        min.
      </span>

      <the-mask :value="proxyMinutes"
                mask="##"
                class="br2 f5 pt2 pb0 ph1 outline-0 di tc bg-white"
                type="text"
                :masked="false"
                placeholder="00"
                :data-cy="datacy + '-minutes'"
                @input="proxyMinutes = $event.target.value"
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
  //  inheritAttrs: false,

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
    classes: {
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
    proxyHours: {
      get() {
        return this.localHours;
      },
      set(value) {
        this.localHours = parseInt(value);
        this.$emit('update:hours', this.localHours);
        this.updateModelValue();
      },
    },
    proxyMinutes: {
      get() {
        return this.localMinutes;
      },
      set(value) {
        this.localMinutes = parseInt(value);
        this.$emit('update:minutes', this.localMinutes);
        this.updateModelValue();
      },
    },
    realId() {
      return this.id + this._.uid;
    },
  },

  mounted() {
    this.$nextTick(() => {
      this.localHours = this.hours;
      this.localMinutes = this.minutes;
    });
  },

  methods: {
    updateModelValue() {
      let value = 0;
      if (this.proxyHours !== undefined) {
        value += this.proxyHours * 60;
      }
      if (this.proxyMinutes !== undefined) {
        value += this.proxyMinutes;
      }
      this.$emit('update:modelValue', value);
    }
  }
};
</script>
