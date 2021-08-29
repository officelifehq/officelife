<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 65px;
}

.employee:not(:last-child) {
  margin-bottom: 15px;
}

.small-skill {
  font-size: 12px;
}

.skill {
  border: 1px solid transparent;
  border-radius: 2em;
  padding: 3px 10px;
  line-height: 22px;
  color: #0366d6;
  background-color: #f1f8ff;

  &:hover {
    background-color: #def;
  }

  a:hover {
    border-bottom: 0;
  }

  span {
    border-radius: 100%;
    background-color: #c8dcf0;
    padding: 0px 5px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/company/skills'"
                  :previous="$t('app.breadcrumb_company_skills')"
      >
        {{ $t('app.breadcrumb_company_skills_detail') }}
      </breadcrumb>

      <div class="mw7 center br3 mb5 bg-white box relative z-1 pa3">
        <!-- title of the page -->
        <h2 class="tc relative fw5 mb4">
          <span class="db lh-copy mb3">
            {{ $t('company.skills_show_title') }}
          </span>

          <!-- skill name -->
          <span v-if="!editMode" class="skill f3 mr2" data-cy="skill-name">
            {{ updatedName }}
          </span>
          <span v-if="editMode" class="f5">
            <form @submit.prevent="update">
              <errors :errors="form.errors" :class="'tl mb2'" />

              <text-input :ref="'editModal'"
                          v-model="form.name"
                          :placeholder="'Enter a new name'"
                          :datacy="'edit-name-input'"
                          :extra-class-upper-div="'mb2'"
                          @esc-key-pressed="editMode = false"
              />

              <div class="w-100 tr">
                <a class="btn dib-l db mb2 mb0-ns" data-cy="rename-cancel-button" @click.prevent="editMode = false">
                  {{ $t('app.cancel') }}
                </a>
                <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" data-cy="rename-cta-button" :state="loadingState" :text="$t('app.update')" />
              </div>
            </form>
          </span>

          <help :url="$page.props.help_links.skills" :datacy="'help-icon-skills'" :top="'1px'" />
        </h2>

        <!-- actions available for HR and administrators -->
        <ul v-if="atLeastHR && !editMode" class="tc pl0 ma0 f6 mb4">
          <li class="mr2 di"><a class="bb b--dotted bt-0 bl-0 br-0 pointer" data-cy="edit-skill" href="#" @click.prevent="showEditMode()">{{ $t('app.edit') }}</a></li>
          <li v-if="deleteMode" class="di">
            {{ $t('app.sure') }}
            <a class="c-delete mr1 pointer" data-cy="delete-confirm-button" @click.prevent="destroy(skill)">
              {{ $t('app.yes') }}
            </a>
            <a class="pointer" data-cy="delete-cancel-button" @click.prevent="deleteMode = false">
              {{ $t('app.no') }}
            </a>
          </li>
          <li v-else class="di">
            <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" data-cy="delete-skill" @click.prevent="deleteMode = true">
              {{ $t('app.delete') }}
            </a>
          </li>
        </ul>

        <!-- list of employees with this skill -->
        <ul class="list pl0 mb0" data-cy="list-of-employees">
          <li v-for="employee in employees" :key="employee.id" :data-cy="'employee-' + employee.id" class="relative ba bb-gray bb-gray-hover pa3 br3 flex items-center employee">
            <avatar :avatar="employee.avatar" :size="64" :class="'avatar br-100'" />

            <div class="ml3 mw-100">
              <inertia-link :href="employee.url">{{ employee.name }}</inertia-link>

              <!-- position -->
              <span v-if="employee.position !== null" class="title db f7 mt1 mb1">
                {{ employee.position.title }}
              </span>
              <span v-else class="title db f7 mt1 mb1">
                {{ $t('app.no_position_defined') }}
              </span>

              <ul v-if="employee.teams.length != 0" class="list pl0 mb2 f7">
                <li class="mr1 di">{{ $t('company.skills_show_in_team') }}</li>
                <li v-for="team in employee.teams" :key="team.id" :data-cy="'employee-' + employee.id" class="di pointer mr2">
                  <inertia-link :href="team.url">{{ team.name }}</inertia-link>
                </li>
              </ul>

              <ul class="list pl0">
                <li v-for="employeeSkill in employee.skills" :key="employeeSkill.id" :data-cy="'employee-' + employee.id" class="skill small-skill di pointer mr2">
                  <inertia-link :href="employeeSkill.url" class="dib no-underline bb-0">{{ employeeSkill.name }}</inertia-link>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';
import TextInput from '@/Shared/TextInput';
import LoadingButton from '@/Shared/LoadingButton';
import Errors from '@/Shared/Errors';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
    Help,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    skill: {
      type: Object,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      updatedName: '',
      loadingState: '',
      editMode: false,
      deleteMode: false,
      form: {
        name: null,
        errors: [],
      },
    };
  },

  computed: {
    atLeastHR() {
      return this.$page.props.auth.employee.permission_level <= 200;
    },
  },

  mounted() {
    this.form.name = this.skill.name;
    this.updatedName = this.skill.name;
  },

  methods: {
    showEditMode() {
      this.editMode = true;

      this.$nextTick(() => {
        this.$refs.editModal.focus();
      });
    },

    update() {
      this.loadingState = 'loading';

      axios.put('/' + this.$page.props.auth.company.id + '/company/skills/' + this.skill.id, this.form)
        .then(response => {
          this.flash(this.$t('company.skill_update_success'), 'success');
          this.updatedName = this.form.name;
          this.editMode = false;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });

      this.loadingState = null;
    },

    destroy(skill) {
      axios.delete('/' + this.$page.props.auth.company.id + '/company/skills/' + skill.id)
        .then(response => {
          localStorage.success = this.$t('company.skill_delete_success');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/company/skills');
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
