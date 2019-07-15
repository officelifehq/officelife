<style scoped>
</style>

<template>
  <div>
    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2 class="mt0 fw5 f4">
          👨‍🌾 What have you done today?
        </h2>
        <p v-show="!showEditor" class="db">
          Let your coworkers know what you've been up to today.
        </p>
        <p v-show="!showEditor" class="ma0">
          <a class="btn btn-secondary dib" @click.prevent="showEditor = true">Log your work</a>
        </p>

        <!-- Shows the editor -->
        <div v-show="showEditor" class="">
          <editor @update="console.log('dsaf')" />
          <p class="db lh-copy f6">
            👋 Your manager and your team members (if you are assigned to a team) will be able to read this status. Also, you won't be able to edit this status once it’s submitted.
          </p>
          <p>
            <a href="" class="btn primary mr2">Submit</a>
            <a href="">Cancel</a>
          </p>
        </div>
      </div>
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2 class="mt0 fw5 f4">
          👨‍🌾 What have you done today?
        </h2>
        <p class="db">
          Let your coworkers know what you've been up to today. <a href="">Read your previous entries</a>
        </p>
        <p class="ma0">
          <a href="" class="btn btn-secondary dib">Log your work</a>
        </p>
      </div>
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2 class="mt0 fw5 f4">
          👨‍🌾 What have you done today?
        </h2>
        <p class="db ma0">
          You have already logged your work today. <a href="">Read your previous entries</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      showEditor: false,
    };
  },

  methods: {
    store() {
      axios.post('/' + this.company.id + '/dashboard/worklog', position)
        .then(response => {
          this.$snotify.success(this.$t('employee.position_modal_assign_success'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

          this.title = response.data.data.position.title;
          this.updatedEmployee = response.data.data;
          this.modal = false;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
