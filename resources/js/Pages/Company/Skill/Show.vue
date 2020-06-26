<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 65px;
}

.employee:not(:last-child) {
  margin-bottom: 15px;
}

.small-skill {
  font-size: 12px;
}

.skill {
  border: 1px solid transparent;
  border-radius: 2em;
  padding: 3px 10px;
  line-height: 22px;
  color: #0366d6;
  background-color: #f1f8ff;

  &:hover {
    background-color: #def;
  }

  a:hover {
    border-bottom: 0;
  }

  span {
    border-radius: 100%;
    background-color: #c8dcf0;
    padding: 0px 5px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/company/skills'">{{ $t('app.breadcrumb_company_skills') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_company_skills_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1 pa3">
        <h2 class="tc relative fw5 mb4">
          <span class="db lh-copy mb3">
            {{ $t('company.skills_show_title') }}
          </span> <span class="skill f3">
            {{ skill.name }}
          </span>

          <help :url="$page.help_links.skills" :datacy="'help-icon-skills'" :top="'1px'" />
        </h2>

        <ul class="list pl0 mb0">
          <li v-for="employee in employees" :key="employee.id" :data-cy="'employee-' + employee.id" class="relative ba bb-gray bb-gray-hover pa3 br3 flex items-center employee">
            <img loading="lazy" :src="employee.avatar" class="br-100 avatar" alt="avatar" />

            <div class="ml3 mw-100">
              <inertia-link :href="employee.url">{{ employee.name }}</inertia-link>

              <!-- position -->
              <span v-if="employee.position !== null" class="title db f7 mt1 mb1">
                {{ employee.position.title }}
              </span>
              <span v-else class="title db f7 mt1 mb1">
                {{ $t('app.no_position_defined') }}
              </span>

              <ul v-if="employee.teams.length != 0" class="list pl0 mb2 f7">
                <li class="mr1 di">{{ $t('company.skills_show_in_team') }}</li>
                <li v-for="team in employee.teams" :key="team.id" :data-cy="'employee-' + employee.id" class="di pointer mr2">
                  <inertia-link :href="team.url">{{ team.name }}</inertia-link>
                </li>
              </ul>

              <ul class="list pl0">
                <li v-for="skill in employee.skills" :key="skill.id" :data-cy="'employee-' + employee.id" class="skill small-skill di pointer mr2">
                  <inertia-link :href="skill.url" class="no-underline bb-0">{{ skill.name }}</inertia-link>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Help,
  },

  props: {
    skill: {
      type: Object,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
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
