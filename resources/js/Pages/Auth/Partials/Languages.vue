<template>
  <div class="f7 mr4 mb4">
    <ul class="list">
      <li class="di silver">
        🌍 {{ $t('auth.change_language') }}
      </li>
      <li v-for="language in $page.props.jetstream.languages" :key="language.lang" :title="language['name-orig']" class="di ml1">
        <a v-if="language.lang !== lang" :href="url+'?lang='+language.lang" :title="language['name-orig']"
           @click.prevent="lang = language.lang"
        >
          {{ language.lang }}
        </a>
        <template v-else class="silver">
          {{ language.lang }}
        </template>
      </li>
    </ul>
  </div>
</template>

<script>
export default {

  model: {
    event: 'update:lang'
  },

  data() {
    return {
      l: null,
    };
  },

  computed: {
    lang: {
      get() {
        return this.l ? this.l : document.querySelector('html').getAttribute('lang');
      },
      set(value) {
        this.loadLanguage(value, true);
        this.$emit('update:lang', value);
        this.l = value;
      }
    },
    url: function() {
      return window.location;
    },
  },
};
</script>
