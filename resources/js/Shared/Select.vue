<style lang="scss" scoped>
@import 'vue-select/src/scss/vue-select.scss';

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
    <v-select :model-value="localValue"
              :options="options"
              :label="customLabelKey"
              :placeholder="placeholder"
              class="style-chooser"
              :data-cy="datacy"
              :close-on-select="true"
              @update:modelValue="onUpdate"
    >
      <!-- all this complex code below just to make sure the select box is required -->
      <template #search="{ attributes, events }">
        <input
          class="vs__search"
          :required="required && !localValue"
          v-bind="attributes"
          v-on="events"
        />
      </template>
    </v-select>
    <div v-if="errors.length" class="error-explanation pa3 ba br3 mt1">
      {{ errors[0] }}
    </div>
    <p v-if="help" class="f7 mb3 lh-title">
      {{ help }}
    </p>
  </div>
</template>

<script>

import vSelect from 'vue-select/src/index.js';

export default {
  components: {
    vSelect,
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
      localValue: null,
      localErrors: [],
    };
  },

  computed: {
    realId() {
      return this.id + this._.uid;
    },
  },

  watch: {
    modelValue(value) {
      this.localValue = value;
    },
    errors(value) {
      this.localErrors = value;
    },
  },

  mounted() {
    this.localValue = this.modelValue;
    this.localErrors = this.errors;
  },

  methods: {
    onUpdate(value) {
      this.localValue = value[this.customValueKey];
      this.$emit('update:modelValue', this.localValue);
    },
    sendEscKey() {
      this.$emit('esc-key-pressed');
    },
  },
};
</script>
