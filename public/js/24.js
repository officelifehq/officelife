(window.webpackJsonp=window.webpackJsonp||[]).push([[24],{"6K+3":function(t,e,a){var s=a("v37U");"string"==typeof s&&(s=[[t.i,s,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,r);s.locals&&(t.exports=s.locals)},EcYk:function(t,e,a){"use strict";a.r(e);var s=a("pF+r"),r=a("cwCQ"),i=a("rrJu"),n=a("Z84v"),o={components:{Layout:a("+SZM").a,TextInput:s.a,Errors:i.a,SelectBox:r.a,LoadingButton:n.a},props:{notifications:{type:Array,default:null},countries:{type:Array,default:null},employee:{type:Object,default:null}},data:function(){return{form:{street:null,city:null,state:null,postal_code:null,country_id:null,errors:{type:Array,default:null}},existing_address:{street:"",city:"",state:"",postal_code:"",country_id:0},loadingState:"",errorTemplate:Error}},created:function(){null!==this.employee.address&&(this.form.city=this.employee.address.city,this.form.street=this.employee.address.street,this.form.state=this.employee.address.province,this.form.postal_code=this.employee.address.postal_code,this.form.country_id={label:this.employee.address.country.name,value:this.employee.address.country.id})},methods:{submit:function(){var t=this;this.loadingState="loading",this.form.country_id=this.form.country_id.value,axios.post("/"+this.$page.auth.company.id+"/employees/"+this.employee.id+"/address/update",this.form).then((function(e){localStorage.success=t.$t("employee.edit_information_success"),t.$inertia.visit("/"+t.$page.auth.company.id+"/employees/"+t.employee.id)})).catch((function(e){t.loadingState=null,t.form.errors=_.flatten(_.toArray(e.response.data))}))}}},l=(a("ag+1"),a("KHd+")),d=Object(l.a)(o,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("layout",{attrs:{title:"Home",notifications:t.notifications}},[a("div",{staticClass:"ph2 ph0-ns"},[a("div",{staticClass:"mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"},[a("ul",{staticClass:"list ph0 tc-l tl"},[a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/dashboard"}},[t._v(t._s(t.$page.auth.company.name))])],1),t._v(" "),a("li",{staticClass:"di"},[t._v("\n          ...\n        ")]),t._v(" "),a("li",{staticClass:"di"},[a("inertia-link",{attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id,"data-cy":"breadcrumb-employee"}},[t._v(t._s(t.employee.name))])],1),t._v(" "),a("li",{staticClass:"di"},[t._v("\n          "+t._s(t.$t("app.breadcrumb_employee_edit"))+"\n        ")])])]),t._v(" "),a("div",{staticClass:"mw7 center br3 mb5 bg-white box relative z-1"},[a("div",{},[a("h2",{staticClass:"pa3 mt2 center tc normal mb2"},[t._v("\n          "+t._s(t.$t("employee.edit_information_title"))+"\n        ")]),t._v(" "),a("div",{staticClass:"cf w-100"},[a("ul",{staticClass:"list pl0 db tc bb bb-gray pa2 edit-information-menu"},[a("li",{staticClass:"di mr2"},[a("inertia-link",{staticClass:"no-underline ph3 pv2 bb-0 bt bl br bb-gray br--top br2 z-3",attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id+"/edit","data-cy":"menu-profile-link"}},[t._v("\n                "+t._s(t.$t("employee.edit_information_menu"))+"\n              ")])],1),t._v(" "),a("li",{staticClass:"di"},[a("inertia-link",{staticClass:"no-underline ph3 pv2 bb-0 bt bl br bb-gray br--top br2 z-3 bg-white selected",attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id+"/address/edit","data-cy":"menu-address-link"}},[t._v("\n                "+t._s(t.$t("employee.edit_information_menu_address"))+"\n              ")])],1)])]),t._v(" "),a("form",{on:{submit:function(e){return e.preventDefault(),t.submit()}}},[a("errors",{attrs:{errors:t.form.errors}}),t._v(" "),a("div",{staticClass:"cf pa3 bb bb-gray pb4"},[a("div",{staticClass:"fl-ns w-third-ns w-100 mb3 mb0-ns"},[a("strong",[t._v(t._s(t.$t("employee.edit_information_address")))]),t._v(" "),a("p",{staticClass:"f7 silver lh-copy pr3-ns"},[t._v("\n                "+t._s(t.$t("employee.edit_information_address_help"))+"\n              ")])]),t._v(" "),a("div",{staticClass:"fl-ns w-two-thirds-ns w-100"},[a("text-input",{attrs:{id:"street",name:"street",errors:t.$page.errors.street,label:t.$t("employee.edit_information_street"),required:!0},model:{value:t.form.street,callback:function(e){t.$set(t.form,"street",e)},expression:"form.street"}}),t._v(" "),a("div",{staticClass:"dt-ns dt--fixed di"},[a("div",{staticClass:"dtc-ns pr2-ns pb0-ns w-100 pb3"},[a("text-input",{attrs:{id:"city",name:"city",errors:t.$page.errors.city,label:t.$t("employee.edit_information_city"),required:!0},model:{value:t.form.city,callback:function(e){t.$set(t.form,"city",e)},expression:"form.city"}})],1),t._v(" "),a("div",{staticClass:"dtc-ns pr2-ns pb0-ns w-100 pb3"},[a("text-input",{attrs:{id:"state",name:"state",errors:t.$page.errors.state,label:t.$t("employee.edit_information_state")},model:{value:t.form.state,callback:function(e){t.$set(t.form,"state",e)},expression:"form.state"}})],1),t._v(" "),a("div",{staticClass:"dtc-ns pr2-ns pb0-ns w-100 pb3"},[a("text-input",{attrs:{id:"postal_code",name:"postal_code",errors:t.$page.errors.postal_code,label:t.$t("employee.edit_information_postal_code"),required:!0},model:{value:t.form.postal_code,callback:function(e){t.$set(t.form,"postal_code",e)},expression:"form.postal_code"}})],1)]),t._v(" "),a("select-box",{attrs:{id:"country_id",options:t.countries,name:"country_id",errors:t.$page.errors.country_id,label:t.$t("employee.edit_information_country"),placeholder:t.$t("app.choose_value"),required:!0,value:t.form.country_id,datacy:"country_selector"},model:{value:t.form.country_id,callback:function(e){t.$set(t.form,"country_id",e)},expression:"form.country_id"}})],1)]),t._v(" "),a("div",{staticClass:"cf pa3"},[a("div",{staticClass:"flex-ns justify-between"},[a("div",[a("inertia-link",{staticClass:"btn btn-secondary dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2",attrs:{href:"/"+t.$page.auth.company.id+"/employees/"+t.employee.id}},[t._v("\n                  "+t._s(t.$t("app.cancel"))+"\n                ")])],1),t._v(" "),a("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 pv2 ph3",state:t.loadingState,text:t.$t("app.save"),"cypress-selector":"submit-edit-employee-button"}})],1)])],1)])])])])}),[],!1,null,"3f7bf90a",null);e.default=d.exports},Eswu:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".error-explanation[data-v-df38947a] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-df38947a]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}\n.optional-badge[data-v-df38947a] {\n  border-radius: 4px;\n  color: #283e59;\n  background-color: #edf2f9;\n  padding: 3px 4px;\n}",""])},PAcC:function(t,e,a){var s=a("Eswu");"string"==typeof s&&(s=[[t.i,s,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,r);s.locals&&(t.exports=s.locals)},"ag+1":function(t,e,a){"use strict";var s=a("6K+3");a.n(s).a},l6R9:function(t,e,a){"use strict";var s=a("PAcC");a.n(s).a},"pF+r":function(t,e,a){"use strict";var s={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},min:{type:Number,default:0},max:{type:Number,default:0},errors:{type:Array,default:function(){return[]}}},computed:{hasError:function(){return!!(this.errors.length>0&&this.required)}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},r=(a("l6R9"),a("KHd+")),i=Object(r.a)(s,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{class:t.extraClassUpperDiv},[t.label?a("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v("\n    "+t._s(t.label)+"\n    "),t.required?t._e():a("span",{staticClass:"optional-badge f7"},[t._v("\n      "+t._s(t.$t("app.optional"))+"\n    ")])]):t._e(),t._v(" "),a("input",t._b({ref:t.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:t.id,required:t.required,type:t.type,name:t.name,max:t.max,min:t.min,placeholder:t.placeholder,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)},keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"])?null:t.sendEscKey(e)}}},"input",t.$attrs,!1)),t._v(" "),t.hasError?a("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?a("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])}),[],!1,null,"df38947a",null);e.a=i.exports},v37U:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".edit-information-menu a[data-v-3f7bf90a] {\n  border-bottom: 0;\n}\n.edit-information-menu .selected[data-v-3f7bf90a] {\n  color: #4d4d4f;\n}",""])}}]);
//# sourceMappingURL=24.js.map?id=b9df3e082eab6ced6a19