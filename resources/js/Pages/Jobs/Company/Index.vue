<style lang="scss" scoped>
img {
  max-height: 100px;
  max-width: 100px;
}

.openings-list:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.openings-list:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

svg {
  width: 16px;
  top: 1px;
  color: #b7b7b7;
}

.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -1px;
  background-color: #ddf4ff;
  color: #0969da;
}
</style>

<template>
  <div class="ph2 ph0-ns mt4 mw7 center br3 mb4 relative z-1">
    <!-- company information -->
    <div class="flex mb4">
      <img v-if="data.company.logo" :src="data.company.logo" class="mr3" :alt="data.company.name" />
      <div>
        <h3>{{ data.company.name }}</h3>
        <ul v-if="data.company.location" class="list pl0 ma0">
          <li class="di mr2 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="mr1 relative" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
            </svg>
            {{ data.company.location }}
          </li>
        </ul>
      </div>
    </div>

    <!-- job openings -->
    <div class="">
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div v-for="jobOpening in data.job_openings" :key="jobOpening.id" class="openings-list bb bb-gray bb-gray-hover pa3">
          <h2 class="mt0 mb2 fw4 f4 relative">
            <inertia-link :href="jobOpening.url">{{ jobOpening.title }}</inertia-link>
            <span v-if="jobOpening.reference_number" class="ml2 reference-number f7 relative">
              {{ jobOpening.reference_number }}
            </span>
          </h2>
          <p v-if="jobOpening.team" class="ma0 f7 gray">
            {{ jobOpening.team.name }}
          </p>
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
