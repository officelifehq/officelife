(window.webpackJsonp=window.webpackJsonp||[]).push([[14],{"2Gah":function(t,a,e){"use strict";var s=e("TJcO");e.n(s).a},"4T6e":function(t,a,e){var s=e("JzQN");"string"==typeof s&&(s=[[t.i,s,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(s,o);s.locals&&(t.exports=s.locals)},"8vwF":function(t,a,e){"use strict";e.r(a);var s=e("pF+r"),o=e("Z84v"),n={components:{Layout:e("+SZM").a,TextInput:s.a,LoadingButton:o.a},props:{notifications:{type:Array,default:null},ptoPolicies:{type:Array,default:null}},data:function(){return{editModal:!1,loadingState:"",idToUpdate:0,localHolidays:null,form:{default_amount_of_allowed_holidays:null,default_amount_of_sick_days:null,default_amount_of_pto_days:null,errors:[]}}},methods:{isOff:function(t){return{weekend:0==t.day_of_week||6==t.day_of_week}},toggleUpdate:function(t){this.editModal?(this.localHolidays=null,this.idToUpdate=0,this.editModal=!1):(this.load(t),this.idToUpdate=t.id,this.form.default_amount_of_allowed_holidays=t.default_amount_of_allowed_holidays,this.form.default_amount_of_sick_days=t.default_amount_of_sick_days,this.form.default_amount_of_pto_days=t.default_amount_of_pto_days,this.editModal=!0)},toggleDayOff:function(t){console.log(t),t.is_worked=!1},load:function(t){var a=this;axios.get("/"+this.$page.auth.company.id+"/account/ptopolicies/"+t.id+"/getHolidays").then((function(t){a.localHolidays=t.data})).catch((function(t){a.form.errors=_.flatten(_.toArray(t.response.data))}))},update:function(t){var a=this;axios.put("/"+this.$page.auth.company.id+"/account/ptopolicies/"+t,this.form).then((function(e){a.$snotify.success(a.$t("account.pto_policies_update"),{timeout:2e3,showProgressBar:!0,closeOnClick:!0,pauseOnHover:!0}),a.idToUpdate=0,a.form.year=null,t=a.ptoPolicies.findIndex((function(a){return a.id===t})),a.$set(a.ptoPolicies,t,e.data.data)})).catch((function(t){a.form.errors=_.flatten(_.toArray(t.response.data))}))}}},i=(e("2Gah"),e("KHd+")),l=Object(i.a)(n,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("layout",{attrs:{title:"Home",notifications:t.notifications}},[e("div",{staticClass:"ph2 ph0-ns"},[e("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[e("ul",{staticClass:"list ph0 tc-l tl"},[e("li",{staticClass:"di"},[e("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v(t._s(t.$page.auth.company.name))])],1),t._v(" "),e("li",{staticClass:"di"},[e("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/account"}},[t._v(t._s(t.$t("app.breadcrumb_account_home")))])],1),t._v(" "),e("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_account_manage_pto_policies"))+"\n        ")])])]),t._v(" "),e("div",{staticClass:"mw8 center br3 mb5 bg-white box restricted relative z-1"},[e("div",{staticClass:"pa3 mt5"},[e("h2",{staticClass:"tc normal mb4"},[t._v("\n          "+t._s(t.$t("account.pto_policies_edit_title",{company:t.$page.auth.company.name}))+"\n        ")]),t._v(" "),e("p",{staticClass:"lh-copy"},[t._v("\n          "+t._s(t.$t("account.pto_policies_edit_title_1"))+"\n        ")]),t._v(" "),e("p",{staticClass:"lh-copy"},[t._v("\n          "+t._s(t.$t("account.pto_policies_edit_title_2"))+"\n        ")]),t._v(" "),e("p",{staticClass:"lh-copy"},[t._v("\n          "+t._s(t.$t("account.pto_policies_edit_title_3"))+"\n        ")]),t._v(" "),e("p",{staticClass:"lh-copy"},[t._v("\n          "+t._s(t.$t("account.pto_policies_edit_title_4"))+"\n        ")]),t._v(" "),e("ul",{staticClass:"list pl0 mv0 center ba br2 bb-gray",attrs:{"data-cy":"pto-policies-list"}},t._l(t.ptoPolicies,(function(a){return e("li",{key:a.id,staticClass:"pv3 ph3 bb bb-gray bb-gray-hover"},[e("h3",{staticClass:"ma0 mb3 f3 fw5 relative"},[t._v("\n              "+t._s(t.$t("account.pto_policies_edit_year",{year:a.year}))+" "),e("a",{staticClass:"pointer absolute right-0 f6 fw4 edit-link",attrs:{"data-cy":"list-edit-button-"+a.id},on:{click:function(e){return e.preventDefault(),t.toggleUpdate(a)}}},[t._v("\n                "+t._s(t.$t("app.edit"))+"\n              ")])]),t._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:t.idToUpdate!=a.id,expression:"idToUpdate != ptoPolicy.id"}],staticClass:"flex items-start-ns flex-wrap flex-nowrap-ns"},[e("div",{staticClass:"mb1 w-25-ns w-50 mr4-ns"},[e("p",{staticClass:"db mb0 mt0 f4 fw3"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_days",{number:a.total_worked_days}))+"\n                ")]),t._v(" "),e("p",{staticClass:"f7 mt1 mb0 fw3 grey"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_worked_days"))+"\n                ")])]),t._v(" "),e("div",{staticClass:"mb1 w-25-ns w-50 mr4-ns"},[e("p",{staticClass:"db mb0 mt0 f4 fw3"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_days",{number:a.default_amount_of_allowed_holidays}))+"\n                ")]),t._v(" "),e("p",{staticClass:"f7 mt1 mb0 fw3 grey"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_default_holidays"))+"\n                ")])]),t._v(" "),e("div",{staticClass:"mb1 w-25-ns w-50 mr4-ns"},[e("p",{staticClass:"db mb0 mt0 f4 fw3"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_days",{number:a.default_amount_of_sick_days}))+"\n                ")]),t._v(" "),e("p",{staticClass:"f7 mt1 mb0 fw3 grey"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_default_sick_days"))+"\n                ")])]),t._v(" "),e("div",{staticClass:"mb1 w-25-ns w-50"},[e("p",{staticClass:"db mb0 mt0 f4 fw3"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_days",{number:a.default_amount_of_pto_days}))+"\n                ")]),t._v(" "),e("p",{staticClass:"f7 mt1 mb0 fw3 grey"},[t._v("\n                  "+t._s(t.$t("account.pto_policies_stat_default_ptos"))+"\n                ")])])]),t._v(" "),e("div",{directives:[{name:"show",rawName:"v-show",value:t.idToUpdate==a.id,expression:"idToUpdate == ptoPolicy.id"}],staticClass:"cf mt3"},[e("form",{on:{submit:function(e){return e.preventDefault(),t.update(a.id)}}},[e("div",{staticClass:"dt-ns dt--fixed di"},[e("div",{staticClass:"dtc-ns pr2-ns pb0-ns w-100 pb3"},[e("text-input",{attrs:{id:"city",name:"city",errors:t.$page.errors.city,label:t.$t("employee.edit_information_city"),required:!0,type:"number",min:1,max:300},model:{value:t.form.default_amount_of_allowed_holidays,callback:function(a){t.$set(t.form,"default_amount_of_allowed_holidays",a)},expression:"form.default_amount_of_allowed_holidays"}})],1),t._v(" "),e("div",{staticClass:"dtc-ns pr2-ns pb0-ns w-100 pb3"},[e("text-input",{attrs:{id:"state",name:"state",errors:t.$page.errors.default_amount_of_sick_days,label:t.$t("employee.edit_information_state"),required:!0,type:"number",min:1,max:300},model:{value:t.form.default_amount_of_sick_days,callback:function(a){t.$set(t.form,"default_amount_of_sick_days",a)},expression:"form.default_amount_of_sick_days"}})],1),t._v(" "),e("div",{staticClass:"dtc-ns pr2-ns pb0-ns w-100 pb3"},[e("text-input",{attrs:{id:"postal_code",name:"postal_code",errors:t.$page.errors.default_amount_of_pto_days,label:t.$t("employee.edit_information_postal_code"),required:!0,type:"number",min:1,max:300},model:{value:t.form.default_amount_of_pto_days,callback:function(a){t.$set(t.form,"default_amount_of_pto_days",a)},expression:"form.default_amount_of_pto_days"}})],1)]),t._v(" "),e("p",[t._v("Edit the calendar below to add/remove holidays.")]),t._v(" "),e("div",{staticClass:"tc db mt3"},[e("table",{staticClass:"center"},[e("thead",[e("tr",{staticClass:"f6 tc"},[e("th",[t._v("Month")]),t._v(" "),e("th",[t._v("1")]),t._v(" "),e("th",[t._v("2")]),t._v(" "),e("th",[t._v("3")]),t._v(" "),e("th",[t._v("4")]),t._v(" "),e("th",[t._v("5")]),t._v(" "),e("th",[t._v("6")]),t._v(" "),e("th",[t._v("7")]),t._v(" "),e("th",[t._v("8")]),t._v(" "),e("th",[t._v("9")]),t._v(" "),e("th",[t._v("10")]),t._v(" "),e("th",[t._v("11")]),t._v(" "),e("th",[t._v("12")]),t._v(" "),e("th",[t._v("13")]),t._v(" "),e("th",[t._v("14")]),t._v(" "),e("th",[t._v("15")]),t._v(" "),e("th",[t._v("16")]),t._v(" "),e("th",[t._v("17")]),t._v(" "),e("th",[t._v("18")]),t._v(" "),e("th",[t._v("19")]),t._v(" "),e("th",[t._v("20")]),t._v(" "),e("th",[t._v("21")]),t._v(" "),e("th",[t._v("22")]),t._v(" "),e("th",[t._v("23")]),t._v(" "),e("th",[t._v("24")]),t._v(" "),e("th",[t._v("25")]),t._v(" "),e("th",[t._v("26")]),t._v(" "),e("th",[t._v("27")]),t._v(" "),e("th",[t._v("28")]),t._v(" "),e("th",[t._v("29")]),t._v(" "),e("th",[t._v("30")]),t._v(" "),e("th",[t._v("31")])])]),t._v(" "),e("tbody",t._l(t.localHolidays,(function(a){return e("tr",{key:a.id},t._l(a,(function(a){return e("td",{key:a.id,staticClass:"f6 tc"},[e("span",{staticClass:"ph1 pointer",class:t.isOff(a),on:{click:function(e){return e.preventDefault(),t.toggleDayOff(a)}}},[t._v("\n                            "+t._s(a.abbreviation)+"\n                          ")])])})),0)})),0)])]),t._v(" "),e("p",[t._v("Note: we'll recalculate the balance of holidays for all your employees based on these new numbers if you happen to change.")]),t._v(" "),e("div",{staticClass:"w-100 tr"},[e("a",{staticClass:"btn dib-l db mb2 mb0-ns",attrs:{"data-cy":"list-edit-cancel-button-"+a.id},on:{click:function(a){a.preventDefault(),t.idToUpdate=0}}},[t._v("\n                    "+t._s(t.$t("app.cancel"))+"\n                  ")]),t._v(" "),e("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3","data-cy":"list-edit-cta-button-"+a.id,state:t.loadingState,text:t.$t("app.update")}})],1)])])])})),0)])])])])}),[],!1,null,"5a4904d7",null);a.default=l.exports},JzQN:function(t,a,e){(t.exports=e("I1BE")(!1)).push([t.i,".error-explanation[data-v-533a82fc] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-533a82fc]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}\n.optional-badge[data-v-533a82fc] {\n  border-radius: 4px;\n  color: #283e59;\n  background-color: #edf2f9;\n  padding: 3px 4px;\n}",""])},LLkT:function(t,a,e){"use strict";var s=e("4T6e");e.n(s).a},TJcO:function(t,a,e){var s=e("eL0z");"string"==typeof s&&(s=[[t.i,s,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(s,o);s.locals&&(t.exports=s.locals)},eL0z:function(t,a,e){(t.exports=e("I1BE")(!1)).push([t.i,"\n.list li[data-v-5a4904d7]:last-child {\n  border-bottom: 0;\n}\n.weekend[data-v-5a4904d7] {\n  background-color: #FFE2AF;\n  border: 1px #ffbd49 solid;\n  border-radius: 11px;\n  color: #B00;\n}\ntd[data-v-5a4904d7], th[data-v-5a4904d7] {\n  padding-bottom: 5px;\n  padding-top: 5px;\n  border-bottom: 1px #ddd dotted;\n}\n.edit-link[data-v-5a4904d7] {\n  top: 8px;\n}\n",""])},"pF+r":function(t,a,e){"use strict";var s={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},min:{type:Number,default:0},max:{type:Number,default:0},errors:{type:Array,default:function(){return[]}}},computed:{hasError:function(){return this.errors.length>0}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,a){this.$refs.input.setSelectionRange(t,a)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},o=(e("LLkT"),e("KHd+")),n=Object(o.a)(s,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{class:t.extraClassUpperDiv},[t.label?e("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v("\n    "+t._s(t.label)+"\n    "),t.required?t._e():e("span",{staticClass:"optional-badge f7"},[t._v("\n      "+t._s(t.$t("app.optional"))+"\n    ")])]):t._e(),t._v(" "),e("input",t._b({ref:t.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:t.id,required:t.required,type:t.type,name:t.name,max:t.max,min:t.min,placeholder:t.placeholder,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(a){return t.$emit("input",a.target.value)},keydown:function(a){return!a.type.indexOf("key")&&t._k(a.keyCode,"esc",27,a.key,["Esc","Escape"])?null:t.sendEscKey(a)}}},"input",t.$attrs,!1)),t._v(" "),t.hasError?e("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?e("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])}),[],!1,null,"533a82fc",null);a.a=n.exports}}]);
//# sourceMappingURL=14.js.map?id=fb259f5264463269be09