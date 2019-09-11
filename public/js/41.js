(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[41],{

<<<<<<< HEAD
/***/ "./node_modules/v-click-outside/dist/v-click-outside.min.umd.js":
/*!**********************************************************************!*\
  !*** ./node_modules/v-click-outside/dist/v-click-outside.min.umd.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("!function(e,n){ true?module.exports=n():undefined}(this,function(){var e=\"undefined\"!=typeof window,n=\"undefined\"!=typeof navigator,t=e&&(\"ontouchstart\"in window||n&&navigator.msMaxTouchPoints>0)?[\"touchstart\",\"click\"]:[\"click\"],r=function(e){return e},i={instances:[]};function a(e){var n=\"function\"==typeof e;if(!n&&\"object\"!=typeof e)throw new Error(\"v-click-outside: Binding value must be a function or an object\");return{handler:n?e:e.handler,middleware:e.middleware||r,events:e.events||t,isActive:!(!1===e.isActive)}}function d(e){var n=e.el,t=e.event,r=e.handler,i=e.middleware;t.target!==n&&!n.contains(t.target)&&i(t,n)&&r(t,n)}function o(e){var n=e.el,t=e.handler,r=e.middleware;return{el:n,eventHandlers:e.events.map(function(e){return{event:e,handler:function(e){return d({event:e,el:n,handler:t,middleware:r})}}})}}function u(e){var n=i.instances.findIndex(function(n){return n.el===e});-1!==n&&(i.instances[n].eventHandlers.forEach(function(e){return document.removeEventListener(e.event,e.handler)}),i.instances.splice(n,1))}return i.bind=function(e,n){var t=a(n.value);if(t.isActive){var r=o({el:e,events:t.events,handler:t.handler,middleware:t.middleware});r.eventHandlers.forEach(function(e){var n=e.event,t=e.handler;return setTimeout(function(){return document.addEventListener(n,t)},0)}),i.instances.push(r)}},i.update=function(e,n){var t=n.value,r=n.oldValue;if(JSON.stringify(t)!==JSON.stringify(r)){var c=a(t),l=c.events,s=c.handler,v=c.middleware;if(c.isActive){var f=i.instances.find(function(n){return n.el===e});f?(f.eventHandlers.forEach(function(e){return document.removeEventListener(e.event,e.handler)}),f.eventHandlers=l.map(function(n){return{event:n,handler:function(n){return d({event:n,el:e,handler:s,middleware:v})}}})):(f=o({el:e,events:l,handler:s,middleware:v}),i.instances.push(f)),f.eventHandlers.forEach(function(e){var n=e.event,t=e.handler;return setTimeout(function(){return document.addEventListener(n,t)},0)})}else u(e)}},i.unbind=u,{install:function(e){e.directive(\"click-outside\",i)},directive:i}});\n//# sourceMappingURL=v-click-outside.min.min.umd.js.map\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvdi1jbGljay1vdXRzaWRlL2Rpc3Qvdi1jbGljay1vdXRzaWRlLm1pbi51bWQuanM/MjEwMyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQSxlQUFlLEtBQW9ELG9CQUFvQixTQUF3RSxDQUFDLGlCQUFpQixnTEFBZ0wsU0FBUyxJQUFJLGNBQWMsY0FBYywyQkFBMkIsNEdBQTRHLE9BQU8saUdBQWlHLGNBQWMsZ0RBQWdELG9EQUFvRCxjQUFjLHNDQUFzQyxPQUFPLDRDQUE0QyxPQUFPLDRCQUE0QixVQUFVLG9DQUFvQyxJQUFJLEdBQUcsY0FBYyx3Q0FBd0MsZ0JBQWdCLEVBQUUsMERBQTBELHVEQUF1RCwyQkFBMkIsNEJBQTRCLGlCQUFpQixlQUFlLFNBQVMsK0RBQStELEVBQUUsb0NBQW9DLDBCQUEwQiw2QkFBNkIsc0NBQXNDLElBQUksdUJBQXVCLHdCQUF3QiwyQkFBMkIsMENBQTBDLGlEQUFpRCxlQUFlLG1DQUFtQyxnQkFBZ0IsRUFBRSx1Q0FBdUMsdURBQXVELG9DQUFvQyxPQUFPLDRCQUE0QixVQUFVLG9DQUFvQyxJQUFJLFNBQVMscUNBQXFDLDJEQUEyRCwwQkFBMEIsNkJBQTZCLHNDQUFzQyxJQUFJLEVBQUUsV0FBVyxhQUFhLG9CQUFvQiwrQkFBK0IsY0FBYztBQUM5bUUiLCJmaWxlIjoiLi9ub2RlX21vZHVsZXMvdi1jbGljay1vdXRzaWRlL2Rpc3Qvdi1jbGljay1vdXRzaWRlLm1pbi51bWQuanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIhZnVuY3Rpb24oZSxuKXtcIm9iamVjdFwiPT10eXBlb2YgZXhwb3J0cyYmXCJ1bmRlZmluZWRcIiE9dHlwZW9mIG1vZHVsZT9tb2R1bGUuZXhwb3J0cz1uKCk6XCJmdW5jdGlvblwiPT10eXBlb2YgZGVmaW5lJiZkZWZpbmUuYW1kP2RlZmluZShuKTplW1widi1jbGljay1vdXRzaWRlXCJdPW4oKX0odGhpcyxmdW5jdGlvbigpe3ZhciBlPVwidW5kZWZpbmVkXCIhPXR5cGVvZiB3aW5kb3csbj1cInVuZGVmaW5lZFwiIT10eXBlb2YgbmF2aWdhdG9yLHQ9ZSYmKFwib250b3VjaHN0YXJ0XCJpbiB3aW5kb3d8fG4mJm5hdmlnYXRvci5tc01heFRvdWNoUG9pbnRzPjApP1tcInRvdWNoc3RhcnRcIixcImNsaWNrXCJdOltcImNsaWNrXCJdLHI9ZnVuY3Rpb24oZSl7cmV0dXJuIGV9LGk9e2luc3RhbmNlczpbXX07ZnVuY3Rpb24gYShlKXt2YXIgbj1cImZ1bmN0aW9uXCI9PXR5cGVvZiBlO2lmKCFuJiZcIm9iamVjdFwiIT10eXBlb2YgZSl0aHJvdyBuZXcgRXJyb3IoXCJ2LWNsaWNrLW91dHNpZGU6IEJpbmRpbmcgdmFsdWUgbXVzdCBiZSBhIGZ1bmN0aW9uIG9yIGFuIG9iamVjdFwiKTtyZXR1cm57aGFuZGxlcjpuP2U6ZS5oYW5kbGVyLG1pZGRsZXdhcmU6ZS5taWRkbGV3YXJlfHxyLGV2ZW50czplLmV2ZW50c3x8dCxpc0FjdGl2ZTohKCExPT09ZS5pc0FjdGl2ZSl9fWZ1bmN0aW9uIGQoZSl7dmFyIG49ZS5lbCx0PWUuZXZlbnQscj1lLmhhbmRsZXIsaT1lLm1pZGRsZXdhcmU7dC50YXJnZXQhPT1uJiYhbi5jb250YWlucyh0LnRhcmdldCkmJmkodCxuKSYmcih0LG4pfWZ1bmN0aW9uIG8oZSl7dmFyIG49ZS5lbCx0PWUuaGFuZGxlcixyPWUubWlkZGxld2FyZTtyZXR1cm57ZWw6bixldmVudEhhbmRsZXJzOmUuZXZlbnRzLm1hcChmdW5jdGlvbihlKXtyZXR1cm57ZXZlbnQ6ZSxoYW5kbGVyOmZ1bmN0aW9uKGUpe3JldHVybiBkKHtldmVudDplLGVsOm4saGFuZGxlcjp0LG1pZGRsZXdhcmU6cn0pfX19KX19ZnVuY3Rpb24gdShlKXt2YXIgbj1pLmluc3RhbmNlcy5maW5kSW5kZXgoZnVuY3Rpb24obil7cmV0dXJuIG4uZWw9PT1lfSk7LTEhPT1uJiYoaS5pbnN0YW5jZXNbbl0uZXZlbnRIYW5kbGVycy5mb3JFYWNoKGZ1bmN0aW9uKGUpe3JldHVybiBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKGUuZXZlbnQsZS5oYW5kbGVyKX0pLGkuaW5zdGFuY2VzLnNwbGljZShuLDEpKX1yZXR1cm4gaS5iaW5kPWZ1bmN0aW9uKGUsbil7dmFyIHQ9YShuLnZhbHVlKTtpZih0LmlzQWN0aXZlKXt2YXIgcj1vKHtlbDplLGV2ZW50czp0LmV2ZW50cyxoYW5kbGVyOnQuaGFuZGxlcixtaWRkbGV3YXJlOnQubWlkZGxld2FyZX0pO3IuZXZlbnRIYW5kbGVycy5mb3JFYWNoKGZ1bmN0aW9uKGUpe3ZhciBuPWUuZXZlbnQsdD1lLmhhbmRsZXI7cmV0dXJuIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtyZXR1cm4gZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihuLHQpfSwwKX0pLGkuaW5zdGFuY2VzLnB1c2gocil9fSxpLnVwZGF0ZT1mdW5jdGlvbihlLG4pe3ZhciB0PW4udmFsdWUscj1uLm9sZFZhbHVlO2lmKEpTT04uc3RyaW5naWZ5KHQpIT09SlNPTi5zdHJpbmdpZnkocikpe3ZhciBjPWEodCksbD1jLmV2ZW50cyxzPWMuaGFuZGxlcix2PWMubWlkZGxld2FyZTtpZihjLmlzQWN0aXZlKXt2YXIgZj1pLmluc3RhbmNlcy5maW5kKGZ1bmN0aW9uKG4pe3JldHVybiBuLmVsPT09ZX0pO2Y/KGYuZXZlbnRIYW5kbGVycy5mb3JFYWNoKGZ1bmN0aW9uKGUpe3JldHVybiBkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyKGUuZXZlbnQsZS5oYW5kbGVyKX0pLGYuZXZlbnRIYW5kbGVycz1sLm1hcChmdW5jdGlvbihuKXtyZXR1cm57ZXZlbnQ6bixoYW5kbGVyOmZ1bmN0aW9uKG4pe3JldHVybiBkKHtldmVudDpuLGVsOmUsaGFuZGxlcjpzLG1pZGRsZXdhcmU6dn0pfX19KSk6KGY9byh7ZWw6ZSxldmVudHM6bCxoYW5kbGVyOnMsbWlkZGxld2FyZTp2fSksaS5pbnN0YW5jZXMucHVzaChmKSksZi5ldmVudEhhbmRsZXJzLmZvckVhY2goZnVuY3Rpb24oZSl7dmFyIG49ZS5ldmVudCx0PWUuaGFuZGxlcjtyZXR1cm4gc2V0VGltZW91dChmdW5jdGlvbigpe3JldHVybiBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKG4sdCl9LDApfSl9ZWxzZSB1KGUpfX0saS51bmJpbmQ9dSx7aW5zdGFsbDpmdW5jdGlvbihlKXtlLmRpcmVjdGl2ZShcImNsaWNrLW91dHNpZGVcIixpKX0sZGlyZWN0aXZlOml9fSk7XG4vLyMgc291cmNlTWFwcGluZ1VSTD12LWNsaWNrLW91dHNpZGUubWluLm1pbi51bWQuanMubWFwXG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./node_modules/v-click-outside/dist/v-click-outside.min.umd.js\n");
=======
/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Shared/Layout */ "./resources/js/Shared/Layout.vue");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    Layout: _Shared_Layout__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_1___default.a
  },
  props: {
    notifications: {
      type: Array,
      "default": null
    },
    employees: {
      type: Array,
      "default": null
    },
    mostRecentEmployee: {
      type: String,
      "default": null
    },
    employeeCount: {
      type: Number,
      "default": null
    },
    team: {
      type: Object,
      "default": null
    }
  },
  data: function data() {
    return {};
  },
  mounted: function mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true
      });
      localStorage.clear();
    }
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "layout",
    { attrs: { title: "Home", notifications: _vm.notifications } },
    [
      _c("div", { staticClass: "ph2 ph5-ns" }, [
        _c(
          "div",
          {
            staticClass:
              "mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
          },
          [
            _c("ul", { staticClass: "list ph0 tc-l tl" }, [
              _c(
                "li",
                { staticClass: "di" },
                [
                  _c(
                    "inertia-link",
                    {
                      attrs: {
                        href: "/" + _vm.$page.auth.company.id + "/dashboard"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.$page.auth.company.name) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "li",
                { staticClass: "di" },
                [
                  _c(
                    "inertia-link",
                    {
                      attrs: {
                        href: "/" + _vm.$page.auth.company.id + "/teams"
                      }
                    },
                    [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.$t("app.breadcrumb_team_list")) +
                          "\n          "
                      )
                    ]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v("\n          " + _vm._s(_vm.team.name) + "\n        ")
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "mw8 center br3 mb4 bg-white box relative z-1" },
          [
            _c("div", { staticClass: "pa3 relative" }, [
              _c("h2", { staticClass: "normal ma0 mb2" }, [
                _vm._v("\n          " + _vm._s(_vm.team.name) + "\n        ")
              ]),
              _vm._v(" "),
              _c("ul", { staticClass: "ma0 pa0 f6" }, [
                _c("li", { staticClass: "di mr2" }, [
                  _c("img", {
                    staticClass: "pr1",
                    attrs: { src: "/img/leader.svg" }
                  }),
                  _vm._v("\n            Lead by John Rambo\n          ")
                ]),
                _vm._v(" "),
                _c("li", { staticClass: "di" }, [
                  _c("img", {
                    staticClass: "pr1",
                    attrs: { src: "/img/location.svg" }
                  }),
                  _vm._v("\n            Office #5, south east\n          ")
                ])
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "cf mw6 center mb4" }, [
          _c("div", { staticClass: "bg-white box pa3 mb4" }, [
            _c("p", { staticClass: "lh-copy ma0 mb2" }, [
              _vm._v(
                "\n          This team has " +
                  _vm._s(_vm.employeeCount) +
                  " members, the most recent being "
              ),
              _c("a", { attrs: { href: "" } }, [_vm._v("sdfsd")]),
              _vm._v(".\n        ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [_vm._v("View team members")])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "bg-white box pa3 mb4" }, [
            _c("p", { staticClass: "ma0 mb2" }, [
              _vm._v(
                "\n          Want to find out how someone in this team can help you?\n        "
              )
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [
                _vm._v("Read about the different ways they can help you")
              ])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "bg-white box pa3 mb4" }, [
            _c("p", { staticClass: "f6 ma0 mb1" }, [
              _vm._v("\n          2 days ago\n        ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "lh-copy ma0 mb2" }, [
              _vm._v(
                "\n          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.\n        "
              )
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [_vm._v("Read all the news")])
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "bg-white box pa3" }, [
            _c("p", { staticClass: "ma0 mb2" }, [
              _vm._v("\n          New to the team?\n        ")
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "ma0" }, [
              _c("a", { attrs: { href: "" } }, [_vm._v("Start here")])
            ])
          ])
        ])
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/Pages/Team/Index.vue":
/*!*******************************************!*\
  !*** ./resources/js/Pages/Team/Index.vue ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Index.vue?vue&type=template&id=16900ce9&scoped=true& */ "./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&");
/* harmony import */ var _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Index.vue?vue&type=script&lang=js& */ "./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "16900ce9",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/Pages/Team/Index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);
>>>>>>> master

/***/ }),

/***/ "./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&":
/*!********************************************************************!*\
  !*** ./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js& ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
<<<<<<< HEAD
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return normalizeComponent; });\n/* globals __VUE_SSR_CONTEXT__ */\n\n// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).\n// This module is a runtime utility for cleaner component module output and will\n// be included in the final webpack user bundle.\n\nfunction normalizeComponent (\n  scriptExports,\n  render,\n  staticRenderFns,\n  functionalTemplate,\n  injectStyles,\n  scopeId,\n  moduleIdentifier, /* server only */\n  shadowMode /* vue-cli only */\n) {\n  // Vue.extend constructor export interop\n  var options = typeof scriptExports === 'function'\n    ? scriptExports.options\n    : scriptExports\n\n  // render functions\n  if (render) {\n    options.render = render\n    options.staticRenderFns = staticRenderFns\n    options._compiled = true\n  }\n\n  // functional template\n  if (functionalTemplate) {\n    options.functional = true\n  }\n\n  // scopedId\n  if (scopeId) {\n    options._scopeId = 'data-v-' + scopeId\n  }\n\n  var hook\n  if (moduleIdentifier) { // server build\n    hook = function (context) {\n      // 2.3 injection\n      context =\n        context || // cached call\n        (this.$vnode && this.$vnode.ssrContext) || // stateful\n        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional\n      // 2.2 with runInNewContext: true\n      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {\n        context = __VUE_SSR_CONTEXT__\n      }\n      // inject component styles\n      if (injectStyles) {\n        injectStyles.call(this, context)\n      }\n      // register component module identifier for async chunk inferrence\n      if (context && context._registeredComponents) {\n        context._registeredComponents.add(moduleIdentifier)\n      }\n    }\n    // used by ssr in case component is cached and beforeCreate\n    // never gets called\n    options._ssrRegister = hook\n  } else if (injectStyles) {\n    hook = shadowMode\n      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }\n      : injectStyles\n  }\n\n  if (hook) {\n    if (options.functional) {\n      // for template-only hot-reload because in that case the render fn doesn't\n      // go through the normalizer\n      options._injectStyles = hook\n      // register for functioal component in vue file\n      var originalRender = options.render\n      options.render = function renderWithStyleInjection (h, context) {\n        hook.call(context)\n        return originalRender(h, context)\n      }\n    } else {\n      // inject component registration as beforeCreate hook\n      var existing = options.beforeCreate\n      options.beforeCreate = existing\n        ? [].concat(existing, hook)\n        : [hook]\n    }\n  }\n\n  return {\n    exports: scriptExports,\n    options: options\n  }\n}\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvcnVudGltZS9jb21wb25lbnROb3JtYWxpemVyLmpzPzI4NzciXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBOztBQUVBO0FBQ0E7QUFDQTs7QUFFZTtBQUNmO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiIuL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9ydW50aW1lL2NvbXBvbmVudE5vcm1hbGl6ZXIuanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvKiBnbG9iYWxzIF9fVlVFX1NTUl9DT05URVhUX18gKi9cblxuLy8gSU1QT1JUQU5UOiBEbyBOT1QgdXNlIEVTMjAxNSBmZWF0dXJlcyBpbiB0aGlzIGZpbGUgKGV4Y2VwdCBmb3IgbW9kdWxlcykuXG4vLyBUaGlzIG1vZHVsZSBpcyBhIHJ1bnRpbWUgdXRpbGl0eSBmb3IgY2xlYW5lciBjb21wb25lbnQgbW9kdWxlIG91dHB1dCBhbmQgd2lsbFxuLy8gYmUgaW5jbHVkZWQgaW4gdGhlIGZpbmFsIHdlYnBhY2sgdXNlciBidW5kbGUuXG5cbmV4cG9ydCBkZWZhdWx0IGZ1bmN0aW9uIG5vcm1hbGl6ZUNvbXBvbmVudCAoXG4gIHNjcmlwdEV4cG9ydHMsXG4gIHJlbmRlcixcbiAgc3RhdGljUmVuZGVyRm5zLFxuICBmdW5jdGlvbmFsVGVtcGxhdGUsXG4gIGluamVjdFN0eWxlcyxcbiAgc2NvcGVJZCxcbiAgbW9kdWxlSWRlbnRpZmllciwgLyogc2VydmVyIG9ubHkgKi9cbiAgc2hhZG93TW9kZSAvKiB2dWUtY2xpIG9ubHkgKi9cbikge1xuICAvLyBWdWUuZXh0ZW5kIGNvbnN0cnVjdG9yIGV4cG9ydCBpbnRlcm9wXG4gIHZhciBvcHRpb25zID0gdHlwZW9mIHNjcmlwdEV4cG9ydHMgPT09ICdmdW5jdGlvbidcbiAgICA/IHNjcmlwdEV4cG9ydHMub3B0aW9uc1xuICAgIDogc2NyaXB0RXhwb3J0c1xuXG4gIC8vIHJlbmRlciBmdW5jdGlvbnNcbiAgaWYgKHJlbmRlcikge1xuICAgIG9wdGlvbnMucmVuZGVyID0gcmVuZGVyXG4gICAgb3B0aW9ucy5zdGF0aWNSZW5kZXJGbnMgPSBzdGF0aWNSZW5kZXJGbnNcbiAgICBvcHRpb25zLl9jb21waWxlZCA9IHRydWVcbiAgfVxuXG4gIC8vIGZ1bmN0aW9uYWwgdGVtcGxhdGVcbiAgaWYgKGZ1bmN0aW9uYWxUZW1wbGF0ZSkge1xuICAgIG9wdGlvbnMuZnVuY3Rpb25hbCA9IHRydWVcbiAgfVxuXG4gIC8vIHNjb3BlZElkXG4gIGlmIChzY29wZUlkKSB7XG4gICAgb3B0aW9ucy5fc2NvcGVJZCA9ICdkYXRhLXYtJyArIHNjb3BlSWRcbiAgfVxuXG4gIHZhciBob29rXG4gIGlmIChtb2R1bGVJZGVudGlmaWVyKSB7IC8vIHNlcnZlciBidWlsZFxuICAgIGhvb2sgPSBmdW5jdGlvbiAoY29udGV4dCkge1xuICAgICAgLy8gMi4zIGluamVjdGlvblxuICAgICAgY29udGV4dCA9XG4gICAgICAgIGNvbnRleHQgfHwgLy8gY2FjaGVkIGNhbGxcbiAgICAgICAgKHRoaXMuJHZub2RlICYmIHRoaXMuJHZub2RlLnNzckNvbnRleHQpIHx8IC8vIHN0YXRlZnVsXG4gICAgICAgICh0aGlzLnBhcmVudCAmJiB0aGlzLnBhcmVudC4kdm5vZGUgJiYgdGhpcy5wYXJlbnQuJHZub2RlLnNzckNvbnRleHQpIC8vIGZ1bmN0aW9uYWxcbiAgICAgIC8vIDIuMiB3aXRoIHJ1bkluTmV3Q29udGV4dDogdHJ1ZVxuICAgICAgaWYgKCFjb250ZXh0ICYmIHR5cGVvZiBfX1ZVRV9TU1JfQ09OVEVYVF9fICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICBjb250ZXh0ID0gX19WVUVfU1NSX0NPTlRFWFRfX1xuICAgICAgfVxuICAgICAgLy8gaW5qZWN0IGNvbXBvbmVudCBzdHlsZXNcbiAgICAgIGlmIChpbmplY3RTdHlsZXMpIHtcbiAgICAgICAgaW5qZWN0U3R5bGVzLmNhbGwodGhpcywgY29udGV4dClcbiAgICAgIH1cbiAgICAgIC8vIHJlZ2lzdGVyIGNvbXBvbmVudCBtb2R1bGUgaWRlbnRpZmllciBmb3IgYXN5bmMgY2h1bmsgaW5mZXJyZW5jZVxuICAgICAgaWYgKGNvbnRleHQgJiYgY29udGV4dC5fcmVnaXN0ZXJlZENvbXBvbmVudHMpIHtcbiAgICAgICAgY29udGV4dC5fcmVnaXN0ZXJlZENvbXBvbmVudHMuYWRkKG1vZHVsZUlkZW50aWZpZXIpXG4gICAgICB9XG4gICAgfVxuICAgIC8vIHVzZWQgYnkgc3NyIGluIGNhc2UgY29tcG9uZW50IGlzIGNhY2hlZCBhbmQgYmVmb3JlQ3JlYXRlXG4gICAgLy8gbmV2ZXIgZ2V0cyBjYWxsZWRcbiAgICBvcHRpb25zLl9zc3JSZWdpc3RlciA9IGhvb2tcbiAgfSBlbHNlIGlmIChpbmplY3RTdHlsZXMpIHtcbiAgICBob29rID0gc2hhZG93TW9kZVxuICAgICAgPyBmdW5jdGlvbiAoKSB7IGluamVjdFN0eWxlcy5jYWxsKHRoaXMsIHRoaXMuJHJvb3QuJG9wdGlvbnMuc2hhZG93Um9vdCkgfVxuICAgICAgOiBpbmplY3RTdHlsZXNcbiAgfVxuXG4gIGlmIChob29rKSB7XG4gICAgaWYgKG9wdGlvbnMuZnVuY3Rpb25hbCkge1xuICAgICAgLy8gZm9yIHRlbXBsYXRlLW9ubHkgaG90LXJlbG9hZCBiZWNhdXNlIGluIHRoYXQgY2FzZSB0aGUgcmVuZGVyIGZuIGRvZXNuJ3RcbiAgICAgIC8vIGdvIHRocm91Z2ggdGhlIG5vcm1hbGl6ZXJcbiAgICAgIG9wdGlvbnMuX2luamVjdFN0eWxlcyA9IGhvb2tcbiAgICAgIC8vIHJlZ2lzdGVyIGZvciBmdW5jdGlvYWwgY29tcG9uZW50IGluIHZ1ZSBmaWxlXG4gICAgICB2YXIgb3JpZ2luYWxSZW5kZXIgPSBvcHRpb25zLnJlbmRlclxuICAgICAgb3B0aW9ucy5yZW5kZXIgPSBmdW5jdGlvbiByZW5kZXJXaXRoU3R5bGVJbmplY3Rpb24gKGgsIGNvbnRleHQpIHtcbiAgICAgICAgaG9vay5jYWxsKGNvbnRleHQpXG4gICAgICAgIHJldHVybiBvcmlnaW5hbFJlbmRlcihoLCBjb250ZXh0KVxuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICAvLyBpbmplY3QgY29tcG9uZW50IHJlZ2lzdHJhdGlvbiBhcyBiZWZvcmVDcmVhdGUgaG9va1xuICAgICAgdmFyIGV4aXN0aW5nID0gb3B0aW9ucy5iZWZvcmVDcmVhdGVcbiAgICAgIG9wdGlvbnMuYmVmb3JlQ3JlYXRlID0gZXhpc3RpbmdcbiAgICAgICAgPyBbXS5jb25jYXQoZXhpc3RpbmcsIGhvb2spXG4gICAgICAgIDogW2hvb2tdXG4gICAgfVxuICB9XG5cbiAgcmV0dXJuIHtcbiAgICBleHBvcnRzOiBzY3JpcHRFeHBvcnRzLFxuICAgIG9wdGlvbnM6IG9wdGlvbnNcbiAgfVxufVxuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./node_modules/vue-loader/lib/runtime/componentNormalizer.js\n");
=======
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&":
/*!**************************************************************************************!*\
  !*** ./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Index.vue?vue&type=template&id=16900ce9&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/Pages/Team/Index.vue?vue&type=template&id=16900ce9&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Index_vue_vue_type_template_id_16900ce9_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });


>>>>>>> master

/***/ })

}]);