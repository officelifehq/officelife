(window.webpackJsonp=window.webpackJsonp||[]).push([[44],{ASc9:function(t,e,a){var s=a("Cy28");"string"==typeof s&&(s=[[t.i,s,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,n);s.locals&&(t.exports=s.locals)},Cy28:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".months[data-v-1810ec28] {\n  color: #718096;\n}\n.months .selected[data-v-1810ec28] {\n  font-weight: 600;\n  text-decoration: none;\n  border-bottom: 0;\n  padding-left: 10px;\n}\n.years .selected[data-v-1810ec28] {\n  border-bottom: 0;\n  text-decoration: none;\n  color: #4d4d4f;\n}",""])},L7ZW:function(t,e,a){"use strict";a.r(e);var s=a("+SZM"),n=a("a22d"),l={components:{Layout:s.a,CalendarHeatmap:n.CalendarHeatmap},props:{notifications:{type:Array,default:null},employee:{type:Object,default:null},worklogs:{type:Array,default:null},year:{type:Number,default:null},years:{type:Object,default:null},months:{type:Array,default:null},currentYear:{type:Number,default:null},currentMonth:{type:Number,default:null},graphData:{type:Array,default:null}},data:function(){return{}},created:function(){},methods:{}},r=(a("Zdyt"),a("KHd+")),o=Object(r.a)(l,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("layout",{attrs:{title:"Home",notifications:t.notifications}},[a("div",{staticClass:"ph2 ph0-ns"},[a("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[a("ul",{staticClass:"list ph0 tc-l tl"},[a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v(t._s(t.$page.auth.company.name))])],1),t._v(" "),a("li",{staticClass:"di"},[t._v("\n          ...\n        ")]),t._v(" "),a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id,"data-cy":"breadcrumb-employee"}},[t._v(t._s(t.employee.name))])],1),t._v(" "),a("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_employee_worklogs"))+"\n        ")])])]),t._v(" "),a("div",{staticClass:"mw7 center br3 mb5 bg-white box relative z-1"},[a("h2",{staticClass:"pa3 mt2 center tc normal mb2"},[t._v("\n        "+t._s(t.$t("employee.worklog_title"))+"\n      ")]),t._v(" "),t.worklogs.length>0?[a("ul",{staticClass:"list years tc",attrs:{"data-cy":"worklog-year-selector"}},[a("li",{staticClass:"di"},[t._v(t._s(t.$t("employee.worklog_year_selector")))]),t._v(" "),t._l(t.years,(function(e){return a("li",{key:e.number,staticClass:"di mh2"},[a("inertia-link",{class:{selected:t.currentYear==e.number},attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id+"/worklogs/"+e.number}},[t._v(t._s(e.number))])],1)}))],2),t._v(" "),a("calendar-heatmap",{staticClass:"pa3",attrs:{"end-date":t.year+"-12-31",values:t.graphData}}),t._v(" "),a("div",{staticClass:"cf w-100"},[a("div",{staticClass:"fl-ns w-third-ns pa3"},[a("p",{staticClass:"f6 mt0 silver"},[t._v(t._s(t.$t("employee.worklog_filter_month")))]),t._v(" "),a("ul",{staticClass:"pl0 list months f6"},[a("li",{staticClass:"mb2"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id+"/worklogs/"+t.year}},[t._v("All")])],1),t._v(" "),t._l(t.months,(function(e){return a("li",{key:e.month,staticClass:"mb2",attrs:{"data-cy":"worklog-month-selector-"+e.month}},[t.currentMonth?[0!=e.occurences?a("inertia-link",{class:{selected:t.currentMonth==e.month},attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id+"/worklogs/"+t.year+"/"+e.month}},[t._v(t._s(e.translation)+" ("+t._s(e.occurences)+")")]):t._e(),t._v(" "),0==e.occurences?a("span",[t._v(t._s(e.translation)+" ("+t._s(e.occurences)+")")]):t._e()]:[0!=e.occurences?a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id+"/worklogs/"+t.year+"/"+e.month}},[t._v(t._s(e.translation)+" ("+t._s(e.occurences)+")")]):t._e(),t._v(" "),0==e.occurences?a("span",[t._v(t._s(e.translation)+" ("+t._s(e.occurences)+")")]):t._e()]],2)}))],2)]),t._v(" "),a("div",{staticClass:"fl-ns w-two-thirds-ns pa3"},[t._l(t.worklogs,(function(e){return a("div",{key:e.id},[a("p",{staticClass:"mt0 f6 mb1 silver"},[t._v(t._s(e.localized_created_at))]),t._v(" "),a("div",{staticClass:"parsed-content",domProps:{innerHTML:t._s(e.parsed_content)}}),t._v(" "),a("div",{staticClass:"tc mb3 green"},[t._v("\n                ~\n              ")])])})),t._v(" "),0==t.worklogs.length?a("p",{staticClass:"tc mt5"},[t._v(t._s(t.$t("employee.worklog_blank_state_for_month")))]):t._e()],2)])]:[a("p",{staticClass:"tc pa3",attrs:{"data-cy":"blank-worklog-message"}},[t._v(t._s(t.$t("employee.worklog_blank")))])]],2)])])}),[],!1,null,"1810ec28",null);e.default=o.exports},Zdyt:function(t,e,a){"use strict";var s=a("ASc9");a.n(s).a}}]);
//# sourceMappingURL=44.js.map?id=28bfd7b7f9b3e9b6e8e7