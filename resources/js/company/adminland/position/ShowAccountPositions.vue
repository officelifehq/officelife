<style scoped>
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
            {{ $t('app.breadcrumb_account_manage_positions') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.positions_title', { company: company.name}) }}
          </h2>
          <p class="relative">
            <span class="dib mb3 di-l">{{ $tc('account.employees_number_employees', positions.length, { company: company.name, count: positions.length}) }}</span>
            <a :href="'/' + company.id + '/account/employees/create'" class="btn-primary br3 ph3 pv2 white no-underline tc absolute-l relative dib-l db right-0" data-cy="add-employee-button">{{ $t('account.employees_cta') }}</a>
          </p>
          <div class="">
            <form @submit.prevent="search">
              <div class="relative pv2 ph2 bb">
                <label for="title">Position name</label>
                <input id="title" type="text" name="title"
                      :placeholder="'Filter the list'" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required
                      @keydown.esc="toggleModal"
                />
                <div class="">
                  <a class="btn br3 ph3 pv2 white no-underline tc absolute-l relative dib-l db right-0" data-cy="add-employee-button">{{ $t('account.employees_cta') }}</a>
                  <a :href="'/' + company.id + '/account/employees/create'" class="btn-primary br3 ph3 pv2 white no-underline tc absolute-l relative dib-l db right-0" data-cy="add-employee-button">{{ $t('account.employees_cta') }}</a>
                </div>
              </div>
            </form>
          </div>
          <ul class="list pl0 mt0 center ba br2">
            <li class="pa2 bb">
              Director of product management international
              <span>3 people</span>
              <ul class="list pa0 ma0 di fr">
                <li class="di"><a href="">Rename</a></li>
                <li class="di"><a href="">Delete</a></li>
              </ul>
            </li>
            <li class="pa2">
              Director of product management international
              <span>3 people</span>
            </li>
            <li>
              Director of product management international
              <span>3 people</span>
            </li>
            <li
              v-for="position in positions" :key="position.id"
              class=""
            >
              {{ position.title }}
            </li>
          </ul>
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
    user: {
      type: Object,
      default: null,
    },
    positions: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      form: {
        title: null,
        errors: [],
      },
    }
  },

  methods: {
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
