<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}

svg {
  width: 16px;
  top: 3px;
  color: #b7b7b7;
}
</style>

<template>
  <div class="ph2 ph5-ns mt5">
    <div class="mw8 center br3 mb5 relative z-1">
      <div class="cf center">
        <!-- LEFT COLUMN -->
        <div class="fl w-70-l w-100 bg-white box pa3">
          <h2 class="mt0 mb2 fw4">
            {{ data.job_opening.title }} <span>{{ data.job_opening.reference_number }}</span>
          </h2>
          <p v-if="data.job_opening.team" class="relative f6 gray mb4">
            <svg xmlns="http://www.w3.org/2000/svg" class="mr1 relative" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
            </svg>
            {{ data.job_opening.team.name }}
          </p>

          <div class="parsed-content mb4" v-html="data.job_opening.description"></div>

          <p>
            <inertia-link :href="data.url_apply" class="btn add">Apply now</inertia-link>
          </p>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-30-l w-100 pl4-l">
          <!-- cta -->
          <p class="mb4 tc mt0">
            <inertia-link :href="data.url_apply" class="btn add w-100 db">Apply now</inertia-link>
          </p>

          <div class="bg-white box mb4 pa3">
            <div v-if="data.company.logo" class="tc">
              <img :src="data.company.logo" :alt="data.company.name" class="mb3" />
            </div>

            <div class="">
              <h3 class="mt0 fw5 f4 mb2">
                {{ data.company.name }}
              </h3>
              <ul v-if="data.company.location" class="list pl0 ma0 f7 gray relative">
                <li class="di mr2 relative">
                  <svg xmlns="http://www.w3.org/2000/svg" class="mr1 relative" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                  </svg>
                  {{ data.company.location }}
                </li>
              </ul>
              <p class="mb0 f7"><inertia-link :href="data.company.url">View all jobs</inertia-link></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      default: null,
    },
  },
};
</script>
