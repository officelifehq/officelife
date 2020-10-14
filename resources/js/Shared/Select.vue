<style lang="scss" scoped>
.optional-badge {
  border-radius: 4px;
  color: #283e59;
  background-color: #edf2f9;
  padding: 3px 4px;
}

.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  border: 1px solid rgba(0, 0, 0, 0.4);
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
    <v-select v-model="selected"
              :options="options"
              :placeholder="placeholder"
              class="style-chooser"
              :label="customLabelKey"
              :data-cy="datacy"
              :close-on-select="true"
              @input="broadcast(selected)"
    >
      <!-- all this complex code below just to make sure the select box is required -->
      <template #search="{attributes, events}">
        <input
          class="vs__search"
          :required="required && !selected"
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
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
  components: {
    vSelect,
  },

  props: {
    id: {
      type: String,
      default() {
        return `text-input-${this._uid}`;
      },
    },
    value: {
      type: Object,
      default: null,
    },
    name: {
      type: String,
      default: 'input',
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
    customLabelKey: {
      type: String,
      default: 'label',
    },
  },

  data() {
    return {
      selected: null,
      errors: [],
    };
  },

  created() {
    if (this.value !== null) {
      this.selected = this.value;
    }
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
