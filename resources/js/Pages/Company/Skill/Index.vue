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

  a:hover {
    border-bottom: 0;
  }

  span {
    border-radius: 100%;
    background-color: #c8dcf0;
    padding: 0px 5px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_company_skills') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1 pa3">
        <h2 class="tc relative fw5">
          {{ $t('company.skills_index_title', { name: $page.props.auth.company.name}) }}

          <help :url="$page.props.help_links.skills" :datacy="'help-icon-skills'" :top="'1px'" />
        </h2>
        <p class="tc lh-copy mb5">{{ $t('company.skills_index_description') }}</p>

        <text-input v-model="search"
                    :datacy="'news-title-input'"
                    :errors="$page.props.errors.title"
                    :required="true"
                    :placeholder="$t('company.skills_index_search_placeholder')"
                    :extra-class-upper-div="'mb4 mw6 center'"
                    @keyup="search"
                    @esc-key-pressed="clear()"
        />

        <ul class="list pl0">
          <li v-for="skill in filteredList" :key="skill.id" class="dib skill pointer mr2 mb2">
            <inertia-link :href="skill.url" class="dib no-underline bb-0" :data-cy="'skill-item-' + skill.id">{{ skill.name }} <span>{{ skill.number_of_employees }}</span></inertia-link>
          </li>
        </ul>

        <p v-if="filteredList.length == 0" class="tc">🤕 {{ $t('company.skills_index_search_no_results') }}</p>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import TextInput from '@/Shared/TextInput';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    TextInput,
    Help,
  },

  props: {
    skills: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      search: '',
      loadingState: '',
    };
  },

  computed: {
    filteredList() {
      return this.skills.filter(item => {
        return item.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
      });
    }
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    clear() {
      this.search = '';
    },
  }
};

</script>
