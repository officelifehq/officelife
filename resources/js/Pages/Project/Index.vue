<style lang="scss" scoped>
.overflow-ellipsis {
  text-overflow: ellipsis;
}

.grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-row-gap: 20px;
  grid-column-gap: 20px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_project_list') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <div class="mt4 mt5-l center section-btn relative mb5">
          <p>
            <span class="pr2">
              {{ $t('project.index_title') }}
            </span>
            <inertia-link :href="'/' + $page.auth.company.id + '/projects/create'" class="btn absolute db-l dn">
              {{ $t('project.index_cta') }}
            </inertia-link>
          </p>
        </div>

        <div class="mt2 grid">
          <div v-for="project in projects.projects" :key="project.id" class="w-100 bg-white box pa3 mb3 mr3">
            <h2 class="fw4 f4 mt0 mb2 lh-copy">
              <inertia-link :href="project.url">{{ project.name }}</inertia-link> <span class="f7 gray">
                {{ project.code }}
              </span>
            </h2>
            <p class="mv0 lh-copy f6">{{ project.summary }}</p>
          </div>
        </div>
      </div>
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
    projects: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
  }
};

</script>
