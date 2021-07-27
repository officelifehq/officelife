<style lang="scss" scoped>
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/hr/job-openings'">{{ $t('app.breadcrumb_hr_job_openings_active') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_job_opening_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- header -->
        <div class="bg-white box pa3 mb4 flex justify-between items-center">
          <!-- name + summary -->
          <div class="pl3">
            <h2 class="mt0 mb2 relative fw4">
              Regis Freyd
            </h2>

            <ul class="list pa0 ma0 f7 gray">
              <li class="di mr3">
                Applied Dec 23, 2018
              </li>
              <li class="di mr3">email address</li>
              <li class="di">telephone number</li>
            </ul>
          </div>

          <!-- actions -->
          <ul class="list ma0 pl0">
            <li class="di mr1"><loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2'" :state="loadingPauseState" :text="'Decide later'" @click="pause()" /></li>
            <li class="di mr1"><loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2'" :state="loadingPauseState" :text="'Reject'" @click="pause()" /></li>
            <li class="di"><loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mr2 mb0-ns mb2'" :state="loadingPauseState" :text="'Select'" @click="pause()" /></li>
          </ul>
        </div>

        <div class="center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              Curriculum vitae
            </inertia-link>
            <inertia-link :href="''" class="f6 fl ph3 pv2 dib pointer no-underline">
              Interviews
            </inertia-link>
          </div>
        </div>

        <h3>Interviews</h3>

        <ul>
          <li>Upcoming interview </li>
          <li>On June 29, 2011 with </li>
        </ul>

        <h3>Interviews</h3>

        <h3></h3>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    jobOpening: {
      type: Object,
      default: null,
    },
  },

  created() {
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
