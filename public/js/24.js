(window.webpackJsonp=window.webpackJsonp||[]).push([[24],{"78K3":function(t,n,e){"use strict";var o=e("p0M4");e.n(o).a},"KHd+":function(t,n,e){"use strict";function o(t,n,e,o,s,a,r,i){var l,d="function"==typeof t?t.options:t;if(n&&(d.render=n,d.staticRenderFns=e,d._compiled=!0),o&&(d.functional=!0),a&&(d._scopeId="data-v-"+a),r?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(r)},d._ssrRegister=l):s&&(l=i?function(){s.call(this,this.$root.$options.shadowRoot)}:s),l)if(d.functional){d._injectStyles=l;var c=d.render;d.render=function(t,n){return l.call(n),c(t,n)}}else{var p=d.beforeCreate;d.beforeCreate=p?[].concat(p,l):[l]}return{exports:t,options:d}}e.d(n,"a",(function(){return o}))},Wf3K:function(t,n,e){"use strict";e.r(n);var o={props:{notifications:{type:Array,default:null},worklogs:{type:Array,default:null}},data:function(){return{}},methods:{}},s=(e("78K3"),e("KHd+")),a=Object(s.a)(o,(function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"mb4 relative"},[e("span",{staticClass:"tc db fw5 mb2"},[t._v("\n    🔨 Work logs\n  ")]),t._v(" "),e("div",{staticClass:"br3 bg-white box z-1 pa3"},[0==t.worklogs.length?e("p",{staticClass:"lh-copy ma0 f6 tc"},[t._v("\n      "+t._s(t.$t("employee.worklog_blank"))+"\n    ")]):t._e(),t._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:0!=t.worklogs.length,expression:"worklogs.length != 0"}],attrs:{"data-cy":"list-worklogs"}},[e("ul",{staticClass:"list mv0 pa0"},t._l(t.worklogs,(function(n){return e("li",{key:n.id,staticClass:"mb3 relative worklog-item"},[e("p",{staticClass:"f7 mb1"},[t._v("\n            "+t._s(t._f("moment")(n.created_at,"dddd, MMMM Do YYYY"))+"\n          ")]),t._v(" "),e("div",{staticClass:"parsed-content",domProps:{innerHTML:t._s(n.parsed_content)}})])})),0)])])])}),[],!1,null,"65d52aed",null);n.default=a.exports},p0M4:function(t,n,e){var o=e("safg");"string"==typeof o&&(o=[[t.i,o,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(o,s);o.locals&&(t.exports=o.locals)},safg:function(t,n,e){(t.exports=e("I1BE")(!1)).push([t.i,".content[data-v-65d52aed] {\n  background-color: #f3f9fc;\n  padding: 1px 10px;\n}\n.worklog-item[data-v-65d52aed]:last-child {\n  margin-bottom: 0;\n}",""])}}]);
//# sourceMappingURL=24.js.map?id=091c78aebb959319e65f