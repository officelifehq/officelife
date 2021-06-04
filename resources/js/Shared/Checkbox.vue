<style lang="scss" scoped>

input[type=checkbox] {
  background-color: #f6f7f7;
  border: 2px solid #a3a9ac;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  cursor: pointer;
  height: 20px;
  padding: 0;
  width: 20px;
  top: 2px;
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
    <div class="flex items-start">
      <input
        :id="realId"
        v-model="proxyValue"
        :value="value"
        type="checkbox"
        class="relative mr2"
        :required="required"
        :name="name"
        :data-cy="datacy"
        v-bind="$attrs"
      />
      <label v-if="label" class="fw4 lh-copy f5 pointer di" :for="realId">
        <span v-html="label"></span>
        <span v-if="!required" class="optional-badge f7">
          {{ $t('app.optional') }}
        </span>
      </label>
    </div>

    <div v-if="hasError" class="error-explanation pa3 ba br3 mt1">
      {{ errors[0] }}
    </div>
    <p v-if="help" class="pl4 ma0 f7 lh-title">
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
      default: '',
    },
    value: {
      type: [Boolean, String],
      default: true,
    },
    modelValue: {
      type: Boolean,
      default: false,
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
      type: Array,
      default: () => [],
    },
    datacy: {
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
    extraClassUpperDiv: {
      type: String,
      default: '',
    },
  },

  emits: [
    'update:modelValue'
  ],

  data() {
    return {
      localErrors: [],
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
      this.$refs.input.focus();
    },
  },
};
</script>
