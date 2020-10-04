<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
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
            <inertia-link :href="'/' + $page.auth.company.id + '/projects'">Project list</inertia-link>
          </li>
          <li class="di">
            Project name
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <!-- Menu -->
        <div class="center br3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="'/' + $page.auth.company.id + '/projects/1'" class="f6 fl ph3 pv2 dib pointer no-underline">
              Project summary
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/projects/1/messages'" class="f6 fl ph3 pv2 dib pointer no-underline">
              Messages
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Tasks
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Calendar
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Members
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Finance
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Files
            </inertia-link>
          </div>
        </div>

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- Project description -->
            <div class="mb2 fw5 relative">
              <span class="mr1">
                🏔
              </span> Description
            </div>

            <div class="bg-white box mb4 pa3">
              <h2 class="mt0 mb2 fw4 lh-copy">
                Project name <span class="f7 gray">
                  SDN-123
                </span>
              </h2>
              <p class="mt0 lh-copy bb bb-gray pb3 mb3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>

              <h3 class="ttc f7 gray mt0 mb2 fw4">
                <span class="ttu">
                  Latest status
                </span> • Written by Michael Scott on Oct 4th
              </h3>
              <p class="lh-copy mt0 mb0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.</p>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <!-- actions -->
            <div class="bg-white box mb4 pa3 tc">
              <loading-button :classes="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="'Start project'" />
            </div>

            <div class="bg-white box mb4 pa3">
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
          </div>
        </div>
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
  },

  data() {
    return {
    };
  },

  methods: {
  }
};

</script>
