<style lang="scss" scoped>
.error-explanation {
  background-color: #fff5f5;
  border-color: #fc8181;
  color: #c53030;
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

.length {
  top: 10px;
  right: 10px;
  background-color: #e5eeff;
  padding: 3px 4px;
}

.counter {
  padding-right: 64px;
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

    <div class="relative">
      <input :id="realId"
             :ref="customRef"
             v-model="proxyValue"
             :class="classes"
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
             :maxlength="maxlength"
             @keydown.esc="sendEscKey"
             @keydown.enter="sendEnterKey"
      />
      <span v-if="maxlength" class="length absolute f7 br2">
        {{ charactersLeft }}
      </span>
    </div>

    <div v-if="hasError" class="error-explanation pa3 ba br3 mt1">
      <template v-if="!arrayError">
        {{ errors }}
      </template>
      <div v-for="error in errors" v-else :key="error.id">
        {{ error }}
      </div>
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
      type: [String, Array],
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
    },
    maxlength: {
      type: Number,
      default: null,
    },
  },

  emits: [
    'esc-key-pressed', 'update:modelValue'
  ],

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
      return this.errors.length > 0 && this.required;
    },

    arrayError() {
      return _.isArray(this.errors);
    },

    charactersLeft() {
      var char = 0;
      if (this.proxyValue) {
        char = this.proxyValue.length;
      }

      return `${this.maxlength - char} / ${this.maxlength}`;
    },
  },

  created() {
    this.classes = this.defaultClass;

    if (this.maxlength) {
      this.classes = this.classes + ' counter';
    }
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
