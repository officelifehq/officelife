<style lang="scss" scoped>
.company-logo {
  top: -40px;
  width: 225px;
}
.company-name {
  margin-bottom: 17px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-row-gap: 20px;
  grid-column-gap: 20px;
}

@media (max-width: 480px) {
  .grid {
    grid-template-columns: 1fr;
  }
}

.dot {
  height: 11px;
  width: 11px;
  top: -1px;
}

.dot-created,
.dot-paused {
  background-color: #b7b7b7;
}

.dot-started {
  background-color: #56bb76;
}

.dot-closed {
  background-color: #c8d7cd;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <!-- company cover -->
    <div class="cover mb3" :style="'height: 25vh; background: url(https://images.unsplash.com/photo-1531973576160-7125cd663d86?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1500&q=80) no-repeat center center'"></div>

    <!-- company name + data -->
    <div class="ph2 ph5-ns mb2">
      <div class="flex relative">
        <div v-if="statistics.logo" class="company-logo relative ba bb-gray pa2 br3 bg-white mr3-ns">
          <img :src="statistics.logo" alt="logo" />
        </div>
        <div class="mt3">
          <h2 class="mt0 fw4 f3 mb4 company-name">
            {{ $page.props.auth.company.name }} <a href="https://dundermifflin.com" class="ml3 f6 fw4 no-underline gray bb-0">https://dundermifflin.com</a>
          </h2>
          <div class="flex">
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">number of employees</p>
              <p class="mt0">{{ statistics.number_of_employees }}</p>
            </div>
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">number of teams</p>
              <p class="mt0">{{ statistics.number_of_teams }}</p>
            </div>
            <div class="">
              <p class="ttu f7 gray mb1 mt0 mr5">founded</p>
              <p class="mt0">1987</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ph2 ph5-ns">
      <!-- central content -->
      <tab :tab="tab" />

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <div v-if="projects.projects.length > 0">
          <div class="mt4 mt5-l center section-btn relative mb5">
            <p>
              <span class="pr2">
                {{ $t('project.index_title') }}
              </span>
              <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/create'" class="btn absolute db-l dn">
                {{ $t('project.index_cta') }}
              </inertia-link>
            </p>
          </div>

          <!-- list of projects -->
          <div class="mt2 grid">
            <div v-for="project in projects.projects" :key="project.id" class="w-100 bg-white box pa3 mb3 mr3">
              <h2 class="fw4 f4 mt0 mb2 lh-copy relative">
                <span :class="'dot-' + project.status" class="dib relative mr1 br-100 dot"></span>
                <inertia-link :href="project.url">{{ project.name }}</inertia-link> <span class="f7 gray">
                  {{ project.code }}
                </span>
              </h2>
              <p class="mv0 lh-copy f6">{{ project.summary }}</p>
            </div>
          </div>
        </div>

        <!-- blank state -->
        <div v-else class="tc">
          <img loading="lazy" src="/img/streamline-icon-projector-pie-chart@140x140.png" alt="project symbol" height="140"
               width="140"
          />
          <p class="mb3"><span class="db mb3">{{ $t('project.index_blank_title') }}</span> <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/create'" class="btn dib">{{ $t('project.index_cta') }}</inertia-link></p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Tab from '@/Pages/Company/Partials/Tab';

export default {
  components: {
    Layout,
    Tab,
  },

  props: {
    tab: {
      type: String,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
    projects: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
