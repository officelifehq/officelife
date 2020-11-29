<style lang="scss" scoped>
input {
  width: 29px;
  border: none;
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
      <input
        class="br2 f5 pv2 ph1 outline-0 di tc"
        type="number"
        placeholder="00"
        min="0"
        max="12"
      />
      <span class="di">
        :
      </span>
      <span class="legend gray tc absolute f7">
        min.
      </span>
      <input
        class="br2 f5 pv2 ph1 outline-0 di tc"
        type="number"
        placeholder="00"
        min="0"
        max="59"
      />
    </div>
  </div>
</template>

<script>
export default {
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
    placeholder: {
      type: String,
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
    required: {
      type: Boolean,
      default: false,
    },
    extraClassUpperDiv: {
      type: String,
      default: 'mb3',
    },
    min: {
      type: Number,
      default: 0,
    },
    max: {
      type: Number,
      default: 0,
    },
    autofocus: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
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
    focus() {
      this.$refs.input.focus();
    },

    select() {
      this.$refs.input.select();
    },

    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end);
    },

    sendEscKey() {
      this.$emit('esc-key-pressed');
    }
  },
};
</script>
