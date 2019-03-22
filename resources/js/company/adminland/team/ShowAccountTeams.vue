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
          <li class="di"><a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a></li>
          <li class="di"><a :href="'/' + company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</a></li>
          <li class="di">{{ $t('app.breadcrumb_account_manage_teams') }}</li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">

        <!-- WHEN THERE ARE TEAMS -->
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">{{ $t('account.teams_title', { company: company.name}) }}</h2>

          <!-- ADD TEAM -->
          <div class="relative">
            <span class="dib mb3 di-l">{{ $tc('account.teams_number_teams', teams.length, { company: company.name, count: teams.length}) }}</span>
            <a @click.prevent="modal = !modal" data-cy="add-team-button" class="btn-primary pointer br3 ph3 pv2 white no-underline tc absolute-l relative dib-l db right-0">{{ $t('account.teams_cta') }}</a>

            <div class="absolute add-modal br2 bg-white z-max tl pv2 ph3 bounceIn faster" v-if="modal == true">
              <errors :errors="form.errors"></errors>

              <form @submit.prevent="submit">
                <div class="mb3">
                  <label class="db fw4 lh-copy f6" for="name">{{ $t('account.team_new_name') }}</label>
                  <input type="text" id="name" name="name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" v-model="form.name" required>
                </div>
                <div class="mv2">
                  <div class="flex-ns justify-between">
                    <div>
                      <a @click="modal = false" class="btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3">{{ $t('app.cancel') }}</a>
                    </div>
                    <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" data-cy="submit-add-team-button"></loading-button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- LIST OF TEAMS -->
          <ul class="list pl0 mt0 center" v-show="teams.length != 0">
            <li
              v-for="team in teams" v-bind:key="team.id"
              class="flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10">
                <div class="flex-auto">
                  <span class="db b">{{ team.name }}</span>
                  <ul class="f6 list pl0">
                    <li class="di pr2"><a :href="'/account/teams/' + team.id">{{ $t('app.view') }}</a></li>
                    <li class="di pr2"><a :href="'/teams/' + team.id + '/lock'">{{ $t('app.rename') }}</a></li>
                    <li class="di"><a :href="'/account/teams/' + team.id + '/destroy'">{{ $t('app.delete') }}</a></li>
                  </ul>
                </div>
            </li>
          </ul>
        </div>

        <!-- NO TEAMS -->
        <div class="pa3" v-show="teams.length == 0">
          <p class="tc measure center mb4">{{ $t('account.teams_blank') }}</p>
          <img class="db center mb4" srcset="/img/company/account/blank-team-1x.png,
                                        /img/company/account/blank-team-2x.png 2x">
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: [
    'company',
    'teams',
    'user',
  ],

  data() {
    return {
      modal: false,
      form: {
        name: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    window.addEventListener('click', this.close);
  },

  beforeDestroy() {
    window.removeEventListener('click', this.close);
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 5000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      })
      localStorage.clear()
    }
  },

  computed: {
    sortedArray: function() {
      function compare(a, b) {

      }

      return this.teams.sort(compare);
    }
  },

  methods: {
    close(e) {
      if (!this.$el.contains(e.target)) {
        this.modal = false;
      }
    },

    submit() {
      this.loadingState = 'loading'

      axios.post('/' + this.company.id + '/account/teams', this.form)
        .then(response => {
          this.$snotify.success('The team has been created', {
            timeout: 5000,
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
