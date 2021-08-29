<style lang="scss" scoped>
.oneonone-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.oneonone-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'"
                  :previous="employee.name"
      >
        {{ $t('app.breadcrumb_employee_one_on_ones') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 mt2 mb4 center tc normal">
          {{ $t('employee.one_on_one_title') }}

          <help :url="$page.props.help_links.one_on_ones" :top="'0px'" />
        </h2>

        <div class="flex-ns justify-around dn">
          <div>
            <img loading="lazy" src="/img/streamline-icon-work-desk-sofa-1-3@140x140.png" width="60" alt="one on ones" class="db center mb4" />
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ statistics.numberOfOccurrencesThisYear }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.one_on_one_index_stat_number_of_entries') }}</p>
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ statistics.averageTimeBetween }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.one_on_one_index_stat_avg_number') }}</p>
          </div>
        </div>

        <div v-if="oneOnOnes.length > 0">
          <ul class="list mt0 mb0 pb3 pr3 pl3">
            <li v-for="entry in oneOnOnes" :key="entry.id" class="flex items-center justify-between oneonone-item br bl bb bb-gray bb-gray-hover pa3 w-100">
              <div>
                <inertia-link :href="entry.url" :data-cy="'entry-list-item-' + entry.id" class="dib mb2">{{ entry.happened_at }}</inertia-link>
                <ul class="list pl0 f7 gray">
                  <li class="di mr2"><span>🗣</span> {{ $tc('employee.one_on_one_index_item_talking_point', entry.number_of_talking_points, { count: entry.number_of_talking_points}) }}</li>
                  <li class="di mr2"><span>🤜</span> {{ $tc('employee.one_on_one_index_item_action_item', entry.number_of_action_items, { count: entry.number_of_action_items}) }}</li>
                  <li class="di"><span>📝</span> {{ $tc('employee.one_on_one_index_item_note', entry.number_of_notes, { count: entry.number_of_notes}) }}</li>
                </ul>
              </div>

              <div class="tr">
                <span class="db f6">with
                  <small-name-and-avatar
                    :name="entry.manager.name"
                    :avatar="entry.manager.avatar"
                    :url="entry.manager.url"
                    :class="'f4 fw4'"
                    :top="'0px'"
                    :margin-between-name-avatar="'29px'"
                  />
                </span>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    SmallNameAndAvatar,
    Help,
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
    oneOnOnes: {
      type: Array,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
  },
};
</script>
