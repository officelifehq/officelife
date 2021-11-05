<style lang="scss" scoped>
.optional-badge {
  border-radius: 4px;
  color: #283e59;
  background-color: #edf2f9;
  padding: 3px 4px;
}

.icon-dropdown {
  top: 10px;
  right: 8px;
  width: 15px;
}

.dropdown {
  width: 300px;
  max-height: 300px;

  li:hover {
    background-color: #4364c8;
    color: #fff;
  }

  li:first-child {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  li:last-child {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
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
    <a-select
      v-model="form.country_id"
      show-search
      :placeholder="$t('app.choose')"
      filter-option="true"
      style="width: 200px"
      :options="countries"
      :dropdown-style="'mb-4'"
      @focus="handleFocus"
      @blur="handleBlur"
      @change="handleChange"
    />

    <div v-if="errors.length" class="error-explanation pa3 ba br3 mt1">
      {{ errors[0] }}
    </div>
    <p v-if="help" class="f7 mb3 lh-title">
      {{ help }}
    </p>
  </div>
</template>

<script>
export default {
  model: {
    prop: 'modelValue',
    event: 'update:modelValue'
  },

  props: {
    id: {
      type: String,
      default: 'text-input-',
    },
    modelValue: {
      type: [Object, String, Number],
      default: null,
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
    extraClassUpperDiv: {
      type: String,
      default: 'mb3',
    },
    options: {
      type: Array,
      default: () => [],
    },
    customValueKey: {
      type: String,
      default: 'value',
    },
    customLabelKey: {
      type: String,
      default: 'label',
    },
  },

  emits: [
    'esc-key-pressed', 'update:modelValue'
  ],

  data() {
    return {
      localErrors: [],
    };
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
    sendEscKey() {
      this.searchMode = false;
      this.$emit('esc-key-pressed');
    },
  },
};
</script>
