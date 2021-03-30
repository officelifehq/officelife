<style lang="scss" scoped>
.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.meeting-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.meeting-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
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

      <div class="mw7 center br3 mb5 relative z-1">
        <!-- Menu -->
        <group-menu :group="group" :tab="tab" />
      </div>

      <div class="mw8 center br3 mb5 relative z-1">
        <p>Meeting</p>

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <p>left</p>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <!-- participants -->
            <participants :group-id="group.id" :meeting="meeting" />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import GroupMenu from '@/Pages/Company/Group/Partials/GroupMenu';
import Participants from '@/Pages/Company/Group/Meetings/Partials/Participants';

export default {
  components: {
    Layout,
    GroupMenu,
    Participants,
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
    meeting: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
      processingSearch: false,
      localMembers: null,
      showModal: false,
      removeMode: false,
      idToDelete: 0,
      potentialMembers: [],
      loadingState: '',
      form: {
        searchTerm: null,
        employee: null,
        role: null,
        errors: [],
      },
    };
  },

  created() {
    this.localMembers = this.members;
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
