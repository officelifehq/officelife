<style scoped>
  .add-modal {
    border: 1px solid rgba(27,31,35,.15);
    box-shadow: 0 3px 12px rgba(27,31,35,.15);
    top: 36px;
    right: 0;
  }

  .add-modal:after,
  .add-modal:before {
    content: "";
    display: inline-block;
    position: absolute;
  }

  .add-modal:after {
    border: 7px solid transparent;
    border-bottom-color: #fff;
    left: auto;
    right: 10px;
    top: -14px;
  }

  .add-modal:before {
    border: 8px solid transparent;
    border-bottom-color: rgba(27,31,35,.15);
    left: auto;
    right: 9px;
    top: -16px;
  }
</style>

<template>
  <layout title="Home" :user="user">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</a>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_teams') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <!-- WHEN THERE ARE TEAMS -->
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.teams_title', { company: company.name}) }}
          </h2>

          <!-- ADD TEAM -->
          <div class="relative">
            <span v-show="teams.length != 0" class="dib mb3 di-l">{{ $tc('account.teams_number_teams', teams.length, { company: company.name, count: teams.length}) }}</span>
            <a data-cy="add-team-button" class="btn primary tc absolute-l relative dib-l db right-0" @click.prevent="modal = !modal">{{ $t('account.teams_cta') }}</a>

            <div v-if="modal == true" class="absolute add-modal br2 bg-white z-max tl pv2 ph3 bounceIn faster">
              <errors :errors="form.errors" />

              <form @submit.prevent="submit">
                <div class="mb3">
                  <label class="db fw4 lh-copy f6" for="name">{{ $t('account.team_new_name') }}</label>
                  <input id="name" v-model="form.name" type="text" name="name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"
                         required
                  />
                </div>
                <div class="mv2">
                  <div class="flex-ns justify-between">
                    <div>
                      <a class="btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="modal = false">{{ $t('app.cancel') }}</a>
                    </div>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" data-cy="submit-add-team-button" />
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- LIST OF TEAMS -->
          <ul v-show="teams.length != 0" class="list pl0 mt0 center">
            <li
              v-for="team in teams" :key="team.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
            >
              <div class="flex-auto">
                <span class="db b">{{ team.name }}</span>
                <ul class="f6 list pl0">
                  <li class="di pr2">
                    <a :href="'/' + company.id + '/teams/' + team.id">{{ $t('app.view') }}</a>
                  </li>
                  <li class="di pr2">
                    <a :href="'/' + company.id + '/teams/' + team.id + '/lock'">{{ $t('app.rename') }}</a>
                  </li>
                  <li class="di">
                    <a :href="'/' + company.id + '/teams/' + team.id + '/destroy'">{{ $t('app.delete') }}</a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>

        <!-- NO TEAMS -->
        <div v-show="teams.length == 0" class="pa3">
          <p class="tc measure center mb4 lh-copy">
            {{ $t('account.teams_blank') }}
          </p>
          <img class="db center mb4" srcset="/img/company/account/blank-team-1x.png,
                                        /img/company/account/blank-team-2x.png 2x"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: {
    company: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      form: {
        name: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    }
  },

  created() {
    window.addEventListener('click', this.close)
  },

  beforeDestroy() {
    window.removeEventListener('click', this.close)
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      })
      localStorage.clear()
    }
  },

  methods: {
    close(e) {
      if (!this.$el.contains(e.target)) {
        this.modal = false
      }
    },

    submit() {
      this.loadingState = 'loading'

      axios.post('/' + this.company.id + '/account/teams', this.form)
        .then(response => {
          this.$snotify.success('The team has been created', {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          })

          this.loadingState = null
          this.form.name = null
          this.modal = false
          this.teams.push(response.data.data)
        })
        .catch(error => {
          this.loadingState = null
          this.form.errors = _.flatten(_.toArray(error.response.data))
        })
    },
  }
}

</script>
