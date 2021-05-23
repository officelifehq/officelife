<style lang="scss" scoped>
.error-explanation {
  background-color: #fde0de;
  border-color: rgb(226, 171, 167);
}

.error {
  &:focus {
    box-shadow: 0 0 0 1px #fff9f8;
  }
}

.optional-badge {
  border-radius: 4px;
  color: #283e59;
  background-color: #edf2f9;
  padding: 3px 4px;
}

</style>

<template>
  <div :class="extraClassUpperDiv">
    <label v-if="label" class="db fw4 lh-copy f6" :for="realId">
      {{ label }}
      <span v-if="!required" class="optional-badge f7">
        {{ $t('app.optional') }}
      </span>
    </label>
    <input :id="realId"
           :ref="customRef"
           v-model="proxyValue"
           :class="defaultClass"
           :required="required"
           :type="type"
           :name="name"
           :autofocus="autofocus"
           :step="step"
           :max="max"
           :min="min"
           :placeholder="placeholder"
           :data-cy="datacy"
           v-bind="$attrs"
           @keydown.esc="sendEscKey"
           @keydown.enter="sendEnterKey"
    />
    <div v-if="hasError" class="error-explanation pa3 ba br3 mt1">
      {{ errors }}
    </div>
    <p v-if="help" class="f7 mb3 lh-copy">
      {{ help }}
    </p>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  model: {
    prop: 'modelValue',
    event: 'update:modelValue'
  },

  props: {
    id: {
      type: String,
      default: 'text-input-',
    },
    type: {
      type: String,
      default: 'text',
    },
    step: {
      type: String,
      default: null,
    },
    modelValue: {
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
    help: {
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
    defaultClass: {
      type: String,
      default: 'br2 f5 w-100 ba b--black-40 pa2 outline-0'
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

  emits: [
    'esc-key-pressed', 'update:modelValue'
  ],

  data() {
    return {
      localErrors: '',
    };
  },

  computed: {
    proxyValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit('update:modelValue', value);
      },
    },

    realId() {
      return this.id + this._.uid;
    },

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
      this.$refs[this.customRef].focus();
    },

    select() {
      this.$refs[this.customRef].select();
    },

    setSelectionRange(start, end) {
      this.$refs[this.customRef].setSelectionRange(start, end);
    },

    sendEscKey() {
      this.$emit('esc-key-pressed');
    },

    sendEnterKey() {
      this.$emit('enter-key-pressed');
    }
  },
};
</script>
