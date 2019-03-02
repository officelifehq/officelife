<style scoped>
ul {
  padding-left: 43px;
}

li:last-child {
  margin-bottom: 0;
}

.avatar {
  top: 1px;
  left: -44px;
  width: 35px;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="tc db b mb2">Place in the company</span>
    <img src="/img/plus_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer">

    <!-- ADD MANAGER -->
    <div class="relative">
      <span class="dib mb3 di-l">{{ company.name }} have {{ teams.length }} teams.</span>
      <a @click.prevent="modal = !modal" class="btn-primary pointer br3 ph3 pv2 white no-underline tc absolute-l relative dib-l db right-0">Add a team</a>

      <div class="absolute add-modal br2 bg-white z-max tl pv2 ph3 bounceIn faster" v-if="modal == true">
        <errors :errors="form.errors"></errors>

        <form @submit.prevent="submit">
          <div class="mb3">
            <label class="db fw4 lh-copy f6" for="name">Name of the team</label>
            <input type="text" id="name" name="name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" v-model="form.name" required>
          </div>
          <div class="mv2">
            <div class="flex-ns justify-between">
              <div>
                <a @click="modal = false" class="btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3">app.cancel</a>
              </div>
              <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="'Add'"></loading-button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="br3 bg-white box z-1 pa3">
      <!-- Managers -->
      <div v-show="managers.length != 0">
        <p class="mt0 mb3">Manager</p>
        <ul class="list mv0">
          <li class="mb3 relative">
            <img :src="employee.avatar" class="br-100 absolute avatar">
            <a :href="'/' + company.id + '/employees/' + 1" class="mb2">Jim Halpert</a>
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
}

</script>
