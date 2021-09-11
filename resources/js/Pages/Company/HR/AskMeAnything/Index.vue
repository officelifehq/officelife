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
        :root-url="'/' + $page.props.auth.company.id + '/company/groups'"
        :root="$t('app.breadcrumb_group_list')"
        :has-more="false"
      >
        {{ $t('app.breadcrumb_group_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <!-- current session -->
        <div class="br3 mb3 bg-white box flex justify-between items-center pa3">
          <div class="relative flex items-center">
            <img loading="lazy" src="/img/streamline-icon-microphone-19@400x400.png" alt="mic symbol" class="mr2" height="80"
                 width="80"
            />

            <div>
              <p class="mt0 mb2 f7 gray">Next session</p>
              <h2 class="fw4 mt0 mb2">
                Sept 33, 2200
              </h2>
              <p class="f6 mt1 mb0">blabla</p>
            </div>
          </div>

          <div class="tc">
            <inertia-link class="btn db mb2">View</inertia-link>
            <p class="mv0 gray f7">33 questions already</p>
          </div>
        </div>

        <!-- past sessions -->
        <div class="mb2 fw5 relative mt4 flex justify-between items-center">
          <div>
            <span class="mr1">
              🎤
            </span> Past sessions
          </div>

          <inertia-link :href="''" class="btn f5">Create a new session</inertia-link>
        </div>
        <div class="br3 mb3 bg-white box flex justify-between items-center pa3">
          <!-- list of past sessions -->
          <ul v-if="sessions.length > 0" class="list pl0 ma0">
            <li v-for="session in sessions" :key="session.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between session-item">
              <div class="mb1 relative">
                <inertia-link :href="''" class="db mb2">
                  {{ session.happened_at }}
                </inertia-link>

                <p class="mv0 f7 gray">{{ session.theme }}</p>
              </div>

              <div>
                <inertia-link :href="''" class="db mb2">{{ $t('app.view') }}</inertia-link>
                <p class="mv0">33/32 questions answered</p>
              </div>
            </li>
          </ul>

          <!-- blank state -->
          <div v-else class="center tc">
            <p class="measure mb4 lh-copy">
              There are no previous sessions yet.
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

export default {
  components: {
    Layout,
    Breadcrumb,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    sessions: {
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
