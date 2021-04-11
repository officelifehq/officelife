<style lang="scss" scoped>
.small-avatar:not(:first-child) {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.more-members {
  height: 32px;
  top: 8px;
}

.project-code {
  color: #737e91;
  padding-bottom: 0;
  border: 1px solid #b3d4ff;
}

.project-list:last-child {
  border-bottom: 0;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        🦺
      </span> {{ $t('dashboard.project_title') }}

      <help :url="$page.props.help_links.project" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <div v-for="project in projects" :key="project.id" class="project-list pa3 bb bb-gray flex justify-between items-center">
        <!-- project name -->
        <div class="flex items-center">
          <inertia-link :href="project.url">{{ project.name }}</inertia-link>
          <span v-if="project.code" class="ml2 ttu f7 project-code code br3 pv1 ph2 relative fw4">
            {{ project.code }}
          </span>
        </div>

        <!-- project members -->
        <div class="flex items-center relative tr">
          <avatar v-for="member in project.preview_members" :key="member.id" :avatar="member.avatar" :url="member.url" :size="32"
                  :class="'br-100 small-avatar'"
          />
          <div v-if="project.remaining_members_count > 0" class="pl2 f7 more-members relative gray">
            + {{ project.remaining_members_count }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';
import Help from '@/Shared/Help';

export default {
  components: {
    Avatar,
    Help,
  },

  props: {
    projects: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      showEditor: false,
      form: {
        emotion: null,
      },
      updatedEmployee: null,
      successMessage: false,
    };
  },

  created: function() {
    this.updatedEmployee = this.employee;
  },

  methods: {
    store(emotion) {
      this.successMessage = true;
      this.form.emotion = emotion;

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/morale', this.form)
        .then(response => {
          this.updatedEmployee.has_logged_morale_today = true;
        })
        .catch(error => {
          this.successMessage = false;
        });
    },
  }
};
</script>
