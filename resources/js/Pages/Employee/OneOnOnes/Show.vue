<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.add-item-section {
  margin-left: 24px;
  top: 5px;
  background-color: #f5f5f5;
}

.edit-item {
  left: 88px;
}

.list-item {
  left: -86px;
}

@media (max-width: 480px) {
  .list-item {
    left: 0;
  }

  .edit-item {
    left: 0;
  }
}

.list-item-offset {
  left: 0px;
}

.title-section {
  background-color: #E9EDF2;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="$route('dashboard', $page.props.auth.company.id)">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/oneonones'" data-cy="view-all-one-on-ones">{{ $t('app.breadcrumb_employee_one_on_ones') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_one_on_one') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <!-- title -->
        <div class="pa3 title-section bb bb-gray mb3">
          <h2 class="tc fw5 mt0">
            1 on 1

            <help :url="$page.props.help_links.one_on_ones" :top="'0px'" />
          </h2>
          <ul class="tc list pl0">
            <li data-cy="employee-name" class="di tl">
              <small-name-and-avatar
                v-if="entry.employee.id"
                :name="entry.employee.name"
                :avatar="entry.employee.avatar"
                :size="'22px'"
                :top="'0px'"
                :margin-between-name-avatar="'29px'"
              />
            </li>
            <li class="di f6 mh3">
              {{ $t('app.and') }}
            </li>
            <li data-cy="manager-name" class="di tl">
              <small-name-and-avatar
                v-if="entry.manager.id"
                :name="entry.manager.name"
                :avatar="entry.manager.avatar"
                :size="'22px'"
                :top="'0px'"
                :margin-between-name-avatar="'29px'"
              />
            </li>
          </ul>
        </div>

        <!-- dates -->
        <div class="cf mb4 pa3 db">
          <!-- desktop view -->
          <div class="fl-ns w-third-ns db-ns dn">
            <template v-if="entry.previous_entry">
              <div class="db mb1 f6 mr5">
                ←
                <inertia-link :href="entry.previous_entry.url">
                  {{ $t('dashboard.one_on_ones_previous_entry') }}
                </inertia-link>
              </div>
              <span class="f7 gray">
                {{ entry.previous_entry.happened_at }}
              </span>
            </template>
            <template v-else>
&nbsp;
            </template>
          </div>
          <div class="fl-ns w-third-ns db-ns dn f4 tc">
            <span class="f7 gray db mb1">
              {{ $t('dashboard.one_on_ones_created_on') }}
            </span>
            {{ entry.happened_at }}
          </div>
          <div class="fl-ns w-third-ns db-ns dn tr">
            <template v-if="entry.next_entry">
              <div class="db mb1 f6 ml5">
                <inertia-link :href="entry.next_entry.url">
                  {{ $t('dashboard.one_on_ones_next_entry') }}
                </inertia-link>
                →
              </div>
              <span class="f7 gray">
                {{ entry.next_entry.happened_at }}
              </span>
            </template>
          </div>

          <!-- mobile view -->
          <div class="fl w-50 dn-ns db">
            <template v-if="entry.previous_entry">
              <div class="db mb1 f6">
                ←
                <inertia-link :href="entry.previous_entry.url">
                  {{ $t('dashboard.one_on_ones_previous_entry') }}
                </inertia-link>
              </div>
              <span class="f7 gray">
                {{ entry.previous_entry.happened_at }}
              </span>
            </template>
            <template v-else>
&nbsp;
            </template>
          </div>
          <div class="fl w-50 dn-ns db tr">
            <template v-if="entry.next_entry">
              <div class="db mb1 f6">
                <inertia-link :href="entry.next_entry.url">
                  {{ $t('dashboard.one_on_ones_next_entry') }}
                </inertia-link>
                →
              </div>
              <span class="f7 gray">
                {{ entry.next_entry.happened_at }}
              </span>
            </template>
          </div>
          <div class="fl w-100 dn-ns db f4 tc">
            <span class="f7 gray db mb1">
              {{ $t('dashboard.one_on_ones_created_on') }}
            </span>
            {{ entry.happened_at }}
          </div>
        </div>

        <div class="pl3 pb3 pr3">
          <talking-point :entry="entry" />

          <action-item :entry="entry" />

          <note :entry="entry" />
        </div>

        <!-- cta -->
        <div v-if="!entry.happened" class="pa3 title-section bb bb-gray tc">
          <loading-button :classes="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :emoji="'✅'" :text="$t('dashboard.one_on_ones_mark_happened')" :cypress-selector="'entry-mark-as-happened-button'"
                          @click="markAsHappened()"
          />

          <p class="mb1 f7 gray">{{ $t('dashboard.one_on_ones_mark_happened_note_1') }}</p>
          <p class="mv0 f7 gray">{{ $t('dashboard.one_on_ones_mark_happened_note_2') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import LoadingButton from '@/Shared/LoadingButton';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import TalkingPoint from '@/Pages/Dashboard/OneOnOnes/Partials/TalkingPoint';
import ActionItem from '@/Pages/Dashboard/OneOnOnes/Partials/ActionItem';
import Note from '@/Pages/Dashboard/OneOnOnes/Partials/Note';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Help,
    LoadingButton,
    SmallNameAndAvatar,
    TalkingPoint,
    ActionItem,
    Note,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    entry: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
    };
  },
};
</script>
