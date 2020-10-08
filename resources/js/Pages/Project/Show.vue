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
            <inertia-link :href="'/' + $page.auth.company.id + '/projects'">{{ $t('app.breadcrumb_project_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <h2 class="tc mb2">
          {{ project.name }} <span>{{ project.code }}</span>
        </h2>
        <p class="tc mt0 mb4">{{ project.summary }}</p>

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
              <div v-if="project.parsed_description" class="parsed-content" v-html="project.parsed_description"></div>
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
            <div class="bg-white box mb4 pa3 tc">
              <loading-button :classes="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="'Start project'" />
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
              <li><inertia-link :href="project.url_delete" data-cy="project-delete" class="f6 gray">Delete the project</inertia-link></li>
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
    };
  },

  methods: {
  }
};

</script>
