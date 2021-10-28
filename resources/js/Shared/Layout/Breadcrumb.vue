<template>
  <div :class="localClasses">
    <ul class="list ph0 tl" :class="centerBox ? 'tc-l' : ''">
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
    customClass: {
      type: String,
      default: null,
    },
    centerBox: {
      type: Boolean,
      default: true,
    },
    customMarginTop: {
      type: String,
      default: 'mt4-l'
    },
    customMarginBottom: {
      type: String,
      default: ' '
    }
  },

  data() {
    return {
      localClasses: '',
    };
  },

  computed: {
    cRootUrl: function () {
      return this.rootUrl ?? this.route('company.index', { company: this.$page.props.auth.company.id });
    },

    cRoot: function () {
      return this.root ?? this.$t('app.breadcrumb_company');
    }
  },

  mounted() {
    this.localClasses = this.customMarginTop + ' mt1 mw6 br3 breadcrumb relative z-0 f6 pb2 ' + this.customMarginBottom;

    if (this.centerBox) {
      this.localClasses = this.localClasses + ' center';
    }

    if (this.withBox) {
      this.localClasses = this.localClasses + ' bg-white box';
    }

    if (this.customClass) {
      this.localClasses = this.localClasses + ' ' + this.customClass;
    }
  }
};
</script>
