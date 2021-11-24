<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

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

.top-1 {
  top: 0.3rem;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        🚨
      </span> {{ $t('dashboard.manager_discipline_cases_index_title') }}

      <help :url="$page.props.help_links.discipline" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <img loading="lazy" src="/img/streamline-icon-prisoner-2-5@100x100.png" width="90" alt="meeting" class="absolute-ns di-ns dn top-1 left-1" />

      <ul class="pl6-ns pl3 pb3 pt3 pr3 ma0">
        <li v-for="disciplineCase in cases" :key="disciplineCase.id" class="flex justify-between items-center br bl bb bb-gray bb-gray-hover pa3 entry-item">
          <!-- identity -->
          <div>
            <span class="pl3 db relative team-member">
              <avatar :avatar="disciplineCase.employee.avatar" :size="35" :class="'br-100 absolute avatar'" />
              <inertia-link :href="disciplineCase.employee.url" class="mb2">{{ disciplineCase.employee.name }}</inertia-link>
              <span class="title db f7 mt1">
                {{ disciplineCase.employee.position }}
              </span>
            </span>
          </div>

          <!-- call to action -->
          <div class="tr">
            <inertia-link :href="disciplineCase.url" class="btn dib-l db">{{ $t('app.view') }}</inertia-link>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Help,
    Avatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    cases: {
      type: Array,
      default: null,
    },
  },
};
</script>
