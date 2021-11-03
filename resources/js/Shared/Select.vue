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
    <div v-if="selectedOption && !searchMode" class="br2 f5 ba b--black-40 pa2 outline-0 pointer relative" @click="displaySearchMode">
      {{ selectedOption.label }}

      <svg xmlns="http://www.w3.org/2000/svg" class="absolute icon-dropdown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>
    <div v-if="!selectedOption && !searchMode" class="relative br2 f5 ba silver b--black-40 pa2 outline-0 pointer" @click="displaySearchMode">
      {{ placeholder }}

      <svg xmlns="http://www.w3.org/2000/svg" class="absolute icon-dropdown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>

    <!-- input field to search options -->
    <div class="relative">
      <input v-if="searchMode"
             :id="realId"
             v-model="search"
             class="br2 f5 ba b--black-40 pa2 outline-0"
             :type="type"
             :name="name"
             :placeholder="$t('app.type_first_letters')"
             v-bind="$attrs"
             :maxlength="maxlength"
             @keydown.esc="sendEscKey"
             @keydown.enter="sendEnterKey"
      />
      <div v-if="options.length > 0 && searchMode" class="overflow-y-scroll absolute z-9999 dropdown bg-white box ba bw2">
        <ul class="ma0 pa1 list">
          <li v-for="option in filteredList" :key="option.key" class="pa2 pointer" @click="select(option)">{{ option.label }}</li>
        </ul>
      </div>
    </div>

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
      selectedOption: null,
      search: '',
      searchMode: false,
      localErrors: [],
    };
  },

  computed: {
    proxyValue: {
      get() {
        return this.options[this.options.findIndex(p => p[this.customValueKey] === this.modelValue)];
      },
      set(value) {
        this.$emit('update:modelValue', value[this.customValueKey]);
      }
    },

    filteredList() {
      // filter the list when searching
      var list;
      return this.options.filter(option => {
        return option.label.toLowerCase().includes(this.search.toLowerCase());
      });
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
    displaySearchMode() {
      this.searchMode = true;
    },

    sendEscKey() {
      this.searchMode = false;
      this.$emit('esc-key-pressed');
    },

    select(option) {
      this.selectedOption = option;
      this.searchMode = false;
      this.proxyValue = option;
    }
  },
};
</script>
