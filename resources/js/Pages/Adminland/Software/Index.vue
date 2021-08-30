<style lang="scss" scoped>
.list li:last-child {
  border-bottom: 0;
}

.software-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.software-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_softwares') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.software_index_title') }}

            <help :url="$page.props.help_links.softwares" :top="'1px'" />
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="{ white: localSoftwares.length === 0 }">
              {{ $tc('account.software_index_count', localSoftwares.length, { company: $page.props.auth.company.name, count: localSoftwares.length}) }}
            </span>
            <inertia-link :href="softwares.url_new" class="btn absolute-l relative dib-l db right-0">
              {{ $t('account.software_index_cta') }}
            </inertia-link>
          </p>

          <!-- LIST OF EXISTING SOFTWARES -->
          <ul v-if="localSoftwares.length != 0" class="list pl0 mv0 center ba br2 bb-gray">
            <li v-for="software in localSoftwares" :key="software.id" class="pv3 ph3 bb bb-gray bb-gray-hover flex justify-between items-center software-item">
              <inertia-link :href="software.url" class="di f4">
                {{ software.name }}
              </inertia-link>
              <div class="flex justify-between">
                <div class="mr3">
                  <p class="ma0 f6 gray mb2">Total seats</p>
                  <p class="ma0 f4">{{ software.seats }}</p>
                </div>
                <div>
                  <p class="ma0 f6 gray mb2">Remaining seats</p>
                  <p class="ma0 f4">{{ software.remaining_seats }}</p>
                </div>
              </div>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-else class="tc">
            <img loading="lazy" src="/img/streamline-icon-code-developer-1-3@140x140.png" alt="project symbol" height="140"
                 width="140"
            />
            <p class="mb3">
              <span class="db mb4">{{ $t('account.software_index_blank_description') }}</span>
            </p>
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
    softwares: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localSoftwares: [],
      form: {
        errors: [],
      },
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  created() {
    this.localSoftwares = this.softwares.softwares;
  },

  methods: {
  }
};

</script>
