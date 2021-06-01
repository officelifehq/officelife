<style lang="scss" scoped>
.border-red {
  background-color: #fff5f5;
  border-color: #fc8181;
  color: #c53030;
}
</style>

<template>
  <div>
    <div v-if="dataerror || exception || $page.props.flash.message" class="border-red ba br3 pa3 mb3" v-bind="$attrs">
      <p class="mv0 fw6">{{ $t('app.error_title') }}</p>
      <p v-if="$page.props.flash.message" class="mb0">
        {{ $page.props.flash.message }}
      </p>
      <template v-if="dataerror">
        <p v-if="flatten[0] != 'The given data was invalid.'" class="mb0">
          {{ flatten[0] }}
        </p>
        <template v-if="display(flatten[1])">
          <p v-for="errorsList in flatten[1]" :key="errorsList.id">
            <span v-for="error in errorsList" :key="error.id" class="mb0 mt2">
              {{ error }}
            </span>
          </p>
        </template>
      </template>
      <template v-else-if="exception">
        <p class="mb0">
          {{ errors.message }}
        </p>
        <p>
          <a href="" @click.prevent="toggle">{{ $t('app.error_more') }}</a>
        </p>
        <p v-show="traces">
          <span class="mb0">
            {{ $t('app.error_exception') }} {{ errors.exception }}
          </span>
          <br />
          <span v-for="trace in errors.trace" :key="trace.id">
            {{ trace.class }}{{ trace.type }}{{ trace.function }}<br />
          </span>
        </p>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,

  props: {
    errors: {
      type: [Object, Array],
      default: null,
    }
  },

  data() {
    return {
      traces: false,
    };
  },

  computed: {
    dataerror() {
      return this.errors !== null && (this.errors.errors !== undefined || this.flatten.length > 0);
    },
    flatten() {
      return _.flatten(_.toArray(this.errors));
    },
    exception() {
      return this.errors !== null && this.errors.exception !== undefined;
    },
  },

  methods: {
    display(val) {
      return _.isObject(val);
    },
    toggle() {
      this.traces = !this.traces;
    }
  }
};
</script>
