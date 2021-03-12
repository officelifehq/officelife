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

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <group-menu :group="group" :tab="info" />

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- list of meetings -->
            <div class="br3 bg-white box z-1">
              <ul data-cy="e-coffee-list" class="list pl0 ma0">
                <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
                  <div class="mb1 relative">
                    <span class="employee-name db">
                      Meeting du 20 janvier
                    </span>
                  </div>
                  <span class="ma0 mb0 f7 grey">
                    <div class="flex items-center relative tr">
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <div class="pl2 f7 more-members relative gray">
                        {{ $t('project.menu_other_member', { count: 3 }) }}
                      </div>
                    </div>
                  </span>
                </li>
                <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
                  <div class="mb1 relative">
                    <span class="employee-name db">
                      Meeting du 20 janvier
                    </span>
                  </div>
                  <span class="ma0 mb0 f7 grey">
                    <div class="flex items-center relative tr">
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                    </div>
                  </span>
                </li>
                <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
                  <div class="mb1 relative">
                    <span class="employee-name db">
                      Meeting du 20 janvier
                    </span>
                  </div>
                  <span class="ma0 mb0 f7 grey">
                    <div class="flex items-center relative tr">
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                    </div>
                  </span>
                </li>
                <li class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
                  <div class="mb1 relative">
                    <span class="employee-name db">
                      Meeting du 20 janvier
                    </span>
                  </div>
                  <span class="ma0 mb0 f7 grey">
                    <div class="flex items-center relative tr">
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                      <img src="https://ui-avatars.com/api/?name=Glenn%20Scott" alt="avatar" class="br-100 small-avatar"
                           width="32" height="32"
                      />
                    </div>
                  </span>
                </li>
                <li class="ph3 pv2 tc f6">
                  <inertia-link href="ecoffees.view_all_url" data-cy="view-all-ecoffees">{{ $t('employee.e_coffee_view_all') }}</inertia-link>
                </li>
              </ul>
            </div>

            <!-- blank -->
            <div class="br3 bg-white box z-1 tc pa3">
              <h3 class="fw4 f5 lh-copy">
                Meeting notes are great to prepare meetings, and record what was said.
              </h3>
              <img loading="lazy" src="/img/streamline-icon-meeting-table-3@140x140.png" width="140" height="140" alt="people hanging out"
                   class="di-ns dn top-1 left-1"
              />
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <inertia-link class="w-100 btn dib-l db mb3" data-cy="add-position-button" @click.prevent="displayAddModal">
              Add a meeting note
            </inertia-link>

            <div class="bg-white box mb4">
              <p>sdfsd</p>
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
