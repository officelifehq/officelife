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
    <!-- Assigning an employee gender pronoun is restricted to HR or admin -->
    <ul v-if="employeeOrAtLeastHR() && updatedEmployee.birthdate" class="ma0 pa0 di">
      <li class="bb b--dotted bt-0 bl-0 br-0 pointer di" data-cy="open-birthdate-modal" @click.prevent="modal = true">{{ $t('employee.birthdate_title') }}</li>
      <li class="di" data-cy="birthdate-name-right-permission">
        {{ updatedEmployee.birthdate.label }}
      </li>
    </ul>

    <!-- Case when birthdate is set but user has no right to edit -->
    <ul v-if="!employeeOrAtLeastHR() && updatedEmployee.pronoun" class="ma0 pa0 di">
      <li class="di">{{ $t('employee.pronoun_title') }}</li>
      <li class="di" data-cy="pronoun-name-wrong-permission">
        {{ updatedEmployee.pronoun.label }}
      </li>
    </ul>

    <!-- Action when there is no birthdate defined -->
    <a v-show="!updatedEmployee.birthdate" v-if="employeeOrAtLeastHR()" class="pointer" data-cy="open-birthdate-modal-blank" @click.prevent="modal = true">No birthdate</a>

    <!-- Modal -->
    <div v-if="modal" v-click-outside="toggleModal" class="popupmenu absolute br2 bg-white z-max tl bounceIn faster">
      <p class="pa2 ma0 bb bb-gray">
        Indicate the birthdate
      </p>

      <form @submit.prevent="search">
        <div class="relative pv2 ph2 bb bb-gray">
          <div class="dt-ns dt--fixed di">
            <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
              <!-- year -->
              <select-box :id="'year'"
                          v-model="form.year"
                          :options="countries"
                          :name="'year'"
                          :errors="$page.errors.year"
                          :label="$t('employee.edit_information_country')"
                          :placeholder="$t('app.choose_value')"
                          :required="true"
                          :value="form.country_id"
                          :datacy="'country_selector'"
              />
            </div>
            <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
              <!-- month -->
              <select-box :id="'month'"
                          v-model="form.month"
                          :options="countries"
                          :name="'month'"
                          :errors="$page.errors.month"
                          :label="$t('employee.edit_information_country')"
                          :placeholder="$t('app.choose_value')"
                          :required="true"
                          :value="form.country_id"
                          :datacy="'country_selector'"
              />
            </div>
            <div class="dtc-ns pr2-ns pb0-ns w-100 pb3">
              <!-- day -->
              <select-box :id="'day'"
                          v-model="form.day"
                          :options="countries"
                          :name="'day'"
                          :errors="$page.errors.day"
                          :label="$t('employee.edit_information_country')"
                          :placeholder="$t('app.choose_value')"
                          :required="true"
                          :value="form.country_id"
                          :datacy="'country_selector'"
              />
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import vClickOutside from 'v-click-outside';
import SelectBox from '@/Shared/Select';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  directives: {
    clickOutside: vClickOutside.directive
  },

  components: {
    SelectBox,
  },

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
      if (!this.employee.user) {
        return false;
      }

      return this.$page.auth.employee.permission_level <= 200 || this.$page.auth.user.id == this.employee.user.id;
    }
  }
};

</script>
