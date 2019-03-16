<style scoped>
.list-employees ul {
  padding-left: 43px;
}

.list-employees li:last-child {
  margin-bottom: 0;
}

.avatar {
  top: 1px;
  left: -44px;
  width: 35px;
}

.popupmenu {
  border: 1px solid rgba(27,31,35,.15);
  box-shadow: 0 3px 12px rgba(27,31,35,.15);
  right: 22px;
  top: 46px;
}

.popupmenu:after,
.popupmenu:before {
  content: "";
  display: inline-block;
  position: absolute;
}

.popupmenu:after {
  border: 7px solid transparent;
  border-bottom-color: #fff;
  left: auto;
  right: 10px;
  top: -14px;
}

.popupmenu:before {
  border: 8px solid transparent;
  border-bottom-color: rgba(27,31,35,.15);
  left: auto;
  right: 9px;
  top: -16px;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="tc db b mb2">Place in the company</span>
    <img @click.prevent="toggleModals()" src="/img/plus_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer">

    <!-- Menu to choose from -->
    <div class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster" v-if="modal == 'menu'">
      <ul class="list ma0 pa0">
        <li class="pv2">
          <a @click.prevent="displayManagerModal()">Add a manager</a>
        </li>
        <li class="pv2">
          Add a direct report
        </li>
      </ul>
    </div>

    <!-- ADD MANAGER -->
    <div class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster" v-if="modal == 'manager'">
      <form @submit.prevent="search">
        <div class="mb3 relative">
          <p>Find an employee to become Regis's manager</p>
          <input type="text" v-model="form.searchTerm" v-on:keyup="search" id="search" name="search" ref="search" :placeholder="'Enter the first letters of the name'" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" @keydown.esc="toggleModals()" required>
        </div>
      </form>
      <ul class="pl0 list ma0">
        <li class="b mb3">
          <span class="f6 mb2 dib">{{ $t('app.header_search_employees') }}</span>
          <ul class="list ma0 pl0" v-if="searchManagers.length > 0">
            <li v-for="manager in searchManagers" :key="manager.id" class="bb relative">
              {{ manager.name }}
              <a @click.prevent="assignManager(manager)" class="absolute right-0 pointer">Choose</a>
            </li>
          </ul>
          <div v-else class="silver">
            {{ $t('app.header_search_no_employee_found') }}
          </div>
        </li>
      </ul>
    </div>

    <div class="br3 bg-white box z-1 pa3 list-employees">
      <!-- Blank state -->

      <!-- Managers -->
      <div v-show="managers.length != 0">
        <p class="mt0 mb3">Manager</p>
        <ul class="list mv0">
          <li class="mb3 relative" v-for="manager in managers" :key="manager.id">
            <img :src="manager.avatar" class="br-100 absolute avatar">
            <a :href="'/' + company.id + '/employees/' + manager.id" class="mb2">{{ manager.name }}</a>
            <span class="title db f7 mt1">Director of Management</span>
          </li>
        </ul>
      </div>

      <!-- Direct reports -->
      <div v-show="directReports.length != 0">
        <p class="mt3 mb3">Direct reports</p>
        <ul class="list mv0">
          <li class="mb3 relative" v-for="directReport in directReports" :key="directReport.id">
            <img :src="directReport.avatar" class="br-100 absolute avatar">
            <a :href="'/' + company.id + '/employees/' + directReport.id" class="mb2">{{ directReport.name }}</a>
            <span class="title db f7 mt1">Director of Management</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: [
    'company',
    'employee',
    'managers',
    'directReports',
  ],

  data() {
    return {
      modal: 'hide',
      searchManagers: [],
      form: {
        searchTerm: null,
        errors: [],
      },
    };
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

  methods: {
    toggleModals() {
      if (this.modal == 'hide') {
        this.modal = 'menu'
      } else {
        this.modal = 'hide'
      }
    },

    displayManagerModal() {
      this.modal = 'manager'
      this.$nextTick(() => {
        this.$refs.search.focus()
      })
    },

    search: _.debounce(
      function() {
        axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/search/managers', this.form)
          .then(response => {
            this.searchManagers = response.data.data
          })
          .catch(error => {
            this.form.errors = _.flatten(_.toArray(error.response.data))
          })
      }, 500),

    assignManager(manager) {
      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/assignManager', manager)
          .then(response => {
            this.managers.push(response.data.data)
            this.modal = 'hide'
          })
          .catch(error => {
            this.form.errors = _.flatten(_.toArray(error.response.data))
          })
    }
  }
}

</script>
