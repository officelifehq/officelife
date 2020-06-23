<style lang="scss" scoped>
.content {
  background-color: #f3f9fc;
  padding: 1px 10px;
}

.worklog-item:last-child {
  margin-bottom: 0;
}

.parsed-content {
  p:last-child {
    margin-bottom: 0;
  }
}
</style>

<template>
  <div class="relative">
    <!-- Blank state -->
    <div v-if="skills.length == 0 && employeeOrAtLeastHR()">
      <p class="lh-copy ma0 f6 tc pa3">No skills calisse</p>
    </div>

    <!-- skills -->
    <div v-if="skills.length > 0" data-cy="list-skills">
      <ul class="list mv0">
        <li v-for="skill in skills" :key="skill.id" class="mb3 relative skill-item">
          <inertia-link :href="skill.url">{{ skill.name }}</inertia-link>
        </li>
      </ul>
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
    skills: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
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

      return false;
    }
  }
};
</script>
