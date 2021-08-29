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
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/company/groups'"
        :root="$t('app.breadcrumb_group_list')"
        :has-more="false"
      >
        {{ $t('app.breadcrumb_group_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <group-menu :group="group" :tab="tab" />

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- mission -->
            <div class="mb2 fw5 relative">
              <span class="mr1">
                🏔
              </span> {{ $t('group.summary_description') }}
            </div>
            <div v-if="group.mission" class="bg-white box pa3 parsed-content" v-html="group.mission"></div>
            <div v-if="!group.mission" class="bg-white box pa3">
              {{ $t('group.summary_mission_blank') }}
            </div>

            <!-- list of meetings -->
            <div v-if="meetings.length > 0" class="mb2 fw5 relative mt4">
              <span class="mr1">
                🌮
              </span> {{ $t('group.summary_meetings') }}
            </div>
            <div v-if="meetings.length > 0" class="br3 bg-white box z-1">
              <ul class="list pl0 ma0">
                <li v-for="meeting in meetings" :key="meeting.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between meeting-item">
                  <div class="mb1 relative">
                    <inertia-link :href="meeting.url" class="employee-name db">
                      {{ meeting.happened_at }}
                    </inertia-link>
                  </div>

                  <span v-if="meeting.preview_members" class="ma0 mb0 f7 grey">
                    <div class="flex items-center relative tr">
                      <avatar v-for="member in meeting.preview_members" :key="member.id" :avatar="member.avatar" :url="member.url" :size="25"
                              :class="'br-100 small-avatar'"
                      />
                      <div v-if="meeting.remaining_members_count > 0" class="pl2 f7 more-members relative gray">
                        + {{ meeting.remaining_members_count }}
                      </div>
                    </div>
                  </span>
                </li>
              </ul>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <div class="mb2 fw5 relative">
              <span class="mr1">
                💹
              </span> {{ $t('group.summary_stat') }}
            </div>
            <div class="bg-white box mb4 pa3">
              <p class="flex justify-between lh-copy f6 fw5 mb0 mt0 ml0 mr0">{{ $t('group.summary_stat_meeting') }} <span class="fw4 gray">{{ stats.number_of_meetings }}</span> </p>
              <p v-if="stats.frequency" class="flex justify-between lh-copy f6 fw5 mb0 mt2">{{ $t('group.summary_stat_days') }} <span class="fw4 gray">{{ stats.frequency }}</span></p>
            </div>

            <ul class="list pl0">
              <li class="mb2 pl2"><inertia-link :href="localGroup.url_edit" class="f6 gray">{{ $t('group.summary_edit') }}</inertia-link></li>
              <li class="pl2"><inertia-link :href="localGroup.url_delete" class="f6 gray c-delete">{{ $t('group.summary_delete') }}</inertia-link></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';
import GroupMenu from '@/Pages/Company/Group/Partials/GroupMenu';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
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
    meetings: {
      type: Object,
      default: null,
    },
    stats: {
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
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
