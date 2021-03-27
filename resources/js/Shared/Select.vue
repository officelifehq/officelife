<style lang="scss" scoped>
@import '@vueform/multiselect/themes/default.css';

.optional-badge {
  border-radius: 4px;
  color: #283e59;
  background-color: #edf2f9;
  padding: 3px 4px;
}

.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  border: 0;
}
</style>

<template>
  <div :class="extraClassUpperDiv">
    <label v-if="label" class="db fw4 lh-copy f6" :for="id">
      {{ label }}
      <span v-if="!required" class="optional-badge f7">
        {{ $t('app.optional') }}
      </span>
    </label>
    <multiselect v-model="selected"
                 :options="options"
                 :value-prop="customValueKey"
                 :label="customLabelKey"
                 :placeholder="placeholder"
                 class="style-chooser"
                 :data-cy="datacy"
                 :close-on-select="true"
                 @change="broadcast"
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

import Multiselect from '@vueform/multiselect';

export default {
  components: {
    Multiselect,
  },

  props: {
    id: {
      type: String,
      default: 'text-input-',
    },
    value: {
      type: Object,
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
      default: 'id',
    },
    customLabelKey: {
      type: String,
      default: 'label',
    },
  },

  emits: [
    'esc-key-pressed', 'input'
  ],

  data() {
    return {
      selected: null,
      localErrors: [],
    };
  },

  computed: {
    realId() {
      return this.id + this._uid;
    },
  },

  watch: {
    value(newValue) {
      this.selected = newValue;
    },
    errors(value) {
      this.localErrors = value;
    },
  },

  mounted() {
    this.selected = this.value;
    this.localErrors = this.errors;
  },

  methods: {
    sendEscKey() {
      this.$emit('esc-key-pressed');
    },

    broadcast(value) {
      this.$emit('input', value);
    },
  },
};
</script>
