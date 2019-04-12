<style scoped>
.positions-list {
  max-height: 100px;
}
</style>

<template>
  <div class="di">
    <a class="pointer" @click.prevent="modal = true">Indicate a title</a>

    <div v-if="modal" v-click-outside="toggleModal" class="popupmenu absolute br2 bg-white z-max tl bounceIn faster">
      <p class="pv2 ph3 ma0 bb">
        Choose a title or create a new one
      </p>
      <form @submit.prevent="search">
        <div class="relative pv2 ph2 bb">
          <input id="search" ref="search" type="text" name="search"
                 :placeholder="'Filter the list'" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required data-cy="search-direct-report"
                 @keyup="search" @keydown.esc="toggleModal"
          />
        </div>
      </form>

      <ul class="pl0 list ma0 overflow-auto relative positions-list">
        <li class="pv2 ph3 bb">
          Director of product
        </li>
        <li class="pv2 ph3 bb">
          Back end developer
        </li>
        <li class="pv2 ph3 bb">
          Director of product
        </li>
        <li class="pv2 ph3 bb">
          Back end developer
        </li>
        <li class="pv2 ph3 bb">
          Director of product
        </li>
        <li class="pv2 ph3 bb">
          Back end developer
        </li>
        <li class="pv2 ph3 bb">
          Director of product
        </li>
        <li class="pv2 ph3 bb">
          Back end developer
        </li>
        <li class="fw5 mb3">
          <span class="f6 mb2 dib">{{ $t('employee.hierarchy_search_results') }}</span>
          <ul v-if="titles.length > 0" class="list ma0 pl0">
            <li v-for="title in titles" :key="title.id" class="bb relative pv2 ph1 bb-gray bb-gray-hover">
              {{ title.name }}
              <a class="absolute right-1 pointer" data-cy="potential-direct-report-button" @click.prevent="assignTitle(title)">{{ $t('app.choose') }}</a>
            </li>
          </ul>
          <div v-else class="silver">
            {{ $t('app.no_results') }}
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {
  directives: {
    ClickOutside
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      modal: false,
      titles: [],
    }
  },

  mounted() {
    // axios.get('/' + this.company.id + '/titles')
    //     .then(response => {
    //       this.titles = response.data
    //     })
  },

  methods: {
    toggleModal() {
      this.modal = false
    },
  }
}

</script>
