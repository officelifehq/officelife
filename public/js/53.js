(window.webpackJsonp=window.webpackJsonp||[]).push([[53],{"3lJJ":function(t,a,e){(t.exports=e("I1BE")(!1)).push([t.i,"\n.avatar[data-v-af8e8ebe] {\n  width: 80px;\n  height: 80px;\n  top: 32px;\n  left: 50%;\n  margin-top: -40px; /* Half the height */\n  margin-left: -40px; /* Half the width */\n}\n",""])},"4MzX":function(t,a,e){var s=e("3lJJ");"string"==typeof s&&(s=[[t.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(s,i);s.locals&&(t.exports=s.locals)},SY7B:function(t,a,e){"use strict";e.r(a);var s={components:{Layout:e("+SZM").a},props:{notifications:{type:Array,default:null},employee:{type:Object,default:null},logs:{type:Array,default:null},paginator:{type:Object,default:null}}},i=(e("hqVX"),e("KHd+")),n=Object(i.a)(s,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("layout",{attrs:{title:"Home",notifications:t.notifications}},[e("div",{staticClass:"ph2 ph0-ns"},[e("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[e("ul",{staticClass:"list ph0 tc-l tl"},[e("li",{staticClass:"di"},[e("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v(t._s(t.$page.auth.company.name))])],1),t._v(" "),e("li",{staticClass:"di"},[t._v("\n          ...\n        ")]),t._v(" "),e("li",{staticClass:"di"},[e("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id,"data-cy":"breadcrumb-employee"}},[t._v(t._s(t.employee.name))])],1),t._v(" "),e("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_employee_logs"))+"\n        ")])])]),t._v(" "),e("div",{staticClass:"mw7 center br3 mb5 bg-white box relative z-1"},[e("div",{staticClass:"pa3 relative pt5"},[e("img",{staticClass:"avatar absolute br-100 db center",attrs:{src:t.employee.avatar}}),t._v(" "),e("h2",{staticClass:"tc normal mb4"},[t._v("\n          Everything that ever happened to "+t._s(t.employee.name)+"\n        ")]),t._v(" "),e("ul",{staticClass:"list pl0 mt0 center",attrs:{"data-cy":"logs-list"}},t._l(t.logs,(function(a){return e("li",{key:a.id,staticClass:"flex items-center lh-copy pa2-l pa1 ph0-l bb b--black-10"},[e("div",{staticClass:"flex-auto"},[a.author.id?e("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+a.author.id}},[t._v("\n                "+t._s(a.author.name)+"\n              ")]):e("span",{staticClass:"black-70"},[t._v("\n                "+t._s(a.author.name)+"\n              ")]),t._v(" "),e("span",{},[t._v("\n                "+t._s(a.localized_content)+"\n              ")]),t._v(" "),e("span",{staticClass:"db f6 log_date"},[t._v("\n                "+t._s(a.localized_audited_at)+"\n              ")])],1)])})),0),t._v(" "),e("div",{staticClass:"center cf"},[e("inertia-link",{directives:[{name:"show",rawName:"v-show",value:t.paginator.previousPageUrl,expression:"paginator.previousPageUrl"}],staticClass:"fl dib",attrs:{href:t.paginator.previousPageUrl,title:"Previous"}},[t._v("\n            ← "+t._s(t.$t("app.previous"))+"\n          ")]),t._v(" "),e("inertia-link",{directives:[{name:"show",rawName:"v-show",value:t.paginator.nextPageUrl,expression:"paginator.nextPageUrl"}],staticClass:"fr dib",attrs:{href:t.paginator.nextPageUrl,title:"Next"}},[t._v("\n            "+t._s(t.$t("app.next"))+" →\n          ")])],1)])])])])}),[],!1,null,"af8e8ebe",null);a.default=n.exports},hqVX:function(t,a,e){"use strict";var s=e("4MzX");e.n(s).a}}]);
//# sourceMappingURL=53.js.map?id=158c65648552df94157e