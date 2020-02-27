<style scoped>
.popupmenu {
  right: 2px;
  top: 26px;
  width: 280px;
}
</style>

<template>
  <div class="di relative">
    <!-- Case when the birthdate is set -->
    <p>{{ employee.birthdate.full }}</p>
  </div>
</template>

<script>

export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      search: '',
      form: {
        year: 1,
        month: 1,
        day: 1,
      },
      updatedEmployee: Object,
    };
  },

  created() {
    this.updatedEmployee = this.employee;
  },

  methods: {
    toggleModal() {
      this.modal = false;
    },

    assign(pronoun) {
      axios.post('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/pronoun', pronoun)
        .then(response => {
          this.$snotify.success(this.$t('employee.pronoun_modal_assign_success'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    reset(pronoun) {
      axios.delete('/' + this.$page.auth.company.id + '/employees/' + this.employee.id + '/pronoun/' + pronoun.id)
        .then(response => {
          this.$snotify.success(this.$t('employee.pronoun_modal_unassign_success'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

          this.updatedEmployee = response.data.data;
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },

    isAssigned : function(id) {
      if (!this.updatedEmployee.pronoun) {
        return false;
      }
      if (this.updatedEmployee.pronoun.id == id) {
        return true;
      }
      return false;
    },

    employeeOrAtLeastHR() {
      if (this.$page.auth.employee.permission_level <= 200) {
        return true;
      }

      if (!this.employee.user) {
        return false;
      }

      if (this.$page.auth.user.id == this.employee.user.id) {
        return true;
      }
    }
  }
};

</script>
