<style lang="scss" scoped>
.ecoffee-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.ecoffee-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.avatar {
  top: 1px;
  left: 0;
  width: 35px;
}

.employee-name,
.position {
  padding-left: 42px;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        ☕️
      </span> {{ $t('employee.e_coffee_title') }}

      <help :url="$page.props.help_links.ecoffee" />
    </span>

    <div class="br3 bg-white box z-1">
      <ul v-if="ecoffees.eCoffees.length > 0" data-cy="e-coffee-list" class="list pl0 ma0">
        <li v-for="ecoffee in ecoffees.eCoffees" :key="ecoffee.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between ecoffee-item" :data-cy="'ecoffee-title-' + ecoffee.id">
          <div class="mb1 relative">
            <avatar :avatar="ecoffee.with_employee.avatar" :size="35" :class="'br-100 absolute avatar'" />
            <span class="employee-name db">
              <inertia-link :href="ecoffee.with_employee.url" class="mb2">{{ ecoffee.with_employee.name }}</inertia-link>
            </span>
            <span v-if="ecoffee.with_employee.position" class="position f7 mt1">{{ ecoffee.with_employee.position }}</span>
          </div>
          <span class="ma0 mb2 f7 grey">
            <span class="db mb1">{{ $t('employee.e_coffee_week') }}</span>
            {{ ecoffee.ecoffee.started_at }} → {{ ecoffee.ecoffee.ended_at }}
          </span>
        </li>
        <li class="ph3 pv2 tc f6">
          <inertia-link :href="ecoffees.view_all_url" data-cy="view-all-ecoffees">{{ $t('employee.e_coffee_view_all') }}</inertia-link>
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
    ecoffees: {
      type: Object,
      default: null,
    },
  },
};
</script>
