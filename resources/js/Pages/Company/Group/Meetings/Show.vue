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
        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- agenda -->
            <agenda :group-id="group.id" :meeting="meeting" :agenda="agenda" />
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <!-- date -->
            <date-widget :group-id="group.id" :meeting="meeting" />

            <!-- participants -->
            <participants :group-id="group.id" :meeting="meeting" />

            <ul class="list pl0">
              <li v-if="!deleteMode" class="pl2"><a data-cy="project-delete" class="f6 gray bb b--dotted bt-0 bl-0 br-0 pointer di c-delete" @click.prevent="deleteMode = true">{{ $t('group.meeting_show_delete') }}</a></li>

              <li v-if="deleteMode" class="f6">
                {{ $t('app.sure') }}
                <a class="c-delete mr1 pointer" @click.prevent="destroy()">
                  {{ $t('app.yes') }}
                </a>
                <a class="pointer" @click.prevent="deleteMode = false">
                  {{ $t('app.no') }}
                </a>
              </li>
            </ul>
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
import DateWidget from '@/Pages/Company/Group/Meetings/Partials/Date';
import Agenda from '@/Pages/Company/Group/Meetings/Partials/Agenda';

export default {
  components: {
    Layout,
    GroupMenu,
    Participants,
    DateWidget,
    Agenda,
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
    agenda: {
      type: Array,
      default: null,
    },
    tab: {
      type: String,
      default: 'summary',
    },
  },

  data() {
    return {
      deleteMode: false,
    };
  },

  methods: {
    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/company/groups/${this.group.id}/meetings/${this.meeting.meeting.id}`)
        .then(response => {
          localStorage.success = this.$t('group.meeting_show_delete_success');

          this.$inertia.visit(`/${this.$page.props.auth.company.id}/company/groups/${this.group.id}/meetings`);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
