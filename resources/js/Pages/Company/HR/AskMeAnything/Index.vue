<style lang="scss" scoped>
.session-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.session-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/company/hr'"
        :root="$t('app.breadcrumb_company')"
        :has-more="false"
        :custom-class="'mb4'"
      >
        {{ $t('app.breadcrumb_ama_list') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <!-- current session -->
        <div class="mb2 fw5 relative mt4 flex justify-between items-center">
          <div>
            <span class="mr1">
              🎉
            </span> {{ $t('company.hr_ama_index_upcoming_session') }}

            <help :url="$page.props.help_links.ask_me_anything" :top="'2px'" />
          </div>

          <inertia-link v-if="data.can_create" :href="data.url_new" class="btn f5">{{ $t('company.hr_ama_index_cta') }}</inertia-link>
        </div>
        <div v-if="data.active_session" class="br3 mb5 bg-white box flex justify-between items-center pa3">
          <div class="relative flex items-center">
            <img loading="lazy" src="/img/streamline-icon-microphone-19@400x400.png" alt="mic symbol" class="mr2" height="80"
                 width="80"
            />

            <div>
              <p class="mt0 mb2 f7 gray">{{ $t('company.hr_ama_index_next_session') }}</p>
              <h2 class="fw4 mt0 mb2">
                {{ data.active_session.happened_at }}
              </h2>
              <p v-if="data.active_session.theme" class="f6 mt1 mb0">{{ data.active_session.theme }}</p>
            </div>
          </div>

          <div class="tc">
            <inertia-link :href="data.active_session.url" class="btn db mb2">{{ $t('app.view') }}</inertia-link>
            <p class="mv0 gray f7">{{ $tc('company.hr_ama_index_next_questions', data.active_session.questions_count, {count: data.active_session.questions_count }) }}</p>
          </div>
        </div>
        <div v-else class="br3 mb5 bg-white box flex justify-between items-center pa3">
          <div class="relative flex items-center">
            <h2 class="fw4 f5 mt0 mb0 tc">
              {{ $t('company.hr_ama_index_blank_note') }}
            </h2>
          </div>
        </div>

        <!-- past sessions -->
        <div class="mb2 fw5 relative mt4">
          <span class="mr1">
            🎤
          </span> {{ $t('company.hr_ama_index_past_session') }}
        </div>
        <div class="br3 mb3 bg-white box">
          <!-- list of past sessions -->
          <ul v-if="data.inactive_sessions.length > 0" class="list pl0 ma0">
            <li v-for="session in data.inactive_sessions" :key="session.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between session-item">
              <div class="mb1 relative">
                <inertia-link :href="session.url" class="dib mb2">
                  {{ session.happened_at }}
                </inertia-link>

                <p class="mv0 f7 gray">{{ session.theme }}</p>
              </div>

              <p class="mv0">{{ $t('company.hr_ama_index_past_session_count', session.questions_count, {count: session.questions_count }) }}</p>
            </li>
          </ul>

          <!-- blank state -->
          <div v-else class="center tc">
            <p class="measure mb4 lh-copy">
              {{ $t('company.hr_ama_index_blank') }}
            </p>
            <img loading="lazy" src="/img/streamline-icon-singer-record-14@400x400.png" alt="mic symbol" class="mr2" height="140"
                 width="140"
            />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
      type: Object,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
