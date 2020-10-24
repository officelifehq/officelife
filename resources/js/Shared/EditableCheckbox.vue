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
</style>

<template>
  <div :class="extraClassUpperDiv" class="hover" :data-cy="datacy" @mouseover="showHover()" @mouseleave="hover = false">
    <!-- actions - available on mouse hover on desktop -->
    <div v-show="editable" :class="hover ? 'di' : 'visibility-hidden'" class="hide-actions di f6 bg-white ph1 pv1 br3">
      <!-- edit -->
      <span :data-cy="datacy + '-edit'" class="di mr1 bb b--dotted bt-0 bl-0 br-0 pointer" @click="$emit('update', itemId)">
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
          v-model="updatedValue"
          :data-cy="datacy + '-single-item'"
          type="checkbox"
          class="relative"
          :class="classes"
          :required="required"
          :name="name"
          :disabled="!editable"
          @change="emitValue()"
        />

        <!-- content of the checkbox -->
        <label v-if="label" :for="id" class="fw4 lh-copy f5 pointer di relative hover-effect">
          <span v-html="label"></span>

          <!-- actions - only shown on mobile -->
          <div class="show-actions">
            <!-- edit -->
            <span class="di mr1 bb b--dotted bt-0 bl-0 br-0 pointer" @click="$emit('update', itemId)">
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
export default {
  props: {
    id: {
      type: String,
      default() {
      },
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
    classes: {
      type: String,
      default: 'mb3',
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
  },

  data() {
    return {
      updatedValue: false,
      hover: false,
      idToDelete: 0,
      errors: [],
    };
  },

  computed: {
    hasError: function () {
      return this.errors.length > 0 && this.required ? true : false;
    }
  },

  mounted: function() {
    this.updatedValue = this.value;
  },

  methods: {
    focus() {
      this.$refs.input.focus();
    },

    emitValue() {
      this.$emit('change', this.updatedValue);
    },

    showHover() {
      this.hover = true;
    },
  },
};
</script>
