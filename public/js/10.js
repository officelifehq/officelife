(window.webpackJsonp=window.webpackJsonp||[]).push([[10,17],{Eswu:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,".error-explanation[data-v-df38947a] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-df38947a]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}\n.optional-badge[data-v-df38947a] {\n  border-radius: 4px;\n  color: #283e59;\n  background-color: #edf2f9;\n  padding: 3px 4px;\n}",""])},Nmnh:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,".avatar[data-v-37c2a441] {\n  left: 1px;\n  top: 5px;\n  width: 35px;\n}\n.team-member[data-v-37c2a441] {\n  padding-left: 44px;\n}\n.team-member .avatar[data-v-37c2a441] {\n  top: 2px;\n}\n.ball-pulse[data-v-37c2a441] {\n  right: 8px;\n  top: 37px;\n  position: absolute;\n}",""])},PAcC:function(e,t,a){var s=a("Eswu");"string"==typeof s&&(s=[[e.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(e.exports=s.locals)},PT7U:function(e,t,a){"use strict";var s=a("TyNh");a.n(s).a},PufQ:function(e,t,a){"use strict";a.r(t);var s=a("pF+r"),i=(a("zHN7"),a("mM8D")),n={components:{TextInput:s.a,BallPulseLoader:i.a},props:{team:{type:Object,default:null},employees:{type:Array,default:null}},data:function(){return{editMode:!1,form:{searchTerm:null,errors:[]},processingSearch:!1,loadingState:"",errorTemplate:Error,potentialMembers:[],listOfEmployees:{type:Array,default:[]}}},mounted:function(){this.listOfEmployees=this.employees},methods:{load:function(e){this.$inertia.visit("/"+this.$page.auth.company.id+"/employees/"+e.id)},search:_.debounce((function(){var e=this;""!=this.form.searchTerm?(this.processingSearch=!0,axios.post("/"+this.$page.auth.company.id+"/teams/"+this.team.id+"/members/search",this.form).then((function(t){e.potentialMembers=t.data.data,e.processingSearch=!1})).catch((function(t){e.form.errors=_.flatten(_.toArray(t.response.data)),e.processingSearch=!1,e.addedEmployeeId=0}))):(this.potentialMembers=[],this.addedEmployeeId=0)}),500),add:function(e){var t=this;axios.post("/"+this.$page.auth.company.id+"/teams/"+this.team.id+"/members/attach/"+e.id).then((function(e){t.listOfEmployees.push(e.data.data),t.potentialMembers=[],t.form.searchTerm="",t.$snotify.success(t.$t("account.employee_statuses_success_destroy"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0})})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data)),t.potentialMembers=[],t.form.searchTerm=""}))},detach:function(e){var t=this;axios.post("/"+this.$page.auth.company.id+"/teams/"+this.team.id+"/members/detach/"+e.id).then((function(a){var s=t.listOfEmployees.findIndex((function(t){return t.id===e.id}));t.listOfEmployees.splice(s,1),t.$snotify.success(t.$t("account.employee_statuses_success_destroy"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0})})).catch((function(e){t.form.errors=_.flatten(_.toArray(e.response.data))}))}}},r=(a("PT7U"),a("KHd+")),l=Object(r.a)(n,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("h3",{staticClass:"db fw5 mb3 flex justify-between items-center"},[a("span",[e._v("🤼‍♀️ "+e._s(e.$tc("team.count_team_members",e.listOfEmployees.length,{count:e.listOfEmployees.length})))]),e._v(" "),!e.editMode&&e.$page.auth.employee.permission_level<=200?a("a",{staticClass:"btn btn-secondary f5",attrs:{href:"","data-cy":"manage-team-on"},on:{click:function(t){t.preventDefault(),e.editMode=!0}}},[e._v(e._s(e.$t("team.members_enable_manage_mode")))]):e._e(),e._v(" "),e.editMode&&e.$page.auth.employee.permission_level<=200?a("a",{staticClass:"btn btn-secondary f5",attrs:{href:"","data-cy":"manage-team-off"},on:{click:function(t){t.preventDefault(),e.editMode=!1}}},[e._v(e._s(e.$t("team.members_disable_manage_mode")))]):e._e()]),e._v(" "),a("div",{staticClass:"mb4 bg-white box cf"},[a("div",{directives:[{name:"show",rawName:"v-show",value:e.editMode,expression:"editMode"}],staticClass:"bb bb-gray pa3"},[a("form",{staticClass:"relative",on:{submit:function(t){return t.preventDefault(),e.search(t)}}},[a("text-input",{attrs:{id:"title",name:"title",datacy:"member-input",errors:e.$page.errors.title,label:e.$t("team.members_add_input"),placeholder:e.$t("team.members_add_input_help"),required:!1},on:{keyup:e.search,input:e.search},model:{value:e.form.searchTerm,callback:function(t){e.$set(e.form,"searchTerm",t)},expression:"form.searchTerm"}}),e._v(" "),e.processingSearch?a("ball-pulse-loader",{attrs:{color:"#5c7575",size:"7px"}}):e._e()],1),e._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:e.potentialMembers.length>0,expression:"potentialMembers.length > 0"}],staticClass:"list pl0 ba bb-gray bb-gray-hover"},e._l(e.potentialMembers,(function(t){return a("li",{key:t.id,staticClass:"relative pa2 bb bb-gray"},[e._v("\n          "+e._s(t.name)+"\n          "),a("a",{staticClass:"fr f6",attrs:{href:"","data-cy":"employee-id-"+t.id},on:{click:function(a){return a.preventDefault(),e.add(t)}}},[e._v(e._s(e.$t("team.members_add_cta")))])])})),0),e._v(" "),a("ul",{directives:[{name:"show",rawName:"v-show",value:0==e.potentialMembers.length&&e.form.searchTerm,expression:"potentialMembers.length == 0 && form.searchTerm"}],staticClass:"list pl0 ba bb-gray bb-gray-hover"},[a("li",{staticClass:"relative pa2 bb bb-gray"},[e._v("\n          "+e._s(e.$t("team.members_no_results"))+"\n        ")])])]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.listOfEmployees.length>0,expression:"listOfEmployees.length > 0"}],staticClass:"pa3"},e._l(e.listOfEmployees,(function(t){return a("div",{key:t.id,staticClass:"fl w-third-l w-100 mb4",attrs:{"data-cy":"members-list"}},[a("span",{staticClass:"pl3 db relative team-member"},[a("img",{staticClass:"br-100 absolute avatar",attrs:{src:t.avatar}}),e._v(" "),a("inertia-link",{directives:[{name:"show",rawName:"v-show",value:!e.editMode,expression:"!editMode"}],staticClass:"mb2",attrs:{href:"/"+e.$page.auth.company.id+"/employees/"+t.id}},[e._v("\n            "+e._s(t.name)+"\n          ")]),e._v(" "),t.position?a("span",{directives:[{name:"show",rawName:"v-show",value:!e.editMode,expression:"!editMode"}],staticClass:"title db f7 mt1"},[e._v("\n            "+e._s(t.position.title)+"\n          ")]):a("span",{directives:[{name:"show",rawName:"v-show",value:!e.editMode,expression:"!editMode"}],staticClass:"title db f7 mt1"},[e._v("\n            "+e._s(e.$t("app.no_position_defined"))+"\n          ")]),e._v(" "),a("span",{directives:[{name:"show",rawName:"v-show",value:e.editMode,expression:"editMode"}],staticClass:"mb2"},[e._v("\n            "+e._s(t.name)+"\n          ")]),e._v(" "),a("a",{directives:[{name:"show",rawName:"v-show",value:e.editMode,expression:"editMode"}],staticClass:"title db f7 mt1 c-delete dib",attrs:{href:"","data-cy":"remove-employee-"+t.id},on:{click:function(a){return a.preventDefault(),e.detach(t)}}},[e._v("\n            "+e._s(e.$t("team.members_remove"))+"\n          ")])],1)])})),0),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:0==e.listOfEmployees.length,expression:"listOfEmployees.length == 0"}],staticClass:"pa3 tc",attrs:{"data-cy":"members-list-blank-state"}},[a("p",{staticClass:"mv0"},[e._v(e._s(e.$t("team.members_blank")))])])])])}),[],!1,null,"37c2a441",null);t.default=l.exports},SQv9:function(e,t,a){(e.exports=a("I1BE")(!1)).push([e.i,".avatar[data-v-fb57dd54] {\n  left: 1px;\n  top: 5px;\n  width: 35px;\n}\n.team-lead[data-v-fb57dd54] {\n  padding-left: 44px;\n}\n.useful-link[data-v-fb57dd54] {\n  top: 6px;\n}\n.team-title[data-v-fb57dd54] {\n  background-color: #2E3748;\n  color: #fff;\n  border-top-right-radius: 6px;\n  border-top-left-radius: 6px;\n}\n.news-information img[data-v-fb57dd54] {\n  top: 7px;\n  width: 23px;\n}",""])},TyNh:function(e,t,a){var s=a("Nmnh");"string"==typeof s&&(s=[[e.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(e.exports=s.locals)},cNfR:function(e,t,a){var s=a("SQv9");"string"==typeof s&&(s=[[e.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(e.exports=s.locals)},l6R9:function(e,t,a){"use strict";var s=a("PAcC");a.n(s).a},"pF+r":function(e,t,a){"use strict";var s={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},min:{type:Number,default:0},max:{type:Number,default:0},errors:{type:Array,default:function(){return[]}}},computed:{hasError:function(){return!!(this.errors.length>0&&this.required)}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(e,t){this.$refs.input.setSelectionRange(e,t)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},i=(a("l6R9"),a("KHd+")),n=Object(i.a)(s,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{class:e.extraClassUpperDiv},[e.label?a("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:e.id}},[e._v("\n    "+e._s(e.label)+"\n    "),e.required?e._e():a("span",{staticClass:"optional-badge f7"},[e._v("\n      "+e._s(e.$t("app.optional"))+"\n    ")])]):e._e(),e._v(" "),a("input",e._b({ref:e.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:e.id,required:e.required,type:e.type,name:e.name,max:e.max,min:e.min,placeholder:e.placeholder,"data-cy":e.datacy},domProps:{value:e.value},on:{input:function(t){return e.$emit("input",t.target.value)},keydown:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"esc",27,t.key,["Esc","Escape"])?null:e.sendEscKey(t)}}},"input",e.$attrs,!1)),e._v(" "),e.hasError?a("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[e._v("\n    "+e._s(e.errors[0])+"\n  ")]):e._e(),e._v(" "),e.help?a("p",{staticClass:"f7 mb3 lh-title"},[e._v("\n    "+e._s(e.help)+"\n  ")]):e._e()])}),[],!1,null,"df38947a",null);t.a=n.exports},wIoM:function(e,t,a){"use strict";a.r(t);var s=a("+SZM"),i=a("IQOQ"),n=a.n(i),r=a("PufQ"),l={components:{Layout:s.a,Members:r.default},directives:{clickOutside:n.a.directive},props:{notifications:{type:Array,default:null},employees:{type:Array,default:null},mostRecentEmployee:{type:String,default:null},employeeCount:{type:Number,default:null},team:{type:Object,default:null},news:{type:Array,default:null},newsCount:{type:Number,default:null}},data:function(){return{}},mounted:function(){localStorage.success&&(this.$snotify.success(localStorage.success,{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),localStorage.clear())},methods:{goToNews:function(){this.$inertia.visit("/"+this.$page.auth.company.id+"/teams/"+this.team.id+"/news")}}},o=(a("ww1G"),a("KHd+")),c=Object(o.a)(l,(function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("layout",{attrs:{title:"Home",notifications:e.notifications}},[a("div",{staticClass:"ph2 ph5-ns"},[a("div",{staticClass:"mt4-l mt1 mb3 mw7 br3 center breadcrumb relative z-0 f6 pb2"},[a("ul",{staticClass:"list ph0 tc-l tl"},[a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+e.$page.auth.company.id+"/dashboard"}},[e._v(e._s(e.$page.auth.company.name))])],1),e._v(" "),a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+e.$page.auth.company.id+"/teams"}},[e._v(e._s(e.$t("app.breadcrumb_team_list")))])],1),e._v(" "),a("li",{staticClass:"di"},[e._v("\n          "+e._s(e.team.name)+"\n        ")])])]),e._v(" "),a("div",{staticClass:"cf mw8 center"},[a("div",{staticClass:"fl w-30-l w-100"},[a("div",{staticClass:"bg-white box mb4"},[a("h2",{staticClass:"bb bb-gray pa3 ma0 fw5 team-title"},[e._v("\n            "+e._s(e.team.name)+"\n          ")]),e._v(" "),a("p",{staticClass:"lh-copy ma0 pa3 bb bb-gray"},[e._v("\n            "+e._s(e.team.parsed_description)+"\n          ")]),e._v(" "),a("p",{staticClass:"lh-copy ma0 pa3 bb bb-gray"},[a("a",{staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer"},[e._v("Add a team description")])]),e._v(" "),a("p",{staticClass:"lh-copy ma0 pa3 bb bb-gray"},[e._v("\n            This team has "+e._s(e.employeeCount)+" members, the most recent being sdfsd.\n          ")]),e._v(" "),a("p",{staticClass:"lh-copy ma0 pa3 bb bb-gray"},[a("a",{staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer"},[e._v("Assign a team lead")])]),e._v(" "),a("div",{staticClass:"lh-copy ma0 pa3 bb bb-gray"},[a("p",{staticClass:"silver f6 ma0 mb1"},[e._v("Team lead")]),e._v(" "),a("span",{staticClass:"pl3 db team-lead relative"},[a("img",{staticClass:"br-100 absolute avatar",attrs:{src:"https://api.adorable.io/avatars/200/f25dbf67-01c1-4d12-91a2-927053fbf671.png"}}),e._v(" "),a("inertia-link",{staticClass:"mb2",attrs:{href:"/"+e.$page.auth.company.id+"/employees/"}},[e._v("\n                JOhn Doe\n              ")]),e._v(" "),a("span",{staticClass:"db f7 mt1"},[e._v("\n                "+e._s(e.$t("app.no_position_defined"))+"\n              ")])],1)]),e._v(" "),a("div",{staticClass:"ma0 pa3"},[a("p",{staticClass:"silver f6 ma0 mb1"},[e._v("Links")]),e._v(" "),a("ul",{staticClass:"list pl0 mb0"},[a("li",{staticClass:"mb2 relative"},[a("img",{staticClass:"relative useful-link",attrs:{src:"/img/slack.svg"}}),e._v(" "),a("a",{staticClass:"relative ml1",attrs:{href:""}},[e._v("sadfsadfs")])]),e._v(" "),a("li",{staticClass:"mb2 relative"},[a("img",{staticClass:"relative useful-link",attrs:{src:"/img/mail.svg"}}),e._v(" "),a("a",{staticClass:"relative ml1",attrs:{href:""}},[e._v("sadfsadfs")])]),e._v(" "),a("li",{staticClass:"mt3"},[a("a",{staticClass:"bb b--dotted bt-0 bl-0 br-0 pointer f6",attrs:{href:""}},[a("span",[e._v("+")]),e._v(" Add a new link")])])])])])]),e._v(" "),a("div",{staticClass:"fl w-70-l w-100 pl4-l"},[a("members",{attrs:{employees:e.employees,team:e.team}}),e._v(" "),a("h3",{staticClass:"db fw5 mb3 flex justify-between items-center"},[a("span",[e._v("🗞 "+e._s(e.$tc("team.count_team_news",e.newsCount,{count:e.newsCount})))]),e._v(" "),a("inertia-link",{staticClass:"btn btn-secondary f5",attrs:{href:"/"+e.$page.auth.company.id+"/teams/"+e.team.id+"/news/create","data-cy":"add-team-news"}},[e._v(e._s(e.$t("team.news_write")))])],1),e._v(" "),a("div",{staticClass:"mb4"},[e.news.length>0?a("div",[a("div",{staticClass:"bg-white box cf mb4 relative",attrs:{"data-cy":"news-list"}},[e._l(e.news,(function(t){return a("div",{key:t.id,staticClass:"pa3 bb bb-gray"},[a("h3",{staticClass:"mt0 mb0-ns mb2 normal pointer",on:{click:function(t){return t.preventDefault(),e.goToNews()}}},[e._v("\n                  "+e._s(t.title)+"\n                ")]),e._v(" "),a("div",{staticClass:"f6 relative news-information silver"},[a("img",{staticClass:"br-100 relative mr1 dib-ns dn",attrs:{src:t.author.avatar}}),e._v("\n                  "+e._s(e.$t("team.team_news_written_by_at",{name:t.author.name,created_at:t.localized_created_at}))+"\n                ")])])})),e._v(" "),a("div",{staticClass:"pa3 tc"},[a("inertia-link",{attrs:{href:"/"+e.$page.auth.company.id+"/teams/"+e.team.id+"/news","data-cy":"view-all-news"}},[e._v(e._s(e.$t("team.news_view_all")))])],1)],2)]):a("div",{staticClass:"bg-white box pa3 cf news mb4 tc"},[e._v("\n            "+e._s(e.$t("team.news_blank"))+"\n          ")])])],1)])])])}),[],!1,null,"fb57dd54",null);t.default=c.exports},ww1G:function(e,t,a){"use strict";var s=a("cNfR");a.n(s).a}}]);
//# sourceMappingURL=10.js.map?id=044262c26363ba502ed1