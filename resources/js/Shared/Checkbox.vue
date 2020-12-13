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
    <input
      :id="id"
      v-model="updatedValue"
      type="checkbox"
      class="relative"
      :class="classes"
      :required="required"
      :name="name"
      :data-cy="datacy"
      @change="$emit('change', updatedValue)"
    />
    <label v-if="label" class="fw4 lh-copy f5 pointer di" :for="id">
      <span v-html="label"></span>
      <span v-if="!required" class="optional-badge f7">
        {{ $t('app.optional') }}
      </span>
    </label>
    <div v-if="hasError" class="error-explanation pa3 ba br3 mt1">
      {{ errors[0] }}
    </div>
    <p v-if="help" class="f7 mb3 lh-title">
      {{ help }}
    </p>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      default: '',
    },
    value: {
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
    classes: {
      type: String,
      default: 'mb3',
    },
    extraClassUpperDiv: {
      type: String,
      default: 'mb3',
    },
  },

  data() {
    return {
      updatedValue: false,
      localErrors: [],
    };
  },

  computed: {
    hasError() {
      return this.localErrors.length > 0 && this.required;
    }
  },

  watch: {
    value(newValue) {
      this.updatedValue = newValue;
    },
    errors(value) {
      this.localErrors = value;
    },
  },

  mounted() {
    this.updatedValue = this.value;
    this.localErrors = this.errors;
  },

  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
};
</script>
