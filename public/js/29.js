(window.webpackJsonp=window.webpackJsonp||[]).push([[29,53],{CaAf:function(e,t,a){"use strict";var n=a("V30q");a.n(n).a},IQOQ:function(e,t,a){e.exports=function(){var e="__v-click-outside",t="undefined"!=typeof window,a="undefined"!=typeof navigator,n=t&&("ontouchstart"in window||a&&navigator.msMaxTouchPoints>0)?["touchstart"]:["click"];function s(t,a){var s=function(e){var t="function"==typeof e;if(!t&&"object"!=typeof e)throw new Error("v-click-outside: Binding value must be a function or an object");return{handler:t?e:e.handler,middleware:e.middleware||function(e){return e},events:e.events||n,isActive:!(!1===e.isActive)}}(a.value),i=s.handler,o=s.middleware;s.isActive&&(t[e]=s.events.map((function(e){return{event:e,handler:function(e){return function(e){var t=e.el,a=e.event,n=e.handler,s=e.middleware;a.target!==t&&!t.contains(a.target)&&s(a,t)&&n(a,t)}({event:e,el:t,handler:i,middleware:o})}}})),t[e].forEach((function(e){var t=e.event,a=e.handler;return setTimeout((function(){return document.documentElement.addEventListener(t,a,!1)}),0)})))}function i(t){(t[e]||[]).forEach((function(e){return document.documentElement.removeEventListener(e.event,e.handler,!1)})),delete t[e]}var o={bind:s,update:function(e,t){var a=t.value,n=t.oldValue;JSON.stringify(a)!==JSON.stringify(n)&&(i(e),s(e,{value:a}))},unbind:i};return{install:function(e){e.directive("click-outside",o)},directive:o}}()},"KHd+":function(e,t,a){"use strict";function n(e,t,a,n,s,i,o,r){var l,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=a,c._compiled=!0),n&&(c.functional=!0),i&&(c._scopeId="data-v-"+i),o?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),s&&s.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(o)},c._ssrRegister=l):s&&(l=r?function(){s.call(this,this.$root.$options.shadowRoot)}:s),l)if(c.functional){c._injectStyles=l;var d=c.render;c.render=function(e,t){return l.call(t),d(e,t)}}else{var m=c.beforeCreate;c.beforeCreate=m?[].concat(m,l):[l]}return{exports:e,options:c}}a.d(t,"a",(function(){return n}))},V30q:function(e,t,a){var n=a("awB0");"string"==typeof n&&(n=[[e.i,n,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(n,s);n.locals&&(e.exports=n.locals)},YlmQ:function(e,t,a){"use strict";a.r(t);var n=a("IQOQ"),s={directives:{clickOutside:a.n(n).a.directive},props:{employee:{type:Object,default:null},employeeTeams:{type:Array,default:null},teams:{type:Array,default:null}},data:function(){return{modal:!1,search:"",updatedEmployeeTeams:Array}},computed:{filteredList:function(){var e=this;return this.teams.filter((function(t){return t.name.toLowerCase().includes(e.search.toLowerCase())})).sort((function(e,t){return e.name<t.name?-1:e.name>t.name?1:0}))}},created:function(){this.updatedEmployeeTeams=this.employeeTeams},methods:{toggleModal:function(){this.modal=!1},assign:function(e){var t=this;axios.post("/"+this.$page.auth.company.id+"/employees/"+this.employee.id+"/team",e).then((function(e){t.$snotify.success(t.$t("employee.team_modal_assign_success"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),t.updatedEmployeeTeams=e.data})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))},reset:function(e){var t=this;axios.delete("/"+this.$page.auth.company.id+"/employees/"+this.employee.id+"/team/"+e.id).then((function(e){t.$snotify.success(t.$t("employee.team_modal_unassign_success"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),t.updatedEmployeeTeams=e.data})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))},isAssigned:function(e){for(var t=0;t<this.updatedEmployeeTeams.length;t++)if(this.updatedEmployeeTeams[t].id==e)return!0;return!1}}},i=(a("CaAf"),a("KHd+")),o=Object(i.a)(s,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"di relative"},[e.$page.auth.employee.permission_level<=200?a("ul",{staticClass:"ma0 pa0 di existing-teams"},[a("li",{directives:[{name:"show",rawName:"v-show",value:0!=e.updatedEmployeeTeams.length,expression:"updatedEmployeeTeams.length != 0"}],staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer di",attrs:{"data-cy":"open-team-modal"},on:{click:function(t){t.preventDefault(),e.modal=!0}}},[e._v("\n      "+e._s(e.$t("employee.team_title"))+"\n    ")]),e._v(" "),e._l(e.updatedEmployeeTeams,(function(t){return a("li",{key:t.id,staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+e.$page.auth.company.id+"/teams/"+t.id}},[e._v(e._s(t.name))])],1)}))],2):a("ul",{staticClass:"ma0 pa0 existing-teams di"},[a("li",{directives:[{name:"show",rawName:"v-show",value:0!=e.updatedEmployeeTeams.length,expression:"updatedEmployeeTeams.length != 0"}],staticClass:"di"},[e._v("\n      "+e._s(e.$t("employee.team_title"))+"\n    ")]),e._v(" "),e._l(e.updatedEmployeeTeams,(function(t){return a("li",{key:t.id,staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+e.$page.auth.company.id+"/teams/"+t.id}},[e._v(e._s(t.name))])],1)}))],2),e._v(" "),e.$page.auth.employee.permission_level<=200?a("a",{directives:[{name:"show",rawName:"v-show",value:0==e.updatedEmployeeTeams.length,expression:"updatedEmployeeTeams.length == 0"}],staticClass:"pointer",attrs:{"data-cy":"open-team-modal-blank"},on:{click:function(t){t.preventDefault(),e.modal=!0}}},[e._v("\n    "+e._s(e.$t("employee.team_modal_title"))+"\n  ")]):a("span",{directives:[{name:"show",rawName:"v-show",value:0==e.updatedEmployeeTeams.length,expression:"updatedEmployeeTeams.length == 0"}]},[e._v("\n    "+e._s(e.$t("employee.team_modal_blank"))+"\n  ")]),e._v(" "),e.modal?a("div",{directives:[{name:"click-outside",rawName:"v-click-outside",value:e.toggleModal,expression:"toggleModal"}],staticClass:"popupmenu absolute br2 bg-white z-max tl bounceIn faster"},[a("div",{directives:[{name:"show",rawName:"v-show",value:0!=e.teams.length,expression:"teams.length != 0"}]},[a("p",{staticClass:"pa2 ma0 bb bb-gray"},[e._v("\n        "+e._s(e.$t("employee.team_modal_title"))+"\n      ")]),e._v(" "),a("form",{on:{submit:function(t){return t.preventDefault(),e.search(t)}}},[a("div",{staticClass:"relative pv2 ph2 bb bb-gray"},[a("input",{directives:[{name:"model",rawName:"v-model",value:e.search,expression:"search"}],staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:"search",type:"text",name:"search",placeholder:e.$t("employee.team_modal_filter")},domProps:{value:e.search},on:{keydown:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"esc",27,t.key,["Esc","Escape"])?null:e.toggleModal(t)},input:function(t){t.target.composing||(e.search=t.target.value)}}})])]),e._v(" "),a("ul",{staticClass:"pl0 list ma0 overflow-auto relative teams-list"},e._l(e.filteredList,(function(t){return a("li",{key:t.id,attrs:{"data-cy":"list-team-"+t.id}},[e.isAssigned(t.id)?a("div",{staticClass:"pv2 ph3 bb bb-gray-hover bb-gray pointer relative",on:{click:function(a){return e.reset(t)}}},[e._v("\n            "+e._s(t.name)+"\n\n            "),a("img",{staticClass:"pr1 absolute right-1",attrs:{src:"/img/check.svg"}})]):a("div",{staticClass:"pv2 ph3 bb bb-gray-hover bb-gray pointer relative",on:{click:function(a){return e.assign(t)}}},[e._v("\n            "+e._s(t.name)+"\n          ")])])})),0)]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:0==e.teams.length,expression:"teams.length == 0"}]},[a("p",{staticClass:"pa2 tc lh-copy",attrs:{"data-cy":"modal-blank-state-copy"}},[e._v("\n        "+e._s(e.$t("employee.team_modal_blank_title"))+" "),a("inertia-link",{attrs:{href:"/"+e.$page.auth.company.id+"/account/teams","data-cy":"modal-blank-state-cta"}},[e._v("\n          "+e._s(e.$t("employee.team_modal_blank_cta"))+"\n        ")])],1),e._v(" "),a("img",{staticClass:"db center mb4",attrs:{srcset:"/img/company/account/blank-team-1x.png, /img/company/account/blank-team-2x.png 2x"}})])]):e._e()])}),[],!1,null,"38886e5e",null);t.default=o.exports},awB0:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,"\n.teams-list[data-v-38886e5e] {\n  max-height: 150px;\n}\n.popupmenu[data-v-38886e5e] {\n  right: 2px;\n  top: 26px;\n  width: 280px;\n}\n.c-delete[data-v-38886e5e]:hover {\n  border-bottom-width: 0;\n}\n.existing-teams li[data-v-38886e5e]:not(:last-child) {\n  margin-right: 5px;\n}\n",""])}}]);
//# sourceMappingURL=29.js.map?id=51fe4b5b7c9f1759ed3c