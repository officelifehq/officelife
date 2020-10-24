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
  <div class="mb3">
    <label v-if="label" class="db fw4 lh-copy f6" :for="id">
      {{ label }}
      <span v-if="!required" class="optional-badge f7">
        {{ $t('app.optional') }}
      </span>
    </label>

    <textarea :id="id"
              :ref="customRef"
              v-bind="$attrs"
              class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
              :class="{ error: errors.length }"
              :required="required"
              :type="type"
              :value="value"
              :data-cy="datacy"
              :rows="rows"
              :maxlength="maxlength"
              @input="$emit('input', $event.target.value)"
              @keydown.esc="sendEscKey"
    ></textarea>

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
  inheritAttrs: false,

  props: {
    id: {
      type: String,
      default() {
        return `text-area-${this._uid}`;
      },
    },
    type: {
      type: String,
      default: 'text',
    },
    value: {
      type: String,
      default: '',
    },
    datacy: {
      type: String,
      default: '',
    },
    customRef: {
      type: String,
      default: 'input',
    },
    label: {
      type: String,
      default: '',
    },
    help: {
      type: String,
      default: '',
    },
    required: {
      type: Boolean,
      default: false,
    },
    rows: {
      type: Number,
      default: 3,
    },
    maxlength: {
      type: Number,
      default: 65535,
    },
  },

  data() {
    return {
      errors: [],
    };
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
