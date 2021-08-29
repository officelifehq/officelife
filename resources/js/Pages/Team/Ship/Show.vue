<style lang="scss" scoped>
.ship-avatar {
  left: 1px;
  text-decoration: none;
  border-bottom: none;
}

.name {
  margin-left: 38px;
  top: 6px;
}

.graffiti {
  background-image: url('/img/confetti.png');
  background-repeat: repeat-x;
  background-position: top;
}

.details li:not(:last-child):after {
  content: "•";
  padding-left: 6px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/teams/' + team.id + '/ships'"
                  :previous="$t('app.breadcrumb_team_show_recent_ships')"
      >
        {{ $t('app.breadcrumb_team_show_recent_ship_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1 graffiti">
        <div class="pa5-ns pa3 center">
          <h2 class="tc normal mb2 mt6" data-cy="recent-ship-title">
            {{ ship.title }}
          </h2>

          <!-- details -->
          <ul class="tc mb4 f6 list pl0 details">
            <li class="di">{{ $t('team.recent_ship_show_date', { date: ship.created_at, team: team.name}) }}</li>
            <!-- delete mode -->
            <li v-if="deleteMode" class="di">
              {{ $t('app.sure') }}
              <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + ship.id" @click.prevent="destroy(ship.id)">
                {{ $t('app.yes') }}
              </a>
              <a class="pointer" :data-cy="'list-delete-cancel-button-' + ship.id" @click.prevent="deleteMode = false">
                {{ $t('app.no') }}
              </a>
            </li>
            <li v-else class="di">
              <a v-if="atLeastHR" class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'list-delete-button-' + ship.id" @click.prevent="deleteMode = true">
                {{ $t('app.delete') }}
              </a>
            </li>
          </ul>

          <!-- description -->
          <div v-if="ship.description" class="lh-copy ma0 parsed-content mb4" data-cy="recent-ship-description" v-html="ship.description">
          </div>

          <!-- list of employees -->
          <div v-if="ship.employees">
            <p class="f6 gray mb2">{{ $t('team.recent_ship_show_members') }}</p>
            <ul class="list ma0 pa0">
              <li v-for="employee in ship.employees" :key="employee.id" class="mb3">
                <span class="pl3 db relative team-member">
                  <avatar :avatar="employee.avatar" :class="'ship-avatar br-100 absolute'" :url="employee.url" :size="44" />

                  <div class="name relative">
                    <inertia-link :href="employee.url" class="mb2" :data-cy="'ship-list-employee-' + employee.id">{{ employee.name }}</inertia-link>

                    <!-- position -->
                    <span v-if="employee.position" class="title db f7 mt1">
                      {{ employee.position.title }}
                    </span>
                    <span v-else class="title db f7 mt1">
                      {{ $t('app.no_position_defined') }}
                    </span>
                  </div>
                </span>
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    team: {
      type: Object,
      default: null,
    },
    ship: {
      type: Object,
      default: null,
    },
    userBelongsToTheTeam: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
      deleteMode: false,
    };
  },

  computed: {
    atLeastHR() {
      return this.$page.props.auth.employee.permission_level <= 200;
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy(id) {
      axios.delete(`${this.$page.props.auth.company.id}/teams/${this.team.id}/ships/${id}`)
        .then(response => {
          localStorage.success = this.$t('team.recent_ship_deletion_success');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/teams/' + this.team.id + '/ships');
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
