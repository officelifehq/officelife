<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 2px;
  width: 35px;
}

.team-member {
  padding-left: 44px;
}

.project-code {
  top: -2px;
  color: #737e91;
  border: 1px solid #b3d4ff;
}

.dot {
  height: 11px;
  width: 11px;
  top: 3px;
}

.created,
.paused {
  background-color: #fff6c5;

  .dot {
    background-color: #b7b7b7;
  }
}

.started {
  background-color: #e8f7f0;

  .dot {
    background-color: #56bb76;
  }
}

.closed {
  background-color: #4f7584;
  color: #fff;

  .dot {
    background-color: #c8d7cd;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/projects'">{{ $t('app.breadcrumb_project_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <h2 class="tc mb2 relative" data-cy="project-name">
          {{ localProject.name }} <span v-if="localProject.code" class="ml2 ttu f7 project-code br3 pa1 relative fw4">
            {{ localProject.code }}
          </span>
        </h2>
        <p class="tc mt0 mb4">{{ localProject.summary }}</p>

        <!-- Menu -->
        <project-menu />

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- Project description -->
            <div class="mb2 fw5 relative">
              <span class="mr1">
                🏔
              </span> {{ $t('project.summary_description') }}
            </div>

            <div class="bg-white box mb4 pa3">
              <div v-if="localProject.parsed_description" class="parsed-content" v-html="localProject.parsed_description"></div>
              <div v-else class="mb0 mt0 lh-copy f6 tc">
                This project doesn’t have a description.
              </div>
            </div>

            <div>
              <div class="mb2 fw5 relative">
                <span class="mr1">
                  👩‍🏫
                </span> {{ $t('project.summary_status') }}
              </div>

              <div class="bg-white box mb4 pa3">
                <h3 class="ttc f7 gray mt0 mb2 fw4">
                  Written by Michael Scott on Oct 4th
                </h3>
                <p class="lh-copy mt0 mb0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <!-- actions -->
            <div class="bg-white box mb4 tc">
              <p class="f6 mt0 pa3 bb bb-gray relative"><span :class="localProject.status" class="pv1 ph2 br3 gray"><span class="dib dot br-100 relative mr2">&nbsp;</span> {{ $t('project.summary_status_' + localProject.status) }}</span></p>

              <!-- start button -->
              <div v-if="localProject.status == 'created'" class="mb3">
                <loading-button :classes="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('project.summary_cta_start_project')" @click="start()" />
              </div>

              <!-- pause or close buttons -->
              <div v-if="localProject.status == 'started' || localProject.status == 'paused'" class="mb3">
                <loading-button v-if="localProject.status != 'paused'" :classes="'btn w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingPauseState" :text="$t('project.summary_cta_pause_project')" @click="pause()" />
                <loading-button v-if="localProject.status != 'started'" :classes="'btn w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingUnpauseState" :text="$t('project.summary_cta_unpause_project')" @click="unpause()" />
                <loading-button :classes="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingCloseState" :text="$t('project.summary_cta_close_project')" @click="close()" />
              </div>

              <!-- reopen -->
              <div v-if="localProject.status == 'closed'" class="mb3">
                <loading-button :classes="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('project.summary_cta_reopen_project')" @click="start()" />
              </div>
            </div>

            <div class="bg-white box mb2 pa3">
              <!-- lead by -->
              <h3 class="ttc f7 gray mt0 mb2 fw4 ttu">
                Lead by
              </h3>
              <div class="bb bb-gray pb3 mb3">
                <span class="pl3 db relative team-member">
                  <img loading="lazy" src="https://api.adorable.io/avatars/200/1499e9ea-b9a2-4d60-8bf6-a9bdf4cacbbc.png" alt="avatar" class="br-100 absolute avatar" />
                  <inertia-link class="mb2">Scott</inertia-link>
                  <span class="title db f7 mt1">
                    Manager
                  </span>
                </span>
              </div>

              <!-- links -->
              <h3 class="ttc f7 gray mt0 mb2 fw4 ttu">
                Project links
              </h3>
              <div class="bb bb-gray pb3 mb3">
                <ul class="list pl0">
                  <li class="mb2"><a href="">https://officelife.io</a></li>
                  <li class="mb2"><a href="">https://officelife.io</a></li>
                  <li class="mb2"><a href="">https://officelife.io</a></li>
                </ul>
              </div>

              <!-- dates -->
              <h3 class="ttc f7 gray mt0 mb2 fw4 ttu">
                Dates
              </h3>
              <p class="mv0">Jan 02, 2019</p>
            </div>

            <ul class="list pl0">
              <li class="mb2 pl2"><inertia-link :href="localProject.url_edit" data-cy="project-edit" class="f6 gray">{{ $t('project.summary_edit') }}</inertia-link></li>
              <li class="pl2"><inertia-link :href="localProject.url_delete" data-cy="project-delete" class="f6 gray">{{ $t('project.summary_delete') }}</inertia-link></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import ProjectMenu from '@/Pages/Project/Partials/ProjectMenu';

export default {
  components: {
    Layout,
    LoadingButton,
    ProjectMenu,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    project: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      localProject: null,
      loadingState: '',
      loadingPauseState: '',
      loadingUnpauseState: '',
      loadingCloseState: '',
    };
  },

  created() {
    this.localProject = this.project;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    start() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/projects/' + this.localProject.id + '/start')
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    pause() {
      this.loadingPauseState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/projects/' + this.localProject.id + '/pause')
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingPauseState = null;
        })
        .catch(error => {
          this.loadingPauseState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    unpause() {
      this.loadingUnpauseState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/projects/' + this.localProject.id + '/start')
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingUnpauseState = null;
        })
        .catch(error => {
          this.loadingUnpauseState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    close() {
      this.loadingCloseState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/projects/' + this.localProject.id + '/close')
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingCloseState = null;
        })
        .catch(error => {
          this.loadingCloseState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
