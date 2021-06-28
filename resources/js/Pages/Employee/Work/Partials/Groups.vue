<style lang="scss" scoped>
.groups-list:last-child {
  border-bottom: 0;
}

.groups-list:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.groups-list:last-child:hover {
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
}
</style>

<template>
  <div>
    <span class="db fw5 mb2">
      <span class="mr1">
        👷
      </span> {{ $t('employee.groups_title', { name: employee.name }) }}

      <help :url="$page.props.help_links.team_recent_ship" :top="'2px'" />
    </span>

    <div class="mb4 bg-white box cf">
      <!-- list of groups -->
      <ul v-if="groups.length > 0" class="list pl0 ma0">
        <li v-for="group in groups" :key="group.id" class="w-100 pa3 mr3 flex justify-between items-center bb bb-gray groups-list">
          <div>
            <p class="ma0 lh-copy relative">
              <inertia-link :href="group.url">{{ group.name }}</inertia-link>
            </p>
            <div v-if="group.mission" class="mt2 lh-copy f6 parsed-content" v-html="group.mission"></div>
          </div>

          <!-- members -->
          <span v-if="group.preview_members" class="ma0 mb0 f7 grey">
            <div class="flex items-center relative tr">
              <avatar v-for="member in group.preview_members" :key="member.id" :avatar="member.avatar" :url="member.url" :size="25"
                      :class="'br-100 small-avatar'"
              />
              <div v-if="group.remaining_members_count > 0" class="pl2 f7 more-members relative gray">
                + {{ group.remaining_members_count }}
              </div>
            </div>
          </span>
        </li>
      </ul>

      <!-- blank state -->
      <div v-if="groups.length == 0" class="pa3">
        <p class="mb0 mt0 lh-copy f6">{{ $t('employee.groups_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help
  },

  props: {
    groups: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
  },
};

</script>
