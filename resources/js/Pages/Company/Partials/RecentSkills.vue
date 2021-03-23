<style lang="scss" scoped>
.skill {
  font-size: 12px;
  border: 1px solid transparent;
  border-radius: 2em;
  padding: 3px 10px;
  line-height: 22px;
  color: #0366d6;
  background-color: #f1f8ff;

  &:hover {
    background-color: #def;
  }
}

.existing-skill:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🧠
      </span> {{ $t('company.recent_skills_title') }}
    </span>

    <!-- Blank state -->
    <div v-if="skills.skills.length == 0" data-cy="skill-list-blank" class="br3 bg-white box z-1 pa3">
      <p class="mb0 mt0 lh-copy f6 tc">
        {{ $t('employee.skills_no_skill_yet') }}
      </p>
    </div>

    <!-- List of existing skills -->
    <div v-if="skills.skills.length > 0" class="br3 bg-white box z-1" data-cy="list-skills">
      <ul class="list mv0 pa3">
        <li v-for="skill in skills.skills" :key="skill.id" class="relative dib fw5 mr2 mb2 existing-skill" :data-cy="'non-edit-skill-list-item-' + skill.id">
          <inertia-link :href="skill.url" class="skill no-underline">{{ skill.name }}</inertia-link>
        </li>
      </ul>
      <div class="ph3 pv2 tc f6 bt bb-gray">
        <inertia-link :href="skills.view_all_url" :data-cy="'company-skills'">{{ $t('company.recent_skills_view_all', { count: skills.count }) }}</inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    skills: {
      type: Object,
      default: null,
    },
  },
};
</script>
