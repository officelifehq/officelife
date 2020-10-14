<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/projects'">Project list</inertia-link>
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/projects/1'" class="f6 fl ph3 pv2 dib pointer no-underline">
              Project summary
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/projects/1/messages'" class="f6 fl ph3 pv2 dib pointer no-underline">
              Messages
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Tasks
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Calendar
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Members
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Finance
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" data-cy="dashboard-team-tab">
              Files
            </inertia-link>
          </div>
        </div>

        <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
          <ul class="list pl0 ma0">
            <li class="mb4">
              <h3 class="mt0 mb2 fw4">This is a long message title</h3>
              <ul class="list pl0 ma0">
                <li class="di mr2">
                  <small-name-and-avatar
                    :name="'regis'"
                    :avatar="'https://api.adorable.io/avatars/200/1499e9ea-b9a2-4d60-8bf6-a9bdf4cacbbc.png'"
                    :classes="'gray'"
                    :size="'18px'"
                    :top="'2px'"
                    :margin-between-name-avatar="'25px'"
                  />
                </li>
                <li class="di f7 gray">Jan 03, 2020</li>
                <li class="di f7 gray"><a href="">3 comments</a></li>
              </ul>
            </li>
            <li>
              <h3 class="mt0 mb2 fw4">This is a long message title</h3>
              <ul class="list pl0 ma0">
                <li class="di mr2">
                  <small-name-and-avatar
                    :name="'regis'"
                    :avatar="'https://api.adorable.io/avatars/200/1499e9ea-b9a2-4d60-8bf6-a9bdf4cacbbc.png'"
                    :classes="'gray'"
                    :size="'18px'"
                    :top="'0px'"
                    :margin-between-name-avatar="'25px'"
                  />
                </li>
                <li class="di f7 gray">Jan 03, 2020</li>
                <li class="di f7 gray"><a href="">3 comments</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    SmallNameAndAvatar,
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
