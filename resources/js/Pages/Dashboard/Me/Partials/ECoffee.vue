<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        ☕️
      </span> E-coffee

      <help :url="$page.props.help_links.one_on_ones" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
      <p class="f4 fw4 mt1">You have been matched with {{ eCoffee.other_employee.name }}.</p>
      <p class="mt0 mb2">{{ eCoffee.other_employee.first_name }} works as {{ eCoffee.other_employee.position }}.</p>
      <p class="mt0 mb0">
        {{ eCoffee.other_employee.first_name }} is in the following team(s):
        <ul v-if="eCoffee.other_employee.teams" class="list di ma0 pl0">
          <li v-for="team in eCoffee.other_employee.teams" :key="team.id" class="di">
            <inertia-link :href="team.url">{{ team.name }}</inertia-link>
          </li>
        </ul>
      </p>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    eCoffee: {
      type: Object,
      default: null,
    },
  },
};
</script>
