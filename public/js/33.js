(window.webpackJsonp=window.webpackJsonp||[]).push([[33],{"/vJW":function(t,n,e){"use strict";var o=e("q2mU");e.n(o).a},"KHd+":function(t,n,e){"use strict";function o(t,n,e,o,s,a,r,i){var l,c="function"==typeof t?t.options:t;if(n&&(c.render=n,c.staticRenderFns=e,c._compiled=!0),o&&(c.functional=!0),a&&(c._scopeId="data-v-"+a),r?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),s&&s.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(r)},c._ssrRegister=l):s&&(l=i?function(){s.call(this,this.$root.$options.shadowRoot)}:s),l)if(c.functional){c._injectStyles=l;var d=c.render;c.render=function(t,n){return l.call(n),d(t,n)}}else{var f=c.beforeCreate;c.beforeCreate=f?[].concat(f,l):[l]}return{exports:t,options:c}}e.d(n,"a",function(){return o})},Wf3K:function(t,n,e){"use strict";e.r(n);var o={props:{notifications:{type:Array,default:null},worklogs:{type:Array,default:null}},data:function(){return{}},methods:{}},s=(e("/vJW"),e("KHd+")),a=Object(s.a)(o,function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"mb4 relative"},[e("span",{staticClass:"tc db fw5 mb2"},[t._v("🔨 Work logs")]),t._v(" "),e("div",{staticClass:"br3 bg-white box z-1 pa3"},[0==t.worklogs.length?e("p",{staticClass:"lh-copy ma0 f6 tc"},[t._v("\n      "+t._s(t.$t("employee.worklog_blank"))+"\n    ")]):t._e(),t._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:0!=t.worklogs.length,expression:"worklogs.length != 0"}],attrs:{"data-cy":"list-worklogs"}},[e("ul",{staticClass:"list mv0 pa0"},t._l(t.worklogs,function(n){return e("li",{key:n.id,staticClass:"mb3 relative worklog-item"},[e("p",{staticClass:"f7 mb1"},[t._v("\n            "+t._s(n.created_at)+"\n          ")]),t._v(" "),e("div",{staticClass:"content",domProps:{innerHTML:t._s(n.content)}})])}),0)])])])},[],!1,null,"fe2ac6f8",null);n.default=a.exports},bMWj:function(t,n,e){(t.exports=e("I1BE")(!1)).push([t.i,"\n.content[data-v-fe2ac6f8] {\n  background-color: #f3f9fc;\n  padding: 1px 10px;\n}\n.worklog-item[data-v-fe2ac6f8]:last-child {\n  margin-bottom: 0;\n}\n",""])},q2mU:function(t,n,e){var o=e("bMWj");"string"==typeof o&&(o=[[t.i,o,""]]);var s={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(o,s);o.locals&&(t.exports=o.locals)}}]);