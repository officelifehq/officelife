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
    <img @click.prevent="modal = !modal" src="/img/plus_button.svg" class="box-plus-button absolute br-100 pa2 bg-white pointer">

    <!-- Menu to choose from -->
    <div class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster" v-if="modal == true">
      <ul class="list ma0 pa0">
        <li class="pv2">
          Add a manager
        </li>
        <li class="pv2">
          Add a direct report
        </li>
      </ul>
    </div>

    <!-- ADD MANAGER -->
    <div class="relative">
    </div>

    <div class="br3 bg-white box z-1 pa3 list-employees">
      <!-- Blank state -->

      <!-- Managers -->
      <div v-show="managers.length != 0">
        <p class="mt0 mb3">Manager</p>
        <ul class="list mv0">
          <li class="mb3 relative">
            <img :src="employee.avatar" class="br-100 absolute avatar">
            <a :href="'/' + company.id + '/employees/' + 1" class="mb2">Jim Halpert</a>
            <span class="title db f7 mt1">Director of Management</span>
          </li>
          <li class="mb3 relative">
            <img :src="employee.avatar" class="br-100 absolute avatar">
            <a :href="'/' + company.id + '/employees/' + 1" class="mb2">Jim Halpert</a>
            <span class="title db f7 mt1">Director of Management</span>
          </li>
        </ul>
        <p class="mt3 mb3">Direct reports</p>
        <ul class="list mv0">
          <li class="mb3 relative">
            <img :src="employee.avatar" class="br-100 absolute avatar">
            <a :href="'/' + company.id + '/employees/' + 1" class="mb2">Jim Halpert</a>
            <span class="title db f7 mt1">Director of Management</span>
          </li>
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

  data() {
    return {
      modal: false,
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
}

</script>
