(window.webpackJsonp=window.webpackJsonp||[]).push([[14],{"16MF":function(t,e,r){var n=r("SVIm");"string"==typeof n&&(n=[[t.i,n,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};r("aET+")(n,a);n.locals&&(t.exports=n.locals)},"1PdU":function(t,e,r){"use strict";r.r(e);var n=r("pF+r"),a=r("rrJu"),s=r("Z84v"),l={components:{TextInput:n.a,Errors:a.a,LoadingButton:s.a},data:function(){return{form:{email:null,password:null,errors:[]},loadingState:"",errorTemplate:Error}},mounted:function(){document.title="Login | Ping CRM"},methods:{submit:function(){var t=this;this.loadingState="loading",this.$inertia.post(this.route("login.attempt"),this.form).then((function(){return t.loadingState=null}))}}},i=r("KHd+"),o=Object(i.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"ph2 ph0-ns"},[r("div",{staticClass:"cf mt4 mw7 center br3 mb3 bg-white box"},[r("div",{staticClass:"fn fl-ns w-50-ns pa3"},[t._v("\n      Login\n    ")]),t._v(" "),r("div",{staticClass:"fn fl-ns w-50-ns pa3"},[r("errors",{attrs:{errors:t.$page.errors}}),t._v(" "),r("form",{on:{submit:function(e){return e.preventDefault(),t.submit(e)}}},[r("text-input",{attrs:{name:"email",errors:t.$page.errors.email,label:t.$t("auth.register_email")},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}}),t._v(" "),r("text-input",{attrs:{name:"password",errors:t.$page.errors.password,type:"password",label:t.$t("auth.register_password")},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}}),t._v(" "),r("div",{staticClass:"flex-ns justify-between"},[r("loading-button",{attrs:{classes:"btn add w-auto-ns w-100 mb2 pv2 ph3",state:t.loadingState,text:t.$t("auth.login_cta")}})],1)],1)],1)])])}),[],!1,null,null,null);e.default=o.exports},"FMG/":function(t,e,r){"use strict";var n=r("16MF");r.n(n).a},SVIm:function(t,e,r){(t.exports=r("I1BE")(!1)).push([t.i,".error-explanation[data-v-8778c8da] {\n  background-color: #fde0de;\n  border-color: #e2aba7;\n}\n.error[data-v-8778c8da]:focus {\n  box-shadow: 0 0 0 1px #fff9f8;\n}",""])},Z84v:function(t,e,r){"use strict";r("zHN7");var n={components:{BallPulseLoader:r("mM8D").a},props:{text:{type:String,default:""},state:{type:String,default:""},classes:{type:String,default:""},cypressSelector:{type:String,default:""}}},a=r("KHd+"),s=Object(a.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"di"},[r("button",{class:t.classes,attrs:{name:"save",type:"submit","data-cy":t.cypressSelector}},["loading"==t.state?r("ball-pulse-loader",{attrs:{color:"#fff",size:"7px"}}):t._e(),t._v(" "),"loading"!=t.state?r("span",[t._v("\n      "+t._s(t.text)+"\n    ")]):t._e()],1)])}),[],!1,null,null,null);e.a=s.exports},"pF+r":function(t,e,r){"use strict";var n={inheritAttrs:!1,props:{id:{type:String,default:function(){return"text-input-".concat(this._uid)}},type:{type:String,default:"text"},value:{type:String,default:""},customRef:{type:String,default:"input"},name:{type:String,default:"input"},datacy:{type:String,default:""},placeholder:{type:String,default:""},help:{type:String,default:""},label:{type:String,default:""},required:{type:Boolean,default:!1},extraClassUpperDiv:{type:String,default:"mb3"},errors:{type:Array,default:function(){return[]}}},methods:{focus:function(){this.$refs.input.focus()},select:function(){this.$refs.input.select()},setSelectionRange:function(t,e){this.$refs.input.setSelectionRange(t,e)},sendEscKey:function(){this.$emit("esc-key-pressed")}}},a=(r("FMG/"),r("KHd+")),s=Object(a.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{class:t.extraClassUpperDiv},[t.label?r("label",{staticClass:"db fw4 lh-copy f6",attrs:{for:t.id}},[t._v("\n    "+t._s(t.label)+"\n  ")]):t._e(),t._v(" "),r("input",t._b({ref:t.customRef,staticClass:"br2 f5 w-100 ba b--black-40 pa2 outline-0",class:{error:t.errors.length},attrs:{id:t.id,required:t.required?"required":"",type:t.type,name:t.name,placeholder:t.placeholder,"data-cy":t.datacy},domProps:{value:t.value},on:{input:function(e){return t.$emit("input",e.target.value)},keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"esc",27,e.key,["Esc","Escape"])?null:t.sendEscKey(e)}}},"input",t.$attrs,!1)),t._v(" "),t.errors.length?r("div",{staticClass:"error-explanation pa3 ba br3 mt1"},[t._v("\n    "+t._s(t.errors[0])+"\n  ")]):t._e(),t._v(" "),t.help?r("p",{staticClass:"f7 mb3 lh-title"},[t._v("\n    "+t._s(t.help)+"\n  ")]):t._e()])}),[],!1,null,"8778c8da",null);e.a=s.exports},rrJu:function(t,e,r){"use strict";var n={props:{errors:{type:Object,default:null}}},a=r("KHd+"),s=Object(a.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return Object.keys(t.errors).length>0?r("div",[r("p",[t._v("app.error_title")]),t._v(" "),r("br"),t._v(" "),t._l(t.errors,(function(e){return r("ul",{key:e.id},t._l(e,(function(e){return r("li",{key:e.id},[t._v("\n      "+t._s(e)+"\n    ")])})),0)}))],2):t._e()}),[],!1,null,null,null);e.a=s.exports}}]);
//# sourceMappingURL=14.js.map?id=0a44fb286a81b648bc5d