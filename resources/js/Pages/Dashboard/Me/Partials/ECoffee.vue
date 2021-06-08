<style lang="scss" scoped>
.avatars {
  top: 25px;
  right: 30px;
}

.avatar {
  left: 1px;
  top: 5px;
  width: 55px;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        ☕️
      </span> {{ $t('dashboard.e_coffee_title') }}

      <help :url="$page.props.help_links.ecoffee" />
    </div>

    <!-- case: the match hasn't taken place yet -->
    <div v-if="!localeCoffee.happened" class="cf mw7 center br3 mb3 bg-white box relative pa3">
      <p class="f4 fw4 mt1 mb2 lh-copy" data-cy="e-coffee-matched-with-name">{{ $t('dashboard.e_coffee_match_title', { name: localeCoffee.other_employee.name }) }}</p>
      <p class="mt1 lh-copy mr6">{{ $t('dashboard.e_coffee_match_desc', { firstname: localeCoffee.other_employee.first_name }) }}</p>

      <p v-if="localeCoffee.other_employee.position" class="mt0 mb2 f6 gray">
        {{ $t('dashboard.e_coffee_match_desc_position', { firstname: localeCoffee.other_employee.first_name, position: localeCoffee.other_employee.position }) }}
      </p>
      <p v-if="localeCoffee.other_employee.teams" class="mt0 mb3 f6 gray">
        {{ $t('dashboard.e_coffee_match_desc_teams', { firstname: localeCoffee.other_employee.first_name }) }}
        <ul v-if="localeCoffee.other_employee.teams" class="list di ma0 pl0">
          <li v-for="team in localeCoffee.other_employee.teams" :key="team.id" class="di">
            <inertia-link :href="team.url">{{ team.name }}</inertia-link>
          </li>
        </ul>
      </p>

      <!-- CTA -->
      <form v-if="! localeCoffee.happened" @submit.prevent="markAsHappened">
        <loading-button :class="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('dashboard.e_coffee_match_cta')" :cypress-selector="'mark-ecoffee-as-happened'" />
      </form>

      <!-- avatars -->
      <div class="absolute-ns avatars tc">
        <img class="avatar br-100" loading="lazy" src="/img/streamline-icon-coffee-idea-sparking@140x140.png" alt="avatar" />
        <avatar :avatar="localeCoffee.other_employee.avatar" :size="55" :class="'avatar br-100'" />
      </div>
    </div>

    <!-- case the match has taken place -->
    <div v-if="localeCoffee.happened" data-cy="ecoffee-already-participated" class="cf mw7 center br3 mb3 bg-white box relative pa3">
      <p class="f4 fw4 mt1 mb2 lh-copy">{{ $t('dashboard.e_coffee_match_happened_title', { name: localeCoffee.other_employee.name }) }}</p>
      <p class="mt1 mb0 lh-copy">👍 {{ $t('dashboard.e_coffee_match_happened_desc') }}</p>

      <!-- avatars -->
      <div class="absolute-ns avatars tc">
        <img class="avatar br-100" loading="lazy" src="/img/streamline-icon-coffee-idea-sparking@140x140.png" alt="avatar" />
        <avatar :avatar="localeCoffee.other_employee.avatar" :size="55" :class="'avatar br-100'" />
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Avatar,
    Help,
    LoadingButton,
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

  data() {
    return {
      loadingState: null,
      localeCoffee: null,
    };
  },

  created() {
    this.localeCoffee = this.eCoffee;
  },

  methods: {
    markAsHappened() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/dashboard/ecoffee/${this.localeCoffee.e_coffee_id}/${this.localeCoffee.id}`)
        .then(response => {
          this.loadingState = null;
          this.localeCoffee.happened = true;
        })
        .catch(error => {
          this.loadingState = null;
        });
    },
  }
};
</script>
