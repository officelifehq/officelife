(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{"7oz0":function(t,e,r){"use strict";r.r(e);var n=r("+SZM"),a=r("pF+r"),s=r("rrJu"),o=r("Z84v"),i={components:{Layout:n.a,TextInput:a.a,Errors:s.a,LoadingButton:o.a},props:{company:{type:Object,default:null},user:{type:Object,default:null},notifications:{type:Array,default:null}},data:function(){return{form:{name:null,errors:[]},loadingState:"",errorTemplate:Error}},methods:{submit:function(){var t=this;this.loadingState="loading",this.$inertia.post(this.route("company.store"),this.form).then((function(){return t.loadingState=null}))}}},l=r("KHd+"),u=Object(l.a)(i,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("layout",{attrs:{title:"Home","no-menu":!0,notifications:t.notifications}},[r("div",{staticClass:"ph2 ph0-ns"},[r("div",{staticClass:"cf mt4 mw7 center br3 mb3 bg-white box"},[r("div",{staticClass:"fn fl-ns w-50-ns pa3"},[t._v("\n        Create a company\n      ")]),t._v(" "),r("div",{staticClass:"fn fl-ns w-50-ns pa3"},[r("errors",{attrs:{errors:t.form.errors}}),t._v(" "),r("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[r("text-input",{attrs:{name:"name",errors:t.$page.errors.name,label:t.$t("company.new_name"),required:!0},model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}}),t._v(" "),r("div",{},[r("div",{staticClass:"flex-ns justify-between"},[r("div",[r("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3",state:t.loadingState,text:"register","data-cy":"create-company-submit"}})],1)])])],1)],1)])])])}),[],!1,null,null,null);e.default=u.exports},Eswu:function(t,e,r){(t.exports=r("I1BE")(!1)).push([t.i,".error-explanation[data-v-df38947a] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-df38947a]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}\n.optional-badge[data-v-df38947a] {\n  border-radius: 4px;\n  color: #283e59;\n  background-color: #edf2f9;\n  padding: 3px 4px;\n}",""])},FUPM:function(t,e,r){var n=r("GqqJ");"string"==typeof n&&(n=[[t.i,n,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(n,a);n.locals&&(t.exports=n.locals)},GqqJ:function(t,e,r){(t.exports=r("I1BE")(!1)).push([t.i,".border-red[data-v-1482dffa] {\n  background-color: #fff5f5;\n  border-color: #fc8181;\n  color: #c53030;\n}",""])},PAcC:function(t,e,r){var n=r("Eswu");"string"==typeof n&&(n=[[t.i,n,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(n,a);n.locals&&(t.exports=n.locals)},"SL/y":function(t,e,r){"use strict";var n=r("FUPM");r.n(n).a},l6R9:function(t,e,r){"use strict";var n=r("PAcC");r.n(n).a},"pF+r":function(t,e,r){"use strict";var n={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},min:{type:Number,default:0},max:{type:Number,default:0},errors:{type:Array,default:function(){return[]}}},computed:{hasError:function(){return!!(this.errors.length>0&&this.required)}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},a=(r("l6R9"),r("KHd+")),s=Object(a.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{class:t.extraClassUpperDiv},[t.label?r("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v("\n    "+t._s(t.label)+"\n    "),t.required?t._e():r("span",{staticClass:"optional-badge f7"},[t._v("\n      "+t._s(t.$t("app.optional"))+"\n    ")])]):t._e(),t._v(" "),r("input",t._b({ref:t.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",attrs:{id:t.id,required:t.required,type:t.type,name:t.name,max:t.max,min:t.min,placeholder:t.placeholder,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)},keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"])?null:t.sendEscKey(e)}}},"input",t.$attrs,!1)),t._v(" "),t.hasError?r("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?r("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])}),[],!1,null,"df38947a",null);e.a=s.exports},rrJu:function(t,e,r){"use strict";var n={props:{errors:{type:Array,default:function(){return[]}}}},a=(r("SL/y"),r("KHd+")),s=Object(a.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return t.errors.length>0?r("div",{staticClass:"border-red ba br3 pa3"},[r("p",{staticClass:"mv0 fw6"},[t._v(t._s(t.$t("app.error_title")))]),t._v(" "),r("p",{staticClass:"mb0"},[t._v(t._s(t.errors[0]))])]):t._e()}),[],!1,null,"1482dffa",null);e.a=s.exports}}]);
//# sourceMappingURL=22.js.map?id=aa71a3c83b7c90771962