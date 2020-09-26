<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.auth.company.name }}
        </h2>
      </div>

      <p>questions</p>
      <ul>
        <li v-for="question in latestQuestions.latest_questions" :key="question.id">{{ question.title }} ({{ question.number_of_answers }})</li>
      </ul>

      <p>birthday this week</p>
      <ul>
        <li v-for="birthday in birthdaysThisWeek" :key="birthday.id">{{ birthday.name }}</li>
      </ul>

      <p>new hires this week</p>
      <ul>
        <li v-for="hire in newHiresThisWeek" :key="hire.id">{{ hire.name }}</li>
      </ul>

      <p>recent ships</p>
      <ul>
        <li v-for="ship in latestShips" :key="ship.id">{{ ship.title }}</li>
      </ul>

      <p>recent skills</p>
      <ul>
        <li v-for="skill in latestSkills.skills" :key="skill.id">{{ skill.name }}</li>
      </ul>

      <p><inertia-link :href="questionsUrl">{{ questions }} questions</inertia-link></p>
      <p><inertia-link :href="skillsUrl" data-cy="company-skills">{{ skills }} skills</inertia-link></p>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    latestQuestions: {
      type: Object,
      default: null,
    },
    birthdaysThisWeek: {
      type: Array,
      default: null,
    },
    newHiresThisWeek: {
      type: Array,
      default: null,
    },
    latestShips: {
      type: Array,
      default: null,
    },
    latestSkills: {
      type: Array,
      default: null,
    },
    questions: {
      type: Number,
      default: null,
    },
    skills: {
      type: Number,
      default: null,
    },
    questionsUrl: {
      type: String,
      default: null,
    },
    skillsUrl: {
      type: String,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};
</script>
