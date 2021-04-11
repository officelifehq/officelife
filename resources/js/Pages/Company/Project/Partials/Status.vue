<style lang="scss" scoped>
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
  <div class="bg-white box mb4 tc">
    <!-- current status -->
    <p class="f6 mt0 pa3 bb bb-gray relative" data-cy="project-status">
      <span :class="localProject.status" class="pv1 ph2 br3 gray">
        <span class="dib dot br-100 relative mr2">&nbsp;</span> {{ $t('project.summary_status_' + localProject.status) }}
      </span>
    </p>

    <div class="pa0-ns pa3">
      <!-- start button -->
      <div v-if="localProject.status == 'created'" class="mb3">
        <loading-button :class="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('project.summary_cta_start_project')" data-cy="start-project" @click="start()" />
      </div>

      <!-- pause or close buttons -->
      <div v-if="localProject.status == 'started' || localProject.status == 'paused'" class="mb3">
        <loading-button v-if="localProject.status != 'paused'" :class="'btn w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2'" :state="loadingPauseState" :text="$t('project.summary_cta_pause_project')" data-cy="pause-project"
                        @click="pause()"
        />
        <loading-button v-if="localProject.status != 'started'" :class="'btn w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2'" :state="loadingUnpauseState" :text="$t('project.summary_cta_unpause_project')" data-cy="unpause-project"
                        @click="unpause()"
        />
        <loading-button :class="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingCloseState" :text="$t('project.summary_cta_close_project')" data-cy="close-project" @click="close()" />
      </div>

      <!-- reopen -->
      <div v-if="localProject.status == 'closed'" class="mb3">
        <loading-button :class="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('project.summary_cta_reopen_project')" data-cy="start-project" @click="start()" />
      </div>
    </div>
  </div>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    LoadingButton,
  },

  props: {
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

  methods: {
    start() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/start`)
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingState = null;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    pause() {
      this.loadingPauseState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/pause`)
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingPauseState = null;
        })
        .catch(error => {
          this.loadingPauseState = null;
          this.form.errors = error.response.data;
        });
    },

    unpause() {
      this.loadingUnpauseState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/start`)
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingUnpauseState = null;
        })
        .catch(error => {
          this.loadingUnpauseState = null;
          this.form.errors = error.response.data;
        });
    },

    close() {
      this.loadingCloseState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/company/projects/${this.localProject.id}/close`)
        .then(response => {
          this.localProject.status = response.data.status;
          this.loadingCloseState = null;
        })
        .catch(error => {
          this.loadingCloseState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
