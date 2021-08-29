<template>
  <div :class="['mt4-l mt1 mw6 br3 center breadcrumb relative z-0 f6 pb2', {'bg-white box': withBox}]">
    <ul class="list ph0 tc-l tl">
      <li class="di">
        <inertia-link :href="cRootUrl">{{ cRoot }}</inertia-link>
      </li>
      <li v-if="hasMore" class="di">
        …
      </li>
      <li v-if="previous" class="di">
        <inertia-link :href="previousUrl">{{ previous }}</inertia-link>
      </li>
      <li class="di">
        <slot></slot>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    withBox: {
      type: Boolean,
      default: false,
    },
    hasMore: {
      type: Boolean,
      default: true,
    },
    rootUrl: {
      type: String,
      default: null,
    },
    root: {
      type: String,
      default: null,
    },
    previousUrl: {
      type: String,
      default: null,
    },
    previous: {
      type: String,
      default: null,
    },
  },

  computed: {
    cRootUrl: function () {
      return this.rootUrl ?? this.route('company.index', { company: this.$page.props.auth.company.id });
    },

    cRoot: function () {
      return this.root ?? this.$t('app.breadcrumb_company');
    }
  }
};
</script>
