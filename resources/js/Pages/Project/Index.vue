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
            Project list
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <div class="mt4 mt5-l center section-btn relative mb5">
          <p>
            <span class="pr2">
              All the projects in the company
            </span>
            <inertia-link href="/1/projects/1/create" class="btn absolute db-l dn">
              Create a project
            </inertia-link>
          </p>
        </div>

        <div class="mt2 grid">
          <div class="w-100 bg-white box pa3 mb3 mr3">
            <h2 class="fw4 f4 mt0 mb2 lh-copy">
              <a href="/1/projects/1">Name of the project</a> <span class="f7 gray">
                SDN-123
              </span>
            </h2>
            <p class="mv0 lh-copy f6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum...</p>
          </div>
          <div class="w-100 bg-white box pa3 mb3 mr3">
            <h2 class="fw4 f4 mt0 mb2 lh-copy">
              Name of the project <span class="f7 gray">
                SDN-123
              </span>
            </h2>
            <p class="mv0 lh-copy f6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum...</p>
          </div>
          <div class="w-100 bg-white box pa3 mb3 mr3">
            <h2 class="fw4 f4 mt0 mb2 lh-copy">
              Name of the project <span class="f7 gray">
                SDN-123
              </span>
            </h2>
            <p class="mv0 lh-copy f6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum...</p>
          </div>
          <div class="w-100 bg-white box pa3 mb3 mr3">
            <h2 class="fw4 f4 mt0 mb2 lh-copy">
              Name of the project <span class="f7 gray">
                SDN-123
              </span>
            </h2>
            <p class="mv0 lh-copy f6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum...</p>
          </div>
          <div class="w-100 bg-white box pa3 mb3 mr3">
            <h2 class="fw4 f4 mt0 mb2 lh-copy">
              Name of the project <span class="f7 gray">
                SDN-123
              </span>
            </h2>
            <p class="mv0 lh-copy f6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum...</p>
          </div>
          <div class="w-100 bg-white box pa3 mb3 mr3">
            <h2 class="fw4 f4 mt0 mb2 lh-copy">
              Name of the project <span class="f7 gray">
                SDN-123
              </span>
            </h2>
            <p class="mv0 lh-copy f6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum...</p>
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
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
  }
};

</script>
