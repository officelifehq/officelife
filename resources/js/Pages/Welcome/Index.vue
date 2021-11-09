<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}
</style>

<template>
  <layout :show-help-on-page="false" :notifications="notifications" data-cy="company-welcome" :data-cy-item="$page.props.auth.company.id">
    <div class="ph2 ph0-ns">
      <!-- toggle button -->
      <p class="mt3 tc f6"><span class="mr1">🙈</span><a data-cy="hide-message" href="#" class="mb2" @click.prevent="hide()">{{ $t('welcome.hide_message_forever') }}</a></p>

      <div class="cf mt4 mw6 center br3 mb3 bg-white box pa3">
        <img loading="lazy" class="db center mb4" alt="no expenses to validate" height="140" src="/img/streamline-icon-plane-hi-sign@140x140.png" />
        <p class="lh-copy">{{ $t('welcome.thanks') }}</p>
        <p class="lh-copy">{{ $t('welcome.admin_line_1') }}</p>
        <p class="lh-copy mb4">{{ $t('welcome.admin_line_2') }}</p>
        <p class="mb4 tc"><a :href="url" class="btn" target="_blank"><span class="mr1">🙉</span> {{ $t('welcome.admin_guide_cta') }}</a></p>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    url: {
      type: String,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  methods: {
    hide() {
      axios.post('/' + this.$page.props.auth.company.id + '/hide')
        .then(response => {
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/dashboard');
        })
        .catch(error => {
        });
    }
  },
};
</script>
