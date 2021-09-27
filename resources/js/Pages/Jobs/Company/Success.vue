<style lang="scss" scoped>
.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -4px;
  background-color: #ddf4ff;
  color: #0969da;
}

.breadcrumb {
  li + li:before {
    content: '>';
    padding-left: 5px;
    padding-right: 5px;
  }
}
</style>

<template>
  <div class="ph2 ph5-ns mt5">
    <!-- job title -->
    <div class="mw7 center box bg-white mb4">
      <div class="bg-gray pa3 f7 box-top">
        <ul class="list pl0 ma0 breadcrumb">
          <li class="mr2 di"><inertia-link :href="data.url_all">All jobs</inertia-link></li>
          <li class="mr2 di"><inertia-link :href="data.url_company">{{ data.company.name }}</inertia-link></li>
          <li class="di">{{ data.job_opening.title }}</li>
        </ul>
      </div>
      <div class="pa3 flex justify-between items-center bt bb-gray">
        <!-- name + summary -->
        <div class="">
          <h2 class="ma0 relative fw4">
            {{ data.job_opening.title }}
            <span v-if="data.job_opening.reference_number" class="reference-number f7 code fw4">
              {{ data.job_opening.reference_number }}
            </span>
          </h2>
        </div>
      </div>
    </div>

    <div class="mw7 center br3 mb3 bg-white box relative z-1 tc mb4 pb4">
      <p class="tc measure center mb4 lh-copy">
        You've successfully applied! We'll keep in touch soon.
      </p>
      <img loading="lazy" src="/img/streamline-icon-promotion-medal-2-1@200x200.png" width="200" height="200" alt="meeting"
           class=""
      />
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
    candidate: {
      type: Object,
      default: null,
    },
  },
};
</script>
