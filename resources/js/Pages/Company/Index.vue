<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.auth.company.name }}
        </h2>
      </div>

      <ul>
        <li v-for="question in latestQuestions.latest_questions" :key="question.id">{{ question.title }}</li>
      </ul>

      <ul>
        <li v-for="birthday in birthdaysThisWeek" :key="birthday.id">{{ birthday.name }}</li>
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
