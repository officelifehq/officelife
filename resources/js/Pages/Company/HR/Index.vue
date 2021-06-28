<style lang="scss" scoped>
.progress-bar {
	border-radius: 3px;
	overflow: hidden;
  width: 100%;

	span {
		display: block;
	}

}

.bar {
  background: rgba(0,0,0,0.075);
  width: 100%
}

.progress {
  animation: loader 8s cubic-bezier(.45,.05,.55,.95) forwards;
  background: #75b800;
  color: #fff;
  padding: 5px;
}

</style>

<template>
  <layout :notifications="notifications">
    <!-- header -->
    <header-component :statistics="statistics" />

    <div class="ph2 ph5-ns">
      <!-- central content -->
      <tab :tab="tab" />

      <div class="cf mw9 center">
        <div class="fl w-50-l w-100">
          <e-coffees
            v-if="eCoffees"
            :e-coffees="eCoffees"
          />
        </div>

        <div class="fl w-50-l w-100 pl4-l">
          <genders
            :genders="genders"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import eCoffees from '@/Pages/Company/HR/Partials/eCoffees';
import Genders from '@/Pages/Company/HR/Partials/Genders';
import Tab from '@/Pages/Company/Partials/Tab';
import HeaderComponent from '@/Pages/Company/Partials/Header';

export default {
  components: {
    Layout,
    eCoffees,
    Genders,
    Tab,
    HeaderComponent,
  },

  props: {
    tab: {
      type: String,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
    eCoffees: {
      type: Object,
      default: null,
    },
    genders: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },
};
</script>
