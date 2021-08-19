<style lang="scss" scoped>
.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -4px;
  background-color: #ddf4ff;
  color: #0969da;
}
</style>

<template>
  <div class="mb4 bg-white box pa3">
    <h2 class="mr2 mt0 mb2 fw4">
      {{ jobOpening.title }}

      <span v-if="jobOpening.reference_number" class="reference-number f7 code fw4">
        {{ jobOpening.reference_number }}
      </span>
    </h2>

    <ul class="ma0 pl0 list f7 gray">
      <li v-if="jobOpening.activated_at" class="di mr3">
        Active since {{ jobOpening.activated_at }}
      </li>
      <li class="di mr3">
        <a :href="jobOpening.url_public_view" target="_blank">{{ $t('dashboard.job_opening_show_view_public_version') }}</a>
      </li>
      <li class="di mr3">
        <inertia-link :href="jobOpening.url_create">{{ $t('app.edit') }}</inertia-link>
      </li>

      <!-- delete -->
      <li v-if="deleteMode" class="di">
        {{ $t('app.sure') }}
        <a class="c-delete mr1 pointer" @click.prevent="destroy()">
          {{ $t('app.yes') }}
        </a>
        <a class="pointer" @click.prevent="deleteMode = false">
          {{ $t('app.no') }}
        </a>
      </li>
      <li v-else class="di">
        <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click.prevent="deleteMode = true">
          {{ $t('app.delete') }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    jobOpening: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      deleteMode: false,
      loadingState: '',
      form: {
        name: null,
        errors: [],
      },
    };
  },

  methods: {
    destroy(stageId) {
      axios.delete('/' + this.$page.props.auth.company.id + '/dashboard/hr/job-openings/' + this.jobOpening.id)
        .then(response => {
          localStorage.success = this.$t('dashboard.job_opening_show_delete_success');
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
