(window.webpackJsonp=window.webpackJsonp||[]).push([[31],{O3Dn:function(t,a,s){(t.exports=s("I1BE")(!1)).push([t.i,"\n.log_date[data-v-bdb75e50] {\n  color: #777A88;\n}\n",""])},gTk0:function(t,a,s){"use strict";s.r(a);var e={components:{Layout:s("+SZM").a},props:{notifications:{type:Array,default:null},logs:{type:Array,default:null},paginator:{type:Object,default:null}}},i=(s("poXB"),s("KHd+")),n=Object(i.a)(e,(function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("layout",{attrs:{title:"Home",notifications:t.notifications}},[s("div",{staticClass:"ph2 ph0-ns"},[s("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[s("ul",{staticClass:"list ph0 tc-l tl"},[s("li",{staticClass:"di"},[s("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v(t._s(t.$page.auth.company.name))])],1),t._v(" "),s("li",{staticClass:"di"},[s("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account"}},[t._v(t._s(t.$t("app.breadcrumb_account_home")))])],1),t._v(" "),s("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_account_audit_logs"))+"\n        ")])])]),t._v(" "),s("div",{staticClass:"mw7 center br3 mb5 bg-white box restricted relative z-1"},[s("div",{staticClass:"pa3 mt5"},[s("h2",{staticClass:"tc normal mb4"},[t._v("\n          "+t._s(t.$t("audit.title"))+"\n        ")]),t._v(" "),s("ul",{staticClass:"list pl0 mt0 center"},t._l(t.logs,(function(a){return s("li",{key:a.id,staticClass:"flex items-center lh-copy pa2-l pa1 ph0-l bb b--black-10"},[s("div",{staticClass:"flex-auto"},[a.author.id?s("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+a.author.id}},[t._v("\n                "+t._s(a.author.name)+"\n              ")]):s("span",{staticClass:"black-70"},[t._v("\n                "+t._s(a.author.name)+"\n              ")]),t._v(" "),s("span",{},[t._v("\n                "+t._s(a.localized_content)+"\n              ")]),t._v(" "),s("span",{staticClass:"db f6 log_date"},[t._v("\n                "+t._s(t._f("moment")(a.created_at,"dddd, MMMM Do YYYY"))+"\n              ")])],1)])})),0),t._v(" "),s("div",{staticClass:"center cf"},[s("inertia-link",{directives:[{name:"show",rawName:"v-show",value:t.paginator.previousPageUrl,expression:"paginator.previousPageUrl"}],staticClass:"fl dib",attrs:{href:t.paginator.previousPageUrl,title:"Previous"}},[t._v("\n            ← "+t._s(t.$t("app.previous"))+"\n          ")]),t._v(" "),s("inertia-link",{directives:[{name:"show",rawName:"v-show",value:t.paginator.nextPageUrl,expression:"paginator.nextPageUrl"}],staticClass:"fr dib",attrs:{href:t.paginator.nextPageUrl,title:"Next"}},[t._v("\n            "+t._s(t.$t("app.next"))+" →\n          ")])],1)])])])])}),[],!1,null,"bdb75e50",null);a.default=n.exports},kCVG:function(t,a,s){var e=s("O3Dn");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};s("aET+")(e,i);e.locals&&(t.exports=e.locals)},poXB:function(t,a,s){"use strict";var e=s("kCVG");s.n(e).a}}]);
//# sourceMappingURL=31.js.map?id=bca0258364ae1756e237