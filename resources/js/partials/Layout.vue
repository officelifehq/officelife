<template>
  <div>
    <vue-snotify></vue-snotify>

    <header class="bg-white dn db-m db-l mb3">
      <div class="ph3 pt1 w-100">
        <div class="cf">
          <div class="fl w-20 pa2">
            <a class="relative header-logo" href="">
              <img src="/img/logo.svg" height="30" />
            </a>
          </div>
          <div class="fl w-60 tc">
            <div v-show="noMenu" class="dib w-100"></div>
            <ul class="mv2" v-show="!noMenu">
              <li class="di header-menu-item pa2">
                <a class="b no-underline no-color" href="">
                  <img class="relative" src="/img/header/icon-home.svg" />
                  Home
                </a>
              </li>
              <li class="di header-menu-item pa2">
                <a class="b no-underline no-color" href="">
                  <img class="relative" src="/img/header/icon-find.svg" />
                  Find
                </a>
              </li>
            </ul>
          </div>
          <div class="fl w-20 pa2 tr relative header-menu-settings">
            <header-menu></header-menu>
          </div>
        </div>
      </div>
    </header>

    <!-- MOBILE MENU -->
    <header class="bg-white mobile dn-ns mb3">
      <div class="ph2 pv2 w-100 relative">
        <div class="pv2 relative menu-toggle">
          <label for="menu-toggle" class="dib b relative">Menu</label>
          <input type="checkbox" id="menu-toggle">
          <ul class="list pa0 mt4 mb0" id="mobile-menu">
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                  app.main_nav_home
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                  app.main_nav_people
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                  app.main_nav_journal
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                  app.main_nav_find
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                  app.main_nav_changelog
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                  app.main_nav_settings
              </a>
            </li>
            <li class="pv2 bt b--light-gray">
              <a class="no-color b no-underline" href="">
                  app.main_nav_signout
              </a>
            </li>
          </ul>
        </div>
        <div class="absolute pa2 header-logo">
          <a href="">
              <img src="/img/logo/logo.svg" width="30" height="27" />
          </a>
        </div>
      </div>
    </header>

    <slot></slot>
  </div>
</template>

<script>
export default {
  props: [
    'title',
    'noMenu'
  ],

  mounted() {
    this.updatePageTitle(this.title)
  },

  watch: {
    title(title) {
      this.updatePageTitle(title)
    }
  },

  methods: {
    updatePageTitle(title) {
      document.title = title ? `${title} | Example app` : `Example app`
    }
  },
}
</script>
