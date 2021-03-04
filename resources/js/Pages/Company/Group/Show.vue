<style lang="scss" scoped>
.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/groups'">{{ $t('app.breadcrumb_group_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_group_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <group-menu :group="group" :tab="info" />

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            test
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <div class="bg-white box mb4">
              test
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import GroupMenu from '@/Pages/Company/Group/Partials/GroupMenu';

export default {
  components: {
    Layout,
    GroupMenu,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    group: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: null,
    },
  },

  created() {
    this.localGroup = this.group;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
