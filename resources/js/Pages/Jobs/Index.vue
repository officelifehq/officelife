<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}

.companies-list:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.companies-list:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

svg {
  width: 16px;
  top: 3px;
  color: #b7b7b7;
}
</style>

<template>
  <div>
    <!-- title -->
    <div class="ph2 ph0-ns mt4 mw7 center br3 mb4 relative z-1">
      <h2 class="fw4 mb0">
        All the job openings
      </h2>
    </div>

    <div v-if="companies.length > 0" class="ph2 ph5-ns">
      <div class="mw7 center br3 mb3 bg-white box relative z-1">
        <div v-for="company in companies" :key="company.id" class="pa3 flex items-center bb bb-gray bb-gray-hover companies-list">
          <img v-if="company.logo" :src="company.logo" class="mr3" :alt="company.name" />
          <div>
            <inertia-link :href="company.url" class="mb2 dib f4 fw4">{{ company.name }}</inertia-link>
            <ul class="list pl0 ma0 f7 gray">
              <li v-if="company.location" class="di mr3 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr1 relative" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                {{ company.location }}
              </li>
              <li class="di">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr1 relative" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                </svg>
                {{ company.count }} job openings
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- blank state -->
    <div v-else class="mw7 center br3 mb3 bg-white box relative z-1">
      <p class="tc measure center mb4 lh-copy">
        There are currently no job openings available.
      </p>
      <img loading="lazy" class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                    /img/company/account/blank-position-2x.png 2x"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    companies: {
      type: Array,
      default: null,
    },
  },
};
</script>
