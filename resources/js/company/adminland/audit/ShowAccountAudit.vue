<style scoped>
</style>

<template>
  <layout title="Home" :user="user">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <a :href="'/' + company.id + '/dashboard'">{{ company.name }}</a>
          </li>
          <li class="di">
            <a :href="'/' + company.id + '/account'">Account administration</a>
          </li>
          <li class="di">
            Audit logs
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            Audit logs
          </h2>
          <ul class="list pl0 mt0 center">
            <li v-for="log in logs" :key="log.id"
                class="flex items-center lh-copy pa2-l pa1 ph0-l bb b--black-10"
            >
              <div class="flex-auto">
                <span class="db black-70" v-html="log.name"></span>
                <span class="db" v-html="log.sentence"></span>
                <span class="db f6" v-html="log.date"></span>
              </div>
            </li>
          </ul>

          <!-- Pagination -->
          <div class="center cf">
            <a class="fl dib" :href="paginator.previousPageUrl" title="Previous">&larr; Previous</a>
            <a class="fr dib" :href="paginator.nextPageUrl" title="Next">Next &rarr;</a>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: {
    company: {
      type: Object,
      default: null,
    },
    user: {
      type: Object,
      default: null,
    },
    logs: {
      type: Array,
      default: null,
    },
    paginator: {
      type: Object,
      default: null,
    },
  },
}

</script>
