<style lang="scss" scoped>
.employee-item {
  &:first-child {
    border-top-width: 1px;
    border-top-style: solid;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
  }

  &:not(:last-child) {
    border-bottom: 0;
  }
}

.progress-bar {
	border-radius: 3px;
	overflow: hidden;
  width: 100%;

	span {
		display: block;
	}
}

.bar {
  background: rgba(0,0,0,0.075);
  width: 100%
}

.progress {
  animation: loader 8s cubic-bezier(.45,.05,.55,.95) forwards;
  background: #75b800;
  color: #fff;
  padding: 5px;
}

.gender-item:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
        :root="$t('app.breadcrumb_dashboard')"
        :previous-url="'/' + $page.props.auth.company.id + '/company/hr'"
        :previous="$t('app.breadcrumb_hr')"
      >
        {{ $t('app.breadcrumb_hr_position') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="cf mw7 center">
        <h2 class="normal tc mb5">
          {{ data.position.title }}
        </h2>

        <!-- left section - employees -->
        <div class="fl w-50-l w-100">
          <span class="db fw5 mb2 relative">
            <span class="mr1">
              👬
            </span> {{ $t('company.hr_positions_show_employees') }}
          </span>

          <div class="br3 bg-white box z-1 pa3 relative">
            <ul class="list pl0 ma0">
              <li v-for="employee in employees" :key="employee.id" class="relative ba bb-gray bb-gray-hover pa3 employee-item flex items-center">
                <avatar :avatar="employee.avatar" :size="30" :class="'avatar br-100'" />

                <div class="ml3 mw-100">
                  <inertia-link :href="employee.url" class="mb1 dib">{{ employee.name }}</inertia-link>

                  <ul v-if="employee.teams.length != 0" class="list pl0 mb2 f7">
                    <li class="mr1 di">{{ $t('company.skills_show_in_team') }}</li>
                    <li v-for="team in employee.teams" :key="team.id" class="di pointer mr2">
                      <inertia-link :href="team.url">{{ team.name }}</inertia-link>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- right section -->
        <div class="fl w-50-l w-100 pl4-l">
          <span class="db fw5 mb2 relative">
            <span class="mr1">
              👩‍🦰
            </span> {{ $t('company.hr_positions_show_gender') }}
          </span>

          <div class="br3 bg-white box z-1 pa3 relative">
            <div v-for="pronoun in pronouns" :key="pronoun.id" class="pronoun-item mb3">
              <div class="flex justify-between mb2">
                <span class="fw5">
                  {{ pronoun.label }}
                </span>
                <span class="gray f6">
                  {{ pronoun.percent }}%
                </span>
              </div>
              <div class="progress-bar mb2">
                <span class="bar">
                  <span class="progress" :style="'width: ' + pronoun.percent + '%;'"></span>
                </span>
              </div>
            </div>
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

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
  },

  props: {
    data: {
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
      employees: Array,
      pronouns: Array,
    };
  },

  created: function() {
    this.employees = this.data.employees;
    this.pronouns = this.data.pronouns;
  },
};

</script>
