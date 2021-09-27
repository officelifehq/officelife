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

.hover {
  display: inline-block;
  position: relative;
  left: -4px;
}

.action {
  left: -92px;
}

.hover-effect:hover {
  background-color: yellow;
}

.visibility-hidden {
  visibility: hidden;
}

.show-actions {
  display: none;
}

@media (max-width: 480px) {
  .hide-actions {
    display: none;
  }

  .show-actions {
    display: inline-block;
  }
}

.icon-comment {
  width: 17px;
  color: #a2a5b1;
  top: 4px;
}

.duration {
  background-color: #f2f7f0;
  font-size: 11px;
  padding: 3px 6px;
  top: -2px;
  border: 1px solid #e4e6e3;
  color: gray;

  svg {
    width: 13px;
    top: 3px;
  }
}
</style>

<template>
  <div :class="extraClassUpperDiv" class="hover relative" :data-cy="datacy" @mouseover="showHover()" @mouseleave="hideHover()">
    <!-- actions - available on mouse hover on desktop -->
    <div v-show="editable" :class="hover ? 'di' : 'visibility-hidden'" class="hide-actions absolute action di f6 bg-white ph1 pv1 br3">
      <!-- edit -->
      <span :data-cy="datacy + '-edit'" class="di mr2 bb b--dotted bt-0 bl-0 br-0 pointer" @click="$emit('edit', itemId)">
        {{ $t('app.edit') }}
      </span>

      <!-- delete -->
      <a v-if="idToDelete == 0" :data-cy="datacy + '-delete'" class="c-delete mr1 pointer" @click.prevent="idToDelete = itemId">{{ $t('app.delete') }}</a>
      <span v-if="idToDelete == itemId">
        {{ $t('app.sure') }}
        <a class="c-delete mr1 pointer" :data-cy="datacy + '-delete-cta'" @click.prevent="$emit('destroy', itemId)">
          {{ $t('app.yes') }}
        </a>
        <a class="pointer" @click.prevent="idToDelete = 0">
          {{ $t('app.no') }}
        </a>
      </span>
    </div>

    <div class="dib">
      <div class="flex items-start">
        <input
          :id="id"
          :ref="'input'"
          v-model="proxyValue"
          :value="value"
          :data-cy="datacy + '-single-item'"
          type="checkbox"
          class="relative mr2"
          :required="required"
          :name="name"
          :disabled="!editable"
          v-bind="$attrs"
        />

        <!-- content of the checkbox -->
        <label v-if="label" class="fw4 lh-copy f5 pointer di relative">
          <span class="mr2 hover-effect" @click="goTo(url)" v-html="label"></span>

          <small-name-and-avatar
            v-if="assignee"
            :name="assignee.name"
            :avatar="assignee.avatar"
            :class="'gray'"
            :size="'15px'"
            :font-size="'f7'"
            :top="'4px'"
            :margin-between-name-avatar="'22px'"
          />

          <span v-if="duration" class="duration br3 relative ml2 mr2">
            <svg class="relative" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            {{ duration }}
          </span>

          <span v-if="comments" class="relative f7">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon-comment relative" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
            </svg>
            {{ comments }}
          </span>

          <!-- actions - only shown on mobile -->
          <div class="show-actions">
            <!-- edit -->
            <span class="di mr1 bb b--dotted bt-0 bl-0 br-0 pointer" @click="$emit('edit', itemId)">
              {{ $t('app.edit') }}
            </span>

            <!-- delete -->
            <a v-if="idToDelete == 0" class="c-delete mr1 pointer" @click.prevent="idToDelete = itemId">{{ $t('app.delete') }}</a>
            <span v-if="idToDelete == itemId">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" @click.prevent="$emit('destroy', itemId)">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" @click.prevent="idToDelete = 0">
                {{ $t('app.no') }}
              </a>
            </span>
          </div>

          <!-- required label, if it exists -->
          <span v-if="!required" class="optional-badge f7">
            {{ $t('app.optional') }}
          </span>
        </label>
      </div>

      <!-- display error message, if any -->
      <div v-if="hasError" class="error-explanation pa3 ba br3 mt1">
        {{ errors[0] }}
      </div>

      <!-- help text, if any -->
      <p v-if="help" class="f7 mb3 lh-title">
        {{ help }}
      </p>
    </div>
  </div>
</template>

<script>
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    SmallNameAndAvatar,
  },
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
      type: Boolean,
      default: null,
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
    checked: {
      type: Boolean,
      default: false,
    },
    extraClassUpperDiv: {
      type: String,
      default: 'mb3',
    },
    editable: {
      type: Boolean,
      default: true,
    },
    itemId: {
      type: Number,
      default: 0,
    },
    assignee: {
      type: Object,
      default: null,
    },
    duration: {
      type: String,
      default: null,
    },
    comments: {
      type: Number,
      default: null,
    },
    url: {
      type: String,
      default: null,
    },
  },

  emits: [
    'edit', 'destroy', 'update:modelValue'
  ],

  data() {
    return {
      hover: false,
      idToDelete: 0,
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
    hasError() {
      return this.errors.length > 0 && this.required;
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

    showHover() {
      this.hover = true;
    },

    goTo(url) {
      this.$inertia.visit(url);
    },

    hideHover() {
      setTimeout(() => this.hover = false, 500);
    }
  },
};
</script>
