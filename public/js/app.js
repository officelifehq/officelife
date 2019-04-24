(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/app"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Login.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/Login.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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
  data: function data() {
    return {
      form: {
        email: null,
        password: null,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/login', this.form).then(function (response) {
        Turbolinks.visit('/home');
      })["catch"](function (error) {
        _this.loadingState = null;

        if (_typeof(error.response.data) === 'object') {
          _this.form.errors = _.flatten(_.toArray(error.response.data));
        } else {
          _this.form.errors = [_this.$t('app.error_try_again')];
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Register.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/Register.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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
  data: function data() {
    return {
      form: {
        email: null,
        password: null,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/signup', this.form).then(function (response) {
        Turbolinks.visit('/home');
      })["catch"](function (error) {
        _this.loadingState = null;

        if (_typeof(error.response.data) === 'object') {
          _this.form.errors = _.flatten(_.toArray(error.response.data));
        } else {
          _this.form.errors = [_this.$t('app.error_try_again')];
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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
  props: {
    company: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    invitationLink: {
      type: String,
      "default": ''
    }
  },
  data: function data() {
    return {
      loadingState: ''
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/invite/employee/' + this.invitationLink + '/accept').then(function (response) {
        Turbolinks.visit('/home');
      })["catch"](function (error) {
        _this.loadingState = null;

        if (_typeof(error.response.data) === 'object') {
          _this.form.errors = _.flatten(_.toArray(error.response.data));
        } else {
          _this.form.errors = [_this.$t('app.error_try_again')];
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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
  props: {
    company: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    invitationLink: {
      type: String,
      "default": ''
    }
  },
  data: function data() {
    return {
      displayCreateAccount: false,
      displaySignin: false,
      form: {
        email: null,
        password: null,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  mounted: function mounted() {
    this.form.email = this.employee.email;
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/invite/employee/' + this.invitationLink + '/join', this.form).then(function (response) {
        Turbolinks.visit('/home');
      })["catch"](function (error) {
        _this.loadingState = null;

        if (_typeof(error.response.data) === 'object') {
          _this.form.errors = _.flatten(_.toArray(error.response.data));
        } else {
          _this.form.errors = [_this.$t('app.error_try_again')];
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
/* harmony default export */ __webpack_exports__["default"] = ({});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
/* harmony default export */ __webpack_exports__["default"] = ({});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/ShowAccount.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    nbEmployees: {
      type: Number,
      "default": 0
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    logs: {
      type: Array,
      "default": null
    },
    paginator: {
      type: Object,
      "default": null
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    }
  },
  data: function data() {
    return {
      form: {
        first_name: null,
        last_name: null,
        email: null,
        permission_level: null,
        send_invitation: false,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/' + this.company.id + '/account/employees', this.form).then(function (response) {
        localStorage.success = 'The employee has been added';
        Turbolinks.visit('/' + response.data.company_id + '/account/employees');
      })["catch"](function (error) {
        _this.loadingState = null;
        _this.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    employees: {
      type: Array,
      "default": null
    }
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
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    positions: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      modal: false,
      deleteModal: false,
      updateModal: false,
      loadingState: '',
      updateModalId: 0,
      idToUpdate: 0,
      idToDelete: 0,
      form: {
        title: null,
        errors: []
      }
    };
  },
  methods: {
    displayUpdateModal: function displayUpdateModal(position) {
      var _this = this;

      this.idToUpdate = position.id;
      this.$nextTick(function () {
        _this.$refs.title.focus();
      });
    },
    submit: function submit() {
      var _this2 = this;

      this.loadingState = 'loading';
      axios.post('/' + this.company.id + '/account/positions', this.form).then(function (response) {
        _this2.$snotify.success(_this2.$t('account.position_success_new'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this2.loadingState = null;
        _this2.form.title = null;
        _this2.modal = false;

        _this2.positions.push(response.data.data);
      })["catch"](function (error) {
        _this2.loadingState = null;
        _this2.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    update: function update(id) {
      var _this3 = this;

      axios.put('/' + this.company.id + '/account/positions/' + id, this.form).then(function (response) {
        _this3.$snotify.success(_this3.$t('account.position_success_update'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this3.idToUpdate = 0;
        _this3.form.title = null;
        id = _this3.positions.findIndex(function (x) {
          return x.id === id;
        });

        _this3.$set(_this3.positions, id, response.data.data);
      })["catch"](function (error) {
        _this3.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    destroy: function destroy(id) {
      var _this4 = this;

      axios["delete"]('/' + this.company.id + '/account/positions/' + id).then(function (response) {
        _this4.$snotify.success(_this4.$t('account.position_success_destroy'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this4.idToDelete = 0;
        id = _this4.positions.findIndex(function (x) {
          return x.id === id;
        });

        _this4.positions.splice(id, 1);
      })["catch"](function (error) {
        _this4.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    teams: {
      type: Array,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    }
  },
  data: function data() {
    return {
      modal: false,
      form: {
        name: null,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  created: function created() {
    window.addEventListener('click', this.close);
  },
  beforeDestroy: function beforeDestroy() {
    window.removeEventListener('click', this.close);
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
  methods: {
    close: function close(e) {
      if (!this.$el.contains(e.target)) {
        this.modal = false;
      }
    },
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/' + this.company.id + '/account/teams', this.form).then(function (response) {
        _this.$snotify.success('The team has been created', {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this.loadingState = null;
        _this.form.name = null;
        _this.modal = false;

        _this.teams.push(response.data.data);
      })["catch"](function (error) {
        _this.loadingState = null;
        _this.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/CreateCompany.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/CreateCompany.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  data: function data() {
    return {
      form: {
        name: null,
        errors: []
      },
      loadingState: '',
      errorTemplate: Error
    };
  },
  methods: {
    submit: function submit() {
      var _this = this;

      this.loadingState = 'loading';
      axios.post('/company', this.form).then(function (response) {
        Turbolinks.visit('/' + response.data.company_id + '/dashboard');
      })["catch"](function (error) {
        _this.loadingState = null;
        _this.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
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
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    company: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    positions: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      modal: false,
      search: '',
      title: '',
      updatedEmployee: Object
    };
  },
  computed: {
    filteredList: function filteredList() {
      var _this = this;

      // filter the list when searching
      // also, sort the list by title
      var list;
      list = this.positions.filter(function (position) {
        return position.title.toLowerCase().includes(_this.search.toLowerCase());
      });

      function compare(a, b) {
        if (a.title < b.title) return -1;
        if (a.title > b.title) return 1;
        return 0;
      }

      return list.sort(compare);
    }
  },
  mounted: function mounted() {
    if (this.employee.position != null) {
      this.title = this.employee.position.title;
    }

    this.updatedEmployee = this.employee;
  },
  methods: {
    toggleModal: function toggleModal() {
      this.modal = false;
    },
    assign: function assign(position) {
      var _this2 = this;

      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/position', position).then(function (response) {
        _this2.$snotify.success(_this2.$t('employee.position_modal_assign_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this2.title = response.data.data.position.title;
        _this2.updatedEmployee = response.data.data;
        _this2.modal = false;
      })["catch"](function (error) {
        _this2.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    reset: function reset() {
      var _this3 = this;

      axios["delete"]('/' + this.company.id + '/employees/' + this.employee.id + '/position/' + this.updatedEmployee.position.id).then(function (response) {
        _this3.$snotify.success(_this3.$t('employee.position_modal_unassign_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this3.title = '';
        _this3.modal = false;
        _this3.updatedEmployee = response.data.data;
      })["catch"](function (error) {
        _this3.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
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
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    company: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    teams: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      modal: false,
      search: '',
      updatedEmployee: Object
    };
  },
  computed: {
    filteredList: function filteredList() {
      var _this = this;

      // filter the list when searching
      // also, sort the list by name
      var list;
      list = this.teams.filter(function (team) {
        return team.name.toLowerCase().includes(_this.search.toLowerCase());
      });

      function compare(a, b) {
        if (a.name < b.name) return -1;
        if (a.name > b.name) return 1;
        return 0;
      }

      return list.sort(compare);
    }
  },
  created: function created() {
    this.updatedEmployee = this.employee;
  },
  methods: {
    toggleModal: function toggleModal() {
      this.modal = false;
    },
    assign: function assign(team) {
      var _this2 = this;

      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/team', team).then(function (response) {
        _this2.$snotify.success(_this2.$t('employee.team_modal_assign_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this2.updatedEmployee = response.data.data;
      })["catch"](function (error) {
        _this2.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    reset: function reset(team) {
      var _this3 = this;

      axios["delete"]('/' + this.company.id + '/employees/' + this.employee.id + '/team/' + team.id).then(function (response) {
        _this3.$snotify.success(_this3.$t('employee.team_modal_unassign_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this3.updatedEmployee = response.data.data;
      })["catch"](function (error) {
        _this3.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    isAssigned: function isAssigned(id) {
      for (var i = 0; i < this.updatedEmployee.teams.length; i++) {
        if (this.updatedEmployee.teams[i].id == id) {
          return true;
        }
      }

      return false;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
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
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    managers: {
      type: Array,
      "default": null
    },
    directReports: {
      type: Array,
      "default": null
    },
    positions: {
      type: Array,
      "default": null
    },
    teams: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      profileMenu: false
    };
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
  methods: {
    toggleProfileMenu: function toggleProfileMenu() {
      this.profileMenu = false;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-loaders/dist/vue-loaders.css */ "./node_modules/vue-loaders/dist/vue-loaders.css");
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue_loaders__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-loaders */ "./node_modules/vue-loaders/dist/vue-loaders.es.js");
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



Vue.use(vue_loaders__WEBPACK_IMPORTED_MODULE_2__);
/* harmony default export */ __webpack_exports__["default"] = ({
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    managers: {
      type: Array,
      "default": null
    },
    directReports: {
      type: Array,
      "default": null
    }
  },
  data: function data() {
    return {
      modal: 'hide',
      processingSearch: false,
      searchManagers: [],
      searchDirectReports: [],
      form: {
        searchTerm: null,
        errors: []
      },
      managerModalId: 0,
      directReportModalId: 0,
      deleteEmployeeConfirmation: false
    };
  },
  mounted: function mounted() {
    // prevent click outside event with popupItem.
    this.popupItem = this.$el;
  },
  methods: {
    toggleModals: function toggleModals() {
      if (this.modal == 'hide') {
        this.modal = 'menu';
      } else {
        this.modal = 'hide';
      }

      this.searchManagers = [];
      this.searchDirectReports = [];
      this.form.searchTerm = null;
    },
    displayManagerModal: function displayManagerModal() {
      var _this = this;

      this.modal = 'manager';
      this.$nextTick(function () {
        _this.$refs.search.focus();
      });
    },
    displayDirectReportModal: function displayDirectReportModal() {
      var _this2 = this;

      this.modal = 'directReport';
      this.$nextTick(function () {
        _this2.$refs.search.focus();
      });
    },
    hideManagerModal: function hideManagerModal() {
      this.managerModalId = 0;
    },
    hideDirectReportModal: function hideDirectReportModal() {
      this.directReportModalId = 0;
    },
    search: _.debounce(function () {
      var _this3 = this;

      if (this.form.searchTerm != '') {
        this.processingSearch = true;
        axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/search/hierarchy', this.form).then(function (response) {
          if (_this3.modal == 'manager') {
            _this3.searchManagers = response.data.data;
          }

          if (_this3.modal == 'directReport') {
            _this3.searchDirectReports = response.data.data;
          }

          _this3.processingSearch = false;
        })["catch"](function (error) {
          _this3.form.errors = _.flatten(_.toArray(error.response.data));
          _this3.processingSearch = false;
        });
      }
    }, 500),
    assignManager: function assignManager(manager) {
      var _this4 = this;

      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/assignManager', manager).then(function (response) {
        _this4.$snotify.success(_this4.$t('employee.hierarchy_modal_add_manager_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this4.managers.push(response.data.data);

        _this4.modal = 'hide';
      })["catch"](function (error) {
        _this4.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    assignDirectReport: function assignDirectReport(directReport) {
      var _this5 = this;

      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/assignDirectReport', directReport).then(function (response) {
        _this5.$snotify.success(_this5.$t('employee.hierarchy_modal_add_direct_report_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this5.directReports.push(response.data.data);

        _this5.modal = 'hide';
      })["catch"](function (error) {
        _this5.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    unassignManager: function unassignManager(manager) {
      var _this6 = this;

      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/unassignManager', manager).then(function (response) {
        _this6.$snotify.success(_this6.$t('employee.hierarchy_modal_remove_manager_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this6.managers.splice(_this6.managers.indexOf(response.data.data), 1);

        _this6.deleteEmployeeConfirmation = false;
        _this6.managerModalId = 0;
      })["catch"](function (error) {
        _this6.form.errors = _.flatten(_.toArray(error.response.data));
      });
    },
    unassignDirectReport: function unassignDirectReport(directReport) {
      var _this7 = this;

      axios.post('/' + this.company.id + '/employees/' + this.employee.id + '/unassignDirectReport', directReport).then(function (response) {
        _this7.$snotify.success(_this7.$t('employee.hierarchy_modal_remove_direct_report_success'), {
          timeout: 2000,
          showProgressBar: true,
          closeOnClick: true,
          pauseOnHover: true
        });

        _this7.directReports.splice(_this7.directReports.indexOf(response.data.data), 1);

        _this7.deleteEmployeeConfirmation = false;
        _this7.directReportModalId = 0;
      })["catch"](function (error) {
        _this7.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    employee: {
      type: Object,
      "default": null
    },
    logs: {
      type: Array,
      "default": null
    },
    paginator: {
      type: Object,
      "default": null
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-click-outside */ "./node_modules/vue-click-outside/index.js");
/* harmony import */ var vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_click_outside__WEBPACK_IMPORTED_MODULE_0__);
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
  directives: {
    ClickOutside: vue_click_outside__WEBPACK_IMPORTED_MODULE_0___default.a
  },
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
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

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/dashboard/ShowCompany.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    company: {
      type: Object,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    },
    ownerPermissionLevel: {
      type: Number,
      "default": 0
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/form/LoadingButton.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/form/LoadingButton.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-loaders/dist/vue-loaders.css */ "./node_modules/vue-loaders/dist/vue-loaders.css");
/* harmony import */ var vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue_loaders_dist_vue_loaders_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vue_loaders__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-loaders */ "./node_modules/vue-loaders/dist/vue-loaders.es.js");
//
//
//
//
//
//
//
//
//


Vue.use(vue_loaders__WEBPACK_IMPORTED_MODULE_1__);
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    text: {
      type: String,
      "default": ''
    },
    state: {
      type: String,
      "default": ''
    },
    classes: {
      type: String,
      "default": ''
    },
    cypressSelector: {
      type: String,
      "default": ''
    }
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/header/HeaderMenu.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    user: {
      type: Object,
      "default": null
    }
  },
  data: function data() {
    return {
      menu: false
    };
  },
  created: function created() {
    window.addEventListener('click', this.close);
  },
  beforeDestroy: function beforeDestroy() {
    window.removeEventListener('click', this.close);
  },
  methods: {
    prepareComponent: function prepareComponent() {
      this.getPrimaryEmotions();
    },
    close: function close(e) {
      if (!this.$el.contains(e.target)) {
        this.menu = false;
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/icons/IconDelete.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    classes: {
      type: String,
      "default": ''
    },
    width: {
      type: Number,
      "default": 0
    },
    height: {
      type: Number,
      "default": 0
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/home/Home.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    employees: {
      type: Array,
      "default": null
    },
    user: {
      type: Object,
      "default": null
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Errors.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/partials/Errors.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    errors: {
      type: Array,
      "default": function _default() {
        return [];
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/partials/Layout.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  props: {
    title: {
      type: String,
      "default": ''
    },
    noMenu: {
      type: Boolean,
      "default": false
    },
    user: {
      type: Object,
      "default": null
    }
  },
  data: function data() {
    return {
      loadingState: '',
      modalFind: false,
      dataReturnedFromSearch: false,
      form: {
        searchTerm: null,
        errors: []
      },
      employees: [],
      teams: []
    };
  },
  watch: {
    title: function title(_title) {
      this.updatePageTitle(_title);
    }
  },
  mounted: function mounted() {
    this.updatePageTitle(this.title);
  },
  methods: {
    updatePageTitle: function updatePageTitle(title) {
      document.title = title ? "".concat(title, " | Example app") : 'Example app';
    },
    showFindModal: function showFindModal() {
      var _this = this;

      this.dataReturnedFromSearch = false;
      this.form.searchTerm = null;
      this.employees = [];
      this.teams = [];
      this.modalFind = !this.modalFind;
      this.$nextTick(function () {
        _this.$refs.search.focus();
      });
    },
    submit: function submit() {
      var _this2 = this;

      axios.post('/search/employees', this.form).then(function (response) {
        _this2.dataReturnedFromSearch = true;
        _this2.employees = response.data.data;
      })["catch"](function (error) {
        _this2.loadingState = null;
        _this2.form.errors = _.flatten(_.toArray(error.response.data));
      });
      axios.post('/search/teams', this.form).then(function (response) {
        _this2.dataReturnedFromSearch = true;
        _this2.teams = response.data.data;
      })["catch"](function (error) {
        _this2.loadingState = null;
        _this2.form.errors = _.flatten(_.toArray(error.response.data));
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loaders/dist/vue-loaders.css":
/*!***************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loaders/dist/vue-loaders.css ***!
  \***************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "@-webkit-keyframes scale{0%,80%{-webkit-transform:scale(1);transform:scale(1);opacity:1}45%{-webkit-transform:scale(.1);transform:scale(.1);opacity:.7}}@-webkit-keyframes ball-pulse-sync{33%{-webkit-transform:translateY(10px);transform:translateY(10px)}66%{-webkit-transform:translateY(-10px);transform:translateY(-10px)}to{-webkit-transform:translateY(0);transform:translateY(0)}}@keyframes ball-pulse-sync{33%{-webkit-transform:translateY(10px);transform:translateY(10px)}66%{-webkit-transform:translateY(-10px);transform:translateY(-10px)}to{-webkit-transform:translateY(0);transform:translateY(0)}}@-webkit-keyframes ball-scale{0%{-webkit-transform:scale(0);transform:scale(0)}to{-webkit-transform:scale(1);transform:scale(1);opacity:0}}@keyframes ball-scale{0%{-webkit-transform:scale(0);transform:scale(0)}to{-webkit-transform:scale(1);transform:scale(1);opacity:0}}@-webkit-keyframes rotate{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}50%{-webkit-transform:rotate(180deg);transform:rotate(180deg)}to{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes scale{30%{-webkit-transform:scale(.3);transform:scale(.3)}to{-webkit-transform:scale(1);transform:scale(1)}}@keyframes rotate{0%{-webkit-transform:rotate(0deg) scale(1);transform:rotate(0deg) scale(1)}50%{-webkit-transform:rotate(180deg) scale(.6);transform:rotate(180deg) scale(.6)}to{-webkit-transform:rotate(360deg) scale(1);transform:rotate(360deg) scale(1)}}@-webkit-keyframes ball-scale-ripple{0%{-webkit-transform:scale(.1);transform:scale(.1);opacity:1}70%{-webkit-transform:scale(1);transform:scale(1);opacity:.7}to{opacity:0}}@keyframes ball-scale-ripple{0%{-webkit-transform:scale(.1);transform:scale(.1);opacity:1}70%{-webkit-transform:scale(1);transform:scale(1);opacity:.7}to{opacity:0}}@-webkit-keyframes ball-scale-ripple-multiple{0%{-webkit-transform:scale(.1);transform:scale(.1);opacity:1}70%{-webkit-transform:scale(1);transform:scale(1);opacity:.7}to{opacity:0}}@keyframes ball-scale-ripple-multiple{0%{-webkit-transform:scale(.1);transform:scale(.1);opacity:1}70%{-webkit-transform:scale(1);transform:scale(1);opacity:.7}to{opacity:0}}@-webkit-keyframes ball-beat{50%{opacity:.2;-webkit-transform:scale(.75);transform:scale(.75)}to{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}@keyframes ball-beat{50%{opacity:.2;-webkit-transform:scale(.75);transform:scale(.75)}to{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}@-webkit-keyframes ball-scale-multiple{0%{-webkit-transform:scale(0);transform:scale(0);opacity:0}5%{opacity:1}to{-webkit-transform:scale(1);transform:scale(1);opacity:0}}@keyframes ball-scale-multiple{0%{-webkit-transform:scale(0);transform:scale(0);opacity:0}5%{opacity:1}to{-webkit-transform:scale(1);transform:scale(1);opacity:0}}@-webkit-keyframes ball-triangle-path-1{33%{-webkit-transform:translate(25px,-50px);transform:translate(25px,-50px)}66%{-webkit-transform:translate(50px,0);transform:translate(50px,0)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-triangle-path-1{33%{-webkit-transform:translate(25px,-50px);transform:translate(25px,-50px)}66%{-webkit-transform:translate(50px,0);transform:translate(50px,0)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes ball-triangle-path-2{33%{-webkit-transform:translate(25px,50px);transform:translate(25px,50px)}66%{-webkit-transform:translate(-25px,50px);transform:translate(-25px,50px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-triangle-path-2{33%{-webkit-transform:translate(25px,50px);transform:translate(25px,50px)}66%{-webkit-transform:translate(-25px,50px);transform:translate(-25px,50px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes ball-triangle-path-3{33%{-webkit-transform:translate(-50px,0);transform:translate(-50px,0)}66%{-webkit-transform:translate(-25px,-50px);transform:translate(-25px,-50px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-triangle-path-3{33%{-webkit-transform:translate(-50px,0);transform:translate(-50px,0)}66%{-webkit-transform:translate(-25px,-50px);transform:translate(-25px,-50px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes ball-pulse-rise-even{0%{-webkit-transform:scale(1.1);transform:scale(1.1)}25%{-webkit-transform:translateY(-30px);transform:translateY(-30px)}50%{-webkit-transform:scale(.4);transform:scale(.4)}75%{-webkit-transform:translateY(30px);transform:translateY(30px)}to{-webkit-transform:translateY(0);transform:translateY(0);-webkit-transform:scale(1);transform:scale(1)}}@keyframes ball-pulse-rise-even{0%{-webkit-transform:scale(1.1);transform:scale(1.1)}25%{-webkit-transform:translateY(-30px);transform:translateY(-30px)}50%{-webkit-transform:scale(.4);transform:scale(.4)}75%{-webkit-transform:translateY(30px);transform:translateY(30px)}to{-webkit-transform:translateY(0);transform:translateY(0);-webkit-transform:scale(1);transform:scale(1)}}@-webkit-keyframes ball-pulse-rise-odd{0%{-webkit-transform:scale(.4);transform:scale(.4)}25%{-webkit-transform:translateY(30px);transform:translateY(30px)}50%{-webkit-transform:scale(1.1);transform:scale(1.1)}75%{-webkit-transform:translateY(-30px);transform:translateY(-30px)}to{-webkit-transform:translateY(0);transform:translateY(0);-webkit-transform:scale(.75);transform:scale(.75)}}@keyframes ball-pulse-rise-odd{0%{-webkit-transform:scale(.4);transform:scale(.4)}25%{-webkit-transform:translateY(30px);transform:translateY(30px)}50%{-webkit-transform:scale(1.1);transform:scale(1.1)}75%{-webkit-transform:translateY(-30px);transform:translateY(-30px)}to{-webkit-transform:translateY(0);transform:translateY(0);-webkit-transform:scale(.75);transform:scale(.75)}}@-webkit-keyframes ball-grid-beat{50%{opacity:.7}to{opacity:1}}@keyframes ball-grid-beat{50%{opacity:.7}to{opacity:1}}@-webkit-keyframes ball-grid-pulse{0%{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5);opacity:.7}to{-webkit-transform:scale(1);transform:scale(1);opacity:1}}@keyframes ball-grid-pulse{0%{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5);opacity:.7}to{-webkit-transform:scale(1);transform:scale(1);opacity:1}}@-webkit-keyframes ball-spin-fade-loader{50%{opacity:.3;-webkit-transform:scale(.4);transform:scale(.4)}to{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}@keyframes ball-spin-fade-loader{50%{opacity:.3;-webkit-transform:scale(.4);transform:scale(.4)}to{opacity:1;-webkit-transform:scale(1);transform:scale(1)}}@-webkit-keyframes ball-spin-loader{75%{opacity:.2}to{opacity:1}}@keyframes ball-spin-loader{75%{opacity:.2}to{opacity:1}}@-webkit-keyframes ball-zig{33%{-webkit-transform:translate(-15px,-30px);transform:translate(-15px,-30px)}66%{-webkit-transform:translate(15px,-30px);transform:translate(15px,-30px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-zig{33%{-webkit-transform:translate(-15px,-30px);transform:translate(-15px,-30px)}66%{-webkit-transform:translate(15px,-30px);transform:translate(15px,-30px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes ball-zag{33%{-webkit-transform:translate(15px,30px);transform:translate(15px,30px)}66%{-webkit-transform:translate(-15px,30px);transform:translate(-15px,30px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-zag{33%{-webkit-transform:translate(15px,30px);transform:translate(15px,30px)}66%{-webkit-transform:translate(-15px,30px);transform:translate(-15px,30px)}to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes ball-zig-deflect{17%,84%{-webkit-transform:translate(-15px,-30px);transform:translate(-15px,-30px)}34%,67%{-webkit-transform:translate(15px,-30px);transform:translate(15px,-30px)}50%,to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-zig-deflect{17%,84%{-webkit-transform:translate(-15px,-30px);transform:translate(-15px,-30px)}34%,67%{-webkit-transform:translate(15px,-30px);transform:translate(15px,-30px)}50%,to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes ball-zag-deflect{17%,84%{-webkit-transform:translate(15px,30px);transform:translate(15px,30px)}34%,67%{-webkit-transform:translate(-15px,30px);transform:translate(-15px,30px)}50%,to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@keyframes ball-zag-deflect{17%,84%{-webkit-transform:translate(15px,30px);transform:translate(15px,30px)}34%,67%{-webkit-transform:translate(-15px,30px);transform:translate(-15px,30px)}50%,to{-webkit-transform:translate(0,0);transform:translate(0,0)}}@-webkit-keyframes line-scale{0%,to{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}}@keyframes line-scale{0%,to{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}}@-webkit-keyframes line-scale-party{0%,to{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5)}}@keyframes line-scale-party{0%,to{-webkit-transform:scale(1);transform:scale(1)}50%{-webkit-transform:scale(.5);transform:scale(.5)}}@-webkit-keyframes line-scale-pulse-out{0%,to{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}}@keyframes line-scale-pulse-out{0%,to{-webkit-transform:scaley(1);transform:scaley(1)}50%{-webkit-transform:scaley(.4);transform:scaley(.4)}}@-webkit-keyframes line-scale-pulse-out-rapid{0%,90%{-webkit-transform:scaley(1);transform:scaley(1)}80%{-webkit-transform:scaley(.3);transform:scaley(.3)}}@keyframes line-scale-pulse-out-rapid{0%,90%{-webkit-transform:scaley(1);transform:scaley(1)}80%{-webkit-transform:scaley(.3);transform:scaley(.3)}}@-webkit-keyframes line-spin-fade-loader{50%{opacity:.3}to{opacity:1}}@keyframes line-spin-fade-loader{50%{opacity:.3}to{opacity:1}}@-webkit-keyframes triangle-skew-spin{25%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(0);transform:perspective(100px) rotateX(180deg) rotateY(0)}50%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(180deg);transform:perspective(100px) rotateX(180deg) rotateY(180deg)}75%{-webkit-transform:perspective(100px) rotateX(0) rotateY(180deg);transform:perspective(100px) rotateX(0) rotateY(180deg)}to{-webkit-transform:perspective(100px) rotateX(0) rotateY(0);transform:perspective(100px) rotateX(0) rotateY(0)}}@keyframes triangle-skew-spin{25%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(0);transform:perspective(100px) rotateX(180deg) rotateY(0)}50%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(180deg);transform:perspective(100px) rotateX(180deg) rotateY(180deg)}75%{-webkit-transform:perspective(100px) rotateX(0) rotateY(180deg);transform:perspective(100px) rotateX(0) rotateY(180deg)}to{-webkit-transform:perspective(100px) rotateX(0) rotateY(0);transform:perspective(100px) rotateX(0) rotateY(0)}}@-webkit-keyframes square-spin{25%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(0);transform:perspective(100px) rotateX(180deg) rotateY(0)}50%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(180deg);transform:perspective(100px) rotateX(180deg) rotateY(180deg)}75%{-webkit-transform:perspective(100px) rotateX(0) rotateY(180deg);transform:perspective(100px) rotateX(0) rotateY(180deg)}to{-webkit-transform:perspective(100px) rotateX(0) rotateY(0);transform:perspective(100px) rotateX(0) rotateY(0)}}@keyframes square-spin{25%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(0);transform:perspective(100px) rotateX(180deg) rotateY(0)}50%{-webkit-transform:perspective(100px) rotateX(180deg) rotateY(180deg);transform:perspective(100px) rotateX(180deg) rotateY(180deg)}75%{-webkit-transform:perspective(100px) rotateX(0) rotateY(180deg);transform:perspective(100px) rotateX(0) rotateY(180deg)}to{-webkit-transform:perspective(100px) rotateX(0) rotateY(0);transform:perspective(100px) rotateX(0) rotateY(0)}}@-webkit-keyframes rotate_pacman_half_up{0%,to{-webkit-transform:rotate(270deg);transform:rotate(270deg)}50%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes rotate_pacman_half_up{0%,to{-webkit-transform:rotate(270deg);transform:rotate(270deg)}50%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@-webkit-keyframes rotate_pacman_half_down{0%,to{-webkit-transform:rotate(90deg);transform:rotate(90deg)}50%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}}@keyframes rotate_pacman_half_down{0%,to{-webkit-transform:rotate(90deg);transform:rotate(90deg)}50%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}}@-webkit-keyframes pacman-balls{75%{opacity:.7}to{-webkit-transform:translate(-100px,-6.25px);transform:translate(-100px,-6.25px)}}@keyframes pacman-balls{75%{opacity:.7}to{-webkit-transform:translate(-100px,-6.25px);transform:translate(-100px,-6.25px)}}@-webkit-keyframes cube-transition{25%{-webkit-transform:translateX(50px) scale(.5) rotate(-90deg);transform:translateX(50px) scale(.5) rotate(-90deg)}50%{-webkit-transform:translate(50px,50px) rotate(-180deg);transform:translate(50px,50px) rotate(-180deg)}75%{-webkit-transform:translateY(50px) scale(.5) rotate(-270deg);transform:translateY(50px) scale(.5) rotate(-270deg)}to{-webkit-transform:rotate(-360deg);transform:rotate(-360deg)}}@keyframes cube-transition{25%{-webkit-transform:translateX(50px) scale(.5) rotate(-90deg);transform:translateX(50px) scale(.5) rotate(-90deg)}50%{-webkit-transform:translate(50px,50px) rotate(-180deg);transform:translate(50px,50px) rotate(-180deg)}75%{-webkit-transform:translateY(50px) scale(.5) rotate(-270deg);transform:translateY(50px) scale(.5) rotate(-270deg)}to{-webkit-transform:rotate(-360deg);transform:rotate(-360deg)}}@-webkit-keyframes spin-rotate{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}50%{-webkit-transform:rotate(180deg);transform:rotate(180deg)}to{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes spin-rotate{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}50%{-webkit-transform:rotate(180deg);transform:rotate(180deg)}to{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@-webkit-keyframes bar-progress{0%,to{-webkit-transform:scaleY(20%);transform:scaleY(20%);opacity:1}25%,75%{-webkit-transform:translateX(6%) scaleY(10%);transform:translateX(6%) scaleY(10%);opacity:.7}50%{-webkit-transform:translateX(20%) scaleY(20%);transform:translateX(20%) scaleY(20%);opacity:1}}@keyframes bar-progress{0%,to{-webkit-transform:scaleY(20%);transform:scaleY(20%);opacity:1}25%,75%{-webkit-transform:translateX(6%) scaleY(10%);transform:translateX(6%) scaleY(10%);opacity:.7}50%{-webkit-transform:translateX(20%) scaleY(20%);transform:translateX(20%) scaleY(20%);opacity:1}}@-webkit-keyframes bar-swing{0%,to{left:0}50%{left:70%}}@keyframes bar-swing{0%,to{left:0}50%{left:70%}}@-webkit-keyframes bar-swing-container{0%,to{left:0;-webkit-transform:translateX(0);transform:translateX(0)}50%{left:70%;-webkit-transform:translateX(-4px);transform:translateX(-4px)}}@keyframes bar-swing-container{0%,to{left:0;-webkit-transform:translateX(0);transform:translateX(0)}50%{left:70%;-webkit-transform:translateX(-4px);transform:translateX(-4px)}}.ball-pulse>div:nth-child(0){-webkit-animation:scale .75s -.36s infinite cubic-bezier(.2,.68,.18,1.08);animation:scale .75s -.36s infinite cubic-bezier(.2,.68,.18,1.08)}.ball-pulse>div:nth-child(1){-webkit-animation:scale .75s -.24s infinite cubic-bezier(.2,.68,.18,1.08);animation:scale .75s -.24s infinite cubic-bezier(.2,.68,.18,1.08)}.ball-pulse>div:nth-child(2){-webkit-animation:scale .75s -.12s infinite cubic-bezier(.2,.68,.18,1.08);animation:scale .75s -.12s infinite cubic-bezier(.2,.68,.18,1.08)}.ball-pulse>div:nth-child(3){-webkit-animation:scale .75s 0s infinite cubic-bezier(.2,.68,.18,1.08);animation:scale .75s 0s infinite cubic-bezier(.2,.68,.18,1.08)}.ball-pulse-sync>div,.ball-pulse>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block}.ball-pulse-sync>div:nth-child(0){-webkit-animation:ball-pulse-sync .6s -.21s infinite ease-in-out;animation:ball-pulse-sync .6s -.21s infinite ease-in-out}.ball-pulse-sync>div:nth-child(1){-webkit-animation:ball-pulse-sync .6s -.14s infinite ease-in-out;animation:ball-pulse-sync .6s -.14s infinite ease-in-out}.ball-pulse-sync>div:nth-child(2){-webkit-animation:ball-pulse-sync .6s -.07s infinite ease-in-out;animation:ball-pulse-sync .6s -.07s infinite ease-in-out}.ball-pulse-sync>div:nth-child(3){-webkit-animation:ball-pulse-sync .6s 0s infinite ease-in-out;animation:ball-pulse-sync .6s 0s infinite ease-in-out}.ball-scale-random>div,.ball-scale>div{background-color:#fff;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block;height:60px;width:60px;-webkit-animation:ball-scale 1s 0s ease-in-out infinite;animation:ball-scale 1s 0s ease-in-out infinite}.ball-scale-random{width:37px;height:40px}.ball-scale-random>div{position:absolute;height:30px;width:30px}.ball-scale-random>div:nth-child(1){margin-left:-7px;-webkit-animation:ball-scale 1s .2s ease-in-out infinite;animation:ball-scale 1s .2s ease-in-out infinite}.ball-scale-random>div:nth-child(3){margin-left:-2px;margin-top:9px;-webkit-animation:ball-scale 1s .5s ease-in-out infinite;animation:ball-scale 1s .5s ease-in-out infinite}.ball-rotate,.ball-rotate>div{position:relative}.ball-rotate>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both}.ball-rotate>div:first-child{-webkit-animation:rotate 1s 0s cubic-bezier(.7,-.13,.22,.86) infinite;animation:rotate 1s 0s cubic-bezier(.7,-.13,.22,.86) infinite}.ball-rotate>div:after,.ball-rotate>div:before{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;content:\"\";position:absolute;opacity:.8}.ball-rotate>div:before{top:0;left:-28px}.ball-rotate>div:after{top:0;left:25px}.ball-clip-rotate-pulse>div,.ball-clip-rotate>div{-webkit-animation-fill-mode:both;animation-fill-mode:both;border-radius:100%}.ball-clip-rotate>div{background-color:#fff;margin:2px;border:2px solid #fff;border-bottom-color:transparent;height:25px;width:25px;background:0 0!important;display:inline-block;-webkit-animation:rotate .75s 0s linear infinite;animation:rotate .75s 0s linear infinite}.ball-clip-rotate-pulse{position:relative;-webkit-transform:translateY(-15px);transform:translateY(-15px)}.ball-clip-rotate-pulse>div{position:absolute;top:0;left:0}.ball-clip-rotate-pulse>div:first-child{background:#fff;height:16px;width:16px;top:7px;left:-7px;-webkit-animation:scale 1s 0s cubic-bezier(.09,.57,.49,.9) infinite;animation:scale 1s 0s cubic-bezier(.09,.57,.49,.9) infinite}.ball-clip-rotate-pulse>div:last-child{position:absolute;width:30px;height:30px;left:-16px;top:-2px;background:0 0;border:2px solid;border-color:#fff transparent;-webkit-animation:rotate 1s 0s cubic-bezier(.09,.57,.49,.9) infinite;animation:rotate 1s 0s cubic-bezier(.09,.57,.49,.9) infinite;-webkit-animation-duration:1s;animation-duration:1s}.ball-clip-rotate-multiple{position:relative}.ball-clip-rotate-multiple>div{-webkit-animation-fill-mode:both;animation-fill-mode:both;position:absolute;left:-20px;top:-20px;border:2px solid #fff;border-bottom-color:transparent;border-top-color:transparent;border-radius:100%;height:35px;width:35px;-webkit-animation:rotate 1s 0s ease-in-out infinite;animation:rotate 1s 0s ease-in-out infinite}.ball-clip-rotate-multiple>div:last-child{display:inline-block;top:-10px;left:-10px;width:15px;height:15px;-webkit-animation-duration:.5s;animation-duration:.5s;border-color:#fff transparent;-webkit-animation-direction:reverse;animation-direction:reverse}.ball-scale-ripple>div{-webkit-animation-fill-mode:both;animation-fill-mode:both;height:50px;width:50px;border-radius:100%;border:2px solid #fff;-webkit-animation:ball-scale-ripple 1s 0s infinite cubic-bezier(.21,.53,.56,.8);animation:ball-scale-ripple 1s 0s infinite cubic-bezier(.21,.53,.56,.8)}.ball-scale-ripple-multiple{position:relative;-webkit-transform:translateY(-25px);transform:translateY(-25px)}.ball-scale-ripple-multiple>div:nth-child(0){-webkit-animation-delay:-.8s;animation-delay:-.8s}.ball-scale-ripple-multiple>div:nth-child(1){-webkit-animation-delay:-.6s;animation-delay:-.6s}.ball-scale-ripple-multiple>div:nth-child(2){-webkit-animation-delay:-.4s;animation-delay:-.4s}.ball-scale-ripple-multiple>div:nth-child(3){-webkit-animation-delay:-.2s;animation-delay:-.2s}.ball-beat>div,.ball-scale-ripple-multiple>div{border-radius:100%;-webkit-animation-fill-mode:both;animation-fill-mode:both}.ball-scale-ripple-multiple>div{position:absolute;top:-2px;left:-26px;border:2px solid #fff;-webkit-animation:ball-scale-ripple-multiple 1.25s 0s infinite cubic-bezier(.21,.53,.56,.8);animation:ball-scale-ripple-multiple 1.25s 0s infinite cubic-bezier(.21,.53,.56,.8);width:50px;height:50px}.ball-beat>div{background-color:#fff;width:15px;height:15px;margin:2px;display:inline-block;-webkit-animation:ball-beat .7s 0s infinite linear;animation:ball-beat .7s 0s infinite linear}.ball-beat>div:nth-child(2n-1){-webkit-animation-delay:-.35s!important;animation-delay:-.35s!important}.ball-scale-multiple{position:relative;-webkit-transform:translateY(-30px);transform:translateY(-30px)}.ball-scale-multiple>div:nth-child(2){-webkit-animation-delay:-.4s;animation-delay:-.4s}.ball-scale-multiple>div:nth-child(3){-webkit-animation-delay:-.2s;animation-delay:-.2s}.ball-scale-multiple>div{background-color:#fff;border-radius:100%;-webkit-animation-fill-mode:both;animation-fill-mode:both;position:absolute;left:-30px;top:0;opacity:0;margin:0;width:60px;height:60px;-webkit-animation:ball-scale-multiple 1s 0s linear infinite;animation:ball-scale-multiple 1s 0s linear infinite}.ball-triangle-path{position:relative;-webkit-transform:translate(-29.994px,-37.50938px);transform:translate(-29.994px,-37.50938px)}.ball-triangle-path>div:nth-child(1),.ball-triangle-path>div:nth-child(2),.ball-triangle-path>div:nth-child(3){-webkit-animation-name:ball-triangle-path-1;animation-name:ball-triangle-path-1;-webkit-animation-delay:0;animation-delay:0;-webkit-animation-duration:2s;animation-duration:2s;-webkit-animation-timing-function:ease-in-out;animation-timing-function:ease-in-out;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite}.ball-triangle-path>div:nth-child(2),.ball-triangle-path>div:nth-child(3){-webkit-animation-name:ball-triangle-path-2;animation-name:ball-triangle-path-2}.ball-triangle-path>div:nth-child(3){-webkit-animation-name:ball-triangle-path-3;animation-name:ball-triangle-path-3}.ball-triangle-path>div{-webkit-animation-fill-mode:both;animation-fill-mode:both;position:absolute;width:10px;height:10px;border-radius:100%;border:1px solid #fff}.ball-triangle-path>div:nth-of-type(1){top:50px}.ball-triangle-path>div:nth-of-type(2){left:25px}.ball-triangle-path>div:nth-of-type(3){top:50px;left:50px}.ball-pulse-rise>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block;-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-timing-function:cubic-bezier(.15,.46,.9,.6);animation-timing-function:cubic-bezier(.15,.46,.9,.6);-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-delay:0;animation-delay:0}.ball-pulse-rise>div:nth-child(2n){-webkit-animation-name:ball-pulse-rise-even;animation-name:ball-pulse-rise-even}.ball-pulse-rise>div:nth-child(2n-1){-webkit-animation-name:ball-pulse-rise-odd;animation-name:ball-pulse-rise-odd}.ball-grid-beat,.ball-grid-pulse{width:57px}.ball-grid-beat>div:nth-child(1){-webkit-animation-delay:.44s;animation-delay:.44s;-webkit-animation-duration:1.27s;animation-duration:1.27s}.ball-grid-beat>div:nth-child(2){-webkit-animation-delay:.2s;animation-delay:.2s;-webkit-animation-duration:1.52s;animation-duration:1.52s}.ball-grid-beat>div:nth-child(3){-webkit-animation-delay:.14s;animation-delay:.14s;-webkit-animation-duration:.61s;animation-duration:.61s}.ball-grid-beat>div:nth-child(4){-webkit-animation-delay:.15s;animation-delay:.15s;-webkit-animation-duration:.82s;animation-duration:.82s}.ball-grid-beat>div:nth-child(5){-webkit-animation-delay:-.01s;animation-delay:-.01s;-webkit-animation-duration:1.24s;animation-duration:1.24s}.ball-grid-beat>div:nth-child(6){-webkit-animation-delay:-.07s;animation-delay:-.07s;-webkit-animation-duration:1.35s;animation-duration:1.35s}.ball-grid-beat>div:nth-child(7){-webkit-animation-delay:.29s;animation-delay:.29s;-webkit-animation-duration:1.44s;animation-duration:1.44s}.ball-grid-beat>div:nth-child(8){-webkit-animation-delay:.63s;animation-delay:.63s;-webkit-animation-duration:1.19s;animation-duration:1.19s}.ball-grid-beat>div:nth-child(9){-webkit-animation-delay:-.18s;animation-delay:-.18s;-webkit-animation-duration:1.48s;animation-duration:1.48s}.ball-grid-beat>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block;float:left;-webkit-animation-name:ball-grid-beat;animation-name:ball-grid-beat;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-delay:0;animation-delay:0}.ball-grid-pulse>div:nth-child(1){-webkit-animation-delay:.58s;animation-delay:.58s;-webkit-animation-duration:.9s;animation-duration:.9s}.ball-grid-pulse>div:nth-child(2){-webkit-animation-delay:.01s;animation-delay:.01s;-webkit-animation-duration:.94s;animation-duration:.94s}.ball-grid-pulse>div:nth-child(3){-webkit-animation-delay:.25s;animation-delay:.25s;-webkit-animation-duration:1.43s;animation-duration:1.43s}.ball-grid-pulse>div:nth-child(4){-webkit-animation-delay:-.03s;animation-delay:-.03s;-webkit-animation-duration:.74s;animation-duration:.74s}.ball-grid-pulse>div:nth-child(5){-webkit-animation-delay:.21s;animation-delay:.21s;-webkit-animation-duration:.68s;animation-duration:.68s}.ball-grid-pulse>div:nth-child(6){-webkit-animation-delay:.25s;animation-delay:.25s;-webkit-animation-duration:1.17s;animation-duration:1.17s}.ball-grid-pulse>div:nth-child(7){-webkit-animation-delay:.46s;animation-delay:.46s;-webkit-animation-duration:1.41s;animation-duration:1.41s}.ball-grid-pulse>div:nth-child(8){-webkit-animation-delay:.02s;animation-delay:.02s;-webkit-animation-duration:1.56s;animation-duration:1.56s}.ball-grid-pulse>div:nth-child(9){-webkit-animation-delay:.13s;animation-delay:.13s;-webkit-animation-duration:.78s;animation-duration:.78s}.ball-grid-pulse>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block;float:left;-webkit-animation-name:ball-grid-pulse;animation-name:ball-grid-pulse;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-delay:0;animation-delay:0}.ball-spin-fade-loader{position:relative;top:-10px;left:-10px}.ball-spin-fade-loader>div:nth-child(1){top:25px;left:0;-webkit-animation:ball-spin-fade-loader 1s -.96s infinite linear;animation:ball-spin-fade-loader 1s -.96s infinite linear}.ball-spin-fade-loader>div:nth-child(2){top:17.04545px;left:17.04545px;-webkit-animation:ball-spin-fade-loader 1s -.84s infinite linear;animation:ball-spin-fade-loader 1s -.84s infinite linear}.ball-spin-fade-loader>div:nth-child(3){top:0;left:25px;-webkit-animation:ball-spin-fade-loader 1s -.72s infinite linear;animation:ball-spin-fade-loader 1s -.72s infinite linear}.ball-spin-fade-loader>div:nth-child(4){top:-17.04545px;left:17.04545px;-webkit-animation:ball-spin-fade-loader 1s -.6s infinite linear;animation:ball-spin-fade-loader 1s -.6s infinite linear}.ball-spin-fade-loader>div:nth-child(5){top:-25px;left:0;-webkit-animation:ball-spin-fade-loader 1s -.48s infinite linear;animation:ball-spin-fade-loader 1s -.48s infinite linear}.ball-spin-fade-loader>div:nth-child(6){top:-17.04545px;left:-17.04545px;-webkit-animation:ball-spin-fade-loader 1s -.36s infinite linear;animation:ball-spin-fade-loader 1s -.36s infinite linear}.ball-spin-fade-loader>div:nth-child(7){top:0;left:-25px;-webkit-animation:ball-spin-fade-loader 1s -.24s infinite linear;animation:ball-spin-fade-loader 1s -.24s infinite linear}.ball-spin-fade-loader>div:nth-child(8){top:17.04545px;left:-17.04545px;-webkit-animation:ball-spin-fade-loader 1s -.12s infinite linear;animation:ball-spin-fade-loader 1s -.12s infinite linear}.ball-spin-fade-loader>div{background-color:#fff;width:15px;height:15px;border-radius:100%;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;position:absolute}.ball-spin-loader{position:relative}.ball-spin-loader>span:nth-child(1){top:45px;left:0;-webkit-animation:ball-spin-loader 2s .9s infinite linear;animation:ball-spin-loader 2s .9s infinite linear}.ball-spin-loader>span:nth-child(2){top:30.68182px;left:30.68182px;-webkit-animation:ball-spin-loader 2s 1.8s infinite linear;animation:ball-spin-loader 2s 1.8s infinite linear}.ball-spin-loader>span:nth-child(3){top:0;left:45px;-webkit-animation:ball-spin-loader 2s 2.7s infinite linear;animation:ball-spin-loader 2s 2.7s infinite linear}.ball-spin-loader>span:nth-child(4){top:-30.68182px;left:30.68182px;-webkit-animation:ball-spin-loader 2s 3.6s infinite linear;animation:ball-spin-loader 2s 3.6s infinite linear}.ball-spin-loader>span:nth-child(5){top:-45px;left:0;-webkit-animation:ball-spin-loader 2s 4.5s infinite linear;animation:ball-spin-loader 2s 4.5s infinite linear}.ball-spin-loader>span:nth-child(6){top:-30.68182px;left:-30.68182px;-webkit-animation:ball-spin-loader 2s 5.4s infinite linear;animation:ball-spin-loader 2s 5.4s infinite linear}.ball-spin-loader>span:nth-child(7){top:0;left:-45px;-webkit-animation:ball-spin-loader 2s 6.3s infinite linear;animation:ball-spin-loader 2s 6.3s infinite linear}.ball-spin-loader>span:nth-child(8){top:30.68182px;left:-30.68182px;-webkit-animation:ball-spin-loader 2s 7.2s infinite linear;animation:ball-spin-loader 2s 7.2s infinite linear}.ball-spin-loader>div,.ball-zig-zag-deflect>div,.ball-zig-zag>div{width:15px;height:15px;border-radius:100%;-webkit-animation-fill-mode:both;animation-fill-mode:both;position:absolute}.ball-spin-loader>div{background:green}.ball-zig-zag,.ball-zig-zag-deflect{position:relative;-webkit-transform:translate(-15px,-15px);transform:translate(-15px,-15px)}.ball-zig-zag-deflect>div,.ball-zig-zag>div{background-color:#fff;margin:2px 2px 2px 15px;top:4px;left:-7px}.ball-zig-zag>div:first-child{-webkit-animation:ball-zig .7s 0s infinite linear;animation:ball-zig .7s 0s infinite linear}.ball-zig-zag>div:last-child{-webkit-animation:ball-zag .7s 0s infinite linear;animation:ball-zag .7s 0s infinite linear}.ball-zig-zag-deflect>div:first-child{-webkit-animation:ball-zig-deflect 1.5s 0s infinite linear;animation:ball-zig-deflect 1.5s 0s infinite linear}.ball-zig-zag-deflect>div:last-child{-webkit-animation:ball-zag-deflect 1.5s 0s infinite linear;animation:ball-zag-deflect 1.5s 0s infinite linear}.line-scale>div:nth-child(1){-webkit-animation:line-scale 1s -.4s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.4s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(2){-webkit-animation:line-scale 1s -.3s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.3s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(3){-webkit-animation:line-scale 1s -.2s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.2s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(4){-webkit-animation:line-scale 1s -.1s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s -.1s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div:nth-child(5){-webkit-animation:line-scale 1s 0s infinite cubic-bezier(.2,.68,.18,1.08);animation:line-scale 1s 0s infinite cubic-bezier(.2,.68,.18,1.08)}.line-scale>div{background-color:#fff;width:4px;height:35px;border-radius:2px;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block}.line-scale-party>div:nth-child(1){-webkit-animation-delay:-.09s;animation-delay:-.09s;-webkit-animation-duration:.83s;animation-duration:.83s}.line-scale-party>div:nth-child(2){-webkit-animation-delay:.33s;animation-delay:.33s;-webkit-animation-duration:.64s;animation-duration:.64s}.line-scale-party>div:nth-child(3){-webkit-animation-delay:.32s;animation-delay:.32s;-webkit-animation-duration:.39s;animation-duration:.39s}.line-scale-party>div:nth-child(4){-webkit-animation-delay:.47s;animation-delay:.47s;-webkit-animation-duration:.52s;animation-duration:.52s}.line-scale-party>div,.line-scale-pulse-out>div{background-color:#fff;width:4px;height:35px;border-radius:2px;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block}.line-scale-party>div{-webkit-animation-name:line-scale-party;animation-name:line-scale-party;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-delay:0;animation-delay:0}.line-scale-pulse-out>div{-webkit-animation:line-scale-pulse-out .9s -.6s infinite cubic-bezier(.85,.25,.37,.85);animation:line-scale-pulse-out .9s -.6s infinite cubic-bezier(.85,.25,.37,.85)}.line-scale-pulse-out>div:nth-child(2),.line-scale-pulse-out>div:nth-child(4){-webkit-animation-delay:-.4s!important;animation-delay:-.4s!important}.line-scale-pulse-out>div:nth-child(1),.line-scale-pulse-out>div:nth-child(5){-webkit-animation-delay:-.2s!important;animation-delay:-.2s!important}.line-scale-pulse-out-rapid>div{background-color:#fff;width:4px;height:35px;border-radius:2px;margin:2px;-webkit-animation-fill-mode:both;animation-fill-mode:both;display:inline-block;-webkit-animation:line-scale-pulse-out-rapid .9s -.5s infinite cubic-bezier(.11,.49,.38,.78);animation:line-scale-pulse-out-rapid .9s -.5s infinite cubic-bezier(.11,.49,.38,.78)}.line-scale-pulse-out-rapid>div:nth-child(2),.line-scale-pulse-out-rapid>div:nth-child(4){-webkit-animation-delay:-.25s!important;animation-delay:-.25s!important}.line-scale-pulse-out-rapid>div:nth-child(1),.line-scale-pulse-out-rapid>div:nth-child(5){-webkit-animation-delay:0s!important;animation-delay:0s!important}.line-spin-fade-loader{position:relative;top:-10px;left:-4px}.line-spin-fade-loader>div:nth-child(1){top:20px;left:0;-webkit-animation:line-spin-fade-loader 1.2s -.84s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.84s infinite ease-in-out}.line-spin-fade-loader>div:nth-child(2){top:13.63636px;left:13.63636px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:line-spin-fade-loader 1.2s -.72s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.72s infinite ease-in-out}.line-spin-fade-loader>div:nth-child(3){top:0;left:20px;-webkit-transform:rotate(90deg);transform:rotate(90deg);-webkit-animation:line-spin-fade-loader 1.2s -.6s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.6s infinite ease-in-out}.line-spin-fade-loader>div:nth-child(4){top:-13.63636px;left:13.63636px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:line-spin-fade-loader 1.2s -.48s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.48s infinite ease-in-out}.line-spin-fade-loader>div:nth-child(5){top:-20px;left:0;-webkit-animation:line-spin-fade-loader 1.2s -.36s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.36s infinite ease-in-out}.line-spin-fade-loader>div:nth-child(6){top:-13.63636px;left:-13.63636px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:line-spin-fade-loader 1.2s -.24s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.24s infinite ease-in-out}.line-spin-fade-loader>div:nth-child(7){top:0;left:-20px;-webkit-transform:rotate(90deg);transform:rotate(90deg);-webkit-animation:line-spin-fade-loader 1.2s -.12s infinite ease-in-out;animation:line-spin-fade-loader 1.2s -.12s infinite ease-in-out}.line-spin-fade-loader>div:nth-child(8){top:13.63636px;left:-13.63636px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:line-spin-fade-loader 1.2s 0s infinite ease-in-out;animation:line-spin-fade-loader 1.2s 0s infinite ease-in-out}.line-spin-fade-loader>div{background-color:#fff;border-radius:2px;margin:2px;position:absolute;width:5px;height:15px}.line-spin-fade-loader>div,.square-spin>div,.triangle-skew-spin>div{-webkit-animation-fill-mode:both;animation-fill-mode:both}.triangle-skew-spin>div{border-left:20px solid transparent;border-right:20px solid transparent;border-bottom:20px solid #fff;width:0;height:0;-webkit-animation:triangle-skew-spin 3s 0s cubic-bezier(.09,.57,.49,.9) infinite;animation:triangle-skew-spin 3s 0s cubic-bezier(.09,.57,.49,.9) infinite}.square-spin>div{width:50px;height:50px;background:#fff;border:1px solid red;-webkit-animation:square-spin 3s 0s cubic-bezier(.09,.57,.49,.9) infinite;animation:square-spin 3s 0s cubic-bezier(.09,.57,.49,.9) infinite}.pacman{position:relative}.pacman>div:nth-child(3){-webkit-animation:pacman-balls 1s -.66s infinite linear;animation:pacman-balls 1s -.66s infinite linear}.pacman>div:nth-child(4){-webkit-animation:pacman-balls 1s -.33s infinite linear;animation:pacman-balls 1s -.33s infinite linear}.pacman>div:nth-child(5){-webkit-animation:pacman-balls 1s 0s infinite linear;animation:pacman-balls 1s 0s infinite linear}.pacman>div:first-of-type{width:0;height:0;border-right:25px solid transparent;border-top:25px solid #fff;border-left:25px solid #fff;border-bottom:25px solid #fff;border-radius:25px;-webkit-animation:rotate_pacman_half_up .5s 0s infinite;animation:rotate_pacman_half_up .5s 0s infinite;position:relative;left:-30px}.pacman>div:nth-child(2){width:0;height:0;border-right:25px solid transparent;border-top:25px solid #fff;border-left:25px solid #fff;border-bottom:25px solid #fff;border-radius:25px;-webkit-animation:rotate_pacman_half_down .5s 0s infinite;animation:rotate_pacman_half_down .5s 0s infinite;margin-top:-50px;position:relative;left:-30px}.pacman>div:nth-child(3),.pacman>div:nth-child(4),.pacman>div:nth-child(5),.pacman>div:nth-child(6){background-color:#fff;border-radius:100%;margin:2px;width:10px;height:10px;position:absolute;-webkit-transform:translate(0,-6.25px);transform:translate(0,-6.25px);top:25px;left:70px}.cube-transition{position:relative;-webkit-transform:translate(-25px,-25px);transform:translate(-25px,-25px)}.cube-transition>div{-webkit-animation-fill-mode:both;animation-fill-mode:both;width:10px;height:10px;position:absolute;top:-5px;left:-5px;background-color:#fff;-webkit-animation:cube-transition 1.6s 0s infinite ease-in-out;animation:cube-transition 1.6s 0s infinite ease-in-out}.cube-transition>div:last-child{-webkit-animation-delay:-.8s;animation-delay:-.8s}.semi-circle-spin{position:relative;width:35px;height:35px;overflow:hidden}.semi-circle-spin>div{position:absolute;border-width:0;border-radius:100%;-webkit-animation:spin-rotate .6s 0s infinite linear;animation:spin-rotate .6s 0s infinite linear;background-image:-webkit-gradient(linear,left top, left bottom,from(transparent),color-stop(70%, transparent),color-stop(30%, #fff),to(#fff));background-image:linear-gradient(transparent 0%,transparent 70%,#fff 30%,#fff 100%);width:100%;height:100%}.bar-progress{width:30%;height:12px}.bar-progress>div{position:relative;width:20%;height:12px;border-radius:10px;background-color:#fff;-webkit-animation:bar-progress 3s cubic-bezier(.57,.1,.44,.93) infinite;animation:bar-progress 3s cubic-bezier(.57,.1,.44,.93) infinite;opacity:1}.bar-swing,.bar-swing>div{width:30%;height:8px}.bar-swing>div{position:relative;border-radius:10px;background-color:#fff;-webkit-animation:bar-swing 1.5s infinite;animation:bar-swing 1.5s infinite}.bar-swing-container{width:20%;height:8px;position:relative}.bar-swing-container div:nth-child(1){position:absolute;width:100%;background-color:rgba(255,255,255,.2);height:12px;border-radius:10px}.bar-swing-container div:nth-child(2){position:absolute;width:30%;height:8px;border-radius:10px;background-color:#fff;-webkit-animation:bar-swing-container 2s cubic-bezier(.91,.35,.12,.6) infinite;animation:bar-swing-container 2s cubic-bezier(.91,.35,.12,.6) infinite;margin:2px 2px 0}\n.vue-loaders{display:inline-block;-webkit-box-sizing:content-box;box-sizing:content-box}.vue-loaders *,.vue-loaders :after,.vue-loaders :before{-webkit-box-sizing:inherit;box-sizing:inherit}\n\n\n\n.vue-loaders.ball-clip-rotate-pulse{-webkit-transform:none;transform:none}.vue-loaders.ball-clip-rotate-multiple>div,.vue-loaders.ball-clip-rotate-multiple>div:last-child{left:auto;top:auto}\n\n.vue-loaders.ball-grid-pulse:after,.vue-loaders.ball-grid-pulse:before{content:'';display:table}.vue-loaders.ball-grid-pulse:after{clear:both}\n\n.vue-loaders.ball-pulse-rise{padding-top:30px;padding-bottom:30px}\n\n.vue-loaders.ball-rotate>div:after,.vue-loaders.ball-rotate>div:before{display:none}.vue-loaders.ball-rotate{padding:26px}.vue-loaders.ball-rotate>div{margin:0}.vue-loaders.ball-rotate>div>div{top:auto;position:absolute;opacity:.8;background-color:#fff;width:15px;height:15px;border-radius:100%}.vue-loaders.ball-rotate>div>div:first-child{left:-26px}.vue-loaders.ball-rotate>div>div:last-child{left:26px}\n\n.vue-loaders.ball-scale-multiple{-webkit-transform:none;transform:none;width:60px;height:60px}.vue-loaders.ball-scale-multiple>div{top:auto;left:auto}\n\n.vue-loaders.ball-scale-ripple-multiple{-webkit-transform:none;transform:none;width:54px;height:54px}.vue-loaders.ball-scale-ripple-multiple>div{top:auto;left:auto}\n.vue-loaders.ball-spin-fade-loader{top:auto;left:auto;border:25px solid transparent;width:15px;height:15px}.vue-loaders.ball-spin-fade-loader>div{margin:0}\n.vue-loaders.ball-triangle-path{-webkit-transform:none;transform:none;width:60px;height:60px}\n.vue-loaders.ball-zig-zag{-webkit-transform:none;transform:none;height:15px;width:15px;padding:30px 15px}.vue-loaders.ball-zig-zag>div{left:auto;top:auto;margin:0}\n.vue-loaders.ball-zig-zag-deflect{-webkit-transform:none;transform:none;height:15px;width:15px;padding:30px 15px}.vue-loaders.ball-zig-zag-deflect>div{left:auto;top:auto;margin:0}\n.vue-loaders.cube-transition{-webkit-transform:none;transform:none;width:60px;height:60px}.vue-loaders.cube-transition>div{left:auto;top:auto}\n\n\n\n\n.vue-loaders.line-spin-fade-loader{top:auto;left:auto;border:20px solid transparent;width:15px;height:15px}.vue-loaders.line-spin-fade-loader>div{margin:0}\n.vue-loaders.pacman{border-left:30px solid transparent;border-right:30px solid transparent}.vue-loaders.pacman>div:nth-child(n+3){margin:0}\n\n.vue-loaders.square-spin>div{border:0}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-snotify/styles/simple.css":
/*!************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-snotify/styles/simple.css ***!
  \************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".snotifyToast {\n  -webkit-animation-fill-mode: both;\n  animation-fill-mode: both; }\n\n.snotify-leftTop .fadeIn,\n.snotify-leftCenter .fadeIn,\n.snotify-leftBottom .fadeIn {\n  -webkit-animation-name: fadeInLeft;\n  animation-name: fadeInLeft; }\n\n.snotify-leftTop .fadeOut,\n.snotify-leftCenter .fadeOut,\n.snotify-leftBottom .fadeOut {\n  -webkit-animation-name: fadeOutLeft;\n  animation-name: fadeOutLeft; }\n\n.snotify-rightTop .fadeIn,\n.snotify-rightCenter .fadeIn,\n.snotify-rightBottom .fadeIn {\n  -webkit-animation-name: fadeInRight;\n  animation-name: fadeInRight; }\n\n.snotify-rightTop .fadeOut,\n.snotify-rightCenter .fadeOut,\n.snotify-rightBottom .fadeOut {\n  -webkit-animation-name: fadeOutRight;\n  animation-name: fadeOutRight; }\n\n.snotify-centerTop .fadeIn {\n  -webkit-animation-name: fadeInDown;\n  animation-name: fadeInDown; }\n\n.snotify-centerTop .fadeOut {\n  -webkit-animation-name: fadeOutUp;\n  animation-name: fadeOutUp; }\n\n.snotify-centerCenter .fadeIn {\n  -webkit-animation-name: fadeIn;\n  animation-name: fadeIn; }\n\n.snotify-centerCenter .fadeOut {\n  -webkit-animation-name: fadeOut;\n  animation-name: fadeOut; }\n\n.snotify-centerBottom .fadeIn {\n  -webkit-animation-name: fadeInUp;\n  animation-name: fadeInUp; }\n\n.snotify-centerBottom .fadeOut {\n  -webkit-animation-name: fadeOutDown;\n  animation-name: fadeOutDown; }\n\n@-webkit-keyframes fadeInLeft {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(-100%, 0, 0) scaleX(1.2);\n    transform: translate3d(-100%, 0, 0) scaleX(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@keyframes fadeInLeft {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(-100%, 0, 0) scaleX(1.2);\n    transform: translate3d(-100%, 0, 0) scaleX(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@-webkit-keyframes fadeInRight {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(100%, 0, 0) scaleX(1.2);\n    transform: translate3d(100%, 0, 0) scaleX(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@keyframes fadeInRight {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(100%, 0, 0) scaleX(1.2);\n    transform: translate3d(100%, 0, 0) scaleX(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@-webkit-keyframes fadeInUp {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, 100%, 0) scaleY(1.2);\n    transform: translate3d(0, 100%, 0) scaleY(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@keyframes fadeInUp {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, 100%, 0) scaleY(1.2);\n    transform: translate3d(0, 100%, 0) scaleY(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@-webkit-keyframes fadeInDown {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, -100%, 0) scaleY(1.2);\n    transform: translate3d(0, -100%, 0) scaleY(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@keyframes fadeInDown {\n  0% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, -100%, 0) scaleY(1.2);\n    transform: translate3d(0, -100%, 0) scaleY(1.2); }\n  100% {\n    opacity: 1;\n    -webkit-transform: none;\n    transform: none; } }\n\n@-webkit-keyframes fadeIn {\n  0% {\n    opacity: 0; }\n  100% {\n    opacity: 1; } }\n\n@keyframes fadeIn {\n  0% {\n    opacity: 0; }\n  100% {\n    opacity: 1; } }\n\n@-webkit-keyframes fadeOut {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0; } }\n\n@keyframes fadeOut {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0; } }\n\n@-webkit-keyframes fadeOutDown {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, 100%, 0);\n    transform: translate3d(0, 100%, 0); } }\n\n@keyframes fadeOutDown {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, 100%, 0);\n    transform: translate3d(0, 100%, 0); } }\n\n@-webkit-keyframes fadeOutLeft {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(-100%, 0, 0);\n    transform: translate3d(-100%, 0, 0); } }\n\n@keyframes fadeOutLeft {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(-100%, 0, 0);\n    transform: translate3d(-100%, 0, 0); } }\n\n@-webkit-keyframes fadeOutRight {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(100%, 0, 0);\n    transform: translate3d(100%, 0, 0); } }\n\n@keyframes fadeOutRight {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(100%, 0, 0);\n    transform: translate3d(100%, 0, 0); } }\n\n@-webkit-keyframes fadeOutUp {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, -100%, 0);\n    transform: translate3d(0, -100%, 0); } }\n\n@keyframes fadeOutUp {\n  0% {\n    opacity: 1; }\n  100% {\n    opacity: 0;\n    -webkit-transform: translate3d(0, -100%, 0);\n    transform: translate3d(0, -100%, 0); } }\n\n@-webkit-keyframes appear {\n  0% {\n    max-height: 0; }\n  100% {\n    max-height: 50vh; } }\n\n@keyframes appear {\n  0% {\n    max-height: 0; }\n  100% {\n    max-height: 50vh; } }\n\n@-webkit-keyframes disappear {\n  0% {\n    opacity: 0;\n    max-height: 50vh; }\n  100% {\n    opacity: 0;\n    max-height: 0; } }\n\n@keyframes disappear {\n  0% {\n    opacity: 0;\n    max-height: 50vh; }\n  100% {\n    opacity: 0;\n    max-height: 0; } }\n\n@-webkit-keyframes async {\n  0% {\n    -webkit-transform: translate(0, -50%) rotate(0deg);\n    transform: translate(0, -50%) rotate(0deg); }\n  100% {\n    -webkit-transform: translate(0, -50%) rotate(360deg);\n    transform: translate(0, -50%) rotate(360deg); } }\n\n@keyframes async {\n  0% {\n    -webkit-transform: translate(0, -50%) rotate(0deg);\n    transform: translate(0, -50%) rotate(0deg); }\n  100% {\n    -webkit-transform: translate(0, -50%) rotate(360deg);\n    transform: translate(0, -50%) rotate(360deg); } }\n\n.snotify {\n  display: block;\n  position: fixed;\n  width: 300px;\n  z-index: 9999;\n  box-sizing: border-box;\n  pointer-events: none; }\n  .snotify * {\n    box-sizing: border-box; }\n\n.snotify-leftTop,\n.snotify-leftCenter,\n.snotify-leftBottom {\n  left: 10px; }\n\n.snotify-rightTop,\n.snotify-rightCenter,\n.snotify-rightBottom {\n  right: 10px; }\n\n.snotify-centerTop,\n.snotify-centerCenter,\n.snotify-centerBottom {\n  left: calc(50% - 300px / 2); }\n\n.snotify-leftTop,\n.snotify-centerTop,\n.snotify-rightTop {\n  top: 10px; }\n\n.snotify-leftCenter,\n.snotify-rightCenter,\n.snotify-centerCenter {\n  top: 50%;\n  -webkit-transform: translateY(-50%);\n  transform: translateY(-50%); }\n\n.snotify-leftBottom,\n.snotify-rightBottom,\n.snotify-centerBottom {\n  bottom: 10px; }\n\n.snotify-backdrop {\n  position: fixed;\n  top: 0;\n  right: 0;\n  bottom: 0;\n  left: 0;\n  background-color: #000;\n  opacity: 0;\n  z-index: 9998;\n  transition: opacity .3s; }\n\n.snotifyToast {\n  display: block;\n  cursor: pointer;\n  background-color: #fff;\n  max-height: 300px;\n  height: 100%;\n  margin: 5px;\n  opacity: 0;\n  overflow: hidden;\n  pointer-events: auto; }\n  .snotifyToast--in {\n    -webkit-animation-name: appear;\n    animation-name: appear; }\n  .snotifyToast--out {\n    -webkit-animation-name: disappear;\n    animation-name: disappear; }\n  .snotifyToast__inner {\n    display: flex;\n    flex-flow: column nowrap;\n    align-items: flex-start;\n    justify-content: center;\n    position: relative;\n    padding: 5px 65px 5px 15px;\n    min-height: 78px;\n    font-size: 16px;\n    color: #000; }\n  .snotifyToast__noIcon {\n    padding: 5px 15px 5px 15px; }\n  .snotifyToast__progressBar {\n    position: relative;\n    width: 100%;\n    height: 5px;\n    background-color: #c7c7c7; }\n    .snotifyToast__progressBar__percentage {\n      position: absolute;\n      top: 0;\n      left: 0;\n      height: 5px;\n      background-color: #4c4c4c;\n      max-width: 100%; }\n  .snotifyToast__title {\n    font-size: 1.8em;\n    line-height: 1.2em;\n    margin-bottom: 5px;\n    color: #000; }\n  .snotifyToast__body {\n    font-size: 1em;\n    color: #000; }\n\n.snotifyToast-show {\n  -webkit-transform: translate(0, 0);\n  transform: translate(0, 0);\n  opacity: 1; }\n\n.snotifyToast-remove {\n  max-height: 0;\n  overflow: hidden;\n  -webkit-transform: translate(0, 50%);\n  transform: translate(0, 50%);\n  opacity: 0; }\n\n/***************\r\n ** Modifiers **\r\n **************/\n.snotify-simple {\n  border-left: 4px solid #000; }\n\n.snotify-success {\n  border-left: 4px solid #4caf50; }\n\n.snotify-info {\n  border-left: 4px solid #1e88e5; }\n\n.snotify-warning {\n  border-left: 4px solid #ff9800; }\n\n.snotify-error {\n  border-left: 4px solid #f44336; }\n\n.snotify-async {\n  border-left: 4px solid #1e88e5; }\n\n.snotify-confirm {\n  border-left: 4px solid #009688; }\n\n.snotify-prompt {\n  border-left: 4px solid #009688; }\n\n.snotify-confirm .snotifyToast__inner,\n.snotify-prompt .snotifyToast__inner {\n  padding: 10px 15px; }\n\n.snotifyToast__input {\n  position: relative;\n  z-index: 1;\n  display: inline-block;\n  margin: 0;\n  width: 100%;\n  vertical-align: top;\n  transition: all .5s;\n  transition-delay: .3s;\n  transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1); }\n  .snotifyToast__input__field {\n    position: relative;\n    display: block;\n    float: right;\n    padding: .85em .5em;\n    width: 100%;\n    border: none;\n    border-radius: 0;\n    background: transparent;\n    color: #333;\n    font-weight: bold;\n    -webkit-appearance: none;\n    /* for box shadows to show on iOS */\n    opacity: 0;\n    transition: opacity .3s; }\n    .snotifyToast__input__field:focus {\n      outline: none; }\n  .snotifyToast__input__label {\n    display: inline-block;\n    float: right;\n    padding: 0 .85em;\n    width: 100%;\n    color: #999;\n    font-weight: bold;\n    font-size: 70.25%;\n    -webkit-font-smoothing: antialiased;\n    -moz-osx-font-smoothing: grayscale;\n    -webkit-touch-callout: none;\n    -webkit-user-select: none;\n    -moz-user-select: none;\n    -ms-user-select: none;\n    user-select: none;\n    position: absolute;\n    left: 0;\n    height: 100%;\n    text-align: left;\n    pointer-events: none; }\n    .snotifyToast__input__label::before, .snotifyToast__input__label::after {\n      content: '';\n      position: absolute;\n      top: 0;\n      left: 0;\n      width: 100%;\n      height: 100%;\n      transition: -webkit-transform .3s;\n      transition: transform .3s;\n      transition: transform .3s, -webkit-transform .3s; }\n    .snotifyToast__input__label::before {\n      border-top: 2px solid #009688;\n      -webkit-transform: translate3d(0, 100%, 0) translate3d(0, -2px, 0);\n      transform: translate3d(0, 100%, 0) translate3d(0, -2px, 0);\n      transition-delay: .3s; }\n    .snotifyToast__input__label::after {\n      z-index: -1;\n      background: #eee;\n      -webkit-transform: scale3d(1, 0, 1);\n      transform: scale3d(1, 0, 1);\n      -webkit-transform-origin: 50% 0;\n      transform-origin: 50% 0; }\n  .snotifyToast__input__labelContent {\n    position: relative;\n    display: block;\n    padding: 1em 0;\n    width: 100%;\n    transition: -webkit-transform .3s .3s;\n    transition: transform .3s .3s;\n    transition: transform .3s .3s, -webkit-transform .3s .3s; }\n\n.snotifyToast__input--filled {\n  margin-top: 2.5em; }\n  .snotifyToast__input--filled:focus,\n  .snotifyToast__input--filled .snotifyToast__input__field {\n    opacity: 1;\n    transition-delay: .3s; }\n\n.snotifyToast__input__field:focus + .snotifyToast__input__label .snotifyToast__input__labelContent,\n.snotifyToast__input--filled .snotifyToast__input__labelContent {\n  -webkit-transform: translate(0, -80%);\n  transform: translate(0, -80%);\n  transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1); }\n\n.snotifyToast__input__field:focus + .snotifyToast__input__label::before,\n.snotifyToast__input--filled .snotifyToast__input__label::before {\n  transition-delay: 0s; }\n\n.snotifyToast__input__field:focus + .snotifyToast__input__label::before,\n.snotifyToast__input--filled .snotifyToast__input__label::before {\n  -webkit-transform: translate(0, 0);\n  transform: translate(0, 0); }\n\n.snotifyToast__input__field:focus + .snotifyToast__input__label::after,\n.snotifyToast__input--filled .snotifyToast__input__label::after {\n  -webkit-transform: scale(1, 1);\n  transform: scale(1, 1);\n  transition-delay: .3s;\n  transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1); }\n\n.snotifyToast--invalid .snotifyToast__input__label::before {\n  border-color: #f44336; }\n\n.snotifyToast--valid .snotifyToast__input__label::before {\n  border-color: #4caf50; }\n\n.snotifyToast__buttons {\n  display: flex;\n  flex-flow: row nowrap;\n  justify-content: space-between;\n  border-top: 1px solid rgba(0, 0, 0, 0.1); }\n  .snotifyToast__buttons button {\n    position: relative;\n    width: 100%;\n    border-right: 1px solid rgba(0, 0, 0, 0.1);\n    border-left: 1px solid rgba(0, 0, 0, 0.1);\n    border-top: none;\n    border-bottom: none;\n    background: transparent;\n    padding: 8px;\n    text-transform: capitalize;\n    color: #000; }\n    .snotifyToast__buttons button:hover, .snotifyToast__buttons button:focus {\n      background: rgba(0, 0, 0, 0.1);\n      outline: none; }\n    .snotifyToast__buttons button:active {\n      background: rgba(0, 0, 0, 0.15); }\n    .snotifyToast__buttons button:last-child {\n      border-right: none; }\n    .snotifyToast__buttons button:first-child {\n      border-left: none; }\n  .snotifyToast__buttons--bold {\n    font-weight: 700; }\n\n.snotify-icon {\n  position: absolute;\n  right: 10px;\n  top: 50%;\n  line-height: 0;\n  -webkit-transform: translate(0, -50%);\n  transform: translate(0, -50%);\n  max-height: 48px;\n  max-width: 48px;\n  width: 100%;\n  height: 100%; }\n\n.snotify-icon--error {\n  background-image: url(\"data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20version=%221.1%22%20x=%220px%22%20y=%220px%22%20viewBox=%220%200%20512%20512%22%20fill=%22%23f44336%22%3E%3Cg%3E%3Cpath%20d=%22M437,75A256,256,0,1,0,75,437,256,256,0,1,0,437,75ZM416.43,416.43a226.82,226.82,0,0,1-320.86,0C7.11,328,7.11,184,95.57,95.57a226.82,226.82,0,0,1,320.86,0C504.89,184,504.89,328,416.43,416.43Z%22/%3E%3Cpath%20d=%22M368.81,143.19a14.5,14.5,0,0,0-20.58,0L256,235.42l-92.23-92.23a14.55,14.55,0,0,0-20.58,20.58L235.42,256l-92.23,92.23a14.6,14.6,0,0,0,10.24,24.89,14.19,14.19,0,0,0,10.24-4.31l92.23-92.23,92.23,92.23a14.64,14.64,0,0,0,10.24,4.31,14,14,0,0,0,10.24-4.31,14.5,14.5,0,0,0,0-20.58l-92-92.23,92.23-92.23A14.5,14.5,0,0,0,368.81,143.19Z%22/%3E%3C/g%3E%3C/svg%3E\"); }\n\n.snotify-icon--warning {\n  background-image: url(\"data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20version=%221.1%22%20x=%220px%22%20y=%220px%22%20viewBox=%220%200%20512%20512%22%20fill=%22%23ff9800%22%3E%3Cg%3E%3Cpath%20d=%22M256,512c141.15,0,256-114.84,256-256S397.15,0,256,0,0,114.84,0,256,114.85,512,256,512Zm0-480.49c123.79,0,224.49,100.71,224.49,224.49S379.79,480.49,256,480.49,31.51,379.79,31.51,256,132.21,31.51,256,31.51Z%22/%3E%3Ccircle%20cx=%22260.08%22%20cy=%22343.87%22%20r=%2226.35%22/%3E%3Cpath%20d=%22M254.68,278.39a15.76,15.76,0,0,0,15.75-15.75V128.72a15.75,15.75,0,1,0-31.51,0V262.63A15.76,15.76,0,0,0,254.68,278.39Z%22/%3E%3C/g%3E%3C/svg%3E\"); }\n\n.snotify-icon--info {\n  background-image: url(\"data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20version=%221.1%22%20x=%220px%22%20y=%220px%22%20viewBox=%220%200%20512%20512%22%20fill=%22%231e88e5%22%3E%3Cg%3E%3Cpath%20d=%22M256,0C114.84,0,0,114.84,0,256S114.84,512,256,512,512,397.16,512,256,397.15,0,256,0Zm0,478.43C133.35,478.43,33.57,378.64,33.57,256S133.35,33.58,256,33.58,478.42,133.36,478.42,256,378.64,478.43,256,478.43Z%22/%3E%3Cpath%20d=%22M251.26,161.24a22.39,22.39,0,1,0-22.38-22.39A22.39,22.39,0,0,0,251.26,161.24Z%22/%3E%3Cpath%20d=%22M286.84,357.87h-14v-160A16.79,16.79,0,0,0,256,181.05H225.17a16.79,16.79,0,0,0,0,33.58h14.05V357.87H225.17a16.79,16.79,0,0,0,0,33.57h61.67a16.79,16.79,0,1,0,0-33.57Z%22/%3E%3C/g%3E%3C/svg%3E\"); }\n\n.snotify-icon--success {\n  background-image: url(\"data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20version=%221.1%22%20x=%220px%22%20y=%220px%22%20viewBox=%220%200%20512%20512%22%20fill=%22%234caf50%22%3E%3Cg%3E%3Cpath%20d=%22M256,0C114.85,0,0,114.84,0,256S114.85,512,256,512,512,397.16,512,256,397.15,0,256,0Zm0,492.31c-130.29,0-236.31-106-236.31-236.31S125.71,19.69,256,19.69,492.31,125.71,492.31,256,386.29,492.31,256,492.31Z%22/%3E%3Cpath%20class=%22cls-1%22%20d=%22M376.64,151,225.31,321.24l-91.17-72.93a9.85,9.85,0,0,0-12.3,15.38l98.46,78.77a9.86,9.86,0,0,0,13.52-1.15L391.36,164.08A9.85,9.85,0,0,0,376.64,151Z%22/%3E%3C/g%3E%3C/svg%3E\"); }\n\n.snotify-icon--async {\n  background-image: url(\"data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20version=%221.1%22%20x=%220px%22%20y=%220px%22%20viewBox=%220%200%20512%20512%22%20fill=%22%231e88e5%22%3E%3Cg%3E%3Cpath%20d=%22M256,0a32,32,0,0,0-32,32V96a32,32,0,0,0,64,0V32A32,32,0,0,0,256,0Zm0,384a32,32,0,0,0-32,32v64a32,32,0,0,0,64,0V416A32,32,0,0,0,256,384ZM391.74,165.5,437,120.22A32,32,0,0,0,391.74,75L346.5,120.22a32,32,0,0,0,45.25,45.28Zm-271.52,181L75,391.74A32,32,0,0,0,120.22,437l45.25-45.25a32,32,0,0,0-45.25-45.25Zm0-271.52A32,32,0,1,0,75,120.22l45.25,45.28a32,32,0,1,0,45.25-45.28ZM391.74,346.5a32,32,0,0,0-45.25,45.25L391.74,437A32,32,0,0,0,437,391.74ZM480,224H416a32,32,0,0,0,0,64h64a32,32,0,0,0,0-64ZM128,256a32,32,0,0,0-32-32H32a32,32,0,0,0,0,64H96A32,32,0,0,0,128,256Z%22/%3E%3C/g%3E%3C/svg%3E\");\n  -webkit-animation: async 3s infinite linear;\n  animation: async 3s infinite linear;\n  -webkit-transform-origin: 50% 50%;\n  transform-origin: 50% 50%; }\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.options[data-v-beebbd4a] {\n  -webkit-column-count: 2;\n     -moz-column-count: 2;\n          column-count: 2;\n}\n@media (max-width: 480px) {\n.options[data-v-beebbd4a] {\n    -webkit-column-count: 1;\n       -moz-column-count: 1;\n            column-count: 1;\n}\n}\n.options img[data-v-beebbd4a] {\n  top: 7px;\n}\n.options a[data-v-beebbd4a] {\n  left: 33px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\ninput[type=checkbox][data-v-152dace3] {\n  top: 5px;\n}\ninput[type=radio][data-v-152dace3] {\n  top: -2px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.list li[data-v-0a3c956b]:last-child {\n  border-bottom: 0;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.add-modal[data-v-6dd31a03] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 36px;\n  right: 0;\n}\n.add-modal[data-v-6dd31a03]:after,\n.add-modal[data-v-6dd31a03]:before {\n  content: \"\";\n  display: inline-block;\n  position: absolute;\n}\n.add-modal[data-v-6dd31a03]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -14px;\n}\n.add-modal[data-v-6dd31a03]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.positions-list[data-v-59756b39] {\n  max-height: 150px;\n}\n.popupmenu[data-v-59756b39] {\n  right: 2px;\n  top: 26px;\n  width: 280px;\n}\n.c-delete[data-v-59756b39]:hover {\n  border-bottom-width: 0;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.teams-list[data-v-6813d326] {\n  max-height: 150px;\n}\n.popupmenu[data-v-6813d326] {\n  right: 2px;\n  top: 26px;\n  width: 280px;\n}\n.c-delete[data-v-6813d326]:hover {\n  border-bottom-width: 0;\n}\n.existing-teams li[data-v-6813d326]:not(:last-child) {\n  margin-right: 5px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.avatar[data-v-0bc2ef2a] {\n  width: 80px;\n  height: 80px;\n  top: 19%;\n  left: 50%;\n  margin-top: -40px; /* Half the height */\n  margin-left: -40px; /* Half the width */\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.list-employees > ul[data-v-329a59ba] {\n  padding-left: 43px;\n}\n.list-employees li[data-v-329a59ba]:last-child {\n  margin-bottom: 0;\n}\n.avatar[data-v-329a59ba] {\n  top: 1px;\n  left: -44px;\n  width: 35px;\n}\n.list-employees-action[data-v-329a59ba] {\n  top: 15px;\n}\n.list-employees-modal[data-v-329a59ba] {\n  right: -6px;\n  top: 27px;\n}\n.icon-delete[data-v-329a59ba] {\n  top: 2px;\n}\n.ball-pulse[data-v-329a59ba] {\n  right: 8px;\n  top: 10px;\n  position: absolute;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.avatar[data-v-37f72ce6] {\n  width: 80px;\n  height: 80px;\n  top: 32px;\n  left: 50%;\n  margin-top: -40px; /* Half the height */\n  margin-left: -40px; /* Half the width */\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.dummy[data-v-4fcdcc85] {\n  right: 40px;\n  bottom: 20px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.absolute[data-v-6e7694b3] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 36px;\n}\n.absolute[data-v-6e7694b3]:after,\n.absolute[data-v-6e7694b3]:before {\n  content: \"\";\n  display: inline-block;\n  position: absolute;\n}\n.absolute[data-v-6e7694b3]:after {\n  border: 7px solid transparent;\n  border-bottom-color: #fff;\n  left: auto;\n  right: 10px;\n  top: -14px;\n}\n.absolute[data-v-6e7694b3]:before {\n  border: 8px solid transparent;\n  border-bottom-color: rgba(27,31,35,.15);\n  left: auto;\n  right: 9px;\n  top: -16px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\nsvg[data-v-4cd6cf84] {\n  color: #b8394c;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.home-box[data-v-dacc1fbe] {\n  color: #4d4d4f;\n  height: 300px;\n  width: 300px;\n}\n@media (max-width: 480px) {\n.home-box[data-v-dacc1fbe] {\n    width: 100%;\n}\n}\n.home-company[data-v-dacc1fbe] {\n  left: -20px;\n  bottom: -20px;\n}\n@media (max-width: 480px) {\n.home-company img[data-v-dacc1fbe] {\n    bottom: 0;\n}\n}\n.home-join[data-v-dacc1fbe] {\n  left: 14px;\n  bottom: 11px;\n}\n@media (max-width: 480px) {\n.home-join img[data-v-dacc1fbe] {\n    bottom: 0;\n}\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.find-box[data-v-10a60d4e] {\n  border: 1px solid rgba(27,31,35,.15);\n  box-shadow: 0 3px 12px rgba(27,31,35,.15);\n  top: 63px;\n  width: 500px;\n  left: 0;\n  right: 0;\n  margin: 0 auto;\n}\n.bg-modal-find[data-v-10a60d4e] {\n  position: fixed;\n  z-index: 100;\n  top: 0;\n  bottom: 0;\n  left: 0;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.3);\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/lib/css-base.js":
/*!*************************************************!*\
  !*** ./node_modules/css-loader/lib/css-base.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),

/***/ "./node_modules/is-buffer/index.js":
/*!*****************************************!*\
  !*** ./node_modules/is-buffer/index.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*!
 * Determine if an object is a Buffer
 *
 * @author   Feross Aboukhadijeh <https://feross.org>
 * @license  MIT
 */

// The _isBuffer check is for Safari 5-7 support, because it's missing
// Object.prototype.constructor. Remove this eventually
module.exports = function (obj) {
  return obj != null && (isBuffer(obj) || isSlowBuffer(obj) || !!obj._isBuffer)
}

function isBuffer (obj) {
  return !!obj.constructor && typeof obj.constructor.isBuffer === 'function' && obj.constructor.isBuffer(obj)
}

// For Node v0.10 support. Remove this eventually.
function isSlowBuffer (obj) {
  return typeof obj.readFloatLE === 'function' && typeof obj.slice === 'function' && isBuffer(obj.slice(0, 0))
}


/***/ }),

/***/ "./node_modules/process/browser.js":
/*!*****************************************!*\
  !*** ./node_modules/process/browser.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// shim for using process in browser
var process = module.exports = {};

// cached from whatever global is present so that test runners that stub it
// don't break things.  But we need to wrap it in a try catch in case it is
// wrapped in strict mode code which doesn't define any globals.  It's inside a
// function because try/catches deoptimize in certain engines.

var cachedSetTimeout;
var cachedClearTimeout;

function defaultSetTimout() {
    throw new Error('setTimeout has not been defined');
}
function defaultClearTimeout () {
    throw new Error('clearTimeout has not been defined');
}
(function () {
    try {
        if (typeof setTimeout === 'function') {
            cachedSetTimeout = setTimeout;
        } else {
            cachedSetTimeout = defaultSetTimout;
        }
    } catch (e) {
        cachedSetTimeout = defaultSetTimout;
    }
    try {
        if (typeof clearTimeout === 'function') {
            cachedClearTimeout = clearTimeout;
        } else {
            cachedClearTimeout = defaultClearTimeout;
        }
    } catch (e) {
        cachedClearTimeout = defaultClearTimeout;
    }
} ())
function runTimeout(fun) {
    if (cachedSetTimeout === setTimeout) {
        //normal enviroments in sane situations
        return setTimeout(fun, 0);
    }
    // if setTimeout wasn't available but was latter defined
    if ((cachedSetTimeout === defaultSetTimout || !cachedSetTimeout) && setTimeout) {
        cachedSetTimeout = setTimeout;
        return setTimeout(fun, 0);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedSetTimeout(fun, 0);
    } catch(e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't trust the global object when called normally
            return cachedSetTimeout.call(null, fun, 0);
        } catch(e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error
            return cachedSetTimeout.call(this, fun, 0);
        }
    }


}
function runClearTimeout(marker) {
    if (cachedClearTimeout === clearTimeout) {
        //normal enviroments in sane situations
        return clearTimeout(marker);
    }
    // if clearTimeout wasn't available but was latter defined
    if ((cachedClearTimeout === defaultClearTimeout || !cachedClearTimeout) && clearTimeout) {
        cachedClearTimeout = clearTimeout;
        return clearTimeout(marker);
    }
    try {
        // when when somebody has screwed with setTimeout but no I.E. maddness
        return cachedClearTimeout(marker);
    } catch (e){
        try {
            // When we are in I.E. but the script has been evaled so I.E. doesn't  trust the global object when called normally
            return cachedClearTimeout.call(null, marker);
        } catch (e){
            // same as above but when it's a version of I.E. that must have the global object for 'this', hopfully our context correct otherwise it will throw a global error.
            // Some versions of I.E. have different rules for clearTimeout vs setTimeout
            return cachedClearTimeout.call(this, marker);
        }
    }



}
var queue = [];
var draining = false;
var currentQueue;
var queueIndex = -1;

function cleanUpNextTick() {
    if (!draining || !currentQueue) {
        return;
    }
    draining = false;
    if (currentQueue.length) {
        queue = currentQueue.concat(queue);
    } else {
        queueIndex = -1;
    }
    if (queue.length) {
        drainQueue();
    }
}

function drainQueue() {
    if (draining) {
        return;
    }
    var timeout = runTimeout(cleanUpNextTick);
    draining = true;

    var len = queue.length;
    while(len) {
        currentQueue = queue;
        queue = [];
        while (++queueIndex < len) {
            if (currentQueue) {
                currentQueue[queueIndex].run();
            }
        }
        queueIndex = -1;
        len = queue.length;
    }
    currentQueue = null;
    draining = false;
    runClearTimeout(timeout);
}

process.nextTick = function (fun) {
    var args = new Array(arguments.length - 1);
    if (arguments.length > 1) {
        for (var i = 1; i < arguments.length; i++) {
            args[i - 1] = arguments[i];
        }
    }
    queue.push(new Item(fun, args));
    if (queue.length === 1 && !draining) {
        runTimeout(drainQueue);
    }
};

// v8 likes predictible objects
function Item(fun, array) {
    this.fun = fun;
    this.array = array;
}
Item.prototype.run = function () {
    this.fun.apply(null, this.array);
};
process.title = 'browser';
process.browser = true;
process.env = {};
process.argv = [];
process.version = ''; // empty string to avoid regexp issues
process.versions = {};

function noop() {}

process.on = noop;
process.addListener = noop;
process.once = noop;
process.off = noop;
process.removeListener = noop;
process.removeAllListeners = noop;
process.emit = noop;
process.prependListener = noop;
process.prependOnceListener = noop;

process.listeners = function (name) { return [] }

process.binding = function (name) {
    throw new Error('process.binding is not supported');
};

process.cwd = function () { return '/' };
process.chdir = function (dir) {
    throw new Error('process.chdir is not supported');
};
process.umask = function() { return 0; };


/***/ }),

/***/ "./node_modules/setimmediate/setImmediate.js":
/*!***************************************************!*\
  !*** ./node_modules/setimmediate/setImmediate.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global, process) {(function (global, undefined) {
    "use strict";

    if (global.setImmediate) {
        return;
    }

    var nextHandle = 1; // Spec says greater than zero
    var tasksByHandle = {};
    var currentlyRunningATask = false;
    var doc = global.document;
    var registerImmediate;

    function setImmediate(callback) {
      // Callback can either be a function or a string
      if (typeof callback !== "function") {
        callback = new Function("" + callback);
      }
      // Copy function arguments
      var args = new Array(arguments.length - 1);
      for (var i = 0; i < args.length; i++) {
          args[i] = arguments[i + 1];
      }
      // Store and register the task
      var task = { callback: callback, args: args };
      tasksByHandle[nextHandle] = task;
      registerImmediate(nextHandle);
      return nextHandle++;
    }

    function clearImmediate(handle) {
        delete tasksByHandle[handle];
    }

    function run(task) {
        var callback = task.callback;
        var args = task.args;
        switch (args.length) {
        case 0:
            callback();
            break;
        case 1:
            callback(args[0]);
            break;
        case 2:
            callback(args[0], args[1]);
            break;
        case 3:
            callback(args[0], args[1], args[2]);
            break;
        default:
            callback.apply(undefined, args);
            break;
        }
    }

    function runIfPresent(handle) {
        // From the spec: "Wait until any invocations of this algorithm started before this one have completed."
        // So if we're currently running a task, we'll need to delay this invocation.
        if (currentlyRunningATask) {
            // Delay by doing a setTimeout. setImmediate was tried instead, but in Firefox 7 it generated a
            // "too much recursion" error.
            setTimeout(runIfPresent, 0, handle);
        } else {
            var task = tasksByHandle[handle];
            if (task) {
                currentlyRunningATask = true;
                try {
                    run(task);
                } finally {
                    clearImmediate(handle);
                    currentlyRunningATask = false;
                }
            }
        }
    }

    function installNextTickImplementation() {
        registerImmediate = function(handle) {
            process.nextTick(function () { runIfPresent(handle); });
        };
    }

    function canUsePostMessage() {
        // The test against `importScripts` prevents this implementation from being installed inside a web worker,
        // where `global.postMessage` means something completely different and can't be used for this purpose.
        if (global.postMessage && !global.importScripts) {
            var postMessageIsAsynchronous = true;
            var oldOnMessage = global.onmessage;
            global.onmessage = function() {
                postMessageIsAsynchronous = false;
            };
            global.postMessage("", "*");
            global.onmessage = oldOnMessage;
            return postMessageIsAsynchronous;
        }
    }

    function installPostMessageImplementation() {
        // Installs an event handler on `global` for the `message` event: see
        // * https://developer.mozilla.org/en/DOM/window.postMessage
        // * http://www.whatwg.org/specs/web-apps/current-work/multipage/comms.html#crossDocumentMessages

        var messagePrefix = "setImmediate$" + Math.random() + "$";
        var onGlobalMessage = function(event) {
            if (event.source === global &&
                typeof event.data === "string" &&
                event.data.indexOf(messagePrefix) === 0) {
                runIfPresent(+event.data.slice(messagePrefix.length));
            }
        };

        if (global.addEventListener) {
            global.addEventListener("message", onGlobalMessage, false);
        } else {
            global.attachEvent("onmessage", onGlobalMessage);
        }

        registerImmediate = function(handle) {
            global.postMessage(messagePrefix + handle, "*");
        };
    }

    function installMessageChannelImplementation() {
        var channel = new MessageChannel();
        channel.port1.onmessage = function(event) {
            var handle = event.data;
            runIfPresent(handle);
        };

        registerImmediate = function(handle) {
            channel.port2.postMessage(handle);
        };
    }

    function installReadyStateChangeImplementation() {
        var html = doc.documentElement;
        registerImmediate = function(handle) {
            // Create a <script> element; its readystatechange event will be fired asynchronously once it is inserted
            // into the document. Do so, thus queuing up the task. Remember to clean up once it's been called.
            var script = doc.createElement("script");
            script.onreadystatechange = function () {
                runIfPresent(handle);
                script.onreadystatechange = null;
                html.removeChild(script);
                script = null;
            };
            html.appendChild(script);
        };
    }

    function installSetTimeoutImplementation() {
        registerImmediate = function(handle) {
            setTimeout(runIfPresent, 0, handle);
        };
    }

    // If supported, we should attach to the prototype of global, since that is where setTimeout et al. live.
    var attachTo = Object.getPrototypeOf && Object.getPrototypeOf(global);
    attachTo = attachTo && attachTo.setTimeout ? attachTo : global;

    // Don't get fooled by e.g. browserify environments.
    if ({}.toString.call(global.process) === "[object process]") {
        // For Node.js before 0.9
        installNextTickImplementation();

    } else if (canUsePostMessage()) {
        // For non-IE10 modern browsers
        installPostMessageImplementation();

    } else if (global.MessageChannel) {
        // For web workers, where supported
        installMessageChannelImplementation();

    } else if (doc && "onreadystatechange" in doc.createElement("script")) {
        // For IE 6–8
        installReadyStateChangeImplementation();

    } else {
        // For older browsers
        installSetTimeoutImplementation();
    }

    attachTo.setImmediate = setImmediate;
    attachTo.clearImmediate = clearImmediate;
}(typeof self === "undefined" ? typeof global === "undefined" ? this : global : self));

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js"), __webpack_require__(/*! ./../process/browser.js */ "./node_modules/process/browser.js")))

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/lib/addStyles.js":
/*!****************************************************!*\
  !*** ./node_modules/style-loader/lib/addStyles.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/

var stylesInDom = {};

var	memoize = function (fn) {
	var memo;

	return function () {
		if (typeof memo === "undefined") memo = fn.apply(this, arguments);
		return memo;
	};
};

var isOldIE = memoize(function () {
	// Test for IE <= 9 as proposed by Browserhacks
	// @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
	// Tests for existence of standard globals is to allow style-loader
	// to operate correctly into non-standard environments
	// @see https://github.com/webpack-contrib/style-loader/issues/177
	return window && document && document.all && !window.atob;
});

var getTarget = function (target, parent) {
  if (parent){
    return parent.querySelector(target);
  }
  return document.querySelector(target);
};

var getElement = (function (fn) {
	var memo = {};

	return function(target, parent) {
                // If passing function in options, then use it for resolve "head" element.
                // Useful for Shadow Root style i.e
                // {
                //   insertInto: function () { return document.querySelector("#foo").shadowRoot }
                // }
                if (typeof target === 'function') {
                        return target();
                }
                if (typeof memo[target] === "undefined") {
			var styleTarget = getTarget.call(this, target, parent);
			// Special case to return head of iframe instead of iframe itself
			if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
				try {
					// This will throw an exception if access to iframe is blocked
					// due to cross-origin restrictions
					styleTarget = styleTarget.contentDocument.head;
				} catch(e) {
					styleTarget = null;
				}
			}
			memo[target] = styleTarget;
		}
		return memo[target]
	};
})();

var singleton = null;
var	singletonCounter = 0;
var	stylesInsertedAtTop = [];

var	fixUrls = __webpack_require__(/*! ./urls */ "./node_modules/style-loader/lib/urls.js");

module.exports = function(list, options) {
	if (typeof DEBUG !== "undefined" && DEBUG) {
		if (typeof document !== "object") throw new Error("The style-loader cannot be used in a non-browser environment");
	}

	options = options || {};

	options.attrs = typeof options.attrs === "object" ? options.attrs : {};

	// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
	// tags it will allow on a page
	if (!options.singleton && typeof options.singleton !== "boolean") options.singleton = isOldIE();

	// By default, add <style> tags to the <head> element
        if (!options.insertInto) options.insertInto = "head";

	// By default, add <style> tags to the bottom of the target
	if (!options.insertAt) options.insertAt = "bottom";

	var styles = listToStyles(list, options);

	addStylesToDom(styles, options);

	return function update (newList) {
		var mayRemove = [];

		for (var i = 0; i < styles.length; i++) {
			var item = styles[i];
			var domStyle = stylesInDom[item.id];

			domStyle.refs--;
			mayRemove.push(domStyle);
		}

		if(newList) {
			var newStyles = listToStyles(newList, options);
			addStylesToDom(newStyles, options);
		}

		for (var i = 0; i < mayRemove.length; i++) {
			var domStyle = mayRemove[i];

			if(domStyle.refs === 0) {
				for (var j = 0; j < domStyle.parts.length; j++) domStyle.parts[j]();

				delete stylesInDom[domStyle.id];
			}
		}
	};
};

function addStylesToDom (styles, options) {
	for (var i = 0; i < styles.length; i++) {
		var item = styles[i];
		var domStyle = stylesInDom[item.id];

		if(domStyle) {
			domStyle.refs++;

			for(var j = 0; j < domStyle.parts.length; j++) {
				domStyle.parts[j](item.parts[j]);
			}

			for(; j < item.parts.length; j++) {
				domStyle.parts.push(addStyle(item.parts[j], options));
			}
		} else {
			var parts = [];

			for(var j = 0; j < item.parts.length; j++) {
				parts.push(addStyle(item.parts[j], options));
			}

			stylesInDom[item.id] = {id: item.id, refs: 1, parts: parts};
		}
	}
}

function listToStyles (list, options) {
	var styles = [];
	var newStyles = {};

	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		var id = options.base ? item[0] + options.base : item[0];
		var css = item[1];
		var media = item[2];
		var sourceMap = item[3];
		var part = {css: css, media: media, sourceMap: sourceMap};

		if(!newStyles[id]) styles.push(newStyles[id] = {id: id, parts: [part]});
		else newStyles[id].parts.push(part);
	}

	return styles;
}

function insertStyleElement (options, style) {
	var target = getElement(options.insertInto)

	if (!target) {
		throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
	}

	var lastStyleElementInsertedAtTop = stylesInsertedAtTop[stylesInsertedAtTop.length - 1];

	if (options.insertAt === "top") {
		if (!lastStyleElementInsertedAtTop) {
			target.insertBefore(style, target.firstChild);
		} else if (lastStyleElementInsertedAtTop.nextSibling) {
			target.insertBefore(style, lastStyleElementInsertedAtTop.nextSibling);
		} else {
			target.appendChild(style);
		}
		stylesInsertedAtTop.push(style);
	} else if (options.insertAt === "bottom") {
		target.appendChild(style);
	} else if (typeof options.insertAt === "object" && options.insertAt.before) {
		var nextSibling = getElement(options.insertAt.before, target);
		target.insertBefore(style, nextSibling);
	} else {
		throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
	}
}

function removeStyleElement (style) {
	if (style.parentNode === null) return false;
	style.parentNode.removeChild(style);

	var idx = stylesInsertedAtTop.indexOf(style);
	if(idx >= 0) {
		stylesInsertedAtTop.splice(idx, 1);
	}
}

function createStyleElement (options) {
	var style = document.createElement("style");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}

	if(options.attrs.nonce === undefined) {
		var nonce = getNonce();
		if (nonce) {
			options.attrs.nonce = nonce;
		}
	}

	addAttrs(style, options.attrs);
	insertStyleElement(options, style);

	return style;
}

function createLinkElement (options) {
	var link = document.createElement("link");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}
	options.attrs.rel = "stylesheet";

	addAttrs(link, options.attrs);
	insertStyleElement(options, link);

	return link;
}

function addAttrs (el, attrs) {
	Object.keys(attrs).forEach(function (key) {
		el.setAttribute(key, attrs[key]);
	});
}

function getNonce() {
	if (false) {}

	return __webpack_require__.nc;
}

function addStyle (obj, options) {
	var style, update, remove, result;

	// If a transform function was defined, run it on the css
	if (options.transform && obj.css) {
	    result = typeof options.transform === 'function'
		 ? options.transform(obj.css) 
		 : options.transform.default(obj.css);

	    if (result) {
	    	// If transform returns a value, use that instead of the original css.
	    	// This allows running runtime transformations on the css.
	    	obj.css = result;
	    } else {
	    	// If the transform function returns a falsy value, don't add this css.
	    	// This allows conditional loading of css
	    	return function() {
	    		// noop
	    	};
	    }
	}

	if (options.singleton) {
		var styleIndex = singletonCounter++;

		style = singleton || (singleton = createStyleElement(options));

		update = applyToSingletonTag.bind(null, style, styleIndex, false);
		remove = applyToSingletonTag.bind(null, style, styleIndex, true);

	} else if (
		obj.sourceMap &&
		typeof URL === "function" &&
		typeof URL.createObjectURL === "function" &&
		typeof URL.revokeObjectURL === "function" &&
		typeof Blob === "function" &&
		typeof btoa === "function"
	) {
		style = createLinkElement(options);
		update = updateLink.bind(null, style, options);
		remove = function () {
			removeStyleElement(style);

			if(style.href) URL.revokeObjectURL(style.href);
		};
	} else {
		style = createStyleElement(options);
		update = applyToTag.bind(null, style);
		remove = function () {
			removeStyleElement(style);
		};
	}

	update(obj);

	return function updateStyle (newObj) {
		if (newObj) {
			if (
				newObj.css === obj.css &&
				newObj.media === obj.media &&
				newObj.sourceMap === obj.sourceMap
			) {
				return;
			}

			update(obj = newObj);
		} else {
			remove();
		}
	};
}

var replaceText = (function () {
	var textStore = [];

	return function (index, replacement) {
		textStore[index] = replacement;

		return textStore.filter(Boolean).join('\n');
	};
})();

function applyToSingletonTag (style, index, remove, obj) {
	var css = remove ? "" : obj.css;

	if (style.styleSheet) {
		style.styleSheet.cssText = replaceText(index, css);
	} else {
		var cssNode = document.createTextNode(css);
		var childNodes = style.childNodes;

		if (childNodes[index]) style.removeChild(childNodes[index]);

		if (childNodes.length) {
			style.insertBefore(cssNode, childNodes[index]);
		} else {
			style.appendChild(cssNode);
		}
	}
}

function applyToTag (style, obj) {
	var css = obj.css;
	var media = obj.media;

	if(media) {
		style.setAttribute("media", media)
	}

	if(style.styleSheet) {
		style.styleSheet.cssText = css;
	} else {
		while(style.firstChild) {
			style.removeChild(style.firstChild);
		}

		style.appendChild(document.createTextNode(css));
	}
}

function updateLink (link, options, obj) {
	var css = obj.css;
	var sourceMap = obj.sourceMap;

	/*
		If convertToAbsoluteUrls isn't defined, but sourcemaps are enabled
		and there is no publicPath defined then lets turn convertToAbsoluteUrls
		on by default.  Otherwise default to the convertToAbsoluteUrls option
		directly
	*/
	var autoFixUrls = options.convertToAbsoluteUrls === undefined && sourceMap;

	if (options.convertToAbsoluteUrls || autoFixUrls) {
		css = fixUrls(css);
	}

	if (sourceMap) {
		// http://stackoverflow.com/a/26603875
		css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
	}

	var blob = new Blob([css], { type: "text/css" });

	var oldSrc = link.href;

	link.href = URL.createObjectURL(blob);

	if(oldSrc) URL.revokeObjectURL(oldSrc);
}


/***/ }),

/***/ "./node_modules/style-loader/lib/urls.js":
/*!***********************************************!*\
  !*** ./node_modules/style-loader/lib/urls.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {


/**
 * When source maps are enabled, `style-loader` uses a link element with a data-uri to
 * embed the css on the page. This breaks all relative urls because now they are relative to a
 * bundle instead of the current page.
 *
 * One solution is to only use full urls, but that may be impossible.
 *
 * Instead, this function "fixes" the relative urls to be absolute according to the current page location.
 *
 * A rudimentary test suite is located at `test/fixUrls.js` and can be run via the `npm test` command.
 *
 */

module.exports = function (css) {
  // get current location
  var location = typeof window !== "undefined" && window.location;

  if (!location) {
    throw new Error("fixUrls requires window.location");
  }

	// blank or null?
	if (!css || typeof css !== "string") {
	  return css;
  }

  var baseUrl = location.protocol + "//" + location.host;
  var currentDir = baseUrl + location.pathname.replace(/\/[^\/]*$/, "/");

	// convert each url(...)
	/*
	This regular expression is just a way to recursively match brackets within
	a string.

	 /url\s*\(  = Match on the word "url" with any whitespace after it and then a parens
	   (  = Start a capturing group
	     (?:  = Start a non-capturing group
	         [^)(]  = Match anything that isn't a parentheses
	         |  = OR
	         \(  = Match a start parentheses
	             (?:  = Start another non-capturing groups
	                 [^)(]+  = Match anything that isn't a parentheses
	                 |  = OR
	                 \(  = Match a start parentheses
	                     [^)(]*  = Match anything that isn't a parentheses
	                 \)  = Match a end parentheses
	             )  = End Group
              *\) = Match anything and then a close parens
          )  = Close non-capturing group
          *  = Match anything
       )  = Close capturing group
	 \)  = Match a close parens

	 /gi  = Get all matches, not the first.  Be case insensitive.
	 */
	var fixedCss = css.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function(fullMatch, origUrl) {
		// strip quotes (if they exist)
		var unquotedOrigUrl = origUrl
			.trim()
			.replace(/^"(.*)"$/, function(o, $1){ return $1; })
			.replace(/^'(.*)'$/, function(o, $1){ return $1; });

		// already a full url? no change
		if (/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(unquotedOrigUrl)) {
		  return fullMatch;
		}

		// convert the url to a full url
		var newUrl;

		if (unquotedOrigUrl.indexOf("//") === 0) {
		  	//TODO: should we add protocol?
			newUrl = unquotedOrigUrl;
		} else if (unquotedOrigUrl.indexOf("/") === 0) {
			// path should be relative to the base url
			newUrl = baseUrl + unquotedOrigUrl; // already starts with '/'
		} else {
			// path should be relative to current directory
			newUrl = currentDir + unquotedOrigUrl.replace(/^\.\//, ""); // Strip leading './'
		}

		// send back the fixed url(...)
		return "url(" + JSON.stringify(newUrl) + ")";
	});

	// send back the fixed css
	return fixedCss;
};


/***/ }),

/***/ "./node_modules/timers-browserify/main.js":
/*!************************************************!*\
  !*** ./node_modules/timers-browserify/main.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var scope = (typeof global !== "undefined" && global) ||
            (typeof self !== "undefined" && self) ||
            window;
var apply = Function.prototype.apply;

// DOM APIs, for completeness

exports.setTimeout = function() {
  return new Timeout(apply.call(setTimeout, scope, arguments), clearTimeout);
};
exports.setInterval = function() {
  return new Timeout(apply.call(setInterval, scope, arguments), clearInterval);
};
exports.clearTimeout =
exports.clearInterval = function(timeout) {
  if (timeout) {
    timeout.close();
  }
};

function Timeout(id, clearFn) {
  this._id = id;
  this._clearFn = clearFn;
}
Timeout.prototype.unref = Timeout.prototype.ref = function() {};
Timeout.prototype.close = function() {
  this._clearFn.call(scope, this._id);
};

// Does not start the time, just sets up the members needed.
exports.enroll = function(item, msecs) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = msecs;
};

exports.unenroll = function(item) {
  clearTimeout(item._idleTimeoutId);
  item._idleTimeout = -1;
};

exports._unrefActive = exports.active = function(item) {
  clearTimeout(item._idleTimeoutId);

  var msecs = item._idleTimeout;
  if (msecs >= 0) {
    item._idleTimeoutId = setTimeout(function onTimeout() {
      if (item._onTimeout)
        item._onTimeout();
    }, msecs);
  }
};

// setimmediate attaches itself to the global object
__webpack_require__(/*! setimmediate */ "./node_modules/setimmediate/setImmediate.js");
// On some exotic environments, it's not clear which object `setimmediate` was
// able to install onto.  Search each possibility in the same order as the
// `setimmediate` library.
exports.setImmediate = (typeof self !== "undefined" && self.setImmediate) ||
                       (typeof global !== "undefined" && global.setImmediate) ||
                       (this && this.setImmediate);
exports.clearImmediate = (typeof self !== "undefined" && self.clearImmediate) ||
                         (typeof global !== "undefined" && global.clearImmediate) ||
                         (this && this.clearImmediate);

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/turbolinks/dist/turbolinks.js":
/*!****************************************************!*\
  !*** ./node_modules/turbolinks/dist/turbolinks.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*
Turbolinks 5.2.0
Copyright © 2018 Basecamp, LLC
 */
(function(){var t=this;(function(){(function(){this.Turbolinks={supported:function(){return null!=window.history.pushState&&null!=window.requestAnimationFrame&&null!=window.addEventListener}(),visit:function(t,r){return e.controller.visit(t,r)},clearCache:function(){return e.controller.clearCache()},setProgressBarDelay:function(t){return e.controller.setProgressBarDelay(t)}}}).call(this)}).call(t);var e=t.Turbolinks;(function(){(function(){var t,r,n,o=[].slice;e.copyObject=function(t){var e,r,n;r={};for(e in t)n=t[e],r[e]=n;return r},e.closest=function(e,r){return t.call(e,r)},t=function(){var t,e;return t=document.documentElement,null!=(e=t.closest)?e:function(t){var e;for(e=this;e;){if(e.nodeType===Node.ELEMENT_NODE&&r.call(e,t))return e;e=e.parentNode}}}(),e.defer=function(t){return setTimeout(t,1)},e.throttle=function(t){var e;return e=null,function(){var r;return r=1<=arguments.length?o.call(arguments,0):[],null!=e?e:e=requestAnimationFrame(function(n){return function(){return e=null,t.apply(n,r)}}(this))}},e.dispatch=function(t,e){var r,o,i,s,a,u;return a=null!=e?e:{},u=a.target,r=a.cancelable,o=a.data,i=document.createEvent("Events"),i.initEvent(t,!0,r===!0),i.data=null!=o?o:{},i.cancelable&&!n&&(s=i.preventDefault,i.preventDefault=function(){return this.defaultPrevented||Object.defineProperty(this,"defaultPrevented",{get:function(){return!0}}),s.call(this)}),(null!=u?u:document).dispatchEvent(i),i},n=function(){var t;return t=document.createEvent("Events"),t.initEvent("test",!0,!0),t.preventDefault(),t.defaultPrevented}(),e.match=function(t,e){return r.call(t,e)},r=function(){var t,e,r,n;return t=document.documentElement,null!=(e=null!=(r=null!=(n=t.matchesSelector)?n:t.webkitMatchesSelector)?r:t.msMatchesSelector)?e:t.mozMatchesSelector}(),e.uuid=function(){var t,e,r;for(r="",t=e=1;36>=e;t=++e)r+=9===t||14===t||19===t||24===t?"-":15===t?"4":20===t?(Math.floor(4*Math.random())+8).toString(16):Math.floor(15*Math.random()).toString(16);return r}}).call(this),function(){e.Location=function(){function t(t){var e,r;null==t&&(t=""),r=document.createElement("a"),r.href=t.toString(),this.absoluteURL=r.href,e=r.hash.length,2>e?this.requestURL=this.absoluteURL:(this.requestURL=this.absoluteURL.slice(0,-e),this.anchor=r.hash.slice(1))}var e,r,n,o;return t.wrap=function(t){return t instanceof this?t:new this(t)},t.prototype.getOrigin=function(){return this.absoluteURL.split("/",3).join("/")},t.prototype.getPath=function(){var t,e;return null!=(t=null!=(e=this.requestURL.match(/\/\/[^\/]*(\/[^?;]*)/))?e[1]:void 0)?t:"/"},t.prototype.getPathComponents=function(){return this.getPath().split("/").slice(1)},t.prototype.getLastPathComponent=function(){return this.getPathComponents().slice(-1)[0]},t.prototype.getExtension=function(){var t,e;return null!=(t=null!=(e=this.getLastPathComponent().match(/\.[^.]*$/))?e[0]:void 0)?t:""},t.prototype.isHTML=function(){return this.getExtension().match(/^(?:|\.(?:htm|html|xhtml))$/)},t.prototype.isPrefixedBy=function(t){var e;return e=r(t),this.isEqualTo(t)||o(this.absoluteURL,e)},t.prototype.isEqualTo=function(t){return this.absoluteURL===(null!=t?t.absoluteURL:void 0)},t.prototype.toCacheKey=function(){return this.requestURL},t.prototype.toJSON=function(){return this.absoluteURL},t.prototype.toString=function(){return this.absoluteURL},t.prototype.valueOf=function(){return this.absoluteURL},r=function(t){return e(t.getOrigin()+t.getPath())},e=function(t){return n(t,"/")?t:t+"/"},o=function(t,e){return t.slice(0,e.length)===e},n=function(t,e){return t.slice(-e.length)===e},t}()}.call(this),function(){var t=function(t,e){return function(){return t.apply(e,arguments)}};e.HttpRequest=function(){function r(r,n,o){this.delegate=r,this.requestCanceled=t(this.requestCanceled,this),this.requestTimedOut=t(this.requestTimedOut,this),this.requestFailed=t(this.requestFailed,this),this.requestLoaded=t(this.requestLoaded,this),this.requestProgressed=t(this.requestProgressed,this),this.url=e.Location.wrap(n).requestURL,this.referrer=e.Location.wrap(o).absoluteURL,this.createXHR()}return r.NETWORK_FAILURE=0,r.TIMEOUT_FAILURE=-1,r.timeout=60,r.prototype.send=function(){var t;return this.xhr&&!this.sent?(this.notifyApplicationBeforeRequestStart(),this.setProgress(0),this.xhr.send(),this.sent=!0,"function"==typeof(t=this.delegate).requestStarted?t.requestStarted():void 0):void 0},r.prototype.cancel=function(){return this.xhr&&this.sent?this.xhr.abort():void 0},r.prototype.requestProgressed=function(t){return t.lengthComputable?this.setProgress(t.loaded/t.total):void 0},r.prototype.requestLoaded=function(){return this.endRequest(function(t){return function(){var e;return 200<=(e=t.xhr.status)&&300>e?t.delegate.requestCompletedWithResponse(t.xhr.responseText,t.xhr.getResponseHeader("Turbolinks-Location")):(t.failed=!0,t.delegate.requestFailedWithStatusCode(t.xhr.status,t.xhr.responseText))}}(this))},r.prototype.requestFailed=function(){return this.endRequest(function(t){return function(){return t.failed=!0,t.delegate.requestFailedWithStatusCode(t.constructor.NETWORK_FAILURE)}}(this))},r.prototype.requestTimedOut=function(){return this.endRequest(function(t){return function(){return t.failed=!0,t.delegate.requestFailedWithStatusCode(t.constructor.TIMEOUT_FAILURE)}}(this))},r.prototype.requestCanceled=function(){return this.endRequest()},r.prototype.notifyApplicationBeforeRequestStart=function(){return e.dispatch("turbolinks:request-start",{data:{url:this.url,xhr:this.xhr}})},r.prototype.notifyApplicationAfterRequestEnd=function(){return e.dispatch("turbolinks:request-end",{data:{url:this.url,xhr:this.xhr}})},r.prototype.createXHR=function(){return this.xhr=new XMLHttpRequest,this.xhr.open("GET",this.url,!0),this.xhr.timeout=1e3*this.constructor.timeout,this.xhr.setRequestHeader("Accept","text/html, application/xhtml+xml"),this.xhr.setRequestHeader("Turbolinks-Referrer",this.referrer),this.xhr.onprogress=this.requestProgressed,this.xhr.onload=this.requestLoaded,this.xhr.onerror=this.requestFailed,this.xhr.ontimeout=this.requestTimedOut,this.xhr.onabort=this.requestCanceled},r.prototype.endRequest=function(t){return this.xhr?(this.notifyApplicationAfterRequestEnd(),null!=t&&t.call(this),this.destroy()):void 0},r.prototype.setProgress=function(t){var e;return this.progress=t,"function"==typeof(e=this.delegate).requestProgressed?e.requestProgressed(this.progress):void 0},r.prototype.destroy=function(){var t;return this.setProgress(1),"function"==typeof(t=this.delegate).requestFinished&&t.requestFinished(),this.delegate=null,this.xhr=null},r}()}.call(this),function(){var t=function(t,e){return function(){return t.apply(e,arguments)}};e.ProgressBar=function(){function e(){this.trickle=t(this.trickle,this),this.stylesheetElement=this.createStylesheetElement(),this.progressElement=this.createProgressElement()}var r;return r=300,e.defaultCSS=".turbolinks-progress-bar {\n  position: fixed;\n  display: block;\n  top: 0;\n  left: 0;\n  height: 3px;\n  background: #0076ff;\n  z-index: 9999;\n  transition: width "+r+"ms ease-out, opacity "+r/2+"ms "+r/2+"ms ease-in;\n  transform: translate3d(0, 0, 0);\n}",e.prototype.show=function(){return this.visible?void 0:(this.visible=!0,this.installStylesheetElement(),this.installProgressElement(),this.startTrickling())},e.prototype.hide=function(){return this.visible&&!this.hiding?(this.hiding=!0,this.fadeProgressElement(function(t){return function(){return t.uninstallProgressElement(),t.stopTrickling(),t.visible=!1,t.hiding=!1}}(this))):void 0},e.prototype.setValue=function(t){return this.value=t,this.refresh()},e.prototype.installStylesheetElement=function(){return document.head.insertBefore(this.stylesheetElement,document.head.firstChild)},e.prototype.installProgressElement=function(){return this.progressElement.style.width=0,this.progressElement.style.opacity=1,document.documentElement.insertBefore(this.progressElement,document.body),this.refresh()},e.prototype.fadeProgressElement=function(t){return this.progressElement.style.opacity=0,setTimeout(t,1.5*r)},e.prototype.uninstallProgressElement=function(){return this.progressElement.parentNode?document.documentElement.removeChild(this.progressElement):void 0},e.prototype.startTrickling=function(){return null!=this.trickleInterval?this.trickleInterval:this.trickleInterval=setInterval(this.trickle,r)},e.prototype.stopTrickling=function(){return clearInterval(this.trickleInterval),this.trickleInterval=null},e.prototype.trickle=function(){return this.setValue(this.value+Math.random()/100)},e.prototype.refresh=function(){return requestAnimationFrame(function(t){return function(){return t.progressElement.style.width=10+90*t.value+"%"}}(this))},e.prototype.createStylesheetElement=function(){var t;return t=document.createElement("style"),t.type="text/css",t.textContent=this.constructor.defaultCSS,t},e.prototype.createProgressElement=function(){var t;return t=document.createElement("div"),t.className="turbolinks-progress-bar",t},e}()}.call(this),function(){var t=function(t,e){return function(){return t.apply(e,arguments)}};e.BrowserAdapter=function(){function r(r){this.controller=r,this.showProgressBar=t(this.showProgressBar,this),this.progressBar=new e.ProgressBar}var n,o,i;return i=e.HttpRequest,n=i.NETWORK_FAILURE,o=i.TIMEOUT_FAILURE,r.prototype.visitProposedToLocationWithAction=function(t,e){return this.controller.startVisitToLocationWithAction(t,e)},r.prototype.visitStarted=function(t){return t.issueRequest(),t.changeHistory(),t.loadCachedSnapshot()},r.prototype.visitRequestStarted=function(t){return this.progressBar.setValue(0),t.hasCachedSnapshot()||"restore"!==t.action?this.showProgressBarAfterDelay():this.showProgressBar()},r.prototype.visitRequestProgressed=function(t){return this.progressBar.setValue(t.progress)},r.prototype.visitRequestCompleted=function(t){return t.loadResponse()},r.prototype.visitRequestFailedWithStatusCode=function(t,e){switch(e){case n:case o:return this.reload();default:return t.loadResponse()}},r.prototype.visitRequestFinished=function(t){return this.hideProgressBar()},r.prototype.visitCompleted=function(t){return t.followRedirect()},r.prototype.pageInvalidated=function(){return this.reload()},r.prototype.showProgressBarAfterDelay=function(){return this.progressBarTimeout=setTimeout(this.showProgressBar,this.controller.progressBarDelay)},r.prototype.showProgressBar=function(){return this.progressBar.show()},r.prototype.hideProgressBar=function(){return this.progressBar.hide(),clearTimeout(this.progressBarTimeout)},r.prototype.reload=function(){return window.location.reload()},r}()}.call(this),function(){var t=function(t,e){return function(){return t.apply(e,arguments)}};e.History=function(){function r(e){this.delegate=e,this.onPageLoad=t(this.onPageLoad,this),this.onPopState=t(this.onPopState,this)}return r.prototype.start=function(){return this.started?void 0:(addEventListener("popstate",this.onPopState,!1),addEventListener("load",this.onPageLoad,!1),this.started=!0)},r.prototype.stop=function(){return this.started?(removeEventListener("popstate",this.onPopState,!1),removeEventListener("load",this.onPageLoad,!1),this.started=!1):void 0},r.prototype.push=function(t,r){return t=e.Location.wrap(t),this.update("push",t,r)},r.prototype.replace=function(t,r){return t=e.Location.wrap(t),this.update("replace",t,r)},r.prototype.onPopState=function(t){var r,n,o,i;return this.shouldHandlePopState()&&(i=null!=(n=t.state)?n.turbolinks:void 0)?(r=e.Location.wrap(window.location),o=i.restorationIdentifier,this.delegate.historyPoppedToLocationWithRestorationIdentifier(r,o)):void 0},r.prototype.onPageLoad=function(t){return e.defer(function(t){return function(){return t.pageLoaded=!0}}(this))},r.prototype.shouldHandlePopState=function(){return this.pageIsLoaded()},r.prototype.pageIsLoaded=function(){return this.pageLoaded||"complete"===document.readyState},r.prototype.update=function(t,e,r){var n;return n={turbolinks:{restorationIdentifier:r}},history[t+"State"](n,null,e)},r}()}.call(this),function(){e.HeadDetails=function(){function t(t){var e,r,n,s,a,u;for(this.elements={},n=0,a=t.length;a>n;n++)u=t[n],u.nodeType===Node.ELEMENT_NODE&&(s=u.outerHTML,r=null!=(e=this.elements)[s]?e[s]:e[s]={type:i(u),tracked:o(u),elements:[]},r.elements.push(u))}var e,r,n,o,i;return t.fromHeadElement=function(t){var e;return new this(null!=(e=null!=t?t.childNodes:void 0)?e:[])},t.prototype.hasElementWithKey=function(t){return t in this.elements},t.prototype.getTrackedElementSignature=function(){var t,e;return function(){var r,n;r=this.elements,n=[];for(t in r)e=r[t].tracked,e&&n.push(t);return n}.call(this).join("")},t.prototype.getScriptElementsNotInDetails=function(t){return this.getElementsMatchingTypeNotInDetails("script",t)},t.prototype.getStylesheetElementsNotInDetails=function(t){return this.getElementsMatchingTypeNotInDetails("stylesheet",t)},t.prototype.getElementsMatchingTypeNotInDetails=function(t,e){var r,n,o,i,s,a;o=this.elements,s=[];for(n in o)i=o[n],a=i.type,r=i.elements,a!==t||e.hasElementWithKey(n)||s.push(r[0]);return s},t.prototype.getProvisionalElements=function(){var t,e,r,n,o,i,s;r=[],n=this.elements;for(e in n)o=n[e],s=o.type,i=o.tracked,t=o.elements,null!=s||i?t.length>1&&r.push.apply(r,t.slice(1)):r.push.apply(r,t);return r},t.prototype.getMetaValue=function(t){var e;return null!=(e=this.findMetaElementByName(t))?e.getAttribute("content"):void 0},t.prototype.findMetaElementByName=function(t){var r,n,o,i;r=void 0,i=this.elements;for(o in i)n=i[o].elements,e(n[0],t)&&(r=n[0]);return r},i=function(t){return r(t)?"script":n(t)?"stylesheet":void 0},o=function(t){return"reload"===t.getAttribute("data-turbolinks-track")},r=function(t){var e;return e=t.tagName.toLowerCase(),"script"===e},n=function(t){var e;return e=t.tagName.toLowerCase(),"style"===e||"link"===e&&"stylesheet"===t.getAttribute("rel")},e=function(t,e){var r;return r=t.tagName.toLowerCase(),"meta"===r&&t.getAttribute("name")===e},t}()}.call(this),function(){e.Snapshot=function(){function t(t,e){this.headDetails=t,this.bodyElement=e}return t.wrap=function(t){return t instanceof this?t:"string"==typeof t?this.fromHTMLString(t):this.fromHTMLElement(t)},t.fromHTMLString=function(t){var e;return e=document.createElement("html"),e.innerHTML=t,this.fromHTMLElement(e)},t.fromHTMLElement=function(t){var r,n,o,i;return o=t.querySelector("head"),r=null!=(i=t.querySelector("body"))?i:document.createElement("body"),n=e.HeadDetails.fromHeadElement(o),new this(n,r)},t.prototype.clone=function(){return new this.constructor(this.headDetails,this.bodyElement.cloneNode(!0))},t.prototype.getRootLocation=function(){var t,r;return r=null!=(t=this.getSetting("root"))?t:"/",new e.Location(r)},t.prototype.getCacheControlValue=function(){return this.getSetting("cache-control")},t.prototype.getElementForAnchor=function(t){try{return this.bodyElement.querySelector("[id='"+t+"'], a[name='"+t+"']")}catch(e){}},t.prototype.getPermanentElements=function(){return this.bodyElement.querySelectorAll("[id][data-turbolinks-permanent]")},t.prototype.getPermanentElementById=function(t){return this.bodyElement.querySelector("#"+t+"[data-turbolinks-permanent]")},t.prototype.getPermanentElementsPresentInSnapshot=function(t){var e,r,n,o,i;for(o=this.getPermanentElements(),i=[],r=0,n=o.length;n>r;r++)e=o[r],t.getPermanentElementById(e.id)&&i.push(e);return i},t.prototype.findFirstAutofocusableElement=function(){return this.bodyElement.querySelector("[autofocus]")},t.prototype.hasAnchor=function(t){return null!=this.getElementForAnchor(t)},t.prototype.isPreviewable=function(){return"no-preview"!==this.getCacheControlValue()},t.prototype.isCacheable=function(){return"no-cache"!==this.getCacheControlValue()},t.prototype.isVisitable=function(){return"reload"!==this.getSetting("visit-control")},t.prototype.getSetting=function(t){return this.headDetails.getMetaValue("turbolinks-"+t)},t}()}.call(this),function(){var t=[].slice;e.Renderer=function(){function e(){}var r;return e.render=function(){var e,r,n,o;return n=arguments[0],r=arguments[1],e=3<=arguments.length?t.call(arguments,2):[],o=function(t,e,r){r.prototype=t.prototype;var n=new r,o=t.apply(n,e);return Object(o)===o?o:n}(this,e,function(){}),o.delegate=n,o.render(r),o},e.prototype.renderView=function(t){return this.delegate.viewWillRender(this.newBody),t(),this.delegate.viewRendered(this.newBody)},e.prototype.invalidateView=function(){return this.delegate.viewInvalidated()},e.prototype.createScriptElement=function(t){var e;return"false"===t.getAttribute("data-turbolinks-eval")?t:(e=document.createElement("script"),e.textContent=t.textContent,e.async=!1,r(e,t),e)},r=function(t,e){var r,n,o,i,s,a,u;for(i=e.attributes,a=[],r=0,n=i.length;n>r;r++)s=i[r],o=s.name,u=s.value,a.push(t.setAttribute(o,u));return a},e}()}.call(this),function(){var t,r,n=function(t,e){function r(){this.constructor=t}for(var n in e)o.call(e,n)&&(t[n]=e[n]);return r.prototype=e.prototype,t.prototype=new r,t.__super__=e.prototype,t},o={}.hasOwnProperty;e.SnapshotRenderer=function(e){function o(t,e,r){this.currentSnapshot=t,this.newSnapshot=e,this.isPreview=r,this.currentHeadDetails=this.currentSnapshot.headDetails,this.newHeadDetails=this.newSnapshot.headDetails,this.currentBody=this.currentSnapshot.bodyElement,this.newBody=this.newSnapshot.bodyElement}return n(o,e),o.prototype.render=function(t){return this.shouldRender()?(this.mergeHead(),this.renderView(function(e){return function(){return e.replaceBody(),e.isPreview||e.focusFirstAutofocusableElement(),t()}}(this))):this.invalidateView()},o.prototype.mergeHead=function(){return this.copyNewHeadStylesheetElements(),this.copyNewHeadScriptElements(),this.removeCurrentHeadProvisionalElements(),this.copyNewHeadProvisionalElements()},o.prototype.replaceBody=function(){var t;return t=this.relocateCurrentBodyPermanentElements(),this.activateNewBodyScriptElements(),this.assignNewBody(),this.replacePlaceholderElementsWithClonedPermanentElements(t)},o.prototype.shouldRender=function(){return this.newSnapshot.isVisitable()&&this.trackedElementsAreIdentical()},o.prototype.trackedElementsAreIdentical=function(){return this.currentHeadDetails.getTrackedElementSignature()===this.newHeadDetails.getTrackedElementSignature()},o.prototype.copyNewHeadStylesheetElements=function(){var t,e,r,n,o;for(n=this.getNewHeadStylesheetElements(),o=[],e=0,r=n.length;r>e;e++)t=n[e],o.push(document.head.appendChild(t));return o},o.prototype.copyNewHeadScriptElements=function(){var t,e,r,n,o;for(n=this.getNewHeadScriptElements(),o=[],e=0,r=n.length;r>e;e++)t=n[e],o.push(document.head.appendChild(this.createScriptElement(t)));return o},o.prototype.removeCurrentHeadProvisionalElements=function(){var t,e,r,n,o;for(n=this.getCurrentHeadProvisionalElements(),o=[],e=0,r=n.length;r>e;e++)t=n[e],o.push(document.head.removeChild(t));return o},o.prototype.copyNewHeadProvisionalElements=function(){var t,e,r,n,o;for(n=this.getNewHeadProvisionalElements(),o=[],e=0,r=n.length;r>e;e++)t=n[e],o.push(document.head.appendChild(t));return o},o.prototype.relocateCurrentBodyPermanentElements=function(){var e,n,o,i,s,a,u;for(a=this.getCurrentBodyPermanentElements(),u=[],e=0,n=a.length;n>e;e++)i=a[e],s=t(i),o=this.newSnapshot.getPermanentElementById(i.id),r(i,s.element),r(o,i),u.push(s);return u},o.prototype.replacePlaceholderElementsWithClonedPermanentElements=function(t){var e,n,o,i,s,a,u;for(u=[],o=0,i=t.length;i>o;o++)a=t[o],n=a.element,s=a.permanentElement,e=s.cloneNode(!0),u.push(r(n,e));return u},o.prototype.activateNewBodyScriptElements=function(){var t,e,n,o,i,s;for(i=this.getNewBodyScriptElements(),s=[],e=0,o=i.length;o>e;e++)n=i[e],t=this.createScriptElement(n),s.push(r(n,t));return s},o.prototype.assignNewBody=function(){return document.body=this.newBody},o.prototype.focusFirstAutofocusableElement=function(){var t;return null!=(t=this.newSnapshot.findFirstAutofocusableElement())?t.focus():void 0},o.prototype.getNewHeadStylesheetElements=function(){return this.newHeadDetails.getStylesheetElementsNotInDetails(this.currentHeadDetails)},o.prototype.getNewHeadScriptElements=function(){return this.newHeadDetails.getScriptElementsNotInDetails(this.currentHeadDetails)},o.prototype.getCurrentHeadProvisionalElements=function(){return this.currentHeadDetails.getProvisionalElements()},o.prototype.getNewHeadProvisionalElements=function(){return this.newHeadDetails.getProvisionalElements()},o.prototype.getCurrentBodyPermanentElements=function(){return this.currentSnapshot.getPermanentElementsPresentInSnapshot(this.newSnapshot)},o.prototype.getNewBodyScriptElements=function(){return this.newBody.querySelectorAll("script")},o}(e.Renderer),t=function(t){var e;return e=document.createElement("meta"),e.setAttribute("name","turbolinks-permanent-placeholder"),e.setAttribute("content",t.id),{element:e,permanentElement:t}},r=function(t,e){var r;return(r=t.parentNode)?r.replaceChild(e,t):void 0}}.call(this),function(){var t=function(t,e){function n(){this.constructor=t}for(var o in e)r.call(e,o)&&(t[o]=e[o]);return n.prototype=e.prototype,t.prototype=new n,t.__super__=e.prototype,t},r={}.hasOwnProperty;e.ErrorRenderer=function(e){function r(t){var e;e=document.createElement("html"),e.innerHTML=t,this.newHead=e.querySelector("head"),this.newBody=e.querySelector("body")}return t(r,e),r.prototype.render=function(t){return this.renderView(function(e){return function(){return e.replaceHeadAndBody(),e.activateBodyScriptElements(),t()}}(this))},r.prototype.replaceHeadAndBody=function(){var t,e;return e=document.head,t=document.body,e.parentNode.replaceChild(this.newHead,e),t.parentNode.replaceChild(this.newBody,t)},r.prototype.activateBodyScriptElements=function(){var t,e,r,n,o,i;for(n=this.getScriptElements(),i=[],e=0,r=n.length;r>e;e++)o=n[e],t=this.createScriptElement(o),i.push(o.parentNode.replaceChild(t,o));return i},r.prototype.getScriptElements=function(){return document.documentElement.querySelectorAll("script")},r}(e.Renderer)}.call(this),function(){e.View=function(){function t(t){this.delegate=t,this.htmlElement=document.documentElement}return t.prototype.getRootLocation=function(){return this.getSnapshot().getRootLocation()},t.prototype.getElementForAnchor=function(t){return this.getSnapshot().getElementForAnchor(t)},t.prototype.getSnapshot=function(){return e.Snapshot.fromHTMLElement(this.htmlElement)},t.prototype.render=function(t,e){var r,n,o;return o=t.snapshot,r=t.error,n=t.isPreview,this.markAsPreview(n),null!=o?this.renderSnapshot(o,n,e):this.renderError(r,e)},t.prototype.markAsPreview=function(t){return t?this.htmlElement.setAttribute("data-turbolinks-preview",""):this.htmlElement.removeAttribute("data-turbolinks-preview")},t.prototype.renderSnapshot=function(t,r,n){return e.SnapshotRenderer.render(this.delegate,n,this.getSnapshot(),e.Snapshot.wrap(t),r)},t.prototype.renderError=function(t,r){return e.ErrorRenderer.render(this.delegate,r,t)},t}()}.call(this),function(){var t=function(t,e){return function(){return t.apply(e,arguments)}};e.ScrollManager=function(){function r(r){this.delegate=r,this.onScroll=t(this.onScroll,this),this.onScroll=e.throttle(this.onScroll)}return r.prototype.start=function(){return this.started?void 0:(addEventListener("scroll",this.onScroll,!1),this.onScroll(),this.started=!0)},r.prototype.stop=function(){return this.started?(removeEventListener("scroll",this.onScroll,!1),this.started=!1):void 0},r.prototype.scrollToElement=function(t){return t.scrollIntoView()},r.prototype.scrollToPosition=function(t){var e,r;return e=t.x,r=t.y,window.scrollTo(e,r)},r.prototype.onScroll=function(t){return this.updatePosition({x:window.pageXOffset,y:window.pageYOffset})},r.prototype.updatePosition=function(t){var e;return this.position=t,null!=(e=this.delegate)?e.scrollPositionChanged(this.position):void 0},r}()}.call(this),function(){e.SnapshotCache=function(){function t(t){this.size=t,this.keys=[],this.snapshots={}}var r;return t.prototype.has=function(t){var e;return e=r(t),e in this.snapshots},t.prototype.get=function(t){var e;if(this.has(t))return e=this.read(t),this.touch(t),e},t.prototype.put=function(t,e){return this.write(t,e),this.touch(t),e},t.prototype.read=function(t){var e;return e=r(t),this.snapshots[e]},t.prototype.write=function(t,e){var n;return n=r(t),this.snapshots[n]=e},t.prototype.touch=function(t){var e,n;return n=r(t),e=this.keys.indexOf(n),e>-1&&this.keys.splice(e,1),this.keys.unshift(n),this.trim()},t.prototype.trim=function(){var t,e,r,n,o;for(n=this.keys.splice(this.size),o=[],t=0,r=n.length;r>t;t++)e=n[t],o.push(delete this.snapshots[e]);return o},r=function(t){return e.Location.wrap(t).toCacheKey()},t}()}.call(this),function(){var t=function(t,e){return function(){return t.apply(e,arguments)}};e.Visit=function(){function r(r,n,o){this.controller=r,this.action=o,this.performScroll=t(this.performScroll,this),this.identifier=e.uuid(),this.location=e.Location.wrap(n),this.adapter=this.controller.adapter,this.state="initialized",this.timingMetrics={}}var n;return r.prototype.start=function(){return"initialized"===this.state?(this.recordTimingMetric("visitStart"),this.state="started",this.adapter.visitStarted(this)):void 0},r.prototype.cancel=function(){var t;return"started"===this.state?(null!=(t=this.request)&&t.cancel(),this.cancelRender(),this.state="canceled"):void 0},r.prototype.complete=function(){var t;return"started"===this.state?(this.recordTimingMetric("visitEnd"),this.state="completed","function"==typeof(t=this.adapter).visitCompleted&&t.visitCompleted(this),this.controller.visitCompleted(this)):void 0},r.prototype.fail=function(){var t;return"started"===this.state?(this.state="failed","function"==typeof(t=this.adapter).visitFailed?t.visitFailed(this):void 0):void 0},r.prototype.changeHistory=function(){var t,e;return this.historyChanged?void 0:(t=this.location.isEqualTo(this.referrer)?"replace":this.action,e=n(t),this.controller[e](this.location,this.restorationIdentifier),this.historyChanged=!0)},r.prototype.issueRequest=function(){return this.shouldIssueRequest()&&null==this.request?(this.progress=0,this.request=new e.HttpRequest(this,this.location,this.referrer),this.request.send()):void 0},r.prototype.getCachedSnapshot=function(){var t;return!(t=this.controller.getCachedSnapshotForLocation(this.location))||null!=this.location.anchor&&!t.hasAnchor(this.location.anchor)||"restore"!==this.action&&!t.isPreviewable()?void 0:t},r.prototype.hasCachedSnapshot=function(){return null!=this.getCachedSnapshot()},r.prototype.loadCachedSnapshot=function(){var t,e;return(e=this.getCachedSnapshot())?(t=this.shouldIssueRequest(),this.render(function(){var r;return this.cacheSnapshot(),this.controller.render({snapshot:e,isPreview:t},this.performScroll),"function"==typeof(r=this.adapter).visitRendered&&r.visitRendered(this),t?void 0:this.complete()})):void 0},r.prototype.loadResponse=function(){return null!=this.response?this.render(function(){var t,e;return this.cacheSnapshot(),this.request.failed?(this.controller.render({error:this.response},this.performScroll),"function"==typeof(t=this.adapter).visitRendered&&t.visitRendered(this),this.fail()):(this.controller.render({snapshot:this.response},this.performScroll),"function"==typeof(e=this.adapter).visitRendered&&e.visitRendered(this),this.complete())}):void 0},r.prototype.followRedirect=function(){return this.redirectedToLocation&&!this.followedRedirect?(this.location=this.redirectedToLocation,this.controller.replaceHistoryWithLocationAndRestorationIdentifier(this.redirectedToLocation,this.restorationIdentifier),this.followedRedirect=!0):void 0},r.prototype.requestStarted=function(){var t;return this.recordTimingMetric("requestStart"),"function"==typeof(t=this.adapter).visitRequestStarted?t.visitRequestStarted(this):void 0},r.prototype.requestProgressed=function(t){var e;return this.progress=t,"function"==typeof(e=this.adapter).visitRequestProgressed?e.visitRequestProgressed(this):void 0},r.prototype.requestCompletedWithResponse=function(t,r){return this.response=t,null!=r&&(this.redirectedToLocation=e.Location.wrap(r)),this.adapter.visitRequestCompleted(this)},r.prototype.requestFailedWithStatusCode=function(t,e){return this.response=e,this.adapter.visitRequestFailedWithStatusCode(this,t)},r.prototype.requestFinished=function(){var t;return this.recordTimingMetric("requestEnd"),"function"==typeof(t=this.adapter).visitRequestFinished?t.visitRequestFinished(this):void 0},r.prototype.performScroll=function(){return this.scrolled?void 0:("restore"===this.action?this.scrollToRestoredPosition()||this.scrollToTop():this.scrollToAnchor()||this.scrollToTop(),this.scrolled=!0)},r.prototype.scrollToRestoredPosition=function(){var t,e;return t=null!=(e=this.restorationData)?e.scrollPosition:void 0,null!=t?(this.controller.scrollToPosition(t),!0):void 0},r.prototype.scrollToAnchor=function(){return null!=this.location.anchor?(this.controller.scrollToAnchor(this.location.anchor),!0):void 0},r.prototype.scrollToTop=function(){return this.controller.scrollToPosition({x:0,y:0})},r.prototype.recordTimingMetric=function(t){var e;return null!=(e=this.timingMetrics)[t]?e[t]:e[t]=(new Date).getTime()},r.prototype.getTimingMetrics=function(){return e.copyObject(this.timingMetrics)},n=function(t){switch(t){case"replace":return"replaceHistoryWithLocationAndRestorationIdentifier";case"advance":case"restore":return"pushHistoryWithLocationAndRestorationIdentifier"}},r.prototype.shouldIssueRequest=function(){return"restore"===this.action?!this.hasCachedSnapshot():!0},r.prototype.cacheSnapshot=function(){return this.snapshotCached?void 0:(this.controller.cacheSnapshot(),this.snapshotCached=!0)},r.prototype.render=function(t){return this.cancelRender(),this.frame=requestAnimationFrame(function(e){return function(){return e.frame=null,t.call(e)}}(this))},r.prototype.cancelRender=function(){return this.frame?cancelAnimationFrame(this.frame):void 0},r}()}.call(this),function(){var t=function(t,e){return function(){return t.apply(e,arguments)}};e.Controller=function(){function r(){this.clickBubbled=t(this.clickBubbled,this),this.clickCaptured=t(this.clickCaptured,this),this.pageLoaded=t(this.pageLoaded,this),this.history=new e.History(this),this.view=new e.View(this),this.scrollManager=new e.ScrollManager(this),this.restorationData={},this.clearCache(),this.setProgressBarDelay(500)}return r.prototype.start=function(){return e.supported&&!this.started?(addEventListener("click",this.clickCaptured,!0),addEventListener("DOMContentLoaded",this.pageLoaded,!1),this.scrollManager.start(),this.startHistory(),this.started=!0,this.enabled=!0):void 0},r.prototype.disable=function(){return this.enabled=!1},r.prototype.stop=function(){return this.started?(removeEventListener("click",this.clickCaptured,!0),removeEventListener("DOMContentLoaded",this.pageLoaded,!1),this.scrollManager.stop(),this.stopHistory(),this.started=!1):void 0},r.prototype.clearCache=function(){return this.cache=new e.SnapshotCache(10)},r.prototype.visit=function(t,r){var n,o;return null==r&&(r={}),t=e.Location.wrap(t),this.applicationAllowsVisitingLocation(t)?this.locationIsVisitable(t)?(n=null!=(o=r.action)?o:"advance",this.adapter.visitProposedToLocationWithAction(t,n)):window.location=t:void 0},r.prototype.startVisitToLocationWithAction=function(t,r,n){var o;return e.supported?(o=this.getRestorationDataForIdentifier(n),this.startVisit(t,r,{restorationData:o})):window.location=t},r.prototype.setProgressBarDelay=function(t){return this.progressBarDelay=t},r.prototype.startHistory=function(){return this.location=e.Location.wrap(window.location),this.restorationIdentifier=e.uuid(),this.history.start(),this.history.replace(this.location,this.restorationIdentifier)},r.prototype.stopHistory=function(){return this.history.stop()},r.prototype.pushHistoryWithLocationAndRestorationIdentifier=function(t,r){return this.restorationIdentifier=r,this.location=e.Location.wrap(t),this.history.push(this.location,this.restorationIdentifier)},r.prototype.replaceHistoryWithLocationAndRestorationIdentifier=function(t,r){return this.restorationIdentifier=r,this.location=e.Location.wrap(t),this.history.replace(this.location,this.restorationIdentifier)},r.prototype.historyPoppedToLocationWithRestorationIdentifier=function(t,r){var n;return this.restorationIdentifier=r,this.enabled?(n=this.getRestorationDataForIdentifier(this.restorationIdentifier),this.startVisit(t,"restore",{restorationIdentifier:this.restorationIdentifier,restorationData:n,historyChanged:!0}),this.location=e.Location.wrap(t)):this.adapter.pageInvalidated()},r.prototype.getCachedSnapshotForLocation=function(t){var e;return null!=(e=this.cache.get(t))?e.clone():void 0},r.prototype.shouldCacheSnapshot=function(){return this.view.getSnapshot().isCacheable();
},r.prototype.cacheSnapshot=function(){var t,r;return this.shouldCacheSnapshot()?(this.notifyApplicationBeforeCachingSnapshot(),r=this.view.getSnapshot(),t=this.lastRenderedLocation,e.defer(function(e){return function(){return e.cache.put(t,r.clone())}}(this))):void 0},r.prototype.scrollToAnchor=function(t){var e;return(e=this.view.getElementForAnchor(t))?this.scrollToElement(e):this.scrollToPosition({x:0,y:0})},r.prototype.scrollToElement=function(t){return this.scrollManager.scrollToElement(t)},r.prototype.scrollToPosition=function(t){return this.scrollManager.scrollToPosition(t)},r.prototype.scrollPositionChanged=function(t){var e;return e=this.getCurrentRestorationData(),e.scrollPosition=t},r.prototype.render=function(t,e){return this.view.render(t,e)},r.prototype.viewInvalidated=function(){return this.adapter.pageInvalidated()},r.prototype.viewWillRender=function(t){return this.notifyApplicationBeforeRender(t)},r.prototype.viewRendered=function(){return this.lastRenderedLocation=this.currentVisit.location,this.notifyApplicationAfterRender()},r.prototype.pageLoaded=function(){return this.lastRenderedLocation=this.location,this.notifyApplicationAfterPageLoad()},r.prototype.clickCaptured=function(){return removeEventListener("click",this.clickBubbled,!1),addEventListener("click",this.clickBubbled,!1)},r.prototype.clickBubbled=function(t){var e,r,n;return this.enabled&&this.clickEventIsSignificant(t)&&(r=this.getVisitableLinkForNode(t.target))&&(n=this.getVisitableLocationForLink(r))&&this.applicationAllowsFollowingLinkToLocation(r,n)?(t.preventDefault(),e=this.getActionForLink(r),this.visit(n,{action:e})):void 0},r.prototype.applicationAllowsFollowingLinkToLocation=function(t,e){var r;return r=this.notifyApplicationAfterClickingLinkToLocation(t,e),!r.defaultPrevented},r.prototype.applicationAllowsVisitingLocation=function(t){var e;return e=this.notifyApplicationBeforeVisitingLocation(t),!e.defaultPrevented},r.prototype.notifyApplicationAfterClickingLinkToLocation=function(t,r){return e.dispatch("turbolinks:click",{target:t,data:{url:r.absoluteURL},cancelable:!0})},r.prototype.notifyApplicationBeforeVisitingLocation=function(t){return e.dispatch("turbolinks:before-visit",{data:{url:t.absoluteURL},cancelable:!0})},r.prototype.notifyApplicationAfterVisitingLocation=function(t){return e.dispatch("turbolinks:visit",{data:{url:t.absoluteURL}})},r.prototype.notifyApplicationBeforeCachingSnapshot=function(){return e.dispatch("turbolinks:before-cache")},r.prototype.notifyApplicationBeforeRender=function(t){return e.dispatch("turbolinks:before-render",{data:{newBody:t}})},r.prototype.notifyApplicationAfterRender=function(){return e.dispatch("turbolinks:render")},r.prototype.notifyApplicationAfterPageLoad=function(t){return null==t&&(t={}),e.dispatch("turbolinks:load",{data:{url:this.location.absoluteURL,timing:t}})},r.prototype.startVisit=function(t,e,r){var n;return null!=(n=this.currentVisit)&&n.cancel(),this.currentVisit=this.createVisit(t,e,r),this.currentVisit.start(),this.notifyApplicationAfterVisitingLocation(t)},r.prototype.createVisit=function(t,r,n){var o,i,s,a,u;return i=null!=n?n:{},a=i.restorationIdentifier,s=i.restorationData,o=i.historyChanged,u=new e.Visit(this,t,r),u.restorationIdentifier=null!=a?a:e.uuid(),u.restorationData=e.copyObject(s),u.historyChanged=o,u.referrer=this.location,u},r.prototype.visitCompleted=function(t){return this.notifyApplicationAfterPageLoad(t.getTimingMetrics())},r.prototype.clickEventIsSignificant=function(t){return!(t.defaultPrevented||t.target.isContentEditable||t.which>1||t.altKey||t.ctrlKey||t.metaKey||t.shiftKey)},r.prototype.getVisitableLinkForNode=function(t){return this.nodeIsVisitable(t)?e.closest(t,"a[href]:not([target]):not([download])"):void 0},r.prototype.getVisitableLocationForLink=function(t){var r;return r=new e.Location(t.getAttribute("href")),this.locationIsVisitable(r)?r:void 0},r.prototype.getActionForLink=function(t){var e;return null!=(e=t.getAttribute("data-turbolinks-action"))?e:"advance"},r.prototype.nodeIsVisitable=function(t){var r;return(r=e.closest(t,"[data-turbolinks]"))?"false"!==r.getAttribute("data-turbolinks"):!0},r.prototype.locationIsVisitable=function(t){return t.isPrefixedBy(this.view.getRootLocation())&&t.isHTML()},r.prototype.getCurrentRestorationData=function(){return this.getRestorationDataForIdentifier(this.restorationIdentifier)},r.prototype.getRestorationDataForIdentifier=function(t){var e;return null!=(e=this.restorationData)[t]?e[t]:e[t]={}},r}()}.call(this),function(){!function(){var t,e;if((t=e=document.currentScript)&&!e.hasAttribute("data-turbolinks-suppress-warning"))for(;t=t.parentNode;)if(t===document.body)return console.warn("You are loading Turbolinks from a <script> element inside the <body> element. This is probably not what you meant to do!\n\nLoad your application\u2019s JavaScript bundle inside the <head> element instead. <script> elements in <body> are evaluated with each page change.\n\nFor more information, see: https://github.com/turbolinks/turbolinks#working-with-script-elements\n\n\u2014\u2014\nSuppress this warning by adding a `data-turbolinks-suppress-warning` attribute to: %s",e.outerHTML)}()}.call(this),function(){var t,r,n;e.start=function(){return r()?(null==e.controller&&(e.controller=t()),e.controller.start()):void 0},r=function(){return null==window.Turbolinks&&(window.Turbolinks=e),n()},t=function(){var t;return t=new e.Controller,t.adapter=new e.BrowserAdapter(t),t},n=function(){return window.Turbolinks===e},n()&&e.start()}.call(this)}).call(this), true&&module.exports?module.exports=e: true&&!(__WEBPACK_AMD_DEFINE_FACTORY__ = (e),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__))}).call(this);

/***/ }),

/***/ "./node_modules/vue-click-outside/index.js":
/*!*************************************************!*\
  !*** ./node_modules/vue-click-outside/index.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function validate(binding) {
  if (typeof binding.value !== 'function') {
    console.warn('[Vue-click-outside:] provided expression', binding.expression, 'is not a function.')
    return false
  }

  return true
}

function isPopup(popupItem, elements) {
  if (!popupItem || !elements)
    return false

  for (var i = 0, len = elements.length; i < len; i++) {
    try {
      if (popupItem.contains(elements[i])) {
        return true
      }
      if (elements[i].contains(popupItem)) {
        return false
      }
    } catch(e) {
      return false
    }
  }

  return false
}

function isServer(vNode) {
  return typeof vNode.componentInstance !== 'undefined' && vNode.componentInstance.$isServer
}

exports = module.exports = {
  bind: function (el, binding, vNode) {
    if (!validate(binding)) return

    // Define Handler and cache it on the element
    function handler(e) {
      if (!vNode.context) return

      // some components may have related popup item, on which we shall prevent the click outside event handler.
      var elements = e.path || (e.composedPath && e.composedPath())
      elements && elements.length > 0 && elements.unshift(e.target)
      
      if (el.contains(e.target) || isPopup(vNode.context.popupItem, elements)) return

      el.__vueClickOutside__.callback(e)
    }

    // add Event Listeners
    el.__vueClickOutside__ = {
      handler: handler,
      callback: binding.value
    }
    !isServer(vNode) && document.addEventListener('click', handler)
  },

  update: function (el, binding) {
    if (validate(binding)) el.__vueClickOutside__.callback = binding.value
  },
  
  unbind: function (el, binding, vNode) {
    // Remove Event Listeners
    !isServer(vNode) && document.removeEventListener('click', el.__vueClickOutside__.handler)
    delete el.__vueClickOutside__
  }
}


/***/ }),

/***/ "./node_modules/vue-i18n/dist/vue-i18n.esm.js":
/*!****************************************************!*\
  !*** ./node_modules/vue-i18n/dist/vue-i18n.esm.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/*!
 * vue-i18n v8.10.0 
 * (c) 2019 kazuya kawaguchi
 * Released under the MIT License.
 */
/*  */

/**
 * constants
 */

var numberFormatKeys = [
  'style',
  'currency',
  'currencyDisplay',
  'useGrouping',
  'minimumIntegerDigits',
  'minimumFractionDigits',
  'maximumFractionDigits',
  'minimumSignificantDigits',
  'maximumSignificantDigits',
  'localeMatcher',
  'formatMatcher'
];

/**
 * utilities
 */

function warn (msg, err) {
  if (typeof console !== 'undefined') {
    console.warn('[vue-i18n] ' + msg);
    /* istanbul ignore if */
    if (err) {
      console.warn(err.stack);
    }
  }
}

function isObject (obj) {
  return obj !== null && typeof obj === 'object'
}

var toString = Object.prototype.toString;
var OBJECT_STRING = '[object Object]';
function isPlainObject (obj) {
  return toString.call(obj) === OBJECT_STRING
}

function isNull (val) {
  return val === null || val === undefined
}

function parseArgs () {
  var args = [], len = arguments.length;
  while ( len-- ) args[ len ] = arguments[ len ];

  var locale = null;
  var params = null;
  if (args.length === 1) {
    if (isObject(args[0]) || Array.isArray(args[0])) {
      params = args[0];
    } else if (typeof args[0] === 'string') {
      locale = args[0];
    }
  } else if (args.length === 2) {
    if (typeof args[0] === 'string') {
      locale = args[0];
    }
    /* istanbul ignore if */
    if (isObject(args[1]) || Array.isArray(args[1])) {
      params = args[1];
    }
  }

  return { locale: locale, params: params }
}

function looseClone (obj) {
  return JSON.parse(JSON.stringify(obj))
}

function remove (arr, item) {
  if (arr.length) {
    var index = arr.indexOf(item);
    if (index > -1) {
      return arr.splice(index, 1)
    }
  }
}

var hasOwnProperty = Object.prototype.hasOwnProperty;
function hasOwn (obj, key) {
  return hasOwnProperty.call(obj, key)
}

function merge (target) {
  var arguments$1 = arguments;

  var output = Object(target);
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments$1[i];
    if (source !== undefined && source !== null) {
      var key = (void 0);
      for (key in source) {
        if (hasOwn(source, key)) {
          if (isObject(source[key])) {
            output[key] = merge(output[key], source[key]);
          } else {
            output[key] = source[key];
          }
        }
      }
    }
  }
  return output
}

function looseEqual (a, b) {
  if (a === b) { return true }
  var isObjectA = isObject(a);
  var isObjectB = isObject(b);
  if (isObjectA && isObjectB) {
    try {
      var isArrayA = Array.isArray(a);
      var isArrayB = Array.isArray(b);
      if (isArrayA && isArrayB) {
        return a.length === b.length && a.every(function (e, i) {
          return looseEqual(e, b[i])
        })
      } else if (!isArrayA && !isArrayB) {
        var keysA = Object.keys(a);
        var keysB = Object.keys(b);
        return keysA.length === keysB.length && keysA.every(function (key) {
          return looseEqual(a[key], b[key])
        })
      } else {
        /* istanbul ignore next */
        return false
      }
    } catch (e) {
      /* istanbul ignore next */
      return false
    }
  } else if (!isObjectA && !isObjectB) {
    return String(a) === String(b)
  } else {
    return false
  }
}

/*  */

function extend (Vue) {
  if (!Vue.prototype.hasOwnProperty('$i18n')) {
    // $FlowFixMe
    Object.defineProperty(Vue.prototype, '$i18n', {
      get: function get () { return this._i18n }
    });
  }

  Vue.prototype.$t = function (key) {
    var values = [], len = arguments.length - 1;
    while ( len-- > 0 ) values[ len ] = arguments[ len + 1 ];

    var i18n = this.$i18n;
    return i18n._t.apply(i18n, [ key, i18n.locale, i18n._getMessages(), this ].concat( values ))
  };

  Vue.prototype.$tc = function (key, choice) {
    var values = [], len = arguments.length - 2;
    while ( len-- > 0 ) values[ len ] = arguments[ len + 2 ];

    var i18n = this.$i18n;
    return i18n._tc.apply(i18n, [ key, i18n.locale, i18n._getMessages(), this, choice ].concat( values ))
  };

  Vue.prototype.$te = function (key, locale) {
    var i18n = this.$i18n;
    return i18n._te(key, i18n.locale, i18n._getMessages(), locale)
  };

  Vue.prototype.$d = function (value) {
    var ref;

    var args = [], len = arguments.length - 1;
    while ( len-- > 0 ) args[ len ] = arguments[ len + 1 ];
    return (ref = this.$i18n).d.apply(ref, [ value ].concat( args ))
  };

  Vue.prototype.$n = function (value) {
    var ref;

    var args = [], len = arguments.length - 1;
    while ( len-- > 0 ) args[ len ] = arguments[ len + 1 ];
    return (ref = this.$i18n).n.apply(ref, [ value ].concat( args ))
  };
}

/*  */

var mixin = {
  beforeCreate: function beforeCreate () {
    var options = this.$options;
    options.i18n = options.i18n || (options.__i18n ? {} : null);

    if (options.i18n) {
      if (options.i18n instanceof VueI18n) {
        // init locale messages via custom blocks
        if (options.__i18n) {
          try {
            var localeMessages = {};
            options.__i18n.forEach(function (resource) {
              localeMessages = merge(localeMessages, JSON.parse(resource));
            });
            Object.keys(localeMessages).forEach(function (locale) {
              options.i18n.mergeLocaleMessage(locale, localeMessages[locale]);
            });
          } catch (e) {
            if (true) {
              warn("Cannot parse locale messages via custom blocks.", e);
            }
          }
        }
        this._i18n = options.i18n;
        this._i18nWatcher = this._i18n.watchI18nData();
        this._i18n.subscribeDataChanging(this);
        this._subscribing = true;
      } else if (isPlainObject(options.i18n)) {
        // component local i18n
        if (this.$root && this.$root.$i18n && this.$root.$i18n instanceof VueI18n) {
          options.i18n.root = this.$root;
          options.i18n.formatter = this.$root.$i18n.formatter;
          options.i18n.fallbackLocale = this.$root.$i18n.fallbackLocale;
          options.i18n.silentTranslationWarn = this.$root.$i18n.silentTranslationWarn;
          options.i18n.silentFallbackWarn = this.$root.$i18n.silentFallbackWarn;
          options.i18n.pluralizationRules = this.$root.$i18n.pluralizationRules;
          options.i18n.preserveDirectiveContent = this.$root.$i18n.preserveDirectiveContent;
        }

        // init locale messages via custom blocks
        if (options.__i18n) {
          try {
            var localeMessages$1 = {};
            options.__i18n.forEach(function (resource) {
              localeMessages$1 = merge(localeMessages$1, JSON.parse(resource));
            });
            options.i18n.messages = localeMessages$1;
          } catch (e) {
            if (true) {
              warn("Cannot parse locale messages via custom blocks.", e);
            }
          }
        }

        this._i18n = new VueI18n(options.i18n);
        this._i18nWatcher = this._i18n.watchI18nData();
        this._i18n.subscribeDataChanging(this);
        this._subscribing = true;

        if (options.i18n.sync === undefined || !!options.i18n.sync) {
          this._localeWatcher = this.$i18n.watchLocale();
        }
      } else {
        if (true) {
          warn("Cannot be interpreted 'i18n' option.");
        }
      }
    } else if (this.$root && this.$root.$i18n && this.$root.$i18n instanceof VueI18n) {
      // root i18n
      this._i18n = this.$root.$i18n;
      this._i18n.subscribeDataChanging(this);
      this._subscribing = true;
    } else if (options.parent && options.parent.$i18n && options.parent.$i18n instanceof VueI18n) {
      // parent i18n
      this._i18n = options.parent.$i18n;
      this._i18n.subscribeDataChanging(this);
      this._subscribing = true;
    }
  },

  beforeDestroy: function beforeDestroy () {
    if (!this._i18n) { return }

    var self = this;
    this.$nextTick(function () {
      if (self._subscribing) {
        self._i18n.unsubscribeDataChanging(self);
        delete self._subscribing;
      }

      if (self._i18nWatcher) {
        self._i18nWatcher();
        self._i18n.destroyVM();
        delete self._i18nWatcher;
      }

      if (self._localeWatcher) {
        self._localeWatcher();
        delete self._localeWatcher;
      }

      self._i18n = null;
    });
  }
};

/*  */

var interpolationComponent = {
  name: 'i18n',
  functional: true,
  props: {
    tag: {
      type: String,
      default: 'span'
    },
    path: {
      type: String,
      required: true
    },
    locale: {
      type: String
    },
    places: {
      type: [Array, Object]
    }
  },
  render: function render (h, ref) {
    var props = ref.props;
    var data = ref.data;
    var children = ref.children;
    var parent = ref.parent;

    var i18n = parent.$i18n;

    children = (children || []).filter(function (child) {
      return child.tag || (child.text = child.text.trim())
    });

    if (!i18n) {
      if (true) {
        warn('Cannot find VueI18n instance!');
      }
      return children
    }

    var path = props.path;
    var locale = props.locale;

    var params = {};
    var places = props.places || {};

    var hasPlaces = Array.isArray(places)
      ? places.length > 0
      : Object.keys(places).length > 0;

    var everyPlace = children.every(function (child) {
      if (child.data && child.data.attrs) {
        var place = child.data.attrs.place;
        return (typeof place !== 'undefined') && place !== ''
      }
    });

    if ( true && hasPlaces && children.length > 0 && !everyPlace) {
      warn('If places prop is set, all child elements must have place prop set.');
    }

    if (Array.isArray(places)) {
      places.forEach(function (el, i) {
        params[i] = el;
      });
    } else {
      Object.keys(places).forEach(function (key) {
        params[key] = places[key];
      });
    }

    children.forEach(function (child, i) {
      var key = everyPlace
        ? ("" + (child.data.attrs.place))
        : ("" + i);
      params[key] = child;
    });

    return h(props.tag, data, i18n.i(path, locale, params))
  }
};

/*  */

var numberComponent = {
  name: 'i18n-n',
  functional: true,
  props: {
    tag: {
      type: String,
      default: 'span'
    },
    value: {
      type: Number,
      required: true
    },
    format: {
      type: [String, Object]
    },
    locale: {
      type: String
    }
  },
  render: function render (h, ref) {
    var props = ref.props;
    var parent = ref.parent;
    var data = ref.data;

    var i18n = parent.$i18n;

    if (!i18n) {
      if (true) {
        warn('Cannot find VueI18n instance!');
      }
      return null
    }

    var key = null;
    var options = null;

    if (typeof props.format === 'string') {
      key = props.format;
    } else if (isObject(props.format)) {
      if (props.format.key) {
        key = props.format.key;
      }

      // Filter out number format options only
      options = Object.keys(props.format).reduce(function (acc, prop) {
        var obj;

        if (numberFormatKeys.includes(prop)) {
          return Object.assign({}, acc, ( obj = {}, obj[prop] = props.format[prop], obj ))
        }
        return acc
      }, null);
    }

    var locale = props.locale || i18n.locale;
    var parts = i18n._ntp(props.value, locale, key, options);

    var values = parts.map(function (part, index) {
      var obj;

      var slot = data.scopedSlots && data.scopedSlots[part.type];
      return slot ? slot(( obj = {}, obj[part.type] = part.value, obj.index = index, obj.parts = parts, obj )) : part.value
    });

    return h(props.tag, {
      attrs: data.attrs,
      'class': data['class'],
      staticClass: data.staticClass
    }, values)
  }
};

/*  */

function bind (el, binding, vnode) {
  if (!assert(el, vnode)) { return }

  t(el, binding, vnode);
}

function update (el, binding, vnode, oldVNode) {
  if (!assert(el, vnode)) { return }

  var i18n = vnode.context.$i18n;
  if (localeEqual(el, vnode) &&
    (looseEqual(binding.value, binding.oldValue) &&
     looseEqual(el._localeMessage, i18n.getLocaleMessage(i18n.locale)))) { return }

  t(el, binding, vnode);
}

function unbind (el, binding, vnode, oldVNode) {
  var vm = vnode.context;
  if (!vm) {
    warn('Vue instance does not exists in VNode context');
    return
  }

  var i18n = vnode.context.$i18n || {};
  if (!binding.modifiers.preserve && !i18n.preserveDirectiveContent) {
    el.textContent = '';
  }
  el._vt = undefined;
  delete el['_vt'];
  el._locale = undefined;
  delete el['_locale'];
  el._localeMessage = undefined;
  delete el['_localeMessage'];
}

function assert (el, vnode) {
  var vm = vnode.context;
  if (!vm) {
    warn('Vue instance does not exists in VNode context');
    return false
  }

  if (!vm.$i18n) {
    warn('VueI18n instance does not exists in Vue instance');
    return false
  }

  return true
}

function localeEqual (el, vnode) {
  var vm = vnode.context;
  return el._locale === vm.$i18n.locale
}

function t (el, binding, vnode) {
  var ref$1, ref$2;

  var value = binding.value;

  var ref = parseValue(value);
  var path = ref.path;
  var locale = ref.locale;
  var args = ref.args;
  var choice = ref.choice;
  if (!path && !locale && !args) {
    warn('value type not supported');
    return
  }

  if (!path) {
    warn('`path` is required in v-t directive');
    return
  }

  var vm = vnode.context;
  if (choice) {
    el._vt = el.textContent = (ref$1 = vm.$i18n).tc.apply(ref$1, [ path, choice ].concat( makeParams(locale, args) ));
  } else {
    el._vt = el.textContent = (ref$2 = vm.$i18n).t.apply(ref$2, [ path ].concat( makeParams(locale, args) ));
  }
  el._locale = vm.$i18n.locale;
  el._localeMessage = vm.$i18n.getLocaleMessage(vm.$i18n.locale);
}

function parseValue (value) {
  var path;
  var locale;
  var args;
  var choice;

  if (typeof value === 'string') {
    path = value;
  } else if (isPlainObject(value)) {
    path = value.path;
    locale = value.locale;
    args = value.args;
    choice = value.choice;
  }

  return { path: path, locale: locale, args: args, choice: choice }
}

function makeParams (locale, args) {
  var params = [];

  locale && params.push(locale);
  if (args && (Array.isArray(args) || isPlainObject(args))) {
    params.push(args);
  }

  return params
}

var Vue;

function install (_Vue) {
  /* istanbul ignore if */
  if ( true && install.installed && _Vue === Vue) {
    warn('already installed.');
    return
  }
  install.installed = true;

  Vue = _Vue;

  var version = (Vue.version && Number(Vue.version.split('.')[0])) || -1;
  /* istanbul ignore if */
  if ( true && version < 2) {
    warn(("vue-i18n (" + (install.version) + ") need to use Vue 2.0 or later (Vue: " + (Vue.version) + ")."));
    return
  }

  extend(Vue);
  Vue.mixin(mixin);
  Vue.directive('t', { bind: bind, update: update, unbind: unbind });
  Vue.component(interpolationComponent.name, interpolationComponent);
  Vue.component(numberComponent.name, numberComponent);

  // use simple mergeStrategies to prevent i18n instance lose '__proto__'
  var strats = Vue.config.optionMergeStrategies;
  strats.i18n = function (parentVal, childVal) {
    return childVal === undefined
      ? parentVal
      : childVal
  };
}

/*  */

var BaseFormatter = function BaseFormatter () {
  this._caches = Object.create(null);
};

BaseFormatter.prototype.interpolate = function interpolate (message, values) {
  if (!values) {
    return [message]
  }
  var tokens = this._caches[message];
  if (!tokens) {
    tokens = parse(message);
    this._caches[message] = tokens;
  }
  return compile(tokens, values)
};



var RE_TOKEN_LIST_VALUE = /^(?:\d)+/;
var RE_TOKEN_NAMED_VALUE = /^(?:\w)+/;

function parse (format) {
  var tokens = [];
  var position = 0;

  var text = '';
  while (position < format.length) {
    var char = format[position++];
    if (char === '{') {
      if (text) {
        tokens.push({ type: 'text', value: text });
      }

      text = '';
      var sub = '';
      char = format[position++];
      while (char !== undefined && char !== '}') {
        sub += char;
        char = format[position++];
      }
      var isClosed = char === '}';

      var type = RE_TOKEN_LIST_VALUE.test(sub)
        ? 'list'
        : isClosed && RE_TOKEN_NAMED_VALUE.test(sub)
          ? 'named'
          : 'unknown';
      tokens.push({ value: sub, type: type });
    } else if (char === '%') {
      // when found rails i18n syntax, skip text capture
      if (format[(position)] !== '{') {
        text += char;
      }
    } else {
      text += char;
    }
  }

  text && tokens.push({ type: 'text', value: text });

  return tokens
}

function compile (tokens, values) {
  var compiled = [];
  var index = 0;

  var mode = Array.isArray(values)
    ? 'list'
    : isObject(values)
      ? 'named'
      : 'unknown';
  if (mode === 'unknown') { return compiled }

  while (index < tokens.length) {
    var token = tokens[index];
    switch (token.type) {
      case 'text':
        compiled.push(token.value);
        break
      case 'list':
        compiled.push(values[parseInt(token.value, 10)]);
        break
      case 'named':
        if (mode === 'named') {
          compiled.push((values)[token.value]);
        } else {
          if (true) {
            warn(("Type of token '" + (token.type) + "' and format of value '" + mode + "' don't match!"));
          }
        }
        break
      case 'unknown':
        if (true) {
          warn("Detect 'unknown' type of token!");
        }
        break
    }
    index++;
  }

  return compiled
}

/*  */

/**
 *  Path parser
 *  - Inspired:
 *    Vue.js Path parser
 */

// actions
var APPEND = 0;
var PUSH = 1;
var INC_SUB_PATH_DEPTH = 2;
var PUSH_SUB_PATH = 3;

// states
var BEFORE_PATH = 0;
var IN_PATH = 1;
var BEFORE_IDENT = 2;
var IN_IDENT = 3;
var IN_SUB_PATH = 4;
var IN_SINGLE_QUOTE = 5;
var IN_DOUBLE_QUOTE = 6;
var AFTER_PATH = 7;
var ERROR = 8;

var pathStateMachine = [];

pathStateMachine[BEFORE_PATH] = {
  'ws': [BEFORE_PATH],
  'ident': [IN_IDENT, APPEND],
  '[': [IN_SUB_PATH],
  'eof': [AFTER_PATH]
};

pathStateMachine[IN_PATH] = {
  'ws': [IN_PATH],
  '.': [BEFORE_IDENT],
  '[': [IN_SUB_PATH],
  'eof': [AFTER_PATH]
};

pathStateMachine[BEFORE_IDENT] = {
  'ws': [BEFORE_IDENT],
  'ident': [IN_IDENT, APPEND],
  '0': [IN_IDENT, APPEND],
  'number': [IN_IDENT, APPEND]
};

pathStateMachine[IN_IDENT] = {
  'ident': [IN_IDENT, APPEND],
  '0': [IN_IDENT, APPEND],
  'number': [IN_IDENT, APPEND],
  'ws': [IN_PATH, PUSH],
  '.': [BEFORE_IDENT, PUSH],
  '[': [IN_SUB_PATH, PUSH],
  'eof': [AFTER_PATH, PUSH]
};

pathStateMachine[IN_SUB_PATH] = {
  "'": [IN_SINGLE_QUOTE, APPEND],
  '"': [IN_DOUBLE_QUOTE, APPEND],
  '[': [IN_SUB_PATH, INC_SUB_PATH_DEPTH],
  ']': [IN_PATH, PUSH_SUB_PATH],
  'eof': ERROR,
  'else': [IN_SUB_PATH, APPEND]
};

pathStateMachine[IN_SINGLE_QUOTE] = {
  "'": [IN_SUB_PATH, APPEND],
  'eof': ERROR,
  'else': [IN_SINGLE_QUOTE, APPEND]
};

pathStateMachine[IN_DOUBLE_QUOTE] = {
  '"': [IN_SUB_PATH, APPEND],
  'eof': ERROR,
  'else': [IN_DOUBLE_QUOTE, APPEND]
};

/**
 * Check if an expression is a literal value.
 */

var literalValueRE = /^\s?(?:true|false|-?[\d.]+|'[^']*'|"[^"]*")\s?$/;
function isLiteral (exp) {
  return literalValueRE.test(exp)
}

/**
 * Strip quotes from a string
 */

function stripQuotes (str) {
  var a = str.charCodeAt(0);
  var b = str.charCodeAt(str.length - 1);
  return a === b && (a === 0x22 || a === 0x27)
    ? str.slice(1, -1)
    : str
}

/**
 * Determine the type of a character in a keypath.
 */

function getPathCharType (ch) {
  if (ch === undefined || ch === null) { return 'eof' }

  var code = ch.charCodeAt(0);

  switch (code) {
    case 0x5B: // [
    case 0x5D: // ]
    case 0x2E: // .
    case 0x22: // "
    case 0x27: // '
      return ch

    case 0x5F: // _
    case 0x24: // $
    case 0x2D: // -
      return 'ident'

    case 0x09: // Tab
    case 0x0A: // Newline
    case 0x0D: // Return
    case 0xA0:  // No-break space
    case 0xFEFF:  // Byte Order Mark
    case 0x2028:  // Line Separator
    case 0x2029:  // Paragraph Separator
      return 'ws'
  }

  return 'ident'
}

/**
 * Format a subPath, return its plain form if it is
 * a literal string or number. Otherwise prepend the
 * dynamic indicator (*).
 */

function formatSubPath (path) {
  var trimmed = path.trim();
  // invalid leading 0
  if (path.charAt(0) === '0' && isNaN(path)) { return false }

  return isLiteral(trimmed) ? stripQuotes(trimmed) : '*' + trimmed
}

/**
 * Parse a string path into an array of segments
 */

function parse$1 (path) {
  var keys = [];
  var index = -1;
  var mode = BEFORE_PATH;
  var subPathDepth = 0;
  var c;
  var key;
  var newChar;
  var type;
  var transition;
  var action;
  var typeMap;
  var actions = [];

  actions[PUSH] = function () {
    if (key !== undefined) {
      keys.push(key);
      key = undefined;
    }
  };

  actions[APPEND] = function () {
    if (key === undefined) {
      key = newChar;
    } else {
      key += newChar;
    }
  };

  actions[INC_SUB_PATH_DEPTH] = function () {
    actions[APPEND]();
    subPathDepth++;
  };

  actions[PUSH_SUB_PATH] = function () {
    if (subPathDepth > 0) {
      subPathDepth--;
      mode = IN_SUB_PATH;
      actions[APPEND]();
    } else {
      subPathDepth = 0;
      key = formatSubPath(key);
      if (key === false) {
        return false
      } else {
        actions[PUSH]();
      }
    }
  };

  function maybeUnescapeQuote () {
    var nextChar = path[index + 1];
    if ((mode === IN_SINGLE_QUOTE && nextChar === "'") ||
      (mode === IN_DOUBLE_QUOTE && nextChar === '"')) {
      index++;
      newChar = '\\' + nextChar;
      actions[APPEND]();
      return true
    }
  }

  while (mode !== null) {
    index++;
    c = path[index];

    if (c === '\\' && maybeUnescapeQuote()) {
      continue
    }

    type = getPathCharType(c);
    typeMap = pathStateMachine[mode];
    transition = typeMap[type] || typeMap['else'] || ERROR;

    if (transition === ERROR) {
      return // parse error
    }

    mode = transition[0];
    action = actions[transition[1]];
    if (action) {
      newChar = transition[2];
      newChar = newChar === undefined
        ? c
        : newChar;
      if (action() === false) {
        return
      }
    }

    if (mode === AFTER_PATH) {
      return keys
    }
  }
}





var I18nPath = function I18nPath () {
  this._cache = Object.create(null);
};

/**
 * External parse that check for a cache hit first
 */
I18nPath.prototype.parsePath = function parsePath (path) {
  var hit = this._cache[path];
  if (!hit) {
    hit = parse$1(path);
    if (hit) {
      this._cache[path] = hit;
    }
  }
  return hit || []
};

/**
 * Get path value from path string
 */
I18nPath.prototype.getPathValue = function getPathValue (obj, path) {
  if (!isObject(obj)) { return null }

  var paths = this.parsePath(path);
  if (paths.length === 0) {
    return null
  } else {
    var length = paths.length;
    var last = obj;
    var i = 0;
    while (i < length) {
      var value = last[paths[i]];
      if (value === undefined) {
        return null
      }
      last = value;
      i++;
    }

    return last
  }
};

/*  */



var linkKeyMatcher = /(?:@(?:\.[a-z]+)?:(?:[\w\-_|.]+|\([\w\-_|.]+\)))/g;
var linkKeyPrefixMatcher = /^@(?:\.([a-z]+))?:/;
var bracketsMatcher = /[()]/g;
var formatters = {
  'upper': function (str) { return str.toLocaleUpperCase(); },
  'lower': function (str) { return str.toLocaleLowerCase(); }
};

var defaultFormatter = new BaseFormatter();

var VueI18n = function VueI18n (options) {
  var this$1 = this;
  if ( options === void 0 ) options = {};

  // Auto install if it is not done yet and `window` has `Vue`.
  // To allow users to avoid auto-installation in some cases,
  // this code should be placed here. See #290
  /* istanbul ignore if */
  if (!Vue && typeof window !== 'undefined' && window.Vue) {
    install(window.Vue);
  }

  var locale = options.locale || 'en-US';
  var fallbackLocale = options.fallbackLocale || 'en-US';
  var messages = options.messages || {};
  var dateTimeFormats = options.dateTimeFormats || {};
  var numberFormats = options.numberFormats || {};

  this._vm = null;
  this._formatter = options.formatter || defaultFormatter;
  this._missing = options.missing || null;
  this._root = options.root || null;
  this._sync = options.sync === undefined ? true : !!options.sync;
  this._fallbackRoot = options.fallbackRoot === undefined
    ? true
    : !!options.fallbackRoot;
  this._silentTranslationWarn = options.silentTranslationWarn === undefined
    ? false
    : !!options.silentTranslationWarn;
  this._silentFallbackWarn = options.silentFallbackWarn === undefined
    ? false
    : !!options.silentFallbackWarn;
  this._dateTimeFormatters = {};
  this._numberFormatters = {};
  this._path = new I18nPath();
  this._dataListeners = [];
  this._preserveDirectiveContent = options.preserveDirectiveContent === undefined
    ? false
    : !!options.preserveDirectiveContent;
  this.pluralizationRules = options.pluralizationRules || {};

  this._exist = function (message, key) {
    if (!message || !key) { return false }
    if (!isNull(this$1._path.getPathValue(message, key))) { return true }
    // fallback for flat key
    if (message[key]) { return true }
    return false
  };

  this._initVM({
    locale: locale,
    fallbackLocale: fallbackLocale,
    messages: messages,
    dateTimeFormats: dateTimeFormats,
    numberFormats: numberFormats
  });
};

var prototypeAccessors = { vm: { configurable: true },messages: { configurable: true },dateTimeFormats: { configurable: true },numberFormats: { configurable: true },availableLocales: { configurable: true },locale: { configurable: true },fallbackLocale: { configurable: true },missing: { configurable: true },formatter: { configurable: true },silentTranslationWarn: { configurable: true },silentFallbackWarn: { configurable: true },preserveDirectiveContent: { configurable: true } };

VueI18n.prototype._initVM = function _initVM (data) {
  var silent = Vue.config.silent;
  Vue.config.silent = true;
  this._vm = new Vue({ data: data });
  Vue.config.silent = silent;
};

VueI18n.prototype.destroyVM = function destroyVM () {
  this._vm.$destroy();
};

VueI18n.prototype.subscribeDataChanging = function subscribeDataChanging (vm) {
  this._dataListeners.push(vm);
};

VueI18n.prototype.unsubscribeDataChanging = function unsubscribeDataChanging (vm) {
  remove(this._dataListeners, vm);
};

VueI18n.prototype.watchI18nData = function watchI18nData () {
  var self = this;
  return this._vm.$watch('$data', function () {
    var i = self._dataListeners.length;
    while (i--) {
      Vue.nextTick(function () {
        self._dataListeners[i] && self._dataListeners[i].$forceUpdate();
      });
    }
  }, { deep: true })
};

VueI18n.prototype.watchLocale = function watchLocale () {
  /* istanbul ignore if */
  if (!this._sync || !this._root) { return null }
  var target = this._vm;
  return this._root.$i18n.vm.$watch('locale', function (val) {
    target.$set(target, 'locale', val);
    target.$forceUpdate();
  }, { immediate: true })
};

prototypeAccessors.vm.get = function () { return this._vm };

prototypeAccessors.messages.get = function () { return looseClone(this._getMessages()) };
prototypeAccessors.dateTimeFormats.get = function () { return looseClone(this._getDateTimeFormats()) };
prototypeAccessors.numberFormats.get = function () { return looseClone(this._getNumberFormats()) };
prototypeAccessors.availableLocales.get = function () { return Object.keys(this.messages).sort() };

prototypeAccessors.locale.get = function () { return this._vm.locale };
prototypeAccessors.locale.set = function (locale) {
  this._vm.$set(this._vm, 'locale', locale);
};

prototypeAccessors.fallbackLocale.get = function () { return this._vm.fallbackLocale };
prototypeAccessors.fallbackLocale.set = function (locale) {
  this._vm.$set(this._vm, 'fallbackLocale', locale);
};

prototypeAccessors.missing.get = function () { return this._missing };
prototypeAccessors.missing.set = function (handler) { this._missing = handler; };

prototypeAccessors.formatter.get = function () { return this._formatter };
prototypeAccessors.formatter.set = function (formatter) { this._formatter = formatter; };

prototypeAccessors.silentTranslationWarn.get = function () { return this._silentTranslationWarn };
prototypeAccessors.silentTranslationWarn.set = function (silent) { this._silentTranslationWarn = silent; };

prototypeAccessors.silentFallbackWarn.get = function () { return this._silentFallbackWarn };
prototypeAccessors.silentFallbackWarn.set = function (silent) { this._silentFallbackWarn = silent; };

prototypeAccessors.preserveDirectiveContent.get = function () { return this._preserveDirectiveContent };
prototypeAccessors.preserveDirectiveContent.set = function (preserve) { this._preserveDirectiveContent = preserve; };

VueI18n.prototype._getMessages = function _getMessages () { return this._vm.messages };
VueI18n.prototype._getDateTimeFormats = function _getDateTimeFormats () { return this._vm.dateTimeFormats };
VueI18n.prototype._getNumberFormats = function _getNumberFormats () { return this._vm.numberFormats };

VueI18n.prototype._warnDefault = function _warnDefault (locale, key, result, vm, values) {
  if (!isNull(result)) { return result }
  if (this._missing) {
    var missingRet = this._missing.apply(null, [locale, key, vm, values]);
    if (typeof missingRet === 'string') {
      return missingRet
    }
  } else {
    if ( true && !this._silentTranslationWarn) {
      warn(
        "Cannot translate the value of keypath '" + key + "'. " +
        'Use the value of keypath as default.'
      );
    }
  }
  return key
};

VueI18n.prototype._isFallbackRoot = function _isFallbackRoot (val) {
  return !val && !isNull(this._root) && this._fallbackRoot
};

VueI18n.prototype._isSilentFallback = function _isSilentFallback (locale) {
  return this._silentFallbackWarn && (this._isFallbackRoot() || locale !== this.fallbackLocale)
};

VueI18n.prototype._interpolate = function _interpolate (
  locale,
  message,
  key,
  host,
  interpolateMode,
  values,
  visitedLinkStack
) {
  if (!message) { return null }

  var pathRet = this._path.getPathValue(message, key);
  if (Array.isArray(pathRet) || isPlainObject(pathRet)) { return pathRet }

  var ret;
  if (isNull(pathRet)) {
    /* istanbul ignore else */
    if (isPlainObject(message)) {
      ret = message[key];
      if (typeof ret !== 'string') {
        if ( true && !this._silentTranslationWarn && !this._isSilentFallback(locale)) {
          warn(("Value of key '" + key + "' is not a string!"));
        }
        return null
      }
    } else {
      return null
    }
  } else {
    /* istanbul ignore else */
    if (typeof pathRet === 'string') {
      ret = pathRet;
    } else {
      if ( true && !this._silentTranslationWarn && !this._isSilentFallback(locale)) {
        warn(("Value of key '" + key + "' is not a string!"));
      }
      return null
    }
  }

  // Check for the existence of links within the translated string
  if (ret.indexOf('@:') >= 0 || ret.indexOf('@.') >= 0) {
    ret = this._link(locale, message, ret, host, 'raw', values, visitedLinkStack);
  }

  return this._render(ret, interpolateMode, values, key)
};

VueI18n.prototype._link = function _link (
  locale,
  message,
  str,
  host,
  interpolateMode,
  values,
  visitedLinkStack
) {
  var ret = str;

  // Match all the links within the local
  // We are going to replace each of
  // them with its translation
  var matches = ret.match(linkKeyMatcher);
  for (var idx in matches) {
    // ie compatible: filter custom array
    // prototype method
    if (!matches.hasOwnProperty(idx)) {
      continue
    }
    var link = matches[idx];
    var linkKeyPrefixMatches = link.match(linkKeyPrefixMatcher);
    var linkPrefix = linkKeyPrefixMatches[0];
      var formatterName = linkKeyPrefixMatches[1];

    // Remove the leading @:, @.case: and the brackets
    var linkPlaceholder = link.replace(linkPrefix, '').replace(bracketsMatcher, '');

    if (visitedLinkStack.includes(linkPlaceholder)) {
      if (true) {
        warn(("Circular reference found. \"" + link + "\" is already visited in the chain of " + (visitedLinkStack.reverse().join(' <- '))));
      }
      return ret
    }
    visitedLinkStack.push(linkPlaceholder);

    // Translate the link
    var translated = this._interpolate(
      locale, message, linkPlaceholder, host,
      interpolateMode === 'raw' ? 'string' : interpolateMode,
      interpolateMode === 'raw' ? undefined : values,
      visitedLinkStack
    );

    if (this._isFallbackRoot(translated)) {
      if ( true && !this._silentTranslationWarn) {
        warn(("Fall back to translate the link placeholder '" + linkPlaceholder + "' with root locale."));
      }
      /* istanbul ignore if */
      if (!this._root) { throw Error('unexpected error') }
      var root = this._root.$i18n;
      translated = root._translate(
        root._getMessages(), root.locale, root.fallbackLocale,
        linkPlaceholder, host, interpolateMode, values
      );
    }
    translated = this._warnDefault(
      locale, linkPlaceholder, translated, host,
      Array.isArray(values) ? values : [values]
    );
    if (formatters.hasOwnProperty(formatterName)) {
      translated = formatters[formatterName](translated);
    }

    visitedLinkStack.pop();

    // Replace the link with the translated
    ret = !translated ? ret : ret.replace(link, translated);
  }

  return ret
};

VueI18n.prototype._render = function _render (message, interpolateMode, values, path) {
  var ret = this._formatter.interpolate(message, values, path);

  // If the custom formatter refuses to work - apply the default one
  if (!ret) {
    ret = defaultFormatter.interpolate(message, values, path);
  }

  // if interpolateMode is **not** 'string' ('row'),
  // return the compiled data (e.g. ['foo', VNode, 'bar']) with formatter
  return interpolateMode === 'string' ? ret.join('') : ret
};

VueI18n.prototype._translate = function _translate (
  messages,
  locale,
  fallback,
  key,
  host,
  interpolateMode,
  args
) {
  var res =
    this._interpolate(locale, messages[locale], key, host, interpolateMode, args, [key]);
  if (!isNull(res)) { return res }

  res = this._interpolate(fallback, messages[fallback], key, host, interpolateMode, args, [key]);
  if (!isNull(res)) {
    if ( true && !this._silentTranslationWarn && !this._silentFallbackWarn) {
      warn(("Fall back to translate the keypath '" + key + "' with '" + fallback + "' locale."));
    }
    return res
  } else {
    return null
  }
};

VueI18n.prototype._t = function _t (key, _locale, messages, host) {
    var ref;

    var values = [], len = arguments.length - 4;
    while ( len-- > 0 ) values[ len ] = arguments[ len + 4 ];
  if (!key) { return '' }

  var parsedArgs = parseArgs.apply(void 0, values);
  var locale = parsedArgs.locale || _locale;

  var ret = this._translate(
    messages, locale, this.fallbackLocale, key,
    host, 'string', parsedArgs.params
  );
  if (this._isFallbackRoot(ret)) {
    if ( true && !this._silentTranslationWarn && !this._silentFallbackWarn) {
      warn(("Fall back to translate the keypath '" + key + "' with root locale."));
    }
    /* istanbul ignore if */
    if (!this._root) { throw Error('unexpected error') }
    return (ref = this._root).$t.apply(ref, [ key ].concat( values ))
  } else {
    return this._warnDefault(locale, key, ret, host, values)
  }
};

VueI18n.prototype.t = function t (key) {
    var ref;

    var values = [], len = arguments.length - 1;
    while ( len-- > 0 ) values[ len ] = arguments[ len + 1 ];
  return (ref = this)._t.apply(ref, [ key, this.locale, this._getMessages(), null ].concat( values ))
};

VueI18n.prototype._i = function _i (key, locale, messages, host, values) {
  var ret =
    this._translate(messages, locale, this.fallbackLocale, key, host, 'raw', values);
  if (this._isFallbackRoot(ret)) {
    if ( true && !this._silentTranslationWarn) {
      warn(("Fall back to interpolate the keypath '" + key + "' with root locale."));
    }
    if (!this._root) { throw Error('unexpected error') }
    return this._root.$i18n.i(key, locale, values)
  } else {
    return this._warnDefault(locale, key, ret, host, [values])
  }
};

VueI18n.prototype.i = function i (key, locale, values) {
  /* istanbul ignore if */
  if (!key) { return '' }

  if (typeof locale !== 'string') {
    locale = this.locale;
  }

  return this._i(key, locale, this._getMessages(), null, values)
};

VueI18n.prototype._tc = function _tc (
  key,
  _locale,
  messages,
  host,
  choice
) {
    var ref;

    var values = [], len = arguments.length - 5;
    while ( len-- > 0 ) values[ len ] = arguments[ len + 5 ];
  if (!key) { return '' }
  if (choice === undefined) {
    choice = 1;
  }

  var predefined = { 'count': choice, 'n': choice };
  var parsedArgs = parseArgs.apply(void 0, values);
  parsedArgs.params = Object.assign(predefined, parsedArgs.params);
  values = parsedArgs.locale === null ? [parsedArgs.params] : [parsedArgs.locale, parsedArgs.params];
  return this.fetchChoice((ref = this)._t.apply(ref, [ key, _locale, messages, host ].concat( values )), choice)
};

VueI18n.prototype.fetchChoice = function fetchChoice (message, choice) {
  /* istanbul ignore if */
  if (!message && typeof message !== 'string') { return null }
  var choices = message.split('|');

  choice = this.getChoiceIndex(choice, choices.length);
  if (!choices[choice]) { return message }
  return choices[choice].trim()
};

/**
 * @param choice {number} a choice index given by the input to $tc: `$tc('path.to.rule', choiceIndex)`
 * @param choicesLength {number} an overall amount of available choices
 * @returns a final choice index
*/
VueI18n.prototype.getChoiceIndex = function getChoiceIndex (choice, choicesLength) {
  // Default (old) getChoiceIndex implementation - english-compatible
  var defaultImpl = function (_choice, _choicesLength) {
    _choice = Math.abs(_choice);

    if (_choicesLength === 2) {
      return _choice
        ? _choice > 1
          ? 1
          : 0
        : 1
    }

    return _choice ? Math.min(_choice, 2) : 0
  };

  if (this.locale in this.pluralizationRules) {
    return this.pluralizationRules[this.locale].apply(this, [choice, choicesLength])
  } else {
    return defaultImpl(choice, choicesLength)
  }
};

VueI18n.prototype.tc = function tc (key, choice) {
    var ref;

    var values = [], len = arguments.length - 2;
    while ( len-- > 0 ) values[ len ] = arguments[ len + 2 ];
  return (ref = this)._tc.apply(ref, [ key, this.locale, this._getMessages(), null, choice ].concat( values ))
};

VueI18n.prototype._te = function _te (key, locale, messages) {
    var args = [], len = arguments.length - 3;
    while ( len-- > 0 ) args[ len ] = arguments[ len + 3 ];

  var _locale = parseArgs.apply(void 0, args).locale || locale;
  return this._exist(messages[_locale], key)
};

VueI18n.prototype.te = function te (key, locale) {
  return this._te(key, this.locale, this._getMessages(), locale)
};

VueI18n.prototype.getLocaleMessage = function getLocaleMessage (locale) {
  return looseClone(this._vm.messages[locale] || {})
};

VueI18n.prototype.setLocaleMessage = function setLocaleMessage (locale, message) {
  this._vm.$set(this._vm.messages, locale, message);
};

VueI18n.prototype.mergeLocaleMessage = function mergeLocaleMessage (locale, message) {
  this._vm.$set(this._vm.messages, locale, merge(this._vm.messages[locale] || {}, message));
};

VueI18n.prototype.getDateTimeFormat = function getDateTimeFormat (locale) {
  return looseClone(this._vm.dateTimeFormats[locale] || {})
};

VueI18n.prototype.setDateTimeFormat = function setDateTimeFormat (locale, format) {
  this._vm.$set(this._vm.dateTimeFormats, locale, format);
};

VueI18n.prototype.mergeDateTimeFormat = function mergeDateTimeFormat (locale, format) {
  this._vm.$set(this._vm.dateTimeFormats, locale, merge(this._vm.dateTimeFormats[locale] || {}, format));
};

VueI18n.prototype._localizeDateTime = function _localizeDateTime (
  value,
  locale,
  fallback,
  dateTimeFormats,
  key
) {
  var _locale = locale;
  var formats = dateTimeFormats[_locale];

  // fallback locale
  if (isNull(formats) || isNull(formats[key])) {
    if ( true && !this._silentTranslationWarn) {
      warn(("Fall back to '" + fallback + "' datetime formats from '" + locale + " datetime formats."));
    }
    _locale = fallback;
    formats = dateTimeFormats[_locale];
  }

  if (isNull(formats) || isNull(formats[key])) {
    return null
  } else {
    var format = formats[key];
    var id = _locale + "__" + key;
    var formatter = this._dateTimeFormatters[id];
    if (!formatter) {
      formatter = this._dateTimeFormatters[id] = new Intl.DateTimeFormat(_locale, format);
    }
    return formatter.format(value)
  }
};

VueI18n.prototype._d = function _d (value, locale, key) {
  /* istanbul ignore if */
  if ( true && !VueI18n.availabilities.dateTimeFormat) {
    warn('Cannot format a Date value due to not supported Intl.DateTimeFormat.');
    return ''
  }

  if (!key) {
    return new Intl.DateTimeFormat(locale).format(value)
  }

  var ret =
    this._localizeDateTime(value, locale, this.fallbackLocale, this._getDateTimeFormats(), key);
  if (this._isFallbackRoot(ret)) {
    if ( true && !this._silentTranslationWarn) {
      warn(("Fall back to datetime localization of root: key '" + key + "' ."));
    }
    /* istanbul ignore if */
    if (!this._root) { throw Error('unexpected error') }
    return this._root.$i18n.d(value, key, locale)
  } else {
    return ret || ''
  }
};

VueI18n.prototype.d = function d (value) {
    var args = [], len = arguments.length - 1;
    while ( len-- > 0 ) args[ len ] = arguments[ len + 1 ];

  var locale = this.locale;
  var key = null;

  if (args.length === 1) {
    if (typeof args[0] === 'string') {
      key = args[0];
    } else if (isObject(args[0])) {
      if (args[0].locale) {
        locale = args[0].locale;
      }
      if (args[0].key) {
        key = args[0].key;
      }
    }
  } else if (args.length === 2) {
    if (typeof args[0] === 'string') {
      key = args[0];
    }
    if (typeof args[1] === 'string') {
      locale = args[1];
    }
  }

  return this._d(value, locale, key)
};

VueI18n.prototype.getNumberFormat = function getNumberFormat (locale) {
  return looseClone(this._vm.numberFormats[locale] || {})
};

VueI18n.prototype.setNumberFormat = function setNumberFormat (locale, format) {
  this._vm.$set(this._vm.numberFormats, locale, format);
};

VueI18n.prototype.mergeNumberFormat = function mergeNumberFormat (locale, format) {
  this._vm.$set(this._vm.numberFormats, locale, merge(this._vm.numberFormats[locale] || {}, format));
};

VueI18n.prototype._getNumberFormatter = function _getNumberFormatter (
  value,
  locale,
  fallback,
  numberFormats,
  key,
  options
) {
  var _locale = locale;
  var formats = numberFormats[_locale];

  // fallback locale
  if (isNull(formats) || isNull(formats[key])) {
    if ( true && !this._silentTranslationWarn) {
      warn(("Fall back to '" + fallback + "' number formats from '" + locale + " number formats."));
    }
    _locale = fallback;
    formats = numberFormats[_locale];
  }

  if (isNull(formats) || isNull(formats[key])) {
    return null
  } else {
    var format = formats[key];

    var formatter;
    if (options) {
      // If options specified - create one time number formatter
      formatter = new Intl.NumberFormat(_locale, Object.assign({}, format, options));
    } else {
      var id = _locale + "__" + key;
      formatter = this._numberFormatters[id];
      if (!formatter) {
        formatter = this._numberFormatters[id] = new Intl.NumberFormat(_locale, format);
      }
    }
    return formatter
  }
};

VueI18n.prototype._n = function _n (value, locale, key, options) {
  /* istanbul ignore if */
  if (!VueI18n.availabilities.numberFormat) {
    if (true) {
      warn('Cannot format a Number value due to not supported Intl.NumberFormat.');
    }
    return ''
  }

  if (!key) {
    var nf = !options ? new Intl.NumberFormat(locale) : new Intl.NumberFormat(locale, options);
    return nf.format(value)
  }

  var formatter = this._getNumberFormatter(value, locale, this.fallbackLocale, this._getNumberFormats(), key, options);
  var ret = formatter && formatter.format(value);
  if (this._isFallbackRoot(ret)) {
    if ( true && !this._silentTranslationWarn) {
      warn(("Fall back to number localization of root: key '" + key + "' ."));
    }
    /* istanbul ignore if */
    if (!this._root) { throw Error('unexpected error') }
    return this._root.$i18n.n(value, Object.assign({}, { key: key, locale: locale }, options))
  } else {
    return ret || ''
  }
};

VueI18n.prototype.n = function n (value) {
    var args = [], len = arguments.length - 1;
    while ( len-- > 0 ) args[ len ] = arguments[ len + 1 ];

  var locale = this.locale;
  var key = null;
  var options = null;

  if (args.length === 1) {
    if (typeof args[0] === 'string') {
      key = args[0];
    } else if (isObject(args[0])) {
      if (args[0].locale) {
        locale = args[0].locale;
      }
      if (args[0].key) {
        key = args[0].key;
      }

      // Filter out number format options only
      options = Object.keys(args[0]).reduce(function (acc, key) {
          var obj;

        if (numberFormatKeys.includes(key)) {
          return Object.assign({}, acc, ( obj = {}, obj[key] = args[0][key], obj ))
        }
        return acc
      }, null);
    }
  } else if (args.length === 2) {
    if (typeof args[0] === 'string') {
      key = args[0];
    }
    if (typeof args[1] === 'string') {
      locale = args[1];
    }
  }

  return this._n(value, locale, key, options)
};

VueI18n.prototype._ntp = function _ntp (value, locale, key, options) {
  /* istanbul ignore if */
  if (!VueI18n.availabilities.numberFormat) {
    if (true) {
      warn('Cannot format to parts a Number value due to not supported Intl.NumberFormat.');
    }
    return []
  }

  if (!key) {
    var nf = !options ? new Intl.NumberFormat(locale) : new Intl.NumberFormat(locale, options);
    return nf.formatToParts(value)
  }

  var formatter = this._getNumberFormatter(value, locale, this.fallbackLocale, this._getNumberFormats(), key, options);
  var ret = formatter && formatter.formatToParts(value);
  if (this._isFallbackRoot(ret)) {
    if ( true && !this._silentTranslationWarn) {
      warn(("Fall back to format number to parts of root: key '" + key + "' ."));
    }
    /* istanbul ignore if */
    if (!this._root) { throw Error('unexpected error') }
    return this._root.$i18n._ntp(value, locale, key, options)
  } else {
    return ret || []
  }
};

Object.defineProperties( VueI18n.prototype, prototypeAccessors );

var availabilities;
// $FlowFixMe
Object.defineProperty(VueI18n, 'availabilities', {
  get: function get () {
    if (!availabilities) {
      var intlDefined = typeof Intl !== 'undefined';
      availabilities = {
        dateTimeFormat: intlDefined && typeof Intl.DateTimeFormat !== 'undefined',
        numberFormat: intlDefined && typeof Intl.NumberFormat !== 'undefined'
      };
    }

    return availabilities
  }
});

VueI18n.install = install;
VueI18n.version = '8.10.0';

/* harmony default export */ __webpack_exports__["default"] = (VueI18n);


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Login.vue?vue&type=template&id=3d143c20&":
/*!**************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/Login.vue?vue&type=template&id=3d143c20& ***!
  \**************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "ph2 ph0-ns" }, [
    _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
      _c("div", { staticClass: "fn fl-ns w-50-ns pa3" }, [
        _vm._v("\n      Login\n    ")
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "fn fl-ns w-50-ns pa3" },
        [
          _c("errors", { attrs: { errors: _vm.form.errors } }),
          _vm._v(" "),
          _c(
            "form",
            {
              on: {
                submit: function($event) {
                  $event.preventDefault()
                  return _vm.submit($event)
                }
              }
            },
            [
              _c("div", {}, [
                _c(
                  "label",
                  { staticClass: "db fw4 lh-copy f6", attrs: { for: "email" } },
                  [_vm._v(_vm._s(_vm.$t("auth.register_email")))]
                ),
                _vm._v(" "),
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.form.email,
                      expression: "form.email"
                    }
                  ],
                  staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                  attrs: { type: "email", name: "email", required: "" },
                  domProps: { value: _vm.form.email },
                  on: {
                    input: function($event) {
                      if ($event.target.composing) {
                        return
                      }
                      _vm.$set(_vm.form, "email", $event.target.value)
                    }
                  }
                }),
                _vm._v(" "),
                _c("p", { staticClass: "f7 mb4 lh-title" }, [
                  _vm._v(
                    "\n            " +
                      _vm._s(_vm.$t("auth.register_email_help")) +
                      "\n          "
                  )
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "mb4" }, [
                _c(
                  "label",
                  {
                    staticClass: "db fw4 lh-copy f6",
                    attrs: { for: "password" }
                  },
                  [_vm._v(_vm._s(_vm.$t("auth.register_password")))]
                ),
                _vm._v(" "),
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.form.password,
                      expression: "form.password"
                    }
                  ],
                  staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                  attrs: { type: "password", name: "password", required: "" },
                  domProps: { value: _vm.form.password },
                  on: {
                    input: function($event) {
                      if ($event.target.composing) {
                        return
                      }
                      _vm.$set(_vm.form, "password", $event.target.value)
                    }
                  }
                })
              ]),
              _vm._v(" "),
              _c("div", {}, [
                _c("div", { staticClass: "flex-ns justify-between" }, [
                  _c(
                    "div",
                    [
                      _c("loading-button", {
                        attrs: {
                          classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                          state: _vm.loadingState,
                          text: _vm.$t("auth.login_cta")
                        }
                      })
                    ],
                    1
                  )
                ])
              ])
            ]
          )
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Register.vue?vue&type=template&id=d7dc2e88&":
/*!*****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/Register.vue?vue&type=template&id=d7dc2e88& ***!
  \*****************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "ph2 ph0-ns" }, [
    _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
      _c("div", { staticClass: "fn fl-ns w-50-ns pa3" }, [
        _vm._v("\n      " + _vm._s(_vm.$t("auth.register_title")) + "\n    ")
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "fn fl-ns w-50-ns pa3" },
        [
          _c("errors", { attrs: { errors: _vm.form.errors } }),
          _vm._v(" "),
          _c(
            "form",
            {
              on: {
                submit: function($event) {
                  $event.preventDefault()
                  return _vm.submit($event)
                }
              }
            },
            [
              _c("div", {}, [
                _c(
                  "label",
                  { staticClass: "db fw4 lh-copy f6", attrs: { for: "email" } },
                  [_vm._v(_vm._s(_vm.$t("auth.register_email")))]
                ),
                _vm._v(" "),
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.form.email,
                      expression: "form.email"
                    }
                  ],
                  staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                  attrs: { type: "email", name: "email", required: "" },
                  domProps: { value: _vm.form.email },
                  on: {
                    input: function($event) {
                      if ($event.target.composing) {
                        return
                      }
                      _vm.$set(_vm.form, "email", $event.target.value)
                    }
                  }
                }),
                _vm._v(" "),
                _c("p", { staticClass: "f7 mb4 lh-title" }, [
                  _vm._v(
                    "\n            " +
                      _vm._s(_vm.$t("auth.register_email_help")) +
                      "\n          "
                  )
                ])
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "mb4" }, [
                _c(
                  "label",
                  {
                    staticClass: "db fw4 lh-copy f6",
                    attrs: { for: "password" }
                  },
                  [_vm._v(_vm._s(_vm.$t("auth.register_password")))]
                ),
                _vm._v(" "),
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.form.password,
                      expression: "form.password"
                    }
                  ],
                  staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                  attrs: { type: "password", name: "password", required: "" },
                  domProps: { value: _vm.form.password },
                  on: {
                    input: function($event) {
                      if ($event.target.composing) {
                        return
                      }
                      _vm.$set(_vm.form, "password", $event.target.value)
                    }
                  }
                })
              ]),
              _vm._v(" "),
              _c("div", {}, [
                _c("div", { staticClass: "flex-ns justify-between" }, [
                  _c(
                    "div",
                    [
                      _c("loading-button", {
                        attrs: {
                          classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                          state: _vm.loadingState,
                          text: _vm.$t("auth.register_cta")
                        }
                      })
                    ],
                    1
                  )
                ])
              ])
            ]
          )
        ],
        1
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=template&id=f02ca1a4&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=template&id=f02ca1a4& ***!
  \************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c("div", { staticClass: "cf mw6 center br3 mb3 bg-white box" }, [
        _c("div", { staticClass: "pa3" }, [
          _c("p", [
            _vm._v(
              _vm._s(
                _vm.$t("auth.invitation_logged_accept_title", {
                  name: _vm.company.name
                })
              ) +
                "Would you like to join " +
                _vm._s(_vm.company.name) +
                "?"
            )
          ]),
          _vm._v(" "),
          _c(
            "form",
            {
              on: {
                submit: function($event) {
                  $event.preventDefault()
                  return _vm.submit($event)
                }
              }
            },
            [
              _c("loading-button", {
                attrs: {
                  classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                  state: _vm.loadingState,
                  text: _vm.$t("auth.invitation_logged_accept_cta")
                }
              })
            ],
            1
          )
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "ph2 ph0-ns" }, [
    _c("div", { staticClass: "cf mt3 mw6 center tc" }, [
      _c("h2", { staticClass: "lh-title" }, [
        _vm._v(
          "\n      " +
            _vm._s(
              _vm.$t("auth.invitation_unlogged_title", {
                name: _vm.company.name
              })
            ) +
            "\n    "
        )
      ]),
      _vm._v(" "),
      _c(
        "p",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: !_vm.displayCreateAccount && !_vm.displaySignin,
              expression: "!displayCreateAccount && !displaySignin"
            }
          ]
        },
        [
          _vm._v(
            "\n      " +
              _vm._s(_vm.$t("auth.invitation_unlogged_desc")) +
              "\n    "
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "p",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.displayCreateAccount,
              expression: "displayCreateAccount"
            }
          ]
        },
        [
          _c(
            "a",
            {
              staticClass: "pointer",
              on: {
                click: function($event) {
                  _vm.displaySignin = true
                  _vm.displayCreateAccount = false
                }
              }
            },
            [
              _vm._v(
                "← " +
                  _vm._s(
                    _vm.$t("auth.invitation_unlogged_create_account_instead")
                  )
              )
            ]
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "p",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.displaySignin,
              expression: "displaySignin"
            }
          ]
        },
        [
          _c(
            "a",
            {
              staticClass: "pointer",
              on: {
                click: function($event) {
                  _vm.displayCreateAccount = true
                  _vm.displaySignin = false
                }
              }
            },
            [
              _vm._v(
                "← " + _vm._s(_vm.$t("auth.invitation_unlogged_login_instead"))
              )
            ]
          )
        ]
      )
    ]),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: !_vm.displayCreateAccount && !_vm.displaySignin,
            expression: "!displayCreateAccount && !displaySignin"
          }
        ],
        staticClass: "cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer",
        on: {
          click: function($event) {
            _vm.displayCreateAccount = true
          }
        }
      },
      [
        _c("p", { staticClass: "fw5" }, [
          _vm._v(
            "\n      " +
              _vm._s(_vm.$t("auth.invitation_unlogged_choice_account_title")) +
              "\n    "
          )
        ]),
        _vm._v(" "),
        _c("p", [
          _vm._v(_vm._s(_vm.$t("auth.invitation_unlogged_choice_account_desc")))
        ])
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: !_vm.displayCreateAccount && !_vm.displaySignin,
            expression: "!displayCreateAccount && !displaySignin"
          }
        ],
        staticClass: "cf mt3 mw6 center br3 mb3 bg-white box pa3 pointer",
        on: {
          click: function($event) {
            _vm.displaySignin = true
          }
        }
      },
      [
        _c("p", { staticClass: "fw5" }, [
          _vm._v(
            "\n      " +
              _vm._s(_vm.$t("auth.invitation_unlogged_choice_login_title")) +
              "\n    "
          )
        ]),
        _vm._v(" "),
        _c("p", [
          _vm._v(_vm._s(_vm.$t("auth.invitation_unlogged_choice_login_desc")))
        ])
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.displayCreateAccount,
            expression: "displayCreateAccount"
          }
        ],
        staticClass: "cf mw6 center br3 mb3 bg-white box"
      },
      [
        _c(
          "div",
          { staticClass: "pa3" },
          [
            _c("h2", { staticClass: "tc f4" }, [
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$t("auth.invitation_unlogged_choice_account")) +
                  "\n      "
              )
            ]),
            _vm._v(" "),
            _c("errors", { attrs: { errors: _vm.form.errors } }),
            _vm._v(" "),
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.submit($event)
                  }
                }
              },
              [
                _c("div", {}, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "email" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_email")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.email,
                        expression: "form.email"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "email", name: "email", required: "" },
                    domProps: { value: _vm.form.email },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "email", $event.target.value)
                      }
                    }
                  }),
                  _vm._v(" "),
                  _c("p", { staticClass: "f7 mb4 lh-title" }, [
                    _vm._v(
                      "\n            " +
                        _vm._s(_vm.$t("auth.register_email_help")) +
                        "\n          "
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb4" }, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "password" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_password")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.password,
                        expression: "form.password"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "password", name: "password", required: "" },
                    domProps: { value: _vm.form.password },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "password", $event.target.value)
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", {}, [
                  _c("div", { staticClass: "flex-ns justify-between" }, [
                    _c(
                      "div",
                      [
                        _c("loading-button", {
                          attrs: {
                            classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                            state: _vm.loadingState,
                            text: _vm.$t("auth.register_cta")
                          }
                        })
                      ],
                      1
                    )
                  ])
                ])
              ]
            )
          ],
          1
        )
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        directives: [
          {
            name: "show",
            rawName: "v-show",
            value: _vm.displaySignin,
            expression: "displaySignin"
          }
        ],
        staticClass: "cf mw6 center br3 mb3 bg-white box"
      },
      [
        _c(
          "div",
          { staticClass: "pa3" },
          [
            _c("h2", { staticClass: "tc f4" }, [
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$t("auth.invitation_unlogged_choice_login")) +
                  "\n      "
              )
            ]),
            _vm._v(" "),
            _c("errors", { attrs: { errors: _vm.form.errors } }),
            _vm._v(" "),
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.submit($event)
                  }
                }
              },
              [
                _c("div", {}, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "email" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_email")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.email,
                        expression: "form.email"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "email", name: "email", required: "" },
                    domProps: { value: _vm.form.email },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "email", $event.target.value)
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "mb4" }, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "password" }
                    },
                    [_vm._v(_vm._s(_vm.$t("auth.register_password")))]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.password,
                        expression: "form.password"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "password", name: "password", required: "" },
                    domProps: { value: _vm.form.password },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "password", $event.target.value)
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", {}, [
                  _c("div", { staticClass: "flex-ns justify-between" }, [
                    _c(
                      "div",
                      [
                        _c("loading-button", {
                          attrs: {
                            classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                            state: _vm.loadingState,
                            text: _vm.$t("auth.login_cta")
                          }
                        })
                      ],
                      1
                    )
                  ])
                ])
              ]
            )
          ],
          1
        )
      ]
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=template&id=04a4e526&":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=template&id=04a4e526& ***!
  \*****************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "ph2 ph0-ns" }, [
    _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
      _c("div", { staticClass: "pa3 tc" }, [
        _vm._v(
          "\n      " + _vm._s(_vm.$t("auth.invitation_invalid_link")) + "\n    "
        )
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "ph2 ph0-ns" }, [
    _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
      _c("div", { staticClass: "pa3 tc" }, [
        _vm._v(
          "\n      " +
            _vm._s(_vm.$t("auth.invitation_link_already_accepted")) +
            "\n    "
        )
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************/
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
    { attrs: { title: "Home", user: _vm.user, "no-menu": false } },
    [
      _c("div", { staticClass: "ph2 ph0-ns" }, [
        _c(
          "div",
          {
            staticClass:
              "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
          },
          [
            _c("ul", { staticClass: "list ph0 tc-l tl" }, [
              _c("li", { staticClass: "di" }, [
                _c(
                  "a",
                  { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                  [_vm._v(_vm._s(_vm.company.name))]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _vm._v(
                  "\n          " +
                    _vm._s(_vm.$t("app.breadcrumb_account_home")) +
                    "\n        "
                )
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass:
              "mw7 center br3 mb3 bg-white box restricted relative z-1"
          },
          [
            _c("div", { staticClass: "pa3 mt5" }, [
              _c("h2", { staticClass: "tc normal mb4" }, [
                _vm._v(
                  "\n          " +
                    _vm._s(_vm.$t("account.home_title")) +
                    "\n        "
                )
              ]),
              _vm._v(" "),
              _c("p", {
                domProps: {
                  innerHTML: _vm._s(_vm.$t("account.home_role_administrator"))
                }
              }),
              _vm._v(" "),
              _c("ul", { staticClass: "options list pl0 mb5" }, [
                _c("li", { staticClass: "pa2 pl0 relative" }, [
                  _c("img", {
                    staticClass: "pr1 absolute",
                    attrs: { src: "/img/company/account/employees.svg" }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "relative",
                      attrs: {
                        href: "/" + _vm.company.id + "/account/employees",
                        "data-cy": "employee-admin-link"
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("account.home_manage_employees")))]
                  )
                ]),
                _vm._v(" "),
                _c("li", { staticClass: "pa2 pl0 relative" }, [
                  _c("img", {
                    staticClass: "pr1 absolute",
                    attrs: { src: "/img/company/account/position.svg" }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "relative",
                      attrs: {
                        href: "/" + _vm.company.id + "/account/positions",
                        "data-cy": "position-admin-link"
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("account.home_manage_positions")))]
                  )
                ]),
                _vm._v(" "),
                _c("li", { staticClass: "pa2 pl0 relative" }, [
                  _c("img", {
                    staticClass: "pr1 absolute",
                    attrs: { src: "/img/company/account/teams.svg" }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "relative",
                      attrs: {
                        href: "/" + _vm.company.id + "/account/teams",
                        "data-cy": "team-admin-link"
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("account.home_manage_teams")))]
                  )
                ]),
                _vm._v(" "),
                _c("li", { staticClass: "pa2 pl0 relative" }, [
                  _c("img", {
                    staticClass: "pr1 absolute",
                    attrs: { src: "/img/company/account/position.svg" }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "relative",
                      attrs: {
                        href: "/" + _vm.company.id + "/account/positions",
                        "data-cy": "-admin-link"
                      }
                    },
                    [_vm._v(_vm._s(_vm.$t("account.home_manage_positions")))]
                  )
                ])
              ]),
              _vm._v(" "),
              _c(
                "div",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.user.permission_level < 200,
                      expression: "user.permission_level < 200"
                    }
                  ]
                },
                [
                  _c("p", {
                    domProps: {
                      innerHTML: _vm._s(_vm.$t("account.home_role_owner"))
                    }
                  }),
                  _vm._v(" "),
                  _c("ul", { staticClass: "options list pl0" }, [
                    _c("li", { staticClass: "pa2 pl0 relative" }, [
                      _c("img", {
                        staticClass: "pr1 absolute",
                        attrs: { src: "/img/company/account/audit.svg" }
                      }),
                      _vm._v(" "),
                      _c(
                        "a",
                        {
                          staticClass: "relative",
                          attrs: {
                            href: "/" + _vm.company.id + "/account/audit"
                          }
                        },
                        [_vm._v(_vm._s(_vm.$t("account.home_audit_log")))]
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "li",
                      {
                        directives: [
                          {
                            name: "show",
                            rawName: "v-show",
                            value: !_vm.company.has_dummy_data,
                            expression: "!company.has_dummy_data"
                          }
                        ],
                        staticClass: "pa2 pl0"
                      },
                      [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "/" + _vm.company.id + "/account/dummy"
                            }
                          },
                          [
                            _vm._v(
                              _vm._s(_vm.$t("account.home_generate_fake_data"))
                            )
                          ]
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "li",
                      {
                        directives: [
                          {
                            name: "show",
                            rawName: "v-show",
                            value: _vm.company.has_dummy_data,
                            expression: "company.has_dummy_data"
                          }
                        ],
                        staticClass: "pa2 pl0"
                      },
                      [
                        _c(
                          "a",
                          {
                            attrs: {
                              href: "/" + _vm.company.id + "/account/dummy"
                            }
                          },
                          [
                            _vm._v(
                              _vm._s(_vm.$t("account.home_remove_fake_data"))
                            )
                          ]
                        )
                      ]
                    )
                  ])
                ]
              )
            ])
          ]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c("a", { attrs: { href: "/" + _vm.company.id + "/account" } }, [
                _vm._v(_vm._s(_vm.$t("app.breadcrumb_account_home")))
              ])
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v(
                "\n          " +
                  _vm._s(_vm.$t("app.breadcrumb_account_audit_logs")) +
                  "\n        "
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass: "mw7 center br3 mb5 bg-white box restricted relative z-1"
        },
        [
          _c("div", { staticClass: "pa3 mt5" }, [
            _c("h2", { staticClass: "tc normal mb4" }, [
              _vm._v(
                "\n          " + _vm._s(_vm.$t("audit.title")) + "\n        "
              )
            ]),
            _vm._v(" "),
            _c(
              "ul",
              { staticClass: "list pl0 mt0 center" },
              _vm._l(_vm.logs, function(log) {
                return _c(
                  "li",
                  {
                    key: log.id,
                    staticClass:
                      "flex items-center lh-copy pa2-l pa1 ph0-l bb b--black-10"
                  },
                  [
                    _c("div", { staticClass: "flex-auto" }, [
                      _c("span", {
                        staticClass: "db black-70",
                        domProps: { innerHTML: _vm._s(log.name) }
                      }),
                      _vm._v(" "),
                      _c("span", {
                        staticClass: "db",
                        domProps: { innerHTML: _vm._s(log.sentence) }
                      }),
                      _vm._v(" "),
                      _c("span", {
                        staticClass: "db f6",
                        domProps: { innerHTML: _vm._s(log.date) }
                      })
                    ])
                  ]
                )
              }),
              0
            ),
            _vm._v(" "),
            _c("div", { staticClass: "center cf" }, [
              _c(
                "a",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.paginator.previousPageUrl,
                      expression: "paginator.previousPageUrl"
                    }
                  ],
                  staticClass: "fl dib",
                  attrs: {
                    href: _vm.paginator.previousPageUrl,
                    title: "Previous"
                  }
                },
                [_vm._v("← " + _vm._s(_vm.$t("app.previous")))]
              ),
              _vm._v(" "),
              _c(
                "a",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.paginator.nextPageUrl,
                      expression: "paginator.nextPageUrl"
                    }
                  ],
                  staticClass: "fr dib",
                  attrs: { href: _vm.paginator.nextPageUrl, title: "Next" }
                },
                [_vm._v(_vm._s(_vm.$t("app.next")) + " →")]
              )
            ])
          ])
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v("\n          ...\n        ")
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                {
                  attrs: { href: "/" + _vm.company.id + "/account/employees" }
                },
                [
                  _vm._v(
                    _vm._s(_vm.$t("app.breadcrumb_account_manage_employees"))
                  )
                ]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v(
                "\n          " +
                  _vm._s(_vm.$t("app.breadcrumb_account_add_employee")) +
                  "\n        "
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass: "mw7 center br3 mb5 bg-white box restricted relative z-1"
        },
        [
          _c(
            "div",
            { staticClass: "pa3 mt5 measure center" },
            [
              _c("h2", { staticClass: "tc normal mb4" }, [
                _vm._v(
                  "\n          " +
                    _vm._s(
                      _vm.$t("account.employee_new_title", {
                        name: _vm.company.name
                      })
                    ) +
                    "\n        "
                )
              ]),
              _vm._v(" "),
              _c("errors", { attrs: { errors: _vm.form.errors } }),
              _vm._v(" "),
              _c(
                "form",
                {
                  on: {
                    submit: function($event) {
                      $event.preventDefault()
                      return _vm.submit($event)
                    }
                  }
                },
                [
                  _c("div", { staticClass: "mb3" }, [
                    _c(
                      "label",
                      {
                        staticClass: "db fw4 lh-copy f6",
                        attrs: { for: "first_name" }
                      },
                      [_vm._v(_vm._s(_vm.$t("account.employee_new_firstname")))]
                    ),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.form.first_name,
                          expression: "form.first_name"
                        }
                      ],
                      staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                      attrs: {
                        id: "first_name",
                        type: "text",
                        name: "first_name",
                        required: ""
                      },
                      domProps: { value: _vm.form.first_name },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.form, "first_name", $event.target.value)
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "mb3" }, [
                    _c(
                      "label",
                      {
                        staticClass: "db fw4 lh-copy f6",
                        attrs: { for: "last_name" }
                      },
                      [_vm._v(_vm._s(_vm.$t("account.employee_new_lastname")))]
                    ),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.form.last_name,
                          expression: "form.last_name"
                        }
                      ],
                      staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                      attrs: {
                        id: "last_name",
                        type: "text",
                        name: "last_name",
                        required: ""
                      },
                      domProps: { value: _vm.form.last_name },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.form, "last_name", $event.target.value)
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "mb3" }, [
                    _c(
                      "label",
                      {
                        staticClass: "db fw4 lh-copy f6",
                        attrs: { for: "email" }
                      },
                      [_vm._v(_vm._s(_vm.$t("account.employee_new_email")))]
                    ),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.form.email,
                          expression: "form.email"
                        }
                      ],
                      staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                      attrs: {
                        id: "email",
                        type: "email",
                        name: "email",
                        required: ""
                      },
                      domProps: { value: _vm.form.email },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(_vm.form, "email", $event.target.value)
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("hr"),
                  _vm._v(" "),
                  _c("div", { staticClass: "mb3" }, [
                    _c("p", [
                      _vm._v(
                        _vm._s(_vm.$t("account.employee_new_permission_level"))
                      )
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "db relative" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.permission_level,
                            expression: "form.permission_level"
                          }
                        ],
                        staticClass: "mr1 relative",
                        attrs: {
                          id: "administrator",
                          type: "radio",
                          name: "permission_level",
                          value: "100"
                        },
                        domProps: {
                          checked: _vm._q(_vm.form.permission_level, "100")
                        },
                        on: {
                          change: function($event) {
                            return _vm.$set(_vm.form, "permission_level", "100")
                          }
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "label",
                        {
                          staticClass: "pointer",
                          attrs: { for: "administrator" }
                        },
                        [
                          _vm._v(
                            _vm._s(_vm.$t("account.employee_new_administrator"))
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c("p", { staticClass: "ma0 lh-copy f6 mb3" }, [
                        _vm._v(
                          "\n                " +
                            _vm._s(
                              _vm.$t("account.employee_new_administrator_desc")
                            ) +
                            "\n              "
                        )
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "db relative" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.permission_level,
                            expression: "form.permission_level"
                          }
                        ],
                        staticClass: "mr1 relative",
                        attrs: {
                          id: "hr",
                          type: "radio",
                          name: "permission_level",
                          value: "200"
                        },
                        domProps: {
                          checked: _vm._q(_vm.form.permission_level, "200")
                        },
                        on: {
                          change: function($event) {
                            return _vm.$set(_vm.form, "permission_level", "200")
                          }
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "label",
                        { staticClass: "pointer", attrs: { for: "hr" } },
                        [_vm._v(_vm._s(_vm.$t("account.employee_new_hr")))]
                      ),
                      _vm._v(" "),
                      _c("p", { staticClass: "ma0 lh-copy f6 mb3" }, [
                        _vm._v(
                          "\n                " +
                            _vm._s(_vm.$t("account.employee_new_hr_desc")) +
                            "\n              "
                        )
                      ])
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "db relative" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.permission_level,
                            expression: "form.permission_level"
                          }
                        ],
                        staticClass: "mr1 relative",
                        attrs: {
                          id: "user",
                          type: "radio",
                          name: "permission_level",
                          value: "300"
                        },
                        domProps: {
                          checked: _vm._q(_vm.form.permission_level, "300")
                        },
                        on: {
                          change: function($event) {
                            return _vm.$set(_vm.form, "permission_level", "300")
                          }
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "label",
                        { staticClass: "pointer", attrs: { for: "user" } },
                        [_vm._v(_vm._s(_vm.$t("account.employee_new_user")))]
                      ),
                      _vm._v(" "),
                      _c("p", { staticClass: "ma0 lh-copy f6 mb3" }, [
                        _vm._v(
                          "\n                " +
                            _vm._s(_vm.$t("account.employee_new_user_desc")) +
                            "\n              "
                        )
                      ])
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "mb3 ba bb-gray bg-gray pa3" }, [
                    _c("div", { staticClass: "flex items-start relative" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.send_invitation,
                            expression: "form.send_invitation"
                          }
                        ],
                        staticClass: "mr2 relative",
                        attrs: {
                          id: "send_email",
                          type: "checkbox",
                          name: "send_email"
                        },
                        domProps: {
                          checked: Array.isArray(_vm.form.send_invitation)
                            ? _vm._i(_vm.form.send_invitation, null) > -1
                            : _vm.form.send_invitation
                        },
                        on: {
                          change: function($event) {
                            var $$a = _vm.form.send_invitation,
                              $$el = $event.target,
                              $$c = $$el.checked ? true : false
                            if (Array.isArray($$a)) {
                              var $$v = null,
                                $$i = _vm._i($$a, $$v)
                              if ($$el.checked) {
                                $$i < 0 &&
                                  _vm.$set(
                                    _vm.form,
                                    "send_invitation",
                                    $$a.concat([$$v])
                                  )
                              } else {
                                $$i > -1 &&
                                  _vm.$set(
                                    _vm.form,
                                    "send_invitation",
                                    $$a.slice(0, $$i).concat($$a.slice($$i + 1))
                                  )
                              }
                            } else {
                              _vm.$set(_vm.form, "send_invitation", $$c)
                            }
                          }
                        }
                      }),
                      _vm._v(" "),
                      _c(
                        "label",
                        {
                          staticClass: "lh-copy ma0",
                          attrs: { for: "send_email" }
                        },
                        [
                          _vm._v(
                            _vm._s(_vm.$t("account.employee_new_send_email"))
                          )
                        ]
                      )
                    ])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "mv4" }, [
                    _c(
                      "div",
                      { staticClass: "flex-ns justify-between" },
                      [
                        _c("div", [
                          _c(
                            "a",
                            {
                              staticClass:
                                "btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3",
                              attrs: {
                                href:
                                  "/" + _vm.company.id + "/account/employees"
                              }
                            },
                            [_vm._v(_vm._s(_vm.$t("app.cancel")))]
                          )
                        ]),
                        _vm._v(" "),
                        _c("loading-button", {
                          attrs: {
                            classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                            state: _vm.loadingState,
                            text: _vm.$t("app.save"),
                            "cypress-selector": "submit-add-employee-button"
                          }
                        })
                      ],
                      1
                    )
                  ])
                ]
              )
            ],
            1
          )
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c("a", { attrs: { href: "/" + _vm.company.id + "/account" } }, [
                _vm._v(_vm._s(_vm.$t("app.breadcrumb_account_home")))
              ])
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v(
                "\n          " +
                  _vm._s(_vm.$t("app.breadcrumb_account_manage_employees")) +
                  "\n        "
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass: "mw7 center br3 mb5 bg-white box restricted relative z-1"
        },
        [
          _c("div", { staticClass: "pa3 mt5" }, [
            _c("h2", { staticClass: "tc normal mb4" }, [
              _vm._v(
                "\n          " +
                  _vm._s(
                    _vm.$t("account.employees_title", {
                      company: _vm.company.name
                    })
                  ) +
                  "\n        "
              )
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "relative" }, [
              _c("span", { staticClass: "dib mb3 di-l" }, [
                _vm._v(
                  _vm._s(
                    _vm.$tc(
                      "account.employees_number_employees",
                      _vm.employees.length,
                      { company: _vm.company.name, count: _vm.employees.length }
                    )
                  )
                )
              ]),
              _vm._v(" "),
              _c(
                "a",
                {
                  staticClass:
                    "btn primary absolute-l relative dib-l db right-0",
                  attrs: {
                    href: "/" + _vm.company.id + "/account/employees/create",
                    "data-cy": "add-employee-button"
                  }
                },
                [_vm._v(_vm._s(_vm.$t("account.employees_cta")))]
              )
            ]),
            _vm._v(" "),
            _c(
              "ul",
              { staticClass: "list pl0 mt0 center" },
              _vm._l(_vm.employees, function(employee) {
                return _c(
                  "li",
                  {
                    key: employee.id,
                    staticClass:
                      "flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
                  },
                  [
                    _c("img", {
                      staticClass: "w2 h2 w3-ns h3-ns br-100",
                      attrs: { src: employee.avatar }
                    }),
                    _vm._v(" "),
                    _c("div", { staticClass: "pl3 flex-auto" }, [
                      _c("span", { staticClass: "db black-70" }, [
                        _vm._v(_vm._s(employee.name))
                      ]),
                      _vm._v(" "),
                      _c("ul", { staticClass: "f6 list pl0" }, [
                        _c("li", { staticClass: "di pr2" }, [
                          _c("span", { staticClass: "badge f7" }, [
                            _vm._v(
                              _vm._s(
                                _vm.$t(
                                  "app.permission_" + employee.permission_level
                                )
                              )
                            )
                          ])
                        ]),
                        _vm._v(" "),
                        _c("li", { staticClass: "di pr2" }, [
                          _c(
                            "a",
                            {
                              attrs: {
                                href:
                                  "/" +
                                  _vm.company.id +
                                  "/employees/" +
                                  employee.id,
                                "data-cy": "employee-view"
                              }
                            },
                            [_vm._v(_vm._s(_vm.$t("app.view")))]
                          )
                        ]),
                        _vm._v(" "),
                        _c("li", { staticClass: "di pr2" }, [
                          _c(
                            "a",
                            {
                              attrs: {
                                href:
                                  "/account/employees/" +
                                  employee.id +
                                  "/permissions"
                              }
                            },
                            [
                              _vm._v(
                                _vm._s(
                                  _vm.$t("account.employees_change_permission")
                                )
                              )
                            ]
                          )
                        ]),
                        _vm._v(" "),
                        _c("li", { staticClass: "di pr2" }, [
                          _c(
                            "a",
                            {
                              attrs: {
                                href: "/employees/" + employee.id + "/lock"
                              }
                            },
                            [
                              _vm._v(
                                _vm._s(_vm.$t("account.employees_lock_account"))
                              )
                            ]
                          )
                        ]),
                        _vm._v(" "),
                        _c("li", { staticClass: "di" }, [
                          _c(
                            "a",
                            {
                              attrs: {
                                href:
                                  "/account/employees/" +
                                  employee.id +
                                  "/destroy"
                              }
                            },
                            [_vm._v(_vm._s(_vm.$t("app.delete")))]
                          )
                        ])
                      ])
                    ])
                  ]
                )
              }),
              0
            )
          ])
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true&":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true& ***!
  \***************************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c("a", { attrs: { href: "/" + _vm.company.id + "/account" } }, [
                _vm._v(_vm._s(_vm.$t("app.breadcrumb_account_home")))
              ])
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v(
                "\n          " +
                  _vm._s(_vm.$t("app.breadcrumb_account_manage_positions")) +
                  "\n        "
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass: "mw7 center br3 mb5 bg-white box restricted relative z-1"
        },
        [
          _c("div", { staticClass: "pa3 mt5" }, [
            _c("h2", { staticClass: "tc normal mb4" }, [
              _vm._v(
                "\n          " +
                  _vm._s(
                    _vm.$t("account.positions_title", {
                      company: _vm.company.name
                    })
                  ) +
                  "\n        "
              )
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "relative" }, [
              _c(
                "span",
                {
                  staticClass: "dib mb3 di-l",
                  class: _vm.positions.length == 0 ? "white" : ""
                },
                [
                  _vm._v(
                    _vm._s(
                      _vm.$tc(
                        "account.positions_number_positions",
                        _vm.positions.length,
                        {
                          company: _vm.company.name,
                          count: _vm.positions.length
                        }
                      )
                    )
                  )
                ]
              ),
              _vm._v(" "),
              _c(
                "a",
                {
                  staticClass:
                    "btn primary absolute-l relative dib-l db right-0",
                  attrs: { "data-cy": "add-position-button" },
                  on: {
                    click: function($event) {
                      $event.preventDefault()
                      _vm.modal = true
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.$t("account.positions_cta")))]
              )
            ]),
            _vm._v(" "),
            _c(
              "form",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.modal,
                    expression: "modal"
                  }
                ],
                staticClass: "mb3 pa3 ba br2 bb-gray bg-gray",
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.submit($event)
                  }
                }
              },
              [
                _c("label", { attrs: { for: "title" } }, [
                  _vm._v(_vm._s(_vm.$t("account.position_new_title")))
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "cf" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.title,
                        expression: "form.title"
                      }
                    ],
                    staticClass:
                      "br2 f5 ba b--black-40 pa2 outline-0 fl w-100 w-70-ns mb3 mb0-ns",
                    attrs: {
                      id: "title",
                      type: "text",
                      name: "title",
                      placeholder: "Marketing coordinator",
                      required: "",
                      "data-cy": "add-title-input"
                    },
                    domProps: { value: _vm.form.title },
                    on: {
                      keydown: function($event) {
                        if (
                          !$event.type.indexOf("key") &&
                          _vm._k($event.keyCode, "esc", 27, $event.key, [
                            "Esc",
                            "Escape"
                          ])
                        ) {
                          return null
                        }
                        _vm.modal = false
                      },
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "title", $event.target.value)
                      }
                    }
                  }),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "fl w-30-ns w-100 tr" },
                    [
                      _c(
                        "a",
                        {
                          staticClass: "btn dib-l db mb2 mb0-ns",
                          on: {
                            click: function($event) {
                              $event.preventDefault()
                              _vm.modal = false
                            }
                          }
                        },
                        [_vm._v(_vm._s(_vm.$t("app.cancel")))]
                      ),
                      _vm._v(" "),
                      _c("loading-button", {
                        attrs: {
                          classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                          "data-cy": "modal-add-cta",
                          state: _vm.loadingState,
                          text: _vm.$t("app.add")
                        }
                      })
                    ],
                    1
                  )
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "ul",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.positions.length != 0,
                    expression: "positions.length != 0"
                  }
                ],
                staticClass: "list pl0 mv0 center ba br2 bb-gray",
                attrs: { "data-cy": "positions-list" }
              },
              _vm._l(_vm.positions, function(position) {
                return _c(
                  "li",
                  {
                    key: position.id,
                    staticClass: "pv3 ph2 bb bb-gray bb-gray-hover"
                  },
                  [
                    _vm._v(
                      "\n            " +
                        _vm._s(position.title) +
                        "\n\n            "
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        directives: [
                          {
                            name: "show",
                            rawName: "v-show",
                            value: _vm.idToUpdate == position.id,
                            expression: "idToUpdate == position.id"
                          }
                        ],
                        staticClass: "cf mt3"
                      },
                      [
                        _c(
                          "form",
                          {
                            on: {
                              submit: function($event) {
                                $event.preventDefault()
                                return _vm.update(position.id)
                              }
                            }
                          },
                          [
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.form.title,
                                  expression: "form.title"
                                }
                              ],
                              ref: "title",
                              refInFor: true,
                              staticClass:
                                "br2 f5 ba b--black-40 pa2 outline-0 fl w-100 w-70-ns mb3 mb0-ns",
                              attrs: {
                                id: "title",
                                type: "text",
                                name: "title",
                                placeholder: "Marketing coordinator",
                                required: "",
                                "data-cy":
                                  "list-rename-input-name-" + position.id
                              },
                              domProps: { value: _vm.form.title },
                              on: {
                                keydown: function($event) {
                                  if (
                                    !$event.type.indexOf("key") &&
                                    _vm._k(
                                      $event.keyCode,
                                      "esc",
                                      27,
                                      $event.key,
                                      ["Esc", "Escape"]
                                    )
                                  ) {
                                    return null
                                  }
                                  _vm.idToUpdate = 0
                                },
                                input: function($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.$set(
                                    _vm.form,
                                    "title",
                                    $event.target.value
                                  )
                                }
                              }
                            }),
                            _vm._v(" "),
                            _c(
                              "div",
                              { staticClass: "fl w-30-ns w-100 tr" },
                              [
                                _c(
                                  "a",
                                  {
                                    staticClass: "btn dib-l db mb2 mb0-ns",
                                    attrs: {
                                      "data-cy":
                                        "list-rename-cancel-button-" +
                                        position.id
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.idToUpdate = 0
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.cancel")))]
                                ),
                                _vm._v(" "),
                                _c("loading-button", {
                                  attrs: {
                                    classes:
                                      "btn add w-auto-ns w-100 mb2 pv2 ph3",
                                    "data-cy":
                                      "list-rename-cta-button-" + position.id,
                                    state: _vm.loadingState,
                                    text: _vm.$t("app.update")
                                  }
                                })
                              ],
                              1
                            )
                          ]
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "ul",
                      {
                        directives: [
                          {
                            name: "show",
                            rawName: "v-show",
                            value: _vm.idToUpdate != position.id,
                            expression: "idToUpdate != position.id"
                          }
                        ],
                        staticClass: "list pa0 ma0 di-ns db fr-ns mt2 mt0-ns"
                      },
                      [
                        _c("li", { staticClass: "di mr2" }, [
                          _c(
                            "a",
                            {
                              staticClass: "pointer",
                              attrs: {
                                "data-cy": "list-rename-button-" + position.id
                              },
                              on: {
                                click: function($event) {
                                  $event.preventDefault()
                                  _vm.displayUpdateModal(position)
                                  _vm.form.title = position.title
                                }
                              }
                            },
                            [_vm._v(_vm._s(_vm.$t("app.rename")))]
                          )
                        ]),
                        _vm._v(" "),
                        _vm.idToDelete == position.id
                          ? _c("li", { staticClass: "di" }, [
                              _vm._v(
                                "\n                " +
                                  _vm._s(_vm.$t("app.sure")) +
                                  "\n                "
                              ),
                              _c(
                                "a",
                                {
                                  staticClass: "c-delete mr1 pointer",
                                  attrs: {
                                    "data-cy":
                                      "list-delete-confirm-button-" +
                                      position.id
                                  },
                                  on: {
                                    click: function($event) {
                                      $event.preventDefault()
                                      return _vm.destroy(position.id)
                                    }
                                  }
                                },
                                [_vm._v(_vm._s(_vm.$t("app.yes")))]
                              ),
                              _vm._v(" "),
                              _c(
                                "a",
                                {
                                  staticClass: "pointer",
                                  attrs: {
                                    "data-cy":
                                      "list-delete-cancel-button-" + position.id
                                  },
                                  on: {
                                    click: function($event) {
                                      $event.preventDefault()
                                      _vm.idToDelete = 0
                                    }
                                  }
                                },
                                [_vm._v(_vm._s(_vm.$t("app.no")))]
                              )
                            ])
                          : _c("li", { staticClass: "di" }, [
                              _c(
                                "a",
                                {
                                  staticClass: "pointer",
                                  attrs: {
                                    "data-cy":
                                      "list-delete-button-" + position.id
                                  },
                                  on: {
                                    click: function($event) {
                                      $event.preventDefault()
                                      _vm.idToDelete = position.id
                                    }
                                  }
                                },
                                [_vm._v(_vm._s(_vm.$t("app.delete")))]
                              )
                            ])
                      ]
                    )
                  ]
                )
              }),
              0
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.positions.length == 0,
                    expression: "positions.length == 0"
                  }
                ],
                staticClass: "pa3 mt5"
              },
              [
                _c("p", { staticClass: "tc measure center mb4 lh-copy" }, [
                  _vm._v(
                    "\n            " +
                      _vm._s(_vm.$t("account.positions_blank")) +
                      "\n          "
                  )
                ]),
                _vm._v(" "),
                _c("img", {
                  staticClass: "db center mb4",
                  attrs: {
                    srcset:
                      "/img/company/account/blank-position-1x.png" +
                      ", " +
                      "/img/company/account/blank-position-2x.png" +
                      " 2x"
                  }
                })
              ]
            )
          ])
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c("a", { attrs: { href: "/" + _vm.company.id + "/account" } }, [
                _vm._v(_vm._s(_vm.$t("app.breadcrumb_account_home")))
              ])
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v(
                "\n          " +
                  _vm._s(_vm.$t("app.breadcrumb_account_manage_teams")) +
                  "\n        "
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          staticClass: "mw7 center br3 mb5 bg-white box restricted relative z-1"
        },
        [
          _c("div", { staticClass: "pa3 mt5" }, [
            _c("h2", { staticClass: "tc normal mb4" }, [
              _vm._v(
                "\n          " +
                  _vm._s(
                    _vm.$t("account.teams_title", { company: _vm.company.name })
                  ) +
                  "\n        "
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "relative" }, [
              _c(
                "span",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.teams.length != 0,
                      expression: "teams.length != 0"
                    }
                  ],
                  staticClass: "dib mb3 di-l"
                },
                [
                  _vm._v(
                    _vm._s(
                      _vm.$tc("account.teams_number_teams", _vm.teams.length, {
                        company: _vm.company.name,
                        count: _vm.teams.length
                      })
                    )
                  )
                ]
              ),
              _vm._v(" "),
              _c(
                "a",
                {
                  staticClass:
                    "btn primary tc absolute-l relative dib-l db right-0",
                  attrs: { "data-cy": "add-team-button" },
                  on: {
                    click: function($event) {
                      $event.preventDefault()
                      _vm.modal = !_vm.modal
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.$t("account.teams_cta")))]
              ),
              _vm._v(" "),
              _vm.modal == true
                ? _c(
                    "div",
                    {
                      staticClass:
                        "absolute add-modal br2 bg-white z-max tl pv2 ph3 bounceIn faster"
                    },
                    [
                      _c("errors", { attrs: { errors: _vm.form.errors } }),
                      _vm._v(" "),
                      _c(
                        "form",
                        {
                          on: {
                            submit: function($event) {
                              $event.preventDefault()
                              return _vm.submit($event)
                            }
                          }
                        },
                        [
                          _c("div", { staticClass: "mb3" }, [
                            _c(
                              "label",
                              {
                                staticClass: "db fw4 lh-copy f6",
                                attrs: { for: "name" }
                              },
                              [_vm._v(_vm._s(_vm.$t("account.team_new_name")))]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.form.name,
                                  expression: "form.name"
                                }
                              ],
                              staticClass:
                                "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                              attrs: {
                                id: "name",
                                type: "text",
                                name: "name",
                                required: ""
                              },
                              domProps: { value: _vm.form.name },
                              on: {
                                input: function($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.$set(
                                    _vm.form,
                                    "name",
                                    $event.target.value
                                  )
                                }
                              }
                            })
                          ]),
                          _vm._v(" "),
                          _c("div", { staticClass: "mv2" }, [
                            _c(
                              "div",
                              { staticClass: "flex-ns justify-between" },
                              [
                                _c("div", [
                                  _c(
                                    "a",
                                    {
                                      staticClass:
                                        "btn btn-secondary dib tc w-auto-ns w-100 mb2 pv2 ph3",
                                      on: {
                                        click: function($event) {
                                          _vm.modal = false
                                        }
                                      }
                                    },
                                    [_vm._v(_vm._s(_vm.$t("app.cancel")))]
                                  )
                                ]),
                                _vm._v(" "),
                                _c("loading-button", {
                                  attrs: {
                                    classes:
                                      "btn add w-auto-ns w-100 mb2 pv2 ph3",
                                    state: _vm.loadingState,
                                    text: _vm.$t("app.add"),
                                    "data-cy": "submit-add-team-button"
                                  }
                                })
                              ],
                              1
                            )
                          ])
                        ]
                      )
                    ],
                    1
                  )
                : _vm._e()
            ]),
            _vm._v(" "),
            _c(
              "ul",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.teams.length != 0,
                    expression: "teams.length != 0"
                  }
                ],
                staticClass: "list pl0 mt0 center"
              },
              _vm._l(_vm.teams, function(team) {
                return _c(
                  "li",
                  {
                    key: team.id,
                    staticClass:
                      "flex items-center lh-copy pa3-l pa1 ph0-l bb b--black-10"
                  },
                  [
                    _c("div", { staticClass: "flex-auto" }, [
                      _c("span", { staticClass: "db b" }, [
                        _vm._v(_vm._s(team.name))
                      ]),
                      _vm._v(" "),
                      _c("ul", { staticClass: "f6 list pl0" }, [
                        _c("li", { staticClass: "di pr2" }, [
                          _c(
                            "a",
                            {
                              attrs: {
                                href: "/" + _vm.company.id + "/teams/" + team.id
                              }
                            },
                            [_vm._v(_vm._s(_vm.$t("app.view")))]
                          )
                        ]),
                        _vm._v(" "),
                        _c("li", { staticClass: "di pr2" }, [
                          _c(
                            "a",
                            {
                              attrs: {
                                href:
                                  "/" +
                                  _vm.company.id +
                                  "/teams/" +
                                  team.id +
                                  "/lock"
                              }
                            },
                            [_vm._v(_vm._s(_vm.$t("app.rename")))]
                          )
                        ]),
                        _vm._v(" "),
                        _c("li", { staticClass: "di" }, [
                          _c(
                            "a",
                            {
                              attrs: {
                                href:
                                  "/" +
                                  _vm.company.id +
                                  "/teams/" +
                                  team.id +
                                  "/destroy"
                              }
                            },
                            [_vm._v(_vm._s(_vm.$t("app.delete")))]
                          )
                        ])
                      ])
                    ])
                  ]
                )
              }),
              0
            )
          ]),
          _vm._v(" "),
          _c(
            "div",
            {
              directives: [
                {
                  name: "show",
                  rawName: "v-show",
                  value: _vm.teams.length == 0,
                  expression: "teams.length == 0"
                }
              ],
              staticClass: "pa3"
            },
            [
              _c("p", { staticClass: "tc measure center mb4 lh-copy" }, [
                _vm._v(
                  "\n          " +
                    _vm._s(_vm.$t("account.teams_blank")) +
                    "\n        "
                )
              ]),
              _vm._v(" "),
              _c("img", {
                staticClass: "db center mb4",
                attrs: {
                  srcset:
                    "/img/company/account/blank-team-1x.png" +
                    ", " +
                    "/img/company/account/blank-team-2x.png" +
                    " 2x"
                }
              })
            ]
          )
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/CreateCompany.vue?vue&type=template&id=5c04b4e2&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/CreateCompany.vue?vue&type=template&id=5c04b4e2& ***!
  \*********************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", "no-menu": true } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
        _c("div", { staticClass: "fn fl-ns w-50-ns pa3" }, [
          _vm._v("\n        Create a company\n      ")
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "fn fl-ns w-50-ns pa3" },
          [
            _c("errors", { attrs: { errors: _vm.form.errors } }),
            _vm._v(" "),
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.submit($event)
                  }
                }
              },
              [
                _c("div", {}, [
                  _c(
                    "label",
                    {
                      staticClass: "db fw4 lh-copy f6",
                      attrs: { for: "name" }
                    },
                    [_vm._v("Name")]
                  ),
                  _vm._v(" "),
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.form.name,
                        expression: "form.name"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: { type: "text", name: "name", required: "" },
                    domProps: { value: _vm.form.name },
                    on: {
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.$set(_vm.form, "name", $event.target.value)
                      }
                    }
                  })
                ]),
                _vm._v(" "),
                _c("div", {}, [
                  _c("div", { staticClass: "flex-ns justify-between" }, [
                    _c(
                      "div",
                      [
                        _c("loading-button", {
                          attrs: {
                            classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                            state: _vm.loadingState,
                            text: "register",
                            "data-cy": "create-company-submit"
                          }
                        })
                      ],
                      1
                    )
                  ])
                ])
              ]
            )
          ],
          1
        )
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "di relative" }, [
    _vm.user.permission_level <= 200
      ? _c(
          "span",
          {
            staticClass: "bb b--dotted bt-0 bl-0 br-0 pointer",
            attrs: { "data-cy": "open-position-modal" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.modal = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.title))]
        )
      : _c("span", { attrs: { "data-cy": "position-title" } }, [
          _vm._v(_vm._s(_vm.title))
        ]),
    _vm._v(" "),
    _vm.user.permission_level <= 200
      ? _c(
          "a",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.title == "",
                expression: "title == ''"
              }
            ],
            staticClass: "pointer",
            attrs: { "data-cy": "open-position-modal-blank" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.modal = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("employee.position_modal_title")))]
        )
      : _c(
          "span",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.title == "",
                expression: "title == ''"
              }
            ]
          },
          [_vm._v(_vm._s(_vm.$t("employee.position_blank")))]
        ),
    _vm._v(" "),
    _vm.modal
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModal,
                expression: "toggleModal"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl bounceIn faster"
          },
          [
            _c("p", { staticClass: "pa2 ma0 bb bb-gray" }, [
              _vm._v(
                "\n      " +
                  _vm._s(_vm.$t("employee.position_modal_title")) +
                  "\n    "
              )
            ]),
            _vm._v(" "),
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.search($event)
                  }
                }
              },
              [
                _c("div", { staticClass: "relative pv2 ph2 bb bb-gray" }, [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model",
                        value: _vm.search,
                        expression: "search"
                      }
                    ],
                    staticClass: "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                    attrs: {
                      id: "search",
                      type: "text",
                      name: "search",
                      placeholder: _vm.$t("employee.position_modal_filter")
                    },
                    domProps: { value: _vm.search },
                    on: {
                      keydown: function($event) {
                        if (
                          !$event.type.indexOf("key") &&
                          _vm._k($event.keyCode, "esc", 27, $event.key, [
                            "Esc",
                            "Escape"
                          ])
                        ) {
                          return null
                        }
                        return _vm.toggleModal($event)
                      },
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.search = $event.target.value
                      }
                    }
                  })
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "ul",
              {
                staticClass:
                  "pl0 list ma0 overflow-auto relative positions-list"
              },
              _vm._l(_vm.filteredList, function(position) {
                return _c(
                  "li",
                  {
                    key: position.id,
                    staticClass: "pv2 ph3 bb bb-gray-hover bb-gray pointer",
                    attrs: { "data-cy": "list-position-" + position.id },
                    on: {
                      click: function($event) {
                        return _vm.assign(position)
                      }
                    }
                  },
                  [_vm._v("\n        " + _vm._s(position.title) + "\n      ")]
                )
              }),
              0
            ),
            _vm._v(" "),
            _vm.title != ""
              ? _c(
                  "a",
                  {
                    staticClass:
                      "pointer pv2 ph3 db no-underline c-delete bb-0",
                    attrs: { "data-cy": "position-reset-button" },
                    on: { click: _vm.reset }
                  },
                  [_vm._v(_vm._s(_vm.$t("employee.position_modal_reset")))]
                )
              : _vm._e()
          ]
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "di relative" }, [
    _vm.user.permission_level <= 200
      ? _c(
          "ul",
          { staticClass: "ma0 pa0 di existing-teams" },
          [
            _c(
              "li",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.updatedEmployee.teams.length != 0,
                    expression: "updatedEmployee.teams.length != 0"
                  }
                ],
                staticClass: "bb b--dotted bt-0 bl-0 br-0 pointer di",
                attrs: { "data-cy": "open-team-modal" },
                on: {
                  click: function($event) {
                    $event.preventDefault()
                    _vm.modal = true
                  }
                }
              },
              [
                _vm._v(
                  "\n      " + _vm._s(_vm.$t("employee.team_title")) + "\n    "
                )
              ]
            ),
            _vm._v(" "),
            _vm._l(_vm.updatedEmployee.teams, function(team) {
              return _c("li", { key: team.id, staticClass: "di" }, [
                _vm._v("\n      " + _vm._s(team.name) + "\n    ")
              ])
            })
          ],
          2
        )
      : _c(
          "ul",
          { staticClass: "ma0 pa0 existing-teams di" },
          [
            _c(
              "li",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.updatedEmployee.teams.length != 0,
                    expression: "updatedEmployee.teams.length != 0"
                  }
                ],
                staticClass: "di"
              },
              [
                _vm._v(
                  "\n      " + _vm._s(_vm.$t("employee.team_title")) + "\n    "
                )
              ]
            ),
            _vm._v(" "),
            _vm._l(_vm.updatedEmployee.teams, function(team) {
              return _c("li", { key: team.id, staticClass: "di" }, [
                _vm._v("\n      " + _vm._s(team.name) + "\n    ")
              ])
            })
          ],
          2
        ),
    _vm._v(" "),
    _vm.user.permission_level <= 200
      ? _c(
          "a",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.updatedEmployee.teams.length == 0,
                expression: "updatedEmployee.teams.length == 0"
              }
            ],
            staticClass: "pointer",
            attrs: { "data-cy": "open-team-modal-blank" },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.modal = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("employee.team_modal_title")))]
        )
      : _c(
          "span",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.updatedEmployee.teams.length == 0,
                expression: "updatedEmployee.teams.length == 0"
              }
            ]
          },
          [_vm._v(_vm._s(_vm.$t("employee.team_modal_blank")))]
        ),
    _vm._v(" "),
    _vm.modal
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModal,
                expression: "toggleModal"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl bounceIn faster"
          },
          [
            _c(
              "div",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.teams.length != 0,
                    expression: "teams.length != 0"
                  }
                ]
              },
              [
                _c("p", { staticClass: "pa2 ma0 bb bb-gray" }, [
                  _vm._v(
                    "\n        " +
                      _vm._s(_vm.$t("employee.team_modal_title")) +
                      "\n      "
                  )
                ]),
                _vm._v(" "),
                _c(
                  "form",
                  {
                    on: {
                      submit: function($event) {
                        $event.preventDefault()
                        return _vm.search($event)
                      }
                    }
                  },
                  [
                    _c("div", { staticClass: "relative pv2 ph2 bb bb-gray" }, [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.search,
                            expression: "search"
                          }
                        ],
                        staticClass:
                          "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                        attrs: {
                          id: "search",
                          type: "text",
                          name: "search",
                          placeholder: _vm.$t("employee.team_modal_filter")
                        },
                        domProps: { value: _vm.search },
                        on: {
                          keydown: function($event) {
                            if (
                              !$event.type.indexOf("key") &&
                              _vm._k($event.keyCode, "esc", 27, $event.key, [
                                "Esc",
                                "Escape"
                              ])
                            ) {
                              return null
                            }
                            return _vm.toggleModal($event)
                          },
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.search = $event.target.value
                          }
                        }
                      })
                    ])
                  ]
                ),
                _vm._v(" "),
                _c(
                  "ul",
                  {
                    staticClass:
                      "pl0 list ma0 overflow-auto relative teams-list"
                  },
                  _vm._l(_vm.filteredList, function(team) {
                    return _c(
                      "li",
                      {
                        key: team.id,
                        attrs: { "data-cy": "list-team-" + team.id }
                      },
                      [
                        _vm.isAssigned(team.id)
                          ? _c(
                              "div",
                              {
                                staticClass:
                                  "pv2 ph3 bb bb-gray-hover bb-gray pointer relative",
                                on: {
                                  click: function($event) {
                                    return _vm.reset(team)
                                  }
                                }
                              },
                              [
                                _vm._v(
                                  "\n            " +
                                    _vm._s(team.name) +
                                    "\n\n            "
                                ),
                                _c("img", {
                                  staticClass: "pr1 absolute right-1",
                                  attrs: { src: "/img/check.svg" }
                                })
                              ]
                            )
                          : _c(
                              "div",
                              {
                                staticClass:
                                  "pv2 ph3 bb bb-gray-hover bb-gray pointer relative",
                                on: {
                                  click: function($event) {
                                    return _vm.assign(team)
                                  }
                                }
                              },
                              [
                                _vm._v(
                                  "\n            " +
                                    _vm._s(team.name) +
                                    "\n          "
                                )
                              ]
                            )
                      ]
                    )
                  }),
                  0
                )
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.teams.length == 0,
                    expression: "teams.length == 0"
                  }
                ]
              },
              [
                _c(
                  "p",
                  {
                    staticClass: "pa2 tc lh-copy",
                    attrs: { "data-cy": "modal-blank-state-copy" }
                  },
                  [
                    _vm._v(
                      "\n        " +
                        _vm._s(_vm.$t("employee.team_modal_blank_title")) +
                        " "
                    ),
                    _c(
                      "a",
                      {
                        attrs: {
                          href: "/" + _vm.company.id + "/account/teams",
                          "data-cy": "modal-blank-state-cta"
                        }
                      },
                      [_vm._v(_vm._s(_vm.$t("employee.team_modal_blank_cta")))]
                    )
                  ]
                ),
                _vm._v(" "),
                _c("img", {
                  staticClass: "db center mb4",
                  attrs: {
                    srcset:
                      "/img/company/account/blank-team-1x.png" +
                      ", " +
                      "/img/company/account/blank-team-2x.png" +
                      " 2x"
                  }
                })
              ]
            )
          ]
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true&":
/*!*****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true& ***!
  \*****************************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph5-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/employees" } },
                [_vm._v(_vm._s(_vm.$t("app.breadcrumb_employee_list")))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v("\n          " + _vm._s(_vm.employee.name) + "\n        ")
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "mw9 center br3 mb4 bg-white box relative z-1" },
        [
          _c("div", { staticClass: "pa3 relative pt5" }, [
            _c("img", {
              directives: [
                {
                  name: "show",
                  rawName: "v-show",
                  value:
                    _vm.user.permission_level <= 200 ||
                    _vm.user.user_id == _vm.employee.user.id,
                  expression:
                    "user.permission_level <= 200 || user.user_id == employee.user.id"
                }
              ],
              staticClass:
                "box-edit-button absolute br-100 pa2 bg-white pointer",
              attrs: {
                src: "/img/menu_button.svg",
                "data-cy": "edit-profile-button"
              },
              on: {
                click: function($event) {
                  _vm.profileMenu = true
                }
              }
            }),
            _vm._v(" "),
            _vm.profileMenu
              ? _c(
                  "div",
                  {
                    directives: [
                      {
                        name: "click-outside",
                        rawName: "v-click-outside",
                        value: _vm.toggleProfileMenu,
                        expression: "toggleProfileMenu"
                      }
                    ],
                    staticClass:
                      "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
                  },
                  [
                    _c("ul", { staticClass: "list ma0 pa0" }, [
                      _c(
                        "li",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value: _vm.user.permission_level <= 200,
                              expression: "user.permission_level <= 200"
                            }
                          ],
                          staticClass: "pv2"
                        },
                        [
                          _c(
                            "a",
                            {
                              staticClass: "pointer",
                              attrs: { "data-cy": "add-manager-button" }
                            },
                            [_vm._v("Edit")]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "li",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value: _vm.user.permission_level <= 200,
                              expression: "user.permission_level <= 200"
                            }
                          ],
                          staticClass: "pv2"
                        },
                        [
                          _c(
                            "a",
                            {
                              staticClass: "pointer",
                              attrs: { "data-cy": "add-direct-report-button" }
                            },
                            [_vm._v("Delete")]
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "li",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value:
                                _vm.user.permission_level <= 200 ||
                                _vm.user.user_id == _vm.employee.user.id,
                              expression:
                                "user.permission_level <= 200 || user.user_id == employee.user.id"
                            }
                          ],
                          staticClass: "pv2"
                        },
                        [
                          _c(
                            "a",
                            {
                              staticClass: "pointer",
                              attrs: {
                                href:
                                  "/" +
                                  _vm.company.id +
                                  "/employees/" +
                                  _vm.employee.id +
                                  "/logs",
                                "data-cy": "view-log-button"
                              }
                            },
                            [_vm._v("View change log")]
                          )
                        ]
                      )
                    ])
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            _c("img", {
              staticClass: "avatar absolute br-100 db center",
              attrs: { src: _vm.employee.avatar }
            }),
            _vm._v(" "),
            _c("h2", { staticClass: "tc normal mb1" }, [
              _vm._v("\n          " + _vm._s(_vm.employee.name) + "\n        ")
            ]),
            _vm._v(" "),
            _c("ul", { staticClass: "list tc pa0 f6 mb0" }, [
              _c(
                "li",
                { staticClass: "di-l db mb0-l mb2 mr2" },
                [
                  _c("assign-employee-position", {
                    attrs: {
                      company: _vm.company,
                      employee: _vm.employee,
                      user: _vm.user,
                      positions: _vm.positions
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di-l db mb0-l mb2 mr2" }, [
                _vm._v("\n            No hire date\n          ")
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "di-l db mb0-l mb2 mr2" }, [
                _vm._v("\n            No indication of status\n          ")
              ]),
              _vm._v(" "),
              _c(
                "li",
                { staticClass: "di-l db mb0-l mb2" },
                [
                  _c("assign-employee-team", {
                    attrs: {
                      company: _vm.company,
                      employee: _vm.employee,
                      user: _vm.user,
                      teams: _vm.teams
                    }
                  })
                ],
                1
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "cf mw9 center" }, [
        _c(
          "div",
          { staticClass: "fl w-40-l w-100" },
          [
            _c("show-company-employee-hierarchy", {
              attrs: {
                company: _vm.company,
                employee: _vm.employee,
                managers: _vm.managers,
                "direct-reports": _vm.directReports,
                user: _vm.user
              }
            })
          ],
          1
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "flex items-center justify-center flex-column mb4" },
          [
            _c("div", { staticClass: "cf dib btn-group" }, [
              _c("span", { staticClass: "f6 fl ph3 pv2 dib pointer" }, [
                _vm._v("\n            Summary\n          ")
              ]),
              _vm._v(" "),
              _c("span", { staticClass: "f6 fl ph3 pv2 pointer dib" }, [
                _vm._v("\n            Life events\n          ")
              ]),
              _vm._v(" "),
              _c("span", { staticClass: "f6 fl ph3 pv2 dib selected" }, [
                _vm._v("\n            Logs\n          ")
              ])
            ])
          ]
        )
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "mb4 relative" }, [
    _c("span", { staticClass: "tc db fw5 mb2" }, [
      _vm._v(_vm._s(_vm.$t("employee.hierarchy_title")))
    ]),
    _vm._v(" "),
    _c("img", {
      directives: [
        {
          name: "show",
          rawName: "v-show",
          value: _vm.user.permission_level <= 200,
          expression: "user.permission_level <= 200"
        }
      ],
      staticClass: "box-plus-button absolute br-100 pa2 bg-white pointer",
      attrs: { src: "/img/plus_button.svg", "data-cy": "add-hierarchy-button" },
      on: {
        click: function($event) {
          $event.preventDefault()
          return _vm.toggleModals()
        }
      }
    }),
    _vm._v(" "),
    _vm.modal == "menu"
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModals,
                expression: "toggleModals"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
            _c("ul", { staticClass: "list ma0 pa0" }, [
              _c("li", { staticClass: "pv2" }, [
                _c(
                  "a",
                  {
                    staticClass: "pointer",
                    attrs: { "data-cy": "add-manager-button" },
                    on: {
                      click: function($event) {
                        $event.preventDefault()
                        return _vm.displayManagerModal()
                      }
                    }
                  },
                  [
                    _vm._v(
                      _vm._s(_vm.$t("employee.hierarchy_modal_add_manager"))
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2" }, [
                _c(
                  "a",
                  {
                    staticClass: "pointer",
                    attrs: { "data-cy": "add-direct-report-button" },
                    on: {
                      click: function($event) {
                        $event.preventDefault()
                        return _vm.displayDirectReportModal()
                      }
                    }
                  },
                  [
                    _vm._v(
                      _vm._s(
                        _vm.$t("employee.hierarchy_modal_add_direct_report")
                      )
                    )
                  ]
                )
              ])
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _vm.modal == "manager"
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModals,
                expression: "toggleModals"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.search($event)
                  }
                }
              },
              [
                _c("div", { staticClass: "mb3 relative" }, [
                  _c("p", [
                    _vm._v(
                      _vm._s(
                        _vm.$t("employee.hierarchy_modal_add_manager_search", {
                          name: _vm.employee.first_name
                        })
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "relative" },
                    [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.searchTerm,
                            expression: "form.searchTerm"
                          }
                        ],
                        ref: "search",
                        staticClass:
                          "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                        attrs: {
                          id: "search",
                          type: "text",
                          name: "search",
                          placeholder: _vm.$t(
                            "employee.hierarchy_search_placeholder"
                          ),
                          required: "",
                          "data-cy": "search-manager"
                        },
                        domProps: { value: _vm.form.searchTerm },
                        on: {
                          keyup: _vm.search,
                          keydown: function($event) {
                            if (
                              !$event.type.indexOf("key") &&
                              _vm._k($event.keyCode, "esc", 27, $event.key, [
                                "Esc",
                                "Escape"
                              ])
                            ) {
                              return null
                            }
                            return _vm.toggleModals()
                          },
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(
                              _vm.form,
                              "searchTerm",
                              $event.target.value
                            )
                          }
                        }
                      }),
                      _vm._v(" "),
                      _vm.processingSearch
                        ? _c("ball-pulse-loader", {
                            attrs: { color: "#5c7575", size: "7px" }
                          })
                        : _vm._e()
                    ],
                    1
                  )
                ])
              ]
            ),
            _vm._v(" "),
            _c("ul", { staticClass: "pl0 list ma0" }, [
              _c("li", { staticClass: "fw5 mb3" }, [
                _c("span", { staticClass: "f6 mb2 dib" }, [
                  _vm._v(_vm._s(_vm.$t("employee.hierarchy_search_results")))
                ]),
                _vm._v(" "),
                _vm.searchManagers.length > 0
                  ? _c(
                      "ul",
                      { staticClass: "list ma0 pl0" },
                      _vm._l(_vm.searchManagers, function(manager) {
                        return _c(
                          "li",
                          {
                            key: manager.id,
                            staticClass:
                              "bb relative pv2 ph1 bb-gray bb-gray-hover"
                          },
                          [
                            _vm._v(
                              "\n            " +
                                _vm._s(manager.name) +
                                "\n            "
                            ),
                            _c(
                              "a",
                              {
                                staticClass: "absolute right-1 pointer",
                                attrs: {
                                  "data-cy": "potential-manager-button"
                                },
                                on: {
                                  click: function($event) {
                                    $event.preventDefault()
                                    return _vm.assignManager(manager)
                                  }
                                }
                              },
                              [_vm._v(_vm._s(_vm.$t("app.choose")))]
                            )
                          ]
                        )
                      }),
                      0
                    )
                  : _c("div", { staticClass: "silver" }, [
                      _vm._v(
                        "\n          " +
                          _vm._s(_vm.$t("app.no_results")) +
                          "\n        "
                      )
                    ])
              ])
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _vm.modal == "directReport"
      ? _c(
          "div",
          {
            directives: [
              {
                name: "click-outside",
                rawName: "v-click-outside",
                value: _vm.toggleModals,
                expression: "toggleModals"
              }
            ],
            staticClass:
              "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
            _c(
              "form",
              {
                on: {
                  submit: function($event) {
                    $event.preventDefault()
                    return _vm.search($event)
                  }
                }
              },
              [
                _c("div", { staticClass: "mb3 relative" }, [
                  _c("p", [
                    _vm._v(
                      _vm._s(
                        _vm.$t(
                          "employee.hierarchy_modal_add_direct_report_search",
                          { name: _vm.employee.first_name }
                        )
                      )
                    )
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "relative" },
                    [
                      _c("input", {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.form.searchTerm,
                            expression: "form.searchTerm"
                          }
                        ],
                        ref: "search",
                        staticClass:
                          "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                        attrs: {
                          id: "search",
                          type: "text",
                          name: "search",
                          placeholder: _vm.$t(
                            "employee.hierarchy_search_placeholder"
                          ),
                          required: "",
                          "data-cy": "search-direct-report"
                        },
                        domProps: { value: _vm.form.searchTerm },
                        on: {
                          keyup: _vm.search,
                          keydown: function($event) {
                            if (
                              !$event.type.indexOf("key") &&
                              _vm._k($event.keyCode, "esc", 27, $event.key, [
                                "Esc",
                                "Escape"
                              ])
                            ) {
                              return null
                            }
                            return _vm.toggleModals()
                          },
                          input: function($event) {
                            if ($event.target.composing) {
                              return
                            }
                            _vm.$set(
                              _vm.form,
                              "searchTerm",
                              $event.target.value
                            )
                          }
                        }
                      }),
                      _vm._v(" "),
                      _vm.processingSearch
                        ? _c("ball-pulse-loader", {
                            attrs: { color: "#5c7575", size: "7px" }
                          })
                        : _vm._e()
                    ],
                    1
                  )
                ])
              ]
            ),
            _vm._v(" "),
            _c("ul", { staticClass: "pl0 list ma0" }, [
              _c("li", { staticClass: "fw5 mb3" }, [
                _c("span", { staticClass: "f6 mb2 dib" }, [
                  _vm._v(_vm._s(_vm.$t("employee.hierarchy_search_results")))
                ]),
                _vm._v(" "),
                _vm.searchDirectReports.length > 0
                  ? _c(
                      "ul",
                      { staticClass: "list ma0 pl0" },
                      _vm._l(_vm.searchDirectReports, function(directReport) {
                        return _c(
                          "li",
                          {
                            key: directReport.id,
                            staticClass:
                              "bb relative pv2 ph1 bb-gray bb-gray-hover"
                          },
                          [
                            _vm._v(
                              "\n            " +
                                _vm._s(directReport.name) +
                                "\n            "
                            ),
                            _c(
                              "a",
                              {
                                staticClass: "absolute right-1 pointer",
                                attrs: {
                                  "data-cy": "potential-direct-report-button"
                                },
                                on: {
                                  click: function($event) {
                                    $event.preventDefault()
                                    return _vm.assignDirectReport(directReport)
                                  }
                                }
                              },
                              [_vm._v(_vm._s(_vm.$t("app.choose")))]
                            )
                          ]
                        )
                      }),
                      0
                    )
                  : _c("div", { staticClass: "silver" }, [
                      _vm._v(
                        "\n          " +
                          _vm._s(_vm.$t("app.no_results")) +
                          "\n        "
                      )
                    ])
              ])
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _c("div", { staticClass: "br3 bg-white box z-1 pa3 list-employees" }, [
      _vm.managers.length == 0 && _vm.directReports.length == 0
        ? _c("p", { staticClass: "lh-copy mb0 f6" }, [
            _vm._v(
              "\n      " + _vm._s(_vm.$t("employee.hierarchy_blank")) + "\n    "
            )
          ])
        : _vm._e(),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.managers.length != 0,
              expression: "managers.length != 0"
            }
          ],
          attrs: { "data-cy": "list-managers" }
        },
        [
          _c("p", { staticClass: "mt0 mb2 f6" }, [
            _vm._v(
              "\n        " +
                _vm._s(
                  _vm.$tc(
                    "employee.hierarchy_list_manager_title",
                    _vm.managers.length
                  )
                ) +
                "\n      "
            )
          ]),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "list mv0" },
            _vm._l(_vm.managers, function(manager) {
              return _c(
                "li",
                { key: manager.id, staticClass: "mb3 relative" },
                [
                  _c("img", {
                    staticClass: "br-100 absolute avatar",
                    attrs: { src: manager.avatar }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "mb2",
                      attrs: {
                        href: "/" + _vm.company.id + "/employees/" + manager.id
                      }
                    },
                    [_vm._v(_vm._s(manager.name))]
                  ),
                  _vm._v(" "),
                  manager.position !== null
                    ? _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(manager.position.title))
                      ])
                    : _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(_vm.$t("app.no_position_defined")))
                      ]),
                  _vm._v(" "),
                  _c("img", {
                    staticClass:
                      "absolute right-0 pointer list-employees-action",
                    attrs: {
                      src: "/img/common/triple-dots.svg",
                      "data-cy": "display-remove-manager-modal"
                    },
                    on: {
                      click: function($event) {
                        _vm.managerModalId = manager.id
                      }
                    }
                  }),
                  _vm._v(" "),
                  _vm.managerModalId == manager.id
                    ? _c(
                        "div",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value: _vm.user.permission_level <= 200,
                              expression: "user.permission_level <= 200"
                            },
                            {
                              name: "click-outside",
                              rawName: "v-click-outside",
                              value: _vm.hideManagerModal,
                              expression: "hideManagerModal"
                            }
                          ],
                          staticClass:
                            "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal"
                        },
                        [
                          _c("ul", { staticClass: "list ma0 pa0" }, [
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: !_vm.deleteEmployeeConfirmation,
                                    expression: "!deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2 relative"
                              },
                              [
                                _c("icon-delete", {
                                  attrs: {
                                    classes: "icon-delete relative",
                                    width: 15,
                                    height: 15
                                  }
                                }),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer ml1 c-delete",
                                    attrs: {
                                      "data-cy": "remove-manager-button"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = true
                                      }
                                    }
                                  },
                                  [
                                    _vm._v(
                                      _vm._s(
                                        _vm.$t(
                                          "employee.hierarchy_modal_remove_manager"
                                        )
                                      )
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: _vm.deleteEmployeeConfirmation,
                                    expression: "deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2"
                              },
                              [
                                _vm._v(
                                  "\n                " +
                                    _vm._s(_vm.$t("app.sure")) +
                                    "\n                "
                                ),
                                _c(
                                  "a",
                                  {
                                    staticClass: "c-delete mr1 pointer",
                                    attrs: {
                                      "data-cy": "confirm-remove-manager"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.unassignManager(manager)
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.yes")))]
                                ),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer",
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = false
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.no")))]
                                )
                              ]
                            )
                          ])
                        ]
                      )
                    : _vm._e()
                ]
              )
            }),
            0
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.directReports.length != 0,
              expression: "directReports.length != 0"
            }
          ],
          class: _vm.managers.length != 0 ? "mt3" : "",
          attrs: { "data-cy": "list-direct-reports" }
        },
        [
          _c("p", { staticClass: "mt0 mb2 f6" }, [
            _vm._v(
              "\n        " +
                _vm._s(
                  _vm.$tc(
                    "employee.hierarchy_list_direct_report_title",
                    _vm.directReports.length
                  )
                ) +
                "\n      "
            )
          ]),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "list mv0" },
            _vm._l(_vm.directReports, function(directReport) {
              return _c(
                "li",
                { key: directReport.id, staticClass: "mb3 relative" },
                [
                  _c("img", {
                    staticClass: "br-100 absolute avatar",
                    attrs: { src: directReport.avatar }
                  }),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "mb2",
                      attrs: {
                        href:
                          "/" + _vm.company.id + "/employees/" + directReport.id
                      }
                    },
                    [_vm._v(_vm._s(directReport.name))]
                  ),
                  _vm._v(" "),
                  directReport.position !== null
                    ? _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(directReport.position.title))
                      ])
                    : _c("span", { staticClass: "title db f7 mt1" }, [
                        _vm._v(_vm._s(_vm.$t("app.no_position_defined")))
                      ]),
                  _vm._v(" "),
                  _c("img", {
                    staticClass:
                      "absolute right-0 pointer list-employees-action",
                    attrs: {
                      src: "/img/common/triple-dots.svg",
                      "data-cy": "display-remove-directreport-modal"
                    },
                    on: {
                      click: function($event) {
                        _vm.directReportModalId = directReport.id
                      }
                    }
                  }),
                  _vm._v(" "),
                  _vm.directReportModalId == directReport.id
                    ? _c(
                        "div",
                        {
                          directives: [
                            {
                              name: "show",
                              rawName: "v-show",
                              value: _vm.user.permission_level <= 200,
                              expression: "user.permission_level <= 200"
                            },
                            {
                              name: "click-outside",
                              rawName: "v-click-outside",
                              value: _vm.hideDirectReportModal,
                              expression: "hideDirectReportModal"
                            }
                          ],
                          staticClass:
                            "popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn list-employees-modal"
                        },
                        [
                          _c("ul", { staticClass: "list ma0 pa0" }, [
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: !_vm.deleteEmployeeConfirmation,
                                    expression: "!deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2 relative"
                              },
                              [
                                _c("icon-delete", {
                                  attrs: {
                                    classes: "icon-delete relative",
                                    width: 15,
                                    height: 15
                                  }
                                }),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer ml1 c-delete",
                                    attrs: {
                                      "data-cy": "remove-directreport-button"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = true
                                      }
                                    }
                                  },
                                  [
                                    _vm._v(
                                      _vm._s(
                                        _vm.$t(
                                          "employee.hierarchy_modal_remove_direct_report"
                                        )
                                      )
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "li",
                              {
                                directives: [
                                  {
                                    name: "show",
                                    rawName: "v-show",
                                    value: _vm.deleteEmployeeConfirmation,
                                    expression: "deleteEmployeeConfirmation"
                                  }
                                ],
                                staticClass: "pv2"
                              },
                              [
                                _vm._v(
                                  "\n                " +
                                    _vm._s(_vm.$t("app.sure")) +
                                    "\n                "
                                ),
                                _c(
                                  "a",
                                  {
                                    staticClass: "c-delete mr1 pointer",
                                    attrs: {
                                      "data-cy": "confirm-remove-directreport"
                                    },
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        return _vm.unassignDirectReport(
                                          directReport
                                        )
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.yes")))]
                                ),
                                _vm._v(" "),
                                _c(
                                  "a",
                                  {
                                    staticClass: "pointer",
                                    on: {
                                      click: function($event) {
                                        $event.preventDefault()
                                        _vm.deleteEmployeeConfirmation = false
                                      }
                                    }
                                  },
                                  [_vm._v(_vm._s(_vm.$t("app.no")))]
                                )
                              ]
                            )
                          ])
                        ]
                      )
                    : _vm._e()
                ]
              )
            }),
            0
          )
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v("\n          ...\n        ")
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                {
                  attrs: {
                    href:
                      "/" + _vm.company.id + "/employees/" + _vm.employee.id,
                    "data-cy": "breadcrumb-employee"
                  }
                },
                [_vm._v(_vm._s(_vm.employee.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _vm._v(
                "\n          " +
                  _vm._s(_vm.$t("app.breadcrumb_employee_logs")) +
                  "\n        "
              )
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "mw7 center br3 mb5 bg-white box relative z-1" },
        [
          _c("div", { staticClass: "pa3 relative pt5" }, [
            _c("img", {
              staticClass: "avatar absolute br-100 db center",
              attrs: { src: _vm.employee.avatar }
            }),
            _vm._v(" "),
            _c("h2", { staticClass: "tc normal mb4" }, [
              _vm._v(
                "\n          Everything that ever happened to " +
                  _vm._s(_vm.employee.first_name) +
                  "\n        "
              )
            ]),
            _vm._v(" "),
            _c(
              "ul",
              {
                staticClass: "list pl0 mt0 center",
                attrs: { "data-cy": "logs-list" }
              },
              _vm._l(_vm.logs, function(log) {
                return _c(
                  "li",
                  {
                    key: log.id,
                    staticClass:
                      "flex items-center lh-copy pa2-l pa1 ph0-l bb b--black-10"
                  },
                  [
                    _c("div", { staticClass: "flex-auto" }, [
                      _c("span", {
                        staticClass: "db",
                        domProps: { innerHTML: _vm._s(log.sentence) }
                      }),
                      _vm._v(" "),
                      _c("span", {
                        staticClass: "db f6",
                        domProps: { innerHTML: _vm._s(log.date) }
                      })
                    ])
                  ]
                )
              }),
              0
            ),
            _vm._v(" "),
            _c("div", { staticClass: "center cf" }, [
              _c(
                "a",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.paginator.previousPageUrl,
                      expression: "paginator.previousPageUrl"
                    }
                  ],
                  staticClass: "fl dib",
                  attrs: {
                    href: _vm.paginator.previousPageUrl,
                    title: "Previous"
                  }
                },
                [_vm._v("← " + _vm._s(_vm.$t("app.previous")))]
              ),
              _vm._v(" "),
              _c(
                "a",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.paginator.nextPageUrl,
                      expression: "paginator.nextPageUrl"
                    }
                  ],
                  staticClass: "fr dib",
                  attrs: { href: _vm.paginator.nextPageUrl, title: "Next" }
                },
                [_vm._v(_vm._s(_vm.$t("app.next")) + " →")]
              )
            ])
          ])
        ]
      )
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph5-ns" }, [
      _c(
        "div",
        {
          staticClass:
            "mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2"
        },
        [
          _c("ul", { staticClass: "list ph0 tc-l tl" }, [
            _c("li", { staticClass: "di" }, [
              _c(
                "a",
                { attrs: { href: "/" + _vm.company.id + "/dashboard" } },
                [_vm._v(_vm._s(_vm.company.name))]
              )
            ]),
            _vm._v(" "),
            _c("li", { staticClass: "di" }, [
              _c("a", { attrs: { href: "/" + _vm.company.id + "/teams" } }, [
                _vm._v(_vm._s(_vm.$t("app.breadcrumb_team_list")))
              ])
            ]),
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
              "This team has " +
                _vm._s(_vm.employeeCount) +
                " members, the most recent being "
            ),
            _c("a", { attrs: { href: "" } }, [
              _vm._v(_vm._s(_vm.mostRecentEmployee.name))
            ]),
            _vm._v(".")
          ]),
          _vm._v(" "),
          _c("p", { staticClass: "ma0" }, [
            _c("a", { attrs: { href: "" } }, [_vm._v("View team members")])
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "bg-white box pa3 mb4" }, [
          _c("p", { staticClass: "ma0 mb2" }, [
            _vm._v("Want to find out how someone in this team can help you?")
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
          _c("p", { staticClass: "f6 ma0 mb1" }, [_vm._v("2 days ago")]),
          _vm._v(" "),
          _c("p", { staticClass: "lh-copy ma0 mb2" }, [
            _vm._v(
              "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor."
            )
          ]),
          _vm._v(" "),
          _c("p", { staticClass: "ma0" }, [
            _c("a", { attrs: { href: "" } }, [_vm._v("Read all the news")])
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "bg-white box pa3" }, [
          _c("p", { staticClass: "ma0 mb2" }, [_vm._v("New to the team?")]),
          _vm._v(" "),
          _c("p", { staticClass: "ma0" }, [
            _c("a", { attrs: { href: "" } }, [_vm._v("Start here")])
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/company/dashboard/ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************/
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
  return _c("layout", { attrs: { title: "Home", user: _vm.user } }, [
    _c("div", { staticClass: "ph2 ph0-ns" }, [
      _c("div", { staticClass: "cf mt4 mw7 center" }, [
        _c("h2", { staticClass: "tc fw5" }, [
          _vm._v("\n        " + _vm._s(_vm.company.name) + "\n      ")
        ])
      ]),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.user.permission_level == _vm.ownerPermissionLevel,
              expression: "user.permission_level == ownerPermissionLevel"
            }
          ],
          staticClass: "cf mw7 center br3 mb3 bg-white box"
        },
        [
          _c("div", { staticClass: "pa3 relative" }, [
            _c("p", { staticClass: "b" }, [
              _vm._v(
                "\n          Would you like to fill your account with fake data?\n        "
              )
            ]),
            _vm._v(" "),
            _c("p", { staticClass: "measure" }, [
              _vm._v(
                "\n          This will let you play with an account with a lot of data. You will be able to remove them at any time to start fresh.\n        "
              )
            ]),
            _vm._v(" "),
            _c("img", {
              staticClass: "dummy w-25 absolute",
              attrs: { src: "/img/company/account/fake-data.png" }
            }),
            _vm._v(" "),
            _c("ul", { staticClass: "list pa0 ma0" }, [
              _c(
                "li",
                { staticClass: "di pr2" },
                [
                  _c("loading-button", {
                    attrs: {
                      classes: "btn add w-auto-ns w-100 mb2 pv2 ph3",
                      state: _vm.loadingState,
                      text: "generate"
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _c("li", { staticClass: "di" }, [
                _c("a", { attrs: { href: "" } }, [
                  _vm._v("Dismiss this message")
                ])
              ])
            ])
          ])
        ]
      ),
      _vm._v(" "),
      _c("div", { staticClass: "cf mw7 center br3 mb3 bg-white box" }, [
        _c("div", { staticClass: "pa3" }, [
          _c("ul", [
            _c("li", [
              _c("a", { attrs: { href: "/" + _vm.company.id + "/account" } }, [
                _vm._v("Access Adminland")
              ])
            ]),
            _vm._v(" "),
            _c("li", [_vm._v("latest news")]),
            _vm._v(" "),
            _c("li", [_vm._v("hr: expense overview")]),
            _vm._v(" "),
            _c("li", [_vm._v("hr: view all teams")]),
            _vm._v(" "),
            _c("li", [_vm._v("view company morale")]),
            _vm._v(" "),
            _c("li", [_vm._v("view all employees")]),
            _vm._v(" "),
            _c("li", [_vm._v("menu de la semaine")]),
            _vm._v(" "),
            _c("li", [_vm._v("Mise en avant random d'un employé")])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
        _c("div", { staticClass: "pa3" }, [
          _c("h2", [_vm._v("Team")]),
          _vm._v(" "),
          _c("ul", [
            _c("li", [_vm._v("team agenda")]),
            _vm._v(" "),
            _c("li", [_vm._v("anniversaires")]),
            _vm._v(" "),
            _c("li", [_vm._v("latest news")]),
            _vm._v(" "),
            _c("li", [_vm._v("manager: view time off requests")]),
            _vm._v(" "),
            _c("li", [_vm._v("manager: view morale")]),
            _vm._v(" "),
            _c("li", [_vm._v("manager: expense approval")]),
            _vm._v(" "),
            _c("li", [_vm._v("manager: one on one")])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "cf mt4 mw7 center br3 mb3 bg-white box" }, [
        _c("div", { staticClass: "pa3" }, [
          _c("h2", [_vm._v("Me")]),
          _vm._v(" "),
          _c("ul", [
            _c("li", [_vm._v("View holidays")]),
            _vm._v(" "),
            _c("li", [_vm._v("Book time off")]),
            _vm._v(" "),
            _c("li", [_vm._v("Log morale")]),
            _vm._v(" "),
            _c("li", [_vm._v("Reply to what you've done this week")]),
            _vm._v(" "),
            _c("li", [_vm._v("Log an expense")]),
            _vm._v(" "),
            _c("li", [_vm._v("View one on ones")])
          ])
        ])
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/form/LoadingButton.vue?vue&type=template&id=48e2f524&":
/*!*********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/form/LoadingButton.vue?vue&type=template&id=48e2f524& ***!
  \*********************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "di" }, [
    _c(
      "button",
      {
        class: _vm.classes,
        attrs: { name: "save", type: "submit", "data-cy": _vm.cypressSelector }
      },
      [
        _vm.state == "loading"
          ? _c("ball-pulse-loader", {
              attrs: { color: "#218b8a", size: "7px" }
            })
          : _vm._e(),
        _vm._v(" "),
        _vm.state != "loading"
          ? _c("span", [_vm._v(_vm._s(_vm.text))])
          : _vm._e()
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/header/HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************/
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
  return _c("div", [
    _c(
      "a",
      {
        staticClass: "no-color no-underline relative pointer",
        attrs: { "data-cy": "header-menu" },
        on: {
          click: function($event) {
            $event.preventDefault()
            _vm.menu = !_vm.menu
          }
        }
      },
      [
        _vm._v("\n    " + _vm._s(_vm.user.email) + " "),
        _c("span", { staticClass: "dropdown-caret" })
      ]
    ),
    _vm._v(" "),
    _vm.menu == true
      ? _c(
          "div",
          {
            staticClass:
              "absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster"
          },
          [
            _c("ul", { staticClass: "list ma0 pa0" }, [
              _c("li", { staticClass: "pv2" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color no-underline",
                    attrs: { href: "/home", "data-cy": "switch-company-button" }
                  },
                  [
                    _vm._v(
                      "\n          " +
                        _vm._s(_vm.$t("app.header_switch_company")) +
                        "\n        "
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color no-underline",
                    attrs: { href: "/logout", "data-cy": "logout-button" }
                  },
                  [
                    _vm._v(
                      "\n          " +
                        _vm._s(_vm.$t("app.header_logout")) +
                        "\n        "
                    )
                  ]
                )
              ])
            ])
          ]
        )
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/icons/IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************/
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
    "svg",
    {
      class: _vm.classes,
      attrs: {
        xmlns: "http://www.w3.org/2000/svg",
        width: _vm.width,
        height: _vm.height,
        viewBox: "0 0 24 24",
        fill: "none",
        stroke: "currentColor",
        "stroke-width": "2",
        "stroke-linecap": "round",
        "stroke-linejoin": "round"
      }
    },
    [
      _c("polyline", { attrs: { points: "3 6 5 6 21 6" } }),
      _vm._v(" "),
      _c("path", {
        attrs: {
          d:
            "M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
        }
      }),
      _vm._v(" "),
      _c("line", { attrs: { x1: "10", y1: "11", x2: "10", y2: "17" } }),
      _c("line", { attrs: { x1: "14", y1: "11", x2: "14", y2: "17" } })
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=template&id=dacc1fbe&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/home/Home.vue?vue&type=template&id=dacc1fbe&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************/
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
    { attrs: { title: "Home", "no-menu": false, user: _vm.user } },
    [
      _c("div", { staticClass: "ph2 ph0-ns" }, [
        _c(
          "div",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.employees.length == 0,
                expression: "employees.length == 0"
              }
            ],
            staticClass: "cf mt4 mt5-l mw7 center"
          },
          [
            _c("div", { staticClass: "fl w-100 w-25-m w-50-l pr2-l" }, [
              _c("a", { attrs: { href: "/company/create" } }, [
                _c("div", { staticClass: "pa3-l" }, [
                  _c(
                    "div",
                    {
                      staticClass:
                        "br3 mb3 bg-white box pa3 tc relative home-box",
                      attrs: { "data-cy": "create-company-blank-state" }
                    },
                    [
                      _c("h3", [_vm._v(_vm._s(_vm.$t("home.create_company")))]),
                      _vm._v(" "),
                      _c("p", [
                        _vm._v(_vm._s(_vm.$t("home.create_company_desc")))
                      ]),
                      _vm._v(" "),
                      _c("img", {
                        staticClass: "home-company absolute",
                        attrs: { src: "/img/home/create-company.png" }
                      })
                    ]
                  )
                ])
              ])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "fl w-100 w-25-m w-50-l" }, [
              _c("a", { attrs: { href: "/company/create" } }, [
                _c("div", { staticClass: "pa3-l" }, [
                  _c(
                    "div",
                    {
                      staticClass:
                        "br3 mb3 bg-white box pa3 tc relative home-box"
                    },
                    [
                      _c("h3", [_vm._v(_vm._s(_vm.$t("home.join_company")))]),
                      _vm._v(" "),
                      _c("p", [
                        _vm._v(_vm._s(_vm.$t("home.join_company_desc")))
                      ]),
                      _vm._v(" "),
                      _c("img", {
                        staticClass: "home-join absolute",
                        attrs: { src: "/img/home/join-company.png" }
                      })
                    ]
                  )
                ])
              ])
            ])
          ]
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.employees.length != 0,
                expression: "employees.length != 0"
              }
            ]
          },
          [
            _c(
              "div",
              { staticClass: "mt4 mt5-l mw7 center section-btn relative" },
              [
                _c("p", [
                  _c("span", { staticClass: "pr2" }, [
                    _vm._v(_vm._s(_vm.$t("home.companies_part_of")))
                  ]),
                  _vm._v(" "),
                  _c(
                    "a",
                    {
                      staticClass: "btn primary absolute db-l dn",
                      attrs: { href: "/company/create" }
                    },
                    [_vm._v(_vm._s(_vm.$t("home.create_company_cta")))]
                  )
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "cf mt4 mw7 center" },
              _vm._l(_vm.employees, function(employee) {
                return _c(
                  "div",
                  {
                    key: employee.id,
                    staticClass: "fl w-100 w-25-m w-third-l pr2"
                  },
                  [
                    _c(
                      "a",
                      {
                        attrs: {
                          href: "/" + employee.company_id + "/dashboard"
                        }
                      },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "br3 mb3 bg-white box pa3 home-index-company fw5 relative"
                          },
                          [
                            _vm._v(
                              "\n              " +
                                _vm._s(employee.company_name) +
                                "\n              "
                            ),
                            _c("span", { staticClass: "absolute normal f6" }, [
                              _vm._v(
                                _vm._s(
                                  _vm.$tc(
                                    "home.number_of_employees",
                                    employee.number_of_employees,
                                    { count: employee.number_of_employees }
                                  )
                                )
                              )
                            ])
                          ]
                        )
                      ]
                    )
                  ]
                )
              }),
              0
            ),
            _vm._v(" "),
            _c("div", { staticClass: "w-100 dn-ns db mt2" }, [
              _c(
                "a",
                {
                  staticClass:
                    "btn-primary br3 pa3 white no-underline bb-0 db tc",
                  attrs: { href: "/company/create" }
                },
                [_vm._v(_vm._s(_vm.$t("home.create_company_cta")))]
              )
            ])
          ]
        )
      ])
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Errors.vue?vue&type=template&id=45e1651a&":
/*!*******************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/partials/Errors.vue?vue&type=template&id=45e1651a& ***!
  \*******************************************************************************************************************************************************************************************************/
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
  return _vm.errors.length > 0
    ? _c(
        "div",
        [
          _c("p", [_vm._v("app.error_title")]),
          _vm._v(" "),
          _c("br"),
          _vm._v(" "),
          _vm.errors[0] != "The given data was invalid."
            ? _c("p", [_vm._v("\n    " + _vm._s(_vm.errors[0]) + "\n  ")])
            : _vm._e(),
          _vm._v(" "),
          _vm._l(_vm.errors[1], function(errorsList) {
            return _c(
              "ul",
              { key: errorsList.id },
              _vm._l(errorsList, function(error) {
                return _c("li", { key: error.id }, [
                  _vm._v("\n      " + _vm._s(error) + "\n    ")
                ])
              }),
              0
            )
          })
        ],
        2
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=template&id=10a60d4e&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/partials/Layout.vue?vue&type=template&id=10a60d4e&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
    "div",
    [
      _c("vue-snotify"),
      _vm._v(" "),
      _c("header", { staticClass: "bg-white dn db-m db-l mb3 relative" }, [
        _c("div", { staticClass: "ph3 pt1 w-100" }, [
          _c("div", { staticClass: "cf" }, [
            _vm._m(0),
            _vm._v(" "),
            _c("div", { staticClass: "fl w-60 tc" }, [
              _c("div", {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.noMenu,
                    expression: "noMenu"
                  }
                ],
                staticClass: "dib w-100"
              }),
              _vm._v(" "),
              _c(
                "ul",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: !_vm.noMenu,
                      expression: "!noMenu"
                    }
                  ],
                  staticClass: "mv2"
                },
                [
                  _c(
                    "li",
                    { staticClass: "di header-menu-item pa2 pointer mr2" },
                    [
                      _c("span", { staticClass: "fw5" }, [
                        _c("img", {
                          staticClass: "relative",
                          attrs: { src: "/img/header/icon-home.svg" }
                        }),
                        _vm._v(
                          "\n                " +
                            _vm._s(_vm.$t("app.header_home")) +
                            "\n              "
                        )
                      ])
                    ]
                  ),
                  _vm._v(" "),
                  _c(
                    "li",
                    {
                      staticClass: "di header-menu-item pa2 pointer",
                      attrs: { "data-cy": "header-find-link" },
                      on: { click: _vm.showFindModal }
                    },
                    [
                      _c("span", { staticClass: "fw5" }, [
                        _c("img", {
                          staticClass: "relative",
                          attrs: { src: "/img/header/icon-find.svg" }
                        }),
                        _vm._v(
                          "\n                " +
                            _vm._s(_vm.$t("app.header_find")) +
                            "\n              "
                        )
                      ])
                    ]
                  )
                ]
              )
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "fl w-20 pa2 tr relative header-menu-settings" },
              [_c("header-menu", { attrs: { user: _vm.user } })],
              1
            )
          ])
        ]),
        _vm._v(" "),
        _c(
          "div",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.modalFind,
                expression: "modalFind"
              }
            ],
            staticClass: "absolute z-max find-box"
          },
          [
            _c(
              "div",
              { staticClass: "br2 bg-white tl pv3 ph3 bounceIn faster" },
              [
                _c(
                  "form",
                  {
                    on: {
                      submit: function($event) {
                        $event.preventDefault()
                        return _vm.submit($event)
                      }
                    }
                  },
                  [
                    _c(
                      "div",
                      { staticClass: "relative" },
                      [
                        _c("input", {
                          directives: [
                            {
                              name: "model",
                              rawName: "v-model",
                              value: _vm.form.searchTerm,
                              expression: "form.searchTerm"
                            }
                          ],
                          ref: "search",
                          staticClass:
                            "br2 f5 w-100 ba b--black-40 pa2 outline-0",
                          attrs: {
                            id: "search",
                            type: "text",
                            name: "search",
                            placeholder: _vm.$t(
                              "app.header_search_placeholder"
                            ),
                            required: ""
                          },
                          domProps: { value: _vm.form.searchTerm },
                          on: {
                            keydown: function($event) {
                              if (
                                !$event.type.indexOf("key") &&
                                _vm._k($event.keyCode, "esc", 27, $event.key, [
                                  "Esc",
                                  "Escape"
                                ])
                              ) {
                                return null
                              }
                              _vm.modalFind = false
                            },
                            input: function($event) {
                              if ($event.target.composing) {
                                return
                              }
                              _vm.$set(
                                _vm.form,
                                "searchTerm",
                                $event.target.value
                              )
                            }
                          }
                        }),
                        _vm._v(" "),
                        _c("loading-button", {
                          attrs: {
                            classes:
                              "btn add w-auto-ns w-100 mb2 pv2 ph3 absolute top-0 right-0",
                            state: _vm.loadingState,
                            text: _vm.$t("app.search"),
                            "cypress-selector": "header-find-submit"
                          }
                        })
                      ],
                      1
                    )
                  ]
                ),
                _vm._v(" "),
                _c(
                  "ul",
                  {
                    directives: [
                      {
                        name: "show",
                        rawName: "v-show",
                        value: _vm.dataReturnedFromSearch,
                        expression: "dataReturnedFromSearch"
                      }
                    ],
                    staticClass: "pl0 list ma0 mt3",
                    attrs: { "data-cy": "results" }
                  },
                  [
                    _c("li", { staticClass: "b mb3" }, [
                      _c("span", { staticClass: "f6 mb2 dib" }, [
                        _vm._v(_vm._s(_vm.$t("app.header_search_employees")))
                      ]),
                      _vm._v(" "),
                      _vm.employees.length > 0
                        ? _c(
                            "ul",
                            { staticClass: "list ma0 pl0" },
                            _vm._l(_vm.employees, function(employee) {
                              return _c("li", { key: employee.id }, [
                                _c(
                                  "a",
                                  {
                                    attrs: {
                                      href:
                                        "/" +
                                        employee.company.id +
                                        "/employees/" +
                                        employee.id
                                    }
                                  },
                                  [_vm._v(_vm._s(employee.name))]
                                )
                              ])
                            }),
                            0
                          )
                        : _c("div", { staticClass: "silver" }, [
                            _vm._v(
                              "\n              " +
                                _vm._s(
                                  _vm.$t("app.header_search_no_employee_found")
                                ) +
                                "\n            "
                            )
                          ])
                    ]),
                    _vm._v(" "),
                    _c("li", { staticClass: "fw5" }, [
                      _c("span", { staticClass: "f6 mb2 dib" }, [
                        _vm._v(_vm._s(_vm.$t("app.header_search_teams")))
                      ]),
                      _vm._v(" "),
                      _vm.teams.length > 0
                        ? _c(
                            "ul",
                            { staticClass: "list ma0 pl0" },
                            _vm._l(_vm.teams, function(team) {
                              return _c("li", { key: team.id }, [
                                _c(
                                  "a",
                                  {
                                    attrs: {
                                      href:
                                        "/" +
                                        team.company.id +
                                        "/teams/" +
                                        team.id
                                    }
                                  },
                                  [_vm._v(_vm._s(team.name))]
                                )
                              ])
                            }),
                            0
                          )
                        : _c("div", { staticClass: "silver" }, [
                            _vm._v(
                              "\n              " +
                                _vm._s(
                                  _vm.$t("app.header_search_no_team_found")
                                ) +
                                "\n            "
                            )
                          ])
                    ])
                  ]
                )
              ]
            )
          ]
        )
      ]),
      _vm._v(" "),
      _vm._m(1),
      _vm._v(" "),
      _c("div", { class: [_vm.modalFind ? "bg-modal-find" : ""] }),
      _vm._v(" "),
      _vm._t("default")
    ],
    2
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "fl w-20 pa2" }, [
      _c("a", { staticClass: "relative header-logo", attrs: { href: "" } }, [
        _c("img", { attrs: { src: "/img/logo.svg", height: "30" } })
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("header", { staticClass: "bg-white mobile dn-ns mb3" }, [
      _c("div", { staticClass: "ph2 pv2 w-100 relative" }, [
        _c("div", { staticClass: "pv2 relative menu-toggle" }, [
          _c(
            "label",
            { staticClass: "dib b relative", attrs: { for: "menu-toggle" } },
            [_vm._v("Menu")]
          ),
          _vm._v(" "),
          _c("input", { attrs: { id: "menu-toggle", type: "checkbox" } }),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "list pa0 mt4 mb0", attrs: { id: "mobile-menu" } },
            [
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              Home\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_people\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_journal\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_find\n            ")]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [
                    _vm._v(
                      "\n              app.main_nav_changelog\n            "
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [
                    _vm._v(
                      "\n              app.main_nav_settings\n            "
                    )
                  ]
                )
              ]),
              _vm._v(" "),
              _c("li", { staticClass: "pv2 bt b--light-gray" }, [
                _c(
                  "a",
                  {
                    staticClass: "no-color b no-underline",
                    attrs: { href: "" }
                  },
                  [_vm._v("\n              app.main_nav_signout\n            ")]
                )
              ])
            ]
          )
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "absolute pa2 header-logo" }, [
          _c("a", { attrs: { href: "" } }, [
            _c("img", {
              attrs: { src: "/img/logo/logo.svg", width: "30", height: "27" }
            })
          ])
        ])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./node_modules/vue-loaders/dist/vue-loaders.css":
/*!*******************************************************!*\
  !*** ./node_modules/vue-loaders/dist/vue-loaders.css ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../css-loader??ref--6-1!../../postcss-loader/src??ref--6-2!./vue-loaders.css */ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loaders/dist/vue-loaders.css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loaders/dist/vue-loaders.es.js":
/*!*********************************************************!*\
  !*** ./node_modules/vue-loaders/dist/vue-loaders.es.js ***!
  \*********************************************************/
/*! exports provided: install, BallBeatLoader, BallClipRotateLoader, BallClipRotateMultipleLoader, BallClipRotatePulseLoader, BallGridBeatLoader, BallGridPulseLoader, BallPulseLoader, BallPulseRiseLoader, BallPulseSyncLoader, BallRotateLoader, BallScaleLoader, BallScaleMultipleLoader, BallScaleRippleLoader, BallScaleRippleMultipleLoader, BallSpinFadeLoader, BallTrianglePathLoader, BallZigZagLoader, BallZigZagDeflectLoader, CubeTransitionLoader, LineScaleLoader, LineScalePartyLoader, LineScalePulseOutLoader, LineScalePulseOutRapidLoader, LineSpinFadeLoader, PacmanLoader, SemiCircleSpinLoader, SquareSpinLoader, TriangleSkewSpinLoader */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "install", function() { return install; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallBeatLoader", function() { return ballBeat; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallClipRotateLoader", function() { return ballClipRotate; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallClipRotateMultipleLoader", function() { return ballClipRotateMultiple; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallClipRotatePulseLoader", function() { return ballClipRotatePulse; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallGridBeatLoader", function() { return ballGridBeat; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallGridPulseLoader", function() { return ballGridPulse; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallPulseLoader", function() { return ballPulse; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallPulseRiseLoader", function() { return ballPulseRise; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallPulseSyncLoader", function() { return ballPulseSync; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallRotateLoader", function() { return ballRotate; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallScaleLoader", function() { return ballScale; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallScaleMultipleLoader", function() { return ballScaleMultiple; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallScaleRippleLoader", function() { return ballScaleRipple; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallScaleRippleMultipleLoader", function() { return ballScaleRippleMultiple; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallSpinFadeLoader", function() { return ballSpinFade; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallTrianglePathLoader", function() { return ballTrianglePath; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallZigZagLoader", function() { return ballZigZag; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "BallZigZagDeflectLoader", function() { return ballZigZagDeflect; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "CubeTransitionLoader", function() { return cubeTransition; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "LineScaleLoader", function() { return lineScale; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "LineScalePartyLoader", function() { return lineScaleParty; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "LineScalePulseOutLoader", function() { return lineScalePulseOut; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "LineScalePulseOutRapidLoader", function() { return lineScalePulseOutRapid; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "LineSpinFadeLoader", function() { return lineSpinFade; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "PacmanLoader", function() { return pacman; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SemiCircleSpinLoader", function() { return semiCircleSpin; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SquareSpinLoader", function() { return squareSpin; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TriangleSkewSpinLoader", function() { return triangleSkewSpin; });
var _isObject = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};

var _anObject = function (it) {
  if (!_isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};

var _fails = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};

// Thank's IE8 for his funny defineProperty
var _descriptors = !_fails(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});

function createCommonjsModule(fn, module) {
	return module = { exports: {} }, fn(module, module.exports), module.exports;
}

var _global = createCommonjsModule(function (module) {
// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef
});

var document = _global.document;
// typeof document.createElement is 'object' in old IE
var is = _isObject(document) && _isObject(document.createElement);
var _domCreate = function (it) {
  return is ? document.createElement(it) : {};
};

var _ie8DomDefine = !_descriptors && !_fails(function () {
  return Object.defineProperty(_domCreate('div'), 'a', { get: function () { return 7; } }).a != 7;
});

// 7.1.1 ToPrimitive(input [, PreferredType])

// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
var _toPrimitive = function (it, S) {
  if (!_isObject(it)) return it;
  var fn, val;
  if (S && typeof (fn = it.toString) == 'function' && !_isObject(val = fn.call(it))) return val;
  if (typeof (fn = it.valueOf) == 'function' && !_isObject(val = fn.call(it))) return val;
  if (!S && typeof (fn = it.toString) == 'function' && !_isObject(val = fn.call(it))) return val;
  throw TypeError("Can't convert object to primitive value");
};

var dP = Object.defineProperty;

var f = _descriptors ? Object.defineProperty : function defineProperty(O, P, Attributes) {
  _anObject(O);
  P = _toPrimitive(P, true);
  _anObject(Attributes);
  if (_ie8DomDefine) try {
    return dP(O, P, Attributes);
  } catch (e) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported!');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};

var _objectDp = {
	f: f
};

var dP$1 = _objectDp.f;
var FProto = Function.prototype;
var nameRE = /^\s*function ([^ (]*)/;
var NAME = 'name';

// 19.2.4.2 name
NAME in FProto || _descriptors && dP$1(FProto, NAME, {
  configurable: true,
  get: function () {
    try {
      return ('' + this).match(nameRE)[1];
    } catch (e) {
      return '';
    }
  }
});

var ballBeat = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-beat vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallBeatLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var BORDER_RATION = 2 / 15;
var ballClipRotate = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-clip-rotate vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallClipRotateLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.borderWidth = "calc(".concat(size, " * ").concat(BORDER_RATION, ")");
      }

      if (color) {
        styles.borderTopColor = styles.borderRightColor = styles.borderLeftColor = color;
      }

      return styles;
    }
  }
};

var BASE_SIZE_PX = '35px';
var BORDER_RATION$1 = 2 / 35;
var INNER_BALL_SIZE_RATION = 15 / 30;
var ballClipRotateMultiple = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-clip-rotate-multiple vue-loaders",
      style: _vm.rootStyles
    }, [_c('div', {
      style: _vm.outerBallStyles
    }), _vm._v(" "), _c('div', {
      style: _vm.innerBallStyles
    })]);
  },
  staticRenderFns: [],
  name: 'BallClipRotateMultipleLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyles: function rootStyles() {
      var size = this.size ? String(this.size) : BASE_SIZE_PX;
      var styles = {
        width: size,
        height: size
      };
      return styles;
    },
    outerBallStyles: function outerBallStyles() {
      var size = this.size ? String(this.size) : BASE_SIZE_PX;
      var color = this.color ? String(this.color) : null;
      var styles = {
        width: size,
        height: size,
        borderWidth: "calc(".concat(size, " * ").concat(BORDER_RATION$1, ")"),
        top: "calc(".concat(size, " * -1 * ").concat(BORDER_RATION$1, ")"),
        left: "calc(".concat(size, " * -1 * ").concat(BORDER_RATION$1, ")")
      };

      if (color) {
        styles.borderLeftColor = color;
        styles.borderRightColor = color;
      }

      return styles;
    },
    innerBallStyles: function innerBallStyles() {
      var size = this.size ? String(this.size) : BASE_SIZE_PX;
      var color = this.color ? String(this.color) : null;
      var styles = {
        width: "calc(".concat(size, " * ").concat(INNER_BALL_SIZE_RATION, ")"),
        height: "calc(".concat(size, " * ").concat(INNER_BALL_SIZE_RATION, ")"),
        borderWidth: "calc(".concat(size, " * ").concat(BORDER_RATION$1, ")"),
        top: "calc((".concat(size, " - (").concat(size, " * ").concat(INNER_BALL_SIZE_RATION, ")) / 2 - ").concat(size, " * ").concat(BORDER_RATION$1),
        left: "calc((".concat(size, " - (").concat(size, " * ").concat(INNER_BALL_SIZE_RATION, ")) / 2 - ").concat(size, " * ").concat(BORDER_RATION$1)
      };

      if (color) {
        styles.borderTopColor = color;
        styles.borderBottomColor = color;
      }

      return styles;
    }
  }
};

var BASE_SIZE_PX$1 = '30px';
var BORDER_RATION$2 = 2 / 30;
var INNER_BALL_SIZE_RATION$1 = 16 / 30;
var INNER_BALL_OFFSET_RATION = 7 / 30;
var ballClipRotatePulse = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-clip-rotate-pulse vue-loaders",
      style: _vm.rootStyles
    }, [_c('div', {
      style: _vm.innerBallStyles
    }), _vm._v(" "), _c('div', {
      style: _vm.outerBallStyles
    })]);
  },
  staticRenderFns: [],
  name: 'BallClipRotatePulseLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyles: function rootStyles() {
      var size = this.size ? String(this.size) : BASE_SIZE_PX$1;
      var styles = {
        width: "calc(".concat(size, " / 2)"),
        marginLeft: "calc(".concat(size, " / 2)"),
        height: size
      };
      return styles;
    },
    outerBallStyles: function outerBallStyles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.top = "calc(".concat(size, " * -1 * ").concat(BORDER_RATION$2, ")");
        styles.borderWidth = "calc(".concat(size, " * ").concat(BORDER_RATION$2, ")");
        styles.left = "calc(".concat(size, " * -1 * ").concat(INNER_BALL_SIZE_RATION$1, ")");
      }

      if (color) {
        styles.borderTopColor = color;
        styles.borderBottomColor = color;
      }

      return styles;
    },
    innerBallStyles: function innerBallStyles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = "calc(".concat(size, " * ").concat(INNER_BALL_SIZE_RATION$1, ")");
        styles.top = "calc(".concat(size, " * ").concat(INNER_BALL_OFFSET_RATION, ")");
        styles.left = "calc(".concat(size, " * -1 * ").concat(INNER_BALL_OFFSET_RATION, ")");
      }

      if (color) {
        styles.background = color;
      }

      return styles;
    }
  }
};

var ballGridBeat = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-grid-beat vue-loaders",
      style: _vm.rootStyles
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallGridBeatLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyles: function rootStyles() {
      return {
        width: "calc(3 * (".concat(this.size || '15px', " + 4px))")
      };
    },
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballGridPulse = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-grid-pulse vue-loaders",
      style: _vm.rootStyles
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallGridPulseLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyles: function rootStyles() {
      return {
        width: "calc(3 * (".concat(this.size || '15px', " + 4px))")
      };
    },
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballPulse = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-pulse vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallPulseLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballPulseRise = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-pulse-rise vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallPulseRiseLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballPulseSync = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-pulse-sync vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallPulseSyncLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var OFFSET = 10 / 15;
var ballRotate = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-rotate vue-loaders",
      style: _vm.rootStyles
    }, [_c('div', {
      style: _vm.middle
    }, [_c('div', {
      style: _vm.innerLeft
    }), _vm._v(" "), _c('div', {
      style: _vm.innerRight
    })])]);
  },
  staticRenderFns: [],
  name: 'BallRotateLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyles: function rootStyles() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: size,
          height: size,
          padding: "calc(".concat(size, " + ").concat(size, " * ").concat(OFFSET, " + 2px)")
        };
      }
    },
    middle: function middle() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    innerLeft: function innerLeft() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.left = "calc((".concat(size, " + ").concat(size, " * ").concat(OFFSET, " + 2px) * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    innerRight: function innerRight() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.left = "calc(".concat(size, " + ").concat(size, " * ").concat(OFFSET, " + 2px)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballScale = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-scale vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallScaleLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballScaleMultiple = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-scale-multiple vue-loaders",
      style: _vm.rootStyle
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallScaleMultipleLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyle: function rootStyle() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: size,
          height: size
        };
      }
    },
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballScaleRipple = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-scale-ripple vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallScaleRippleLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.borderColor = color;
      }

      return styles;
    }
  }
};

var BORDER_RATION$3 = 2 / 50;
var ballScaleRippleMultiple = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-scale-ripple-multiple vue-loaders",
      style: _vm.rootStyle
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallScaleRippleMultipleLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyle: function rootStyle() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: "calc(".concat(size, " + ").concat(size, " * ").concat(BORDER_RATION$3, ")"),
          height: "calc(".concat(size, " + ").concat(size, " * ").concat(BORDER_RATION$3, ")")
        };
      }
    },
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.borderWidth = "calc(".concat(size, " * ").concat(BORDER_RATION$3, ")");
      }

      if (color) {
        styles.borderColor = color;
      }

      return styles;
    }
  }
};

var TO_CORNER_RATIO = 17.05 / 15;
var TO_SIDE_RATIO = 25 / 15;
var ballSpinFade = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-spin-fade-loader vue-loaders",
      style: _vm.root
    }, [_c('div', {
      style: _vm.bottom
    }), _vm._v(" "), _c('div', {
      style: _vm.bottomRight
    }), _vm._v(" "), _c('div', {
      style: _vm.right
    }), _vm._v(" "), _c('div', {
      style: _vm.topRight
    }), _vm._v(" "), _c('div', {
      style: _vm.top
    }), _vm._v(" "), _c('div', {
      style: _vm.topLeft
    }), _vm._v(" "), _c('div', {
      style: _vm.left
    }), _vm._v(" "), _c('div', {
      style: _vm.bottomLeft
    })]);
  },
  staticRenderFns: [],
  name: 'BallSpinFadeLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    root: function root() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: size,
          height: size,
          borderWidth: "calc(".concat(size, " * ").concat(TO_SIDE_RATIO, ")")
        };
      }
    },
    top: function top() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.top = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    topRight: function topRight() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, " * -1)");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    right: function right() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.left = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottomRight: function bottomRight() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, ")");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottom: function bottom() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.top = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottomLeft: function bottomLeft() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, ")");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    left: function left() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.left = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    topLeft: function topLeft() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, " * -1)");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var BORDER_RATION$4 = 1 / 10;
var ballTrianglePath = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-triangle-path vue-loaders",
      style: _vm.rootStyle
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'BallTrianglePathLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyle: function rootStyle() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: "calc(".concat(size, " + 50px)"),
          height: "calc(".concat(size, " + 50px)")
        };
      }
    },
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
        styles.borderWidth = "calc(".concat(size, " * ").concat(BORDER_RATION$4, ")");
      }

      if (color) {
        styles.borderColor = color;
      }

      return styles;
    }
  }
};

var ballZigZag = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-zig-zag vue-loaders",
      style: _vm.rootStyle
    }, [_c('div', {
      style: _vm.top
    }), _vm._v(" "), _c('div', {
      style: _vm.bottom
    })]);
  },
  staticRenderFns: [],
  name: 'BallZigZagLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyle: function rootStyle() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: size,
          height: size
        };
      }
    },
    top: function top() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottom: function bottom() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var ballZigZagDeflect = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "ball-zig-zag-deflect vue-loaders",
      style: _vm.rootStyle
    }, [_c('div', {
      style: _vm.top
    }), _vm._v(" "), _c('div', {
      style: _vm.bottom
    })]);
  },
  staticRenderFns: [],
  name: 'BallZigZagDeflectLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyle: function rootStyle() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: size,
          height: size
        };
      }
    },
    top: function top() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottom: function bottom() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var cubeTransition = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "cube-transition vue-loaders",
      style: _vm.rootStyle
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'CubeTransitionLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    rootStyle: function rootStyle() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: "calc(".concat(size, " + 50px)"),
          height: "calc(".concat(size, " + 50px)")
        };
      }
    },
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var WIDTH_RATIO = 4 / 35;
var BORDER_RATIO = 2 / 4;
var lineScale = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "line-scale vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'LineScaleLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO, ")");
        styles.height = size;
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var WIDTH_RATIO$1 = 4 / 35;
var BORDER_RATIO$1 = 2 / 4;
var lineScaleParty = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "line-scale-party vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'LineScalePartyLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$1, ")");
        styles.height = size;
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$1, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var WIDTH_RATIO$2 = 4 / 35;
var BORDER_RATIO$2 = 2 / 4;
var lineScalePulseOut = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "line-scale-pulse-out vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'LineScalePulseOutLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$2, ")");
        styles.height = size;
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$2, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var WIDTH_RATIO$3 = 4 / 35;
var BORDER_RATIO$3 = 2 / 4;
var lineScalePulseOutRapid = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "line-scale-pulse-out-rapid vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    }), _vm._v(" "), _c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'LineScalePulseOutRapidLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$3, ")");
        styles.height = size;
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$3, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var TO_CORNER_RATIO$1 = 13.64 / 15;
var TO_SIDE_RATIO$1 = 20 / 15;
var WIDTH_RATIO$4 = 5 / 15;
var BORDER_RATIO$4 = 2 / 15;
var lineSpinFade = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "line-spin-fade-loader vue-loaders",
      style: _vm.root
    }, [_c('div', {
      style: _vm.bottom
    }), _vm._v(" "), _c('div', {
      style: _vm.bottomRight
    }), _vm._v(" "), _c('div', {
      style: _vm.right
    }), _vm._v(" "), _c('div', {
      style: _vm.topRight
    }), _vm._v(" "), _c('div', {
      style: _vm.top
    }), _vm._v(" "), _c('div', {
      style: _vm.topLeft
    }), _vm._v(" "), _c('div', {
      style: _vm.left
    }), _vm._v(" "), _c('div', {
      style: _vm.bottomLeft
    })]);
  },
  staticRenderFns: [],
  name: 'LineSpinFadeLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    root: function root() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: size,
          height: size,
          borderWidth: "calc(".concat(size, " * ").concat(TO_SIDE_RATIO$1, ")")
        };
      }
    },
    top: function top() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.top = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO$1, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    topRight: function topRight() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, " * -1)");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    right: function right() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.left = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO$1, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottomRight: function bottomRight() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, ")");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottom: function bottom() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.top = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO$1, ")");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    bottomLeft: function bottomLeft() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, ")");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    left: function left() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.left = "calc(".concat(size, " * ").concat(TO_SIDE_RATIO$1, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    },
    topLeft: function topLeft() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.height = size;
        styles.width = "calc(".concat(size, " * ").concat(WIDTH_RATIO$4, ")");
        styles.borderRadius = "calc(".concat(size, " * ").concat(BORDER_RATIO$4, ")");
        styles.top = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, " * -1)");
        styles.left = "calc(".concat(size, " * ").concat(TO_CORNER_RATIO$1, " * -1)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var CIRCLE_SIZE_RATIO = 10 / 50;
var pacman = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "pacman vue-loaders",
      style: _vm.root
    }, [_c('div', {
      style: _vm.bottom
    }), _vm._v(" "), _c('div', {
      style: _vm.top
    }), _vm._v(" "), _c('div', {
      style: _vm.circle
    }), _vm._v(" "), _c('div', {
      style: _vm.circle
    }), _vm._v(" "), _c('div', {
      style: _vm.circle
    })]);
  },
  staticRenderFns: [],
  name: 'PacmanLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    root: function root() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          borderRightWidth: "calc(70px + ".concat(CIRCLE_SIZE_RATIO, " * ").concat(size, " - ").concat(size, ")")
        };
      }
    },
    top: function top() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.borderWidth = styles.borderRadius = "calc(".concat(size, " / 2)");
        styles.marginTop = "calc(".concat(size, " * -1)");
      }

      if (color) {
        styles.borderTopColor = styles.borderLeftColor = styles.borderBottomColor = color;
      }

      return styles;
    },
    bottom: function bottom() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.borderWidth = styles.borderRadius = "calc(".concat(size, " / 2)");
      }

      if (color) {
        styles.borderTopColor = styles.borderLeftColor = styles.borderBottomColor = color;
      }

      return styles;
    },
    circle: function circle() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};
      var circleSize = "(".concat(size, " * ").concat(CIRCLE_SIZE_RATIO, ")");

      if (size) {
        styles.width = styles.height = "calc(".concat(circleSize, ")");
        styles.top = "calc(".concat(size, " * 0.5 + ").concat(circleSize, " / -2 + 6.25px)");
      }

      if (color) {
        styles.backgroundColor = color;
      }

      return styles;
    }
  }
};

var semiCircleSpin = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "semi-circle-spin vue-loaders",
      style: _vm.root
    }, [_c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'SemiCircleSpinLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    root: function root() {
      var size = this.size ? String(this.size) : null;

      if (size) {
        return {
          width: size,
          height: size
        };
      }
    },
    styles: function styles() {
      var color = this.color ? String(this.color) : null;

      if (color) {
        return {
          backgroundImage: "linear-gradient(transparent 0,transparent 70%,".concat(color, " 30%,").concat(color, " 100%)")
        };
      }
    }
  }
};

var squareSpin = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "square-spin vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'SquareSpinLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.width = styles.height = size;
      }

      if (color) {
        styles.background = color;
      }

      return styles;
    }
  }
};

var triangleSkewSpin = {
  render: function render() {
    var _vm = this;

    var _h = _vm.$createElement;

    var _c = _vm._self._c || _h;

    return _c('div', {
      staticClass: "triangle-skew-spin vue-loaders"
    }, [_c('div', {
      style: _vm.styles
    })]);
  },
  staticRenderFns: [],
  name: 'TriangleSkewSpinLoader',
  props: {
    size: String,
    color: String
  },
  computed: {
    styles: function styles() {
      var size = this.size ? String(this.size) : null;
      var color = this.color ? String(this.color) : null;

      if (!color && !size) {
        return;
      }

      var styles = {};

      if (size) {
        styles.borderLeftWidth = styles.borderRightWidth = styles.borderBottomWidth = "calc(".concat(size, " / 2)");
      }

      if (color) {
        styles.borderBottomColor = color;
      }

      return styles;
    }
  }
};

function install(Vue) {
  var compKeys = Object.keys(this).filter(function (key) {
    return key !== 'install';
  });
  var ln = compKeys.length;

  while (ln--) {
    var component = this[compKeys[ln]];
    Vue.component(component.name, component);
  }
}




/***/ }),

/***/ "./node_modules/vue-snotify/styles/simple.css":
/*!****************************************************!*\
  !*** ./node_modules/vue-snotify/styles/simple.css ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../css-loader??ref--6-1!../../postcss-loader/src??ref--6-2!./simple.css */ "./node_modules/css-loader/index.js?!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-snotify/styles/simple.css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-snotify/vue-snotify.esm.js":
/*!*****************************************************!*\
  !*** ./node_modules/vue-snotify/vue-snotify.esm.js ***!
  \*****************************************************/
/*! exports provided: SnotifyPosition, SnotifyStyle, SnotifyToast, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SnotifyPosition", function() { return SnotifyPosition; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SnotifyStyle", function() { return SnotifyStyle; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SnotifyToast", function() { return SnotifyToast; });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.runtime.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/**
 * vue-snotify v3.2.0
 * (c) 2018 artemsky <mr.artemsky@gmail.com>
 * @license MIT
 */


/**
 * Toast position
 */
var SnotifyPosition;
(function (SnotifyPosition) {
    SnotifyPosition["leftTop"] = "leftTop";
    SnotifyPosition["leftCenter"] = "leftCenter";
    SnotifyPosition["leftBottom"] = "leftBottom";
    SnotifyPosition["rightTop"] = "rightTop";
    SnotifyPosition["rightCenter"] = "rightCenter";
    SnotifyPosition["rightBottom"] = "rightBottom";
    SnotifyPosition["centerTop"] = "centerTop";
    SnotifyPosition["centerCenter"] = "centerCenter";
    SnotifyPosition["centerBottom"] = "centerBottom";
})(SnotifyPosition || (SnotifyPosition = {}));

/**
 * Toast style.
 */
var SnotifyStyle = {
    simple: 'simple',
    success: 'success',
    error: 'error',
    warning: 'warning',
    info: 'info',
    async: 'async',
    confirm: 'confirm',
    prompt: 'prompt'
};

var script = vue__WEBPACK_IMPORTED_MODULE_0___default.a.extend({
    props: ['toast'],
    data: function () {
        return {
            isPromptFocused: false,
        };
    },
    methods: {
        valueChanged: function (e) {
            this.toast.value = e.target.value;
            this.toast.eventEmitter.$emit('input');
        }
    }
});

/* script */

            const __vue_script__ = script;
            
/* template */
var __vue_render__ = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',{staticClass:"snotifyToast__input",class:{'snotifyToast__input--filled': _vm.isPromptFocused}},[_c('input',{staticClass:"snotifyToast__input__field",attrs:{"type":"text","id":_vm.toast.id},on:{"input":_vm.valueChanged,"focus":function($event){_vm.isPromptFocused = true;},"blur":function($event){_vm.isPromptFocused = !!_vm.toast.value.length;}}}),_vm._v(" "),_c('label',{staticClass:"snotifyToast__input__label",attrs:{"for":_vm.toast.id}},[_c('span',{staticClass:"snotifyToast__input__labelContent"},[_vm._v(_vm._s(_vm._f("truncate")(_vm.toast.config.placeholder)))])])])};
var __vue_staticRenderFns__ = [];

  /* style */
  const __vue_inject_styles__ = undefined;
  /* scoped */
  const __vue_scope_id__ = undefined;
  /* module identifier */
  const __vue_module_identifier__ = undefined;
  /* functional template */
  const __vue_is_functional_template__ = false;
  /* component normalizer */
  function __vue_normalize__(
    template, style, script$$1,
    scope, functional, moduleIdentifier,
    createInjector, createInjectorSSR
  ) {
    const component = (typeof script$$1 === 'function' ? script$$1.options : script$$1) || {};

    if (!component.render) {
      component.render = template.render;
      component.staticRenderFns = template.staticRenderFns;
      component._compiled = true;

      if (functional) component.functional = true;
    }

    component._scopeId = scope;

    return component
  }
  /* style inject */
  function __vue_create_injector__() {
    const head = document.head || document.getElementsByTagName('head')[0];
    const styles = __vue_create_injector__.styles || (__vue_create_injector__.styles = {});
    const isOldIE =
      typeof navigator !== 'undefined' &&
      /msie [6-9]\\b/.test(navigator.userAgent.toLowerCase());

    return function addStyle(id, css) {
      if (document.querySelector('style[data-vue-ssr-id~="' + id + '"]')) return // SSR styles are present.

      const group = isOldIE ? css.media || 'default' : id;
      const style = styles[group] || (styles[group] = { ids: [], parts: [], element: undefined });

      if (!style.ids.includes(id)) {
        let code = css.source;
        let index = style.ids.length;

        style.ids.push(id);

        if ( true && css.map) {
          // https://developer.chrome.com/devtools/docs/javascript-debugging
          // this makes source maps inside style tags work properly in Chrome
          code += '\n/*# sourceURL=' + css.map.sources[0] + ' */';
          // http://stackoverflow.com/a/26603875
          code +=
            '\n/*# sourceMappingURL=data:application/json;base64,' +
            btoa(unescape(encodeURIComponent(JSON.stringify(css.map)))) +
            ' */';
        }

        if (isOldIE) {
          style.element = style.element || document.querySelector('style[data-group=' + group + ']');
        }

        if (!style.element) {
          const el = style.element = document.createElement('style');
          el.type = 'text/css';

          if (css.media) el.setAttribute('media', css.media);
          if (isOldIE) {
            el.setAttribute('data-group', group);
            el.setAttribute('data-next-index', '0');
          }

          head.appendChild(el);
        }

        if (isOldIE) {
          index = parseInt(style.element.getAttribute('data-next-index'));
          style.element.setAttribute('data-next-index', index + 1);
        }

        if (style.element.styleSheet) {
          style.parts.push(code);
          style.element.styleSheet.cssText = style.parts
            .filter(Boolean)
            .join('\n');
        } else {
          const textNode = document.createTextNode(code);
          const nodes = style.element.childNodes;
          if (nodes[index]) style.element.removeChild(nodes[index]);
          if (nodes.length) style.element.insertBefore(textNode, nodes[index]);
          else style.element.appendChild(textNode);
        }
      }
    }
  }
  /* style inject SSR */
  

  
  var SnotifyPrompt = __vue_normalize__(
    { render: __vue_render__, staticRenderFns: __vue_staticRenderFns__ },
    __vue_inject_styles__,
    __vue_script__,
    __vue_scope_id__,
    __vue_is_functional_template__,
    __vue_module_identifier__,
    __vue_create_injector__,
    undefined
  )

var script$1 = vue__WEBPACK_IMPORTED_MODULE_0___default.a.extend({
    props: ['toast'],
    methods: {
        remove: function () {
            this.$snotify.remove(this.toast.id);
        }
    }
});

/* script */

            const __vue_script__$1 = script$1;
            
/* template */
var __vue_render__$1 = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"snotifyToast__buttons"},_vm._l((_vm.toast.config.buttons),function(button){return _c('button',{class:[{'snotifyToast__buttons--bold': button.bold}, button.className],attrs:{"type":"button"},on:{"click":function($event){$event.preventDefault();$event.stopPropagation();button.action ? button.action(_vm.toast) : _vm.remove();}}},[_vm._v("\n    "+_vm._s(button.text)+"\n  ")])}))};
var __vue_staticRenderFns__$1 = [];

  /* style */
  const __vue_inject_styles__$1 = undefined;
  /* scoped */
  const __vue_scope_id__$1 = undefined;
  /* module identifier */
  const __vue_module_identifier__$1 = undefined;
  /* functional template */
  const __vue_is_functional_template__$1 = false;
  /* component normalizer */
  function __vue_normalize__$1(
    template, style, script,
    scope, functional, moduleIdentifier,
    createInjector, createInjectorSSR
  ) {
    const component = (typeof script === 'function' ? script.options : script) || {};

    if (!component.render) {
      component.render = template.render;
      component.staticRenderFns = template.staticRenderFns;
      component._compiled = true;

      if (functional) component.functional = true;
    }

    component._scopeId = scope;

    return component
  }
  /* style inject */
  function __vue_create_injector__$1() {
    const head = document.head || document.getElementsByTagName('head')[0];
    const styles = __vue_create_injector__$1.styles || (__vue_create_injector__$1.styles = {});
    const isOldIE =
      typeof navigator !== 'undefined' &&
      /msie [6-9]\\b/.test(navigator.userAgent.toLowerCase());

    return function addStyle(id, css) {
      if (document.querySelector('style[data-vue-ssr-id~="' + id + '"]')) return // SSR styles are present.

      const group = isOldIE ? css.media || 'default' : id;
      const style = styles[group] || (styles[group] = { ids: [], parts: [], element: undefined });

      if (!style.ids.includes(id)) {
        let code = css.source;
        let index = style.ids.length;

        style.ids.push(id);

        if ( true && css.map) {
          // https://developer.chrome.com/devtools/docs/javascript-debugging
          // this makes source maps inside style tags work properly in Chrome
          code += '\n/*# sourceURL=' + css.map.sources[0] + ' */';
          // http://stackoverflow.com/a/26603875
          code +=
            '\n/*# sourceMappingURL=data:application/json;base64,' +
            btoa(unescape(encodeURIComponent(JSON.stringify(css.map)))) +
            ' */';
        }

        if (isOldIE) {
          style.element = style.element || document.querySelector('style[data-group=' + group + ']');
        }

        if (!style.element) {
          const el = style.element = document.createElement('style');
          el.type = 'text/css';

          if (css.media) el.setAttribute('media', css.media);
          if (isOldIE) {
            el.setAttribute('data-group', group);
            el.setAttribute('data-next-index', '0');
          }

          head.appendChild(el);
        }

        if (isOldIE) {
          index = parseInt(style.element.getAttribute('data-next-index'));
          style.element.setAttribute('data-next-index', index + 1);
        }

        if (style.element.styleSheet) {
          style.parts.push(code);
          style.element.styleSheet.cssText = style.parts
            .filter(Boolean)
            .join('\n');
        } else {
          const textNode = document.createTextNode(code);
          const nodes = style.element.childNodes;
          if (nodes[index]) style.element.removeChild(nodes[index]);
          if (nodes.length) style.element.insertBefore(textNode, nodes[index]);
          else style.element.appendChild(textNode);
        }
      }
    }
  }
  /* style inject SSR */
  

  
  var SnotifyButton = __vue_normalize__$1(
    { render: __vue_render__$1, staticRenderFns: __vue_staticRenderFns__$1 },
    __vue_inject_styles__$1,
    __vue_script__$1,
    __vue_scope_id__$1,
    __vue_is_functional_template__$1,
    __vue_module_identifier__$1,
    __vue_create_injector__$1,
    undefined
  )

var script$2 = vue__WEBPACK_IMPORTED_MODULE_0___default.a.extend({
    props: ['toastData'],
    components: {
        SnotifyPrompt: SnotifyPrompt,
        SnotifyButton: SnotifyButton
    },
    data: function () {
        return {
            toast: this.toastData,
            animationFrame: null,
            state: {
                paused: false,
                progress: 0,
                animation: '',
                isDestroying: false,
                promptType: SnotifyStyle.prompt
            }
        };
    },
    methods: {
        /**
         * Initialize base toast config
         */
        initToast: function () {
            if (this.toast.config.timeout > 0) {
                this.startTimeout(0);
            }
        },
        onClick: function () {
            this.toast.eventEmitter.$emit('click');
            if (this.toast.config.closeOnClick) {
                this.$snotify.remove(this.toast.id);
            }
        },
        onMouseEnter: function () {
            this.toast.eventEmitter.$emit('mouseenter');
            if (this.toast.config.pauseOnHover) {
                this.state.paused = true;
            }
        },
        onMouseLeave: function () {
            if (this.toast.config.pauseOnHover && this.toast.config.timeout) {
                this.state.paused = false;
                this.startTimeout(this.toast.config.timeout * this.state.progress);
            }
            this.toast.eventEmitter.$emit('mouseleave');
        },
        /**
         * Remove toast completely after animation
         */
        onExitTransitionEnd: function () {
            if (this.state.isDestroying) {
                return;
            }
            this.initToast();
            this.toast.eventEmitter.$emit('shown');
        },
        /**
         * Start progress bar
         * @param startTime {number}
         * @default 0
         */
        startTimeout: function (startTime) {
            var _this = this;
            if (startTime === void 0) { startTime = 0; }
            var start = performance.now();
            var calculate = function () {
                _this.animationFrame = requestAnimationFrame(function (timestamp) {
                    var runtime = timestamp + startTime - start;
                    var progress = Math.min(runtime / _this.toast.config.timeout, 1);
                    if (_this.state.paused) {
                        cancelAnimationFrame(_this.animationFrame);
                    }
                    else if (runtime < _this.toast.config.timeout) {
                        _this.state.progress = progress;
                        calculate();
                    }
                    else {
                        _this.state.progress = 1;
                        cancelAnimationFrame(_this.animationFrame);
                        _this.$snotify.emitter.$emit('remove', _this.toast.id);
                    }
                });
            };
            calculate();
        },
        /**
         * Trigger beforeDestroy lifecycle. Removes toast
         */
        onRemove: function () {
            var _this = this;
            this.state.isDestroying = true;
            this.$emit('stateChanged', 'beforeHide');
            this.toast.eventEmitter.$emit('beforeHide');
            this.state.animation = this.toast.config.animation.exit;
            setTimeout(function () {
                _this.$emit('stateChanged', 'hidden');
                _this.state.animation = 'snotifyToast--out';
                _this.toast.eventEmitter.$emit('hidden');
                setTimeout(function () { return _this.$snotify.remove(_this.toast.id, true); }, _this.toast.config.animation.time / 2);
            }, this.toast.config.animation.time / 2);
        },
    },
    created: function () {
        var _this = this;
        this.$snotify.emitter.$on('toastChanged', function (toast) {
            if (_this.toast.id === toast.id) {
                _this.initToast();
            }
        });
        this.$snotify.emitter.$on('remove', function (id) {
            if (_this.toast.id === id) {
                _this.onRemove();
            }
        });
    },
    mounted: function () {
        var _this = this;
        this.$nextTick(function () {
            _this.toast.eventEmitter.$emit('mounted');
            _this.state.animation = 'snotifyToast--in';
            _this.$nextTick(function () {
                setTimeout(function () {
                    _this.$emit('stateChanged', 'beforeShow');
                    _this.toast.eventEmitter.$emit('beforeShow');
                    _this.state.animation = _this.toast.config.animation.enter;
                }, _this.toast.config.animation.time / 5); // time to show toast push animation (snotifyToast--in)
            });
        });
    },
    destroyed: function () {
        cancelAnimationFrame(this.animationFrame);
        this.toast.eventEmitter.$emit('destroyed');
    }
});

/* script */

            const __vue_script__$2 = script$2;
            
/* template */
var __vue_render__$2 = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"snotifyToast animated",class:['snotify-' + _vm.toast.config.type,
          _vm.state.animation,
          _vm.toast.valid === undefined ? '' : (_vm.toast.valid ? 'snotifyToast--valid' : 'snotifyToast--invalid')
        ],style:({
          '-webkit-animation-duration': _vm.toast.config.animation.time + 'ms',
          'animation-duration': _vm.toast.config.animation.time + 'ms',
          '-webkit-transition': _vm.toast.config.animation.time + 'ms',
          transition: _vm.toast.config.animation.time + 'ms'
        }),on:{"click":_vm.onClick,"mouseenter":_vm.onMouseEnter,"mouseleave":_vm.onMouseLeave,"animationend":_vm.onExitTransitionEnd}},[(_vm.toast.config.showProgressBar && _vm.toast.config.timeout > 0)?_c('div',{staticClass:"snotifyToast__progressBar"},[_c('span',{staticClass:"snotifyToast__progressBar__percentage",style:({'width': (_vm.state.progress * 100) + '%'})})]):_vm._e(),_vm._v(" "),(!_vm.toast.config.html)?_c('div',{staticClass:"snotifyToast__inner",class:{'snotifyToast__noIcon': _vm.toast.config.icon === false}},[(_vm.toast.title)?_c('div',{staticClass:"snotifyToast__title"},[_vm._v(_vm._s(_vm._f("truncate")(_vm.toast.title,_vm.toast.config.titleMaxLength)))]):_vm._e(),_vm._v(" "),(_vm.toast.body)?_c('div',{staticClass:"snotifyToast__body"},[_vm._v(_vm._s(_vm._f("truncate")(_vm.toast.body,_vm.toast.config.bodyMaxLength)))]):_vm._e(),_vm._v(" "),(_vm.toast.config.type === _vm.state.promptType)?_c('snotify-prompt',{attrs:{"toast":_vm.toast}}):_vm._e(),_vm._v(" "),(typeof _vm.toast.config.icon === 'undefined')?_c('div',{class:['snotify-icon', 'snotify-icon--' + _vm.toast.config.type]}):(_vm.toast.config.icon !== false)?_c('div',[_c('img',{staticClass:"snotify-icon",attrs:{"src":_vm.toast.config.icon}})]):_vm._e()],1):_c('div',{staticClass:"snotifyToast__inner",domProps:{"innerHTML":_vm._s(_vm.toast.config.html)}}),_vm._v(" "),(_vm.toast.config.buttons)?_c('snotify-button',{attrs:{"toast":_vm.toast}}):_vm._e()],1)};
var __vue_staticRenderFns__$2 = [];

  /* style */
  const __vue_inject_styles__$2 = undefined;
  /* scoped */
  const __vue_scope_id__$2 = undefined;
  /* module identifier */
  const __vue_module_identifier__$2 = undefined;
  /* functional template */
  const __vue_is_functional_template__$2 = false;
  /* component normalizer */
  function __vue_normalize__$2(
    template, style, script,
    scope, functional, moduleIdentifier,
    createInjector, createInjectorSSR
  ) {
    const component = (typeof script === 'function' ? script.options : script) || {};

    if (!component.render) {
      component.render = template.render;
      component.staticRenderFns = template.staticRenderFns;
      component._compiled = true;

      if (functional) component.functional = true;
    }

    component._scopeId = scope;

    return component
  }
  /* style inject */
  function __vue_create_injector__$2() {
    const head = document.head || document.getElementsByTagName('head')[0];
    const styles = __vue_create_injector__$2.styles || (__vue_create_injector__$2.styles = {});
    const isOldIE =
      typeof navigator !== 'undefined' &&
      /msie [6-9]\\b/.test(navigator.userAgent.toLowerCase());

    return function addStyle(id, css) {
      if (document.querySelector('style[data-vue-ssr-id~="' + id + '"]')) return // SSR styles are present.

      const group = isOldIE ? css.media || 'default' : id;
      const style = styles[group] || (styles[group] = { ids: [], parts: [], element: undefined });

      if (!style.ids.includes(id)) {
        let code = css.source;
        let index = style.ids.length;

        style.ids.push(id);

        if ( true && css.map) {
          // https://developer.chrome.com/devtools/docs/javascript-debugging
          // this makes source maps inside style tags work properly in Chrome
          code += '\n/*# sourceURL=' + css.map.sources[0] + ' */';
          // http://stackoverflow.com/a/26603875
          code +=
            '\n/*# sourceMappingURL=data:application/json;base64,' +
            btoa(unescape(encodeURIComponent(JSON.stringify(css.map)))) +
            ' */';
        }

        if (isOldIE) {
          style.element = style.element || document.querySelector('style[data-group=' + group + ']');
        }

        if (!style.element) {
          const el = style.element = document.createElement('style');
          el.type = 'text/css';

          if (css.media) el.setAttribute('media', css.media);
          if (isOldIE) {
            el.setAttribute('data-group', group);
            el.setAttribute('data-next-index', '0');
          }

          head.appendChild(el);
        }

        if (isOldIE) {
          index = parseInt(style.element.getAttribute('data-next-index'));
          style.element.setAttribute('data-next-index', index + 1);
        }

        if (style.element.styleSheet) {
          style.parts.push(code);
          style.element.styleSheet.cssText = style.parts
            .filter(Boolean)
            .join('\n');
        } else {
          const textNode = document.createTextNode(code);
          const nodes = style.element.childNodes;
          if (nodes[index]) style.element.removeChild(nodes[index]);
          if (nodes.length) style.element.insertBefore(textNode, nodes[index]);
          else style.element.appendChild(textNode);
        }
      }
    }
  }
  /* style inject SSR */
  

  
  var Toast = __vue_normalize__$2(
    { render: __vue_render__$2, staticRenderFns: __vue_staticRenderFns__$2 },
    __vue_inject_styles__$2,
    __vue_script__$2,
    __vue_scope_id__$2,
    __vue_is_functional_template__$2,
    __vue_module_identifier__$2,
    __vue_create_injector__$2,
    undefined
  )

var script$3 = vue__WEBPACK_IMPORTED_MODULE_0___default.a.extend({
    components: {
        Toast: Toast
    },
    data: function () {
        return {
            /**
             * Toasts array
             */
            notifications: {
                left_top: [],
                left_center: [],
                left_bottom: [],
                right_top: [],
                right_center: [],
                right_bottom: [],
                center_top: [],
                center_center: [],
                center_bottom: []
            },
            /**
             * Helper for slice pipe (maxOnScreen)
             */
            dockSize_a: 0,
            /**
             * Helper for slice pipe (maxOnScreen)
             */
            dockSize_b: 0,
            /**
             * Helper for slice pipe (maxAtPosition)
             */
            blockSize_a: 0,
            /**
             * Helper for slice pipe (maxAtPosition)
             */
            blockSize_b: 0,
            /**
             * Backdrop Opacity
             */
            backdrop: -1,
            /**
             * How many toasts with backdrop in current queue
             */
            withBackdrop: [],
        };
    },
    methods: {
        setOptions: function (toasts) {
            if (this.$snotify.config.global.newOnTop) {
                this.dockSize_a = -this.$snotify.config.global.maxOnScreen;
                this.dockSize_b = undefined;
                this.blockSize_a = -this.$snotify.config.global.maxAtPosition;
                this.blockSize_b = undefined;
                this.withBackdrop = toasts.filter(function (toast) { return toast.config.backdrop >= 0; });
            }
            else {
                this.dockSize_a = 0;
                this.dockSize_b = this.$snotify.config.global.maxOnScreen;
                this.blockSize_a = 0;
                this.blockSize_b = this.$snotify.config.global.maxAtPosition;
                this.withBackdrop = toasts.filter(function (toast) { return toast.config.backdrop >= 0; }).reverse();
            }
            this.notifications = this.splitToasts(toasts.slice(this.dockSize_a, this.dockSize_b));
            this.stateChanged('mounted');
        },
        // TODO: fix backdrop if more than one toast called in a row
        /**
         * Changes the backdrop opacity
         * @param {SnotifyEvent} event
         */
        stateChanged: function (event) {
            if (!this.withBackdrop.length) {
                return;
            }
            switch (event) {
                case 'mounted':
                    if (this.backdrop < 0) {
                        this.backdrop = 0;
                    }
                    break;
                case 'beforeShow':
                    this.backdrop = this.withBackdrop[this.withBackdrop.length - 1].config.backdrop;
                    break;
                case 'beforeHide':
                    if (this.withBackdrop.length === 1) {
                        this.backdrop = 0;
                    }
                    break;
                case 'hidden':
                    if (this.withBackdrop.length === 1) {
                        this.backdrop = -1;
                    }
                    break;
            }
        },
        /**
         * Split toasts toasts into different objects
         * @param {SnotifyToast[]} toasts
         * @returns {SnotifyNotifications}
         */
        splitToasts: function (toasts) {
            var result = {};
            for (var property in SnotifyPosition) {
                if (SnotifyPosition.hasOwnProperty(property)) {
                    result[SnotifyPosition[property]] = [];
                }
            }
            toasts.forEach(function (toast) {
                result[toast.config.position].push(toast);
            });
            return result;
        },
    },
    created: function () {
        var _this = this;
        this.$snotify.emitter.$on('snotify', function (toasts) {
            _this.setOptions(toasts);
        });
    }
});

/* script */

            const __vue_script__$3 = script$3;
            
/* template */
var __vue_render__$3 = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',[(_vm.backdrop >= 0)?_c('div',{staticClass:"snotify-backdrop",style:({opacity: _vm.backdrop})}):_vm._e(),_vm._v(" "),_vm._l((_vm.notifications),function(position,index){return _c('div',{staticClass:"snotify",class:'snotify-' + index},_vm._l((_vm.notifications[index].slice(_vm.blockSize_a, _vm.blockSize_b)),function(toast){return _c('toast',{key:toast.id,attrs:{"toastData":toast},on:{"stateChanged":_vm.stateChanged}})}))})],2)};
var __vue_staticRenderFns__$3 = [];

  /* style */
  const __vue_inject_styles__$3 = undefined;
  /* scoped */
  const __vue_scope_id__$3 = undefined;
  /* module identifier */
  const __vue_module_identifier__$3 = undefined;
  /* functional template */
  const __vue_is_functional_template__$3 = false;
  /* component normalizer */
  function __vue_normalize__$3(
    template, style, script,
    scope, functional, moduleIdentifier,
    createInjector, createInjectorSSR
  ) {
    const component = (typeof script === 'function' ? script.options : script) || {};

    if (!component.render) {
      component.render = template.render;
      component.staticRenderFns = template.staticRenderFns;
      component._compiled = true;

      if (functional) component.functional = true;
    }

    component._scopeId = scope;

    return component
  }
  /* style inject */
  function __vue_create_injector__$3() {
    const head = document.head || document.getElementsByTagName('head')[0];
    const styles = __vue_create_injector__$3.styles || (__vue_create_injector__$3.styles = {});
    const isOldIE =
      typeof navigator !== 'undefined' &&
      /msie [6-9]\\b/.test(navigator.userAgent.toLowerCase());

    return function addStyle(id, css) {
      if (document.querySelector('style[data-vue-ssr-id~="' + id + '"]')) return // SSR styles are present.

      const group = isOldIE ? css.media || 'default' : id;
      const style = styles[group] || (styles[group] = { ids: [], parts: [], element: undefined });

      if (!style.ids.includes(id)) {
        let code = css.source;
        let index = style.ids.length;

        style.ids.push(id);

        if ( true && css.map) {
          // https://developer.chrome.com/devtools/docs/javascript-debugging
          // this makes source maps inside style tags work properly in Chrome
          code += '\n/*# sourceURL=' + css.map.sources[0] + ' */';
          // http://stackoverflow.com/a/26603875
          code +=
            '\n/*# sourceMappingURL=data:application/json;base64,' +
            btoa(unescape(encodeURIComponent(JSON.stringify(css.map)))) +
            ' */';
        }

        if (isOldIE) {
          style.element = style.element || document.querySelector('style[data-group=' + group + ']');
        }

        if (!style.element) {
          const el = style.element = document.createElement('style');
          el.type = 'text/css';

          if (css.media) el.setAttribute('media', css.media);
          if (isOldIE) {
            el.setAttribute('data-group', group);
            el.setAttribute('data-next-index', '0');
          }

          head.appendChild(el);
        }

        if (isOldIE) {
          index = parseInt(style.element.getAttribute('data-next-index'));
          style.element.setAttribute('data-next-index', index + 1);
        }

        if (style.element.styleSheet) {
          style.parts.push(code);
          style.element.styleSheet.cssText = style.parts
            .filter(Boolean)
            .join('\n');
        } else {
          const textNode = document.createTextNode(code);
          const nodes = style.element.childNodes;
          if (nodes[index]) style.element.removeChild(nodes[index]);
          if (nodes.length) style.element.insertBefore(textNode, nodes[index]);
          else style.element.appendChild(textNode);
        }
      }
    }
  }
  /* style inject SSR */
  

  
  var Snotify = __vue_normalize__$3(
    { render: __vue_render__$3, staticRenderFns: __vue_staticRenderFns__$3 },
    __vue_inject_styles__$3,
    __vue_script__$3,
    __vue_scope_id__$3,
    __vue_is_functional_template__$3,
    __vue_module_identifier__$3,
    __vue_create_injector__$3,
    undefined
  )

/*! *****************************************************************************
Copyright (c) Microsoft Corporation. All rights reserved.
Licensed under the Apache License, Version 2.0 (the "License"); you may not use
this file except in compliance with the License. You may obtain a copy of the
License at http://www.apache.org/licenses/LICENSE-2.0

THIS CODE IS PROVIDED ON AN *AS IS* BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
KIND, EITHER EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED
WARRANTIES OR CONDITIONS OF TITLE, FITNESS FOR A PARTICULAR PURPOSE,
MERCHANTABLITY OR NON-INFRINGEMENT.

See the Apache Version 2.0 License for specific language governing permissions
and limitations under the License.
***************************************************************************** */
/* global Reflect, Promise */



var __assign = Object.assign || function __assign(t) {
    for (var s, i = 1, n = arguments.length; i < n; i++) {
        s = arguments[i];
        for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p)) t[p] = s[p];
    }
    return t;
};



function __decorate(decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
}



function __metadata(metadataKey, metadataValue) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(metadataKey, metadataValue);
}

/**
 * Toast model
 */
var SnotifyToast = /** @class */ (function () {
    /**
     *
     * @param {number|string} id
     * @param {string} title
     * @param {string} body
     * @param {SnotifyToastConfig} [config]
     */
    function SnotifyToast(id, title, body, config) {
        var _this = this;
        this.id = id;
        this.title = title;
        this.body = body;
        this.config = config;
        /**
         * Emits {SnotifyEvent}
         * @type {Vue}
         */
        this.eventEmitter = new vue__WEBPACK_IMPORTED_MODULE_0___default.a();
        /**
         * Holds all subscribers because we need to unsubscribe from all before toast get destroyed
         * @type {Vue[]}
         * @private
         */
        this._eventsHolder = [];
        /**
         * Toast validator
         */
        this.valid = undefined;
        if (this.config.type === SnotifyStyle.prompt) {
            this.value = '';
        }
        this.on('hidden', function () {
            _this._eventsHolder.forEach(function (o) {
                _this.eventEmitter.$off(o.event, o.action);
            });
        });
    }
    /**
     * This callback is displayed as a global member.
     * @callback action
     * @param {toast} responseCode
     * @returns {void}
     */
    /**
     * Subscribe to toast events
     * @param {String<SnotifyEvent>} event
     * @param  {SnotifyToast~action} action
     * @returns {SnotifyToast}
     */
    SnotifyToast.prototype.on = function (event, action) {
        var _this = this;
        this._eventsHolder.push({ event: event, action: action });
        this.eventEmitter.$on(event, function () { return action(_this); });
        return this;
    };
    return SnotifyToast;
}());

/**
 * Snotify default configuration object
 * @type {SnotifyDefaults}
 */
var ToastDefaults = {
    global: {
        newOnTop: true,
        maxOnScreen: 8,
        maxAtPosition: 8,
        oneAtTime: false,
        preventDuplicates: false
    },
    toast: {
        type: SnotifyStyle.simple,
        showProgressBar: true,
        timeout: 2000,
        closeOnClick: true,
        pauseOnHover: true,
        bodyMaxLength: 150,
        titleMaxLength: 16,
        backdrop: -1,
        icon: undefined,
        html: null,
        position: SnotifyPosition.rightBottom,
        animation: { enter: 'fadeIn', exit: 'fadeOut', time: 400 }
    },
    type: (_a = {}, _a[SnotifyStyle.prompt] = {
            timeout: 0,
            closeOnClick: false,
            buttons: [
                { text: 'Ok', action: null, bold: true },
                { text: 'Cancel', action: null, bold: false },
            ],
            placeholder: 'Enter answer here...',
            type: SnotifyStyle.prompt,
        }, _a[SnotifyStyle.confirm] = {
            timeout: 0,
            closeOnClick: false,
            buttons: [
                { text: 'Ok', action: null, bold: true },
                { text: 'Cancel', action: null, bold: false },
            ],
            type: SnotifyStyle.confirm,
        }, _a[SnotifyStyle.simple] = {
            type: SnotifyStyle.simple
        }, _a[SnotifyStyle.success] = {
            type: SnotifyStyle.success
        }, _a[SnotifyStyle.error] = {
            type: SnotifyStyle.error
        }, _a[SnotifyStyle.warning] = {
            type: SnotifyStyle.warning
        }, _a[SnotifyStyle.info] = {
            type: SnotifyStyle.info
        }, _a[SnotifyStyle.async] = {
            pauseOnHover: false,
            closeOnClick: false,
            timeout: 0,
            showProgressBar: false,
            type: SnotifyStyle.async
        }, _a)
};
var _a;

/**
 * Transform arguments to Snotify object
 * @param target
 * @param {SnotifyType} propertyKey
 * @param {PropertyDescriptor} descriptor
 * @returns {Snotify}
 * @constructor
 */
function TransformArgument(target, propertyKey, descriptor) {
    if (propertyKey === SnotifyStyle.async) {
        return {
            value: function () {
                var args = [];
                for (var _i = 0; _i < arguments.length; _i++) {
                    args[_i] = arguments[_i];
                }
                var result;
                if (args.length === 2) {
                    result = {
                        title: null,
                        body: args[0],
                        config: null,
                        action: args[1]
                    };
                }
                else if (args.length === 3) {
                    if (typeof args[1] === 'string') {
                        result = {
                            title: args[1],
                            body: args[0],
                            config: null,
                            action: args[2]
                        };
                    }
                    else {
                        result = {
                            title: null,
                            body: args[0],
                            config: args[2],
                            action: args[1]
                        };
                    }
                }
                else {
                    result = {
                        title: args[1],
                        body: args[0],
                        config: args[3],
                        action: args[2]
                    };
                }
                return descriptor.value.apply(this, [result]);
            }
        };
    }
    else {
        return {
            value: function () {
                var args = [];
                for (var _i = 0; _i < arguments.length; _i++) {
                    args[_i] = arguments[_i];
                }
                var result;
                if (args.length === 1) {
                    result = {
                        title: null,
                        body: args[0],
                        config: null
                    };
                }
                else if (args.length === 3) {
                    result = {
                        title: args[1],
                        body: args[0],
                        config: args[2]
                    };
                }
                else {
                    result = (_a = {
                            title: null,
                            config: null,
                            body: args[0]
                        }, _a[typeof args[1] === 'string' ? 'title' : 'config'] = args[1], _a);
                }
                return descriptor.value.apply(this, [result]);
                var _a;
            }
        };
    }
}

/**
 * Generates random id
 * @return {number}
 */
function uuid() {
    return Math.floor(Math.random() * (Date.now() - 1)) + 1;
}
/**
 * Simple is object check.
 * @param item {Object<any>}
 * @returns {boolean}
 */
function isObject(item) {
    return (item && typeof item === 'object' && !Array.isArray(item) && item !== null);
}
/**
 * Deep merge objects.
 * @param sources {Array<Object<any>>}
 * @returns {Object<any>}
 */
function mergeDeep() {
    var sources = [];
    for (var _i = 0; _i < arguments.length; _i++) {
        sources[_i] = arguments[_i];
    }
    var target = {};
    if (!sources.length) {
        return target;
    }
    while (sources.length > 0) {
        var source = sources.shift();
        if (isObject(source)) {
            for (var key in source) {
                if (isObject(source[key])) {
                    target[key] = mergeDeep(target[key], source[key]);
                }
                else {
                    Object.assign(target, (_a = {}, _a[key] = source[key], _a));
                }
            }
        }
    }
    return target;
    var _a;
}

/**
 * Defines toast style depending on method name
 * @param target
 * @param {SnotifyType} propertyKey
 * @param {PropertyDescriptor} descriptor
 * @returns {{value: ((...args: any[]) => any)}}
 * @constructor
 */
function SetToastType(target, propertyKey, descriptor) {
    return {
        value: function () {
            var args = [];
            for (var _i = 0; _i < arguments.length; _i++) {
                args[_i] = arguments[_i];
            }
            args[0].config = __assign({}, args[0].config, { type: propertyKey });
            return descriptor.value.apply(this, args);
        }
    };
}

/**
 * this - create, remove, config toasts
 */
// tslint:disable:unified-signatures
var SnotifyService = /** @class */ (function () {
    function SnotifyService() {
        this.emitter = new vue__WEBPACK_IMPORTED_MODULE_0___default.a();
        this.notifications = [];
        this.config = ToastDefaults;
    }
    /**
     * emit changes in notifications array
     */
    SnotifyService.prototype.emit = function () {
        this.emitter.$emit('snotify', this.notifications.slice());
    };
    /**
     * returns SnotifyToast object
     * @param id {Number}
     * @return {SnotifyToast|undefined}
     */
    SnotifyService.prototype.get = function (id) {
        return this.notifications.find(function (toast) { return toast.id === id; });
    };
    /**
     * add SnotifyToast to notifications array
     * @param toast {SnotifyToast}
     */
    SnotifyService.prototype.add = function (toast) {
        if (this.config.global.newOnTop) {
            this.notifications.unshift(toast);
        }
        else {
            this.notifications.push(toast);
        }
        this.emit();
    };
    /**
     * If ID passed, emits toast animation remove, if ID & REMOVE passed, removes toast from notifications array
     * @param id {number}
     * @param remove {boolean}
     */
    SnotifyService.prototype.remove = function (id, remove) {
        if (!id) {
            return this.clear();
        }
        else if (remove) {
            this.notifications = this.notifications.filter(function (toast) { return toast.id !== id; });
            return this.emit();
        }
        this.emitter.$emit('remove', id);
    };
    /**
     * Clear notifications array
     */
    SnotifyService.prototype.clear = function () {
        this.notifications = [];
        this.emit();
    };
    SnotifyService.prototype.button = function (text, closeOnClick, action, bold) {
        var _this = this;
        if (closeOnClick === void 0) { closeOnClick = true; }
        if (action === void 0) { action = null; }
        if (bold === void 0) { bold = false; }
        return {
            text: text,
            action: closeOnClick ? function (toast) {
                action(toast);
                _this.remove(toast.id);
            } : action,
            bold: bold
        };
    };
    /**
     * Creates toast and add it to array, returns toast id
     * @param snotify {Snotify}
     * @return {number}
     */
    SnotifyService.prototype.create = function (snotify) {
        if (this.config.global.oneAtTime && this.notifications.length !== 0)
            return;
        if (this.config.global.preventDuplicates
            && this.notifications.filter(function (t) { return t.config.type === snotify.config.type; }).length === 1)
            return;
        var config = mergeDeep(this.config.toast, this.config.type[snotify.config.type], snotify.config);
        var toast = new SnotifyToast(config.id ? config.id : uuid(), snotify.title, snotify.body, config);
        this.add(toast);
        return toast;
    };
    SnotifyService.prototype.setDefaults = function (defaults) {
        return this.config = mergeDeep(this.config, defaults);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.simple = function (args) {
        return this.create(args);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.success = function (args) {
        return this.create(args);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.error = function (args) {
        return this.create(args);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.info = function (args) {
        return this.create(args);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.warning = function (args) {
        return this.create(args);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.confirm = function (args) {
        return this.create(args);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.prompt = function (args) {
        return this.create(args);
    };
    /**
     * Transform toast arguments into {Snotify} object
     */
    SnotifyService.prototype.async = function (args) {
        var _this = this;
        var async = args.action;
        var toast = this.create(args);
        toast.on('mounted', function () {
            async()
                .then(function (next) { return _this.mergeToast(toast, next, SnotifyStyle.success); })
                .catch(function (error) { return _this.mergeToast(toast, error, SnotifyStyle.error); });
        });
        return toast;
    };
    SnotifyService.prototype.mergeToast = function (toast, next, type) {
        if (next.body) {
            toast.body = next.body;
        }
        if (next.title) {
            toast.title = next.title;
        }
        if (type) {
            toast.config = mergeDeep(toast.config, this.config.global, this.config.toast[type], { type: type }, next.config);
        }
        else {
            toast.config = mergeDeep(toast.config, next.config);
        }
        if (next.html) {
            toast.config.html = next.html;
        }
        this.emit();
        this.emitter.$emit('toastChanged', toast);
    };
    /**
     * Creates empty toast with html string inside
     * @param {string} html
     * @param {SnotifyToastConfig} config
     * @returns {number}
     */
    SnotifyService.prototype.html = function (html, config) {
        return this.create({
            title: null,
            body: null,
            config: __assign({}, config, { html: html })
        });
    };
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "simple", null);
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "success", null);
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "error", null);
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "info", null);
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "warning", null);
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "confirm", null);
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "prompt", null);
    __decorate([
        TransformArgument
        /**
         * Determines current toast type and collects default configuration
         */
        ,
        SetToastType,
        __metadata("design:type", Function),
        __metadata("design:paramtypes", [Object]),
        __metadata("design:returntype", SnotifyToast)
    ], SnotifyService.prototype, "async", null);
    return SnotifyService;
}());

var Plugin = {
    install: function (Vue$$1, options) {
        if (options === void 0) { options = {}; }
        Vue$$1.filter('truncate', function (value, limit, trail) {
            if (limit === void 0) { limit = 40; }
            if (trail === void 0) { trail = '...'; }
            return value.length > limit ? value.substring(0, limit) + trail : value;
        });
        var service = new SnotifyService();
        service.setDefaults(options);
        Vue$$1.prototype.$snotify = service;
        Vue$$1.component('vue-snotify', Snotify);
        // auto install
        if (typeof window !== 'undefined' && window.hasOwnProperty('Vue')) {
            window.Snotify = service;
        }
    }
};
// auto install
if (typeof window !== 'undefined' && window.hasOwnProperty('Vue')) {
    window.Vue.use(Plugin.install);
}


/* harmony default export */ __webpack_exports__["default"] = (Plugin);


/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./node_modules/webpack/buildin/module.js":
/*!***********************************!*\
  !*** (webpack)/buildin/module.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function(module) {
	if (!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if (!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ "./public/js/langs/en.json":
/*!*********************************!*\
  !*** ./public/js/langs/en.json ***!
  \*********************************/
/*! exports provided: account, app, audit, auth, company, employee, format, home, pagination, passwords, team, validation, default */
/***/ (function(module) {

module.exports = {"account":{"employee_new_administrator":"Administrator","employee_new_administrator_desc":"Can do everything, including account management.","employee_new_email":"Email address","employee_new_firstname":"Firstname","employee_new_hr":"Human Resource Representative","employee_new_hr_desc":"Have access to most features, including reading and writing private information, but can't manage the account itself.","employee_new_lastname":"Lastname","employee_new_permission_level":"What can this person do?","employee_new_send_email":"Send an email to this person with a link to access the account. This is optional - you will be able to invite this person later.","employee_new_title":"Add employee","employee_new_user":"Employee","employee_new_user_desc":"Can see all teams and employees, but can not manage the account or read private information.","employees_change_permission":"Change permission","employees_cta":"Add an employee","employees_lock_account":"Lock account","employees_number_employees":"{company} has one employee. | {company} has {count} employees.","employees_title":"All the employees in {company}","home_audit_log":"View audit log to see who has done what","home_generate_fake_data":"Generate fake data","home_manage_employees":"Add/remove existing employees","home_manage_positions":"Add/remove job positions","home_manage_teams":"Add/remove teams","home_remove_fake_data":"Remove fake data","home_role_administrator":"As an <span class=\"fw5 brush-blue\">administrator</span>, you can…","home_role_owner":"As an <span class=\"fw5 brush-orange\">owner</span>, you can…","home_title":"Administration of your Homas account","position_new_title":"Position name","position_success_destroy":"The position has been destroyed","position_success_new":"The position has been created","position_success_update":"The position has been updated","positions_blank":"Positions are terms that describe in a few words what an employee does. Like Marketing Coordinator for example.","positions_cta":"Add a position","positions_number_positions":"{company} has one position. | {company} has {count} positions.","positions_title":"All the positions used in {company}","team_new_name":"Name of the team","teams_blank":"Teams are a great way for groups of people in your company to work together in Homas.","teams_cta":"Add a team","teams_number_teams":"{company} has one team. | {company} has {count} teams.","teams_title":"All the teams listed in {company}"},"app":{"add":"Add","breadcrumb_account_add_employee":"Add an employee","breadcrumb_account_audit_logs":"Audit logs","breadcrumb_account_home":"Account administration","breadcrumb_account_manage_employees":"Manage employees","breadcrumb_account_manage_positions":"Positions","breadcrumb_account_manage_teams":"Manage teams","breadcrumb_employee_list":"All employees","breadcrumb_employee_logs":"Logs","breadcrumb_team_list":"All teams","cancel":"Cancel","choose":"Choose","default_position_ceo":"CEO","default_position_front_end_developer":"Front end developer","default_position_marketing_specialist":"Marketing specialist","default_position_sales_representative":"Sales representative","delete":"Delete","delete_confirm":"Sure?","edit":"Edit","header_find":"Find","header_home":"Home","header_logout":"Logout","header_search_employees":"Employees","header_search_no_employee_found":"No employees found","header_search_no_team_found":"No teams found","header_search_placeholder":"Find an employee or a team by name","header_search_teams":"Teams","header_switch_company":"Switch company","next":"Next","no":"No","no_position_defined":"No position defined","no_results":"No results","permission_100":"Administrator","permission_200":"Human Resource Representative","permission_300":"Employee","previous":"Previous","rename":"Rename","save":"Save","search":"Search","sure":"Are you sure?","update":"Update","view":"View","yes":"Yes"},"audit":{"title":"Audit logs"},"auth":{"failed":"These credentials do not match our records.","invitation_invalid_link":"This invitation link is invalid.","invitation_link_already_accepted":"This invitation has already been accepted. Please sign in to your account.","invitation_logged_accept_cta":"Yes, let me in","invitation_logged_accept_title":"Would you like to join {name}?","invitation_unlogged_choice_account":"Create an account","invitation_unlogged_choice_account_desc":"Use this option if you don't have an account","invitation_unlogged_choice_account_title":"Create an account","invitation_unlogged_choice_login":"Sign in to your account","invitation_unlogged_choice_login_desc":"Use this option if you already have an account on Homas","invitation_unlogged_choice_login_title":"Use an existing Homas account","invitation_unlogged_create_account_instead":"Login with an existing account instead.","invitation_unlogged_desc":"To accept the invitation, use one of these two options below.","invitation_unlogged_login_instead":"Create a new account instead.","invitation_unlogged_title":"You have been invited to join the human resource software used by {name}.","login_cta":"Login →","login_email":"Your email address","login_invalid_credentials":"😳 Invalid credentials","login_password":"Your password","register_cta":"Create your account →","register_email":"Your email address","register_email_help":"We'll never spam. You'll receive one email to confirm your email address once you sign up, and won't be added to any nasty email marketing campaigns, nor will you receive emails from a sales team.","register_password":"Enter a hard-to-guess password","register_title":"Create an account now","throttle":"Too many login attempts. Please try again in :seconds seconds."},"company":{"new_name":"What is the name of the company?"},"employee":{"hierarchy_blank":"Add a manager or a direct report to position this employee within the company.","hierarchy_list_direct_report_title":"Direct report | Direct reports","hierarchy_list_manager_title":"Manager | Managers","hierarchy_modal_add_direct_report":"Add a direct report","hierarchy_modal_add_direct_report_search":"Assign an employee as {name}'s direct report","hierarchy_modal_add_direct_report_success":"The direct report has been set","hierarchy_modal_add_manager":"Add a manager","hierarchy_modal_add_manager_search":"Assign an employee as {name}'s manager","hierarchy_modal_add_manager_success":"The manager has been set","hierarchy_modal_remove_direct_report":"Remove direct report","hierarchy_modal_remove_direct_report_success":"The direct report has been unassigned","hierarchy_modal_remove_manager":"Remove manager","hierarchy_modal_remove_manager_success":"The manager has been unassigned","hierarchy_search_placeholder":"Enter the first letters of the name","hierarchy_search_results":"Search results:","hierarchy_title":"Position in the company","position_blank":"No position set","position_modal_assign_success":"The position has been set","position_modal_filter":"Filter the list","position_modal_reset":"Remove current position","position_modal_title":"Choose a position","position_modal_unassign_success":"The position has been removed","team_modal_assign_success":"The team has been assigned","team_modal_blank":"No team set","team_modal_blank_cta":"Create one now","team_modal_blank_title":"There is no team in this account yet.","team_modal_filter":"Filter the list","team_modal_title":"Choose a team","team_modal_unassign_success":"The team has been removed","team_title":"Teams:"},"format":{"short_date_year_time":"M d, Y H:i"},"home":{"companies_part_of":"All the companies you are part of","create_company":"Create a company","create_company_cta":"Add a company","create_company_desc":"Choose this if you want to create an account for your company.","join_company":"Join a company","join_company_desc":"Choose this is you are an employee of an existing company and need access to your account.","number_of_employees":"no employee | 1 employee | {count} employees"},"pagination":{"next":"Next &raquo;","previous":"&laquo; Previous"},"passwords":{"password":"Passwords must be at least six characters and match the confirmation.","reset":"Your password has been reset!","sent":"We have e-mailed your password reset link!","token":"This password reset token is invalid.","user":"We can't find a user with that e-mail address."},"team":{"new_name":"Name","new_title":"Create team"},"validation":{"accepted":"The :attribute must be accepted.","active_url":"The :attribute is not a valid URL.","after":"The :attribute must be a date after :date.","after_or_equal":"The :attribute must be a date after or equal to :date.","alpha":"The :attribute may only contain letters.","alpha_dash":"The :attribute may only contain letters, numbers, dashes and underscores.","alpha_num":"The :attribute may only contain letters and numbers.","array":"The :attribute must be an array.","attributes":[],"before":"The :attribute must be a date before :date.","before_or_equal":"The :attribute must be a date before or equal to :date.","between":{"array":"The :attribute must have between :min and :max items.","file":"The :attribute must be between :min and :max kilobytes.","numeric":"The :attribute must be between :min and :max.","string":"The :attribute must be between :min and :max characters."},"boolean":"The :attribute field must be true or false.","confirmed":"The :attribute confirmation does not match.","custom":{"attribute-name":{"rule-name":"custom-message"}},"date":"The :attribute is not a valid date.","date_equals":"The :attribute must be a date equal to :date.","date_format":"The :attribute does not match the format :format.","different":"The :attribute and :other must be different.","digits":"The :attribute must be :digits digits.","digits_between":"The :attribute must be between :min and :max digits.","dimensions":"The :attribute has invalid image dimensions.","distinct":"The :attribute field has a duplicate value.","email":"The :attribute must be a valid email address.","exists":"The selected :attribute is invalid.","file":"The :attribute must be a file.","filled":"The :attribute field must have a value.","gt":{"array":"The :attribute must have more than :value items.","file":"The :attribute must be greater than :value kilobytes.","numeric":"The :attribute must be greater than :value.","string":"The :attribute must be greater than :value characters."},"gte":{"array":"The :attribute must have :value items or more.","file":"The :attribute must be greater than or equal :value kilobytes.","numeric":"The :attribute must be greater than or equal :value.","string":"The :attribute must be greater than or equal :value characters."},"image":"The :attribute must be an image.","in":"The selected :attribute is invalid.","in_array":"The :attribute field does not exist in :other.","integer":"The :attribute must be an integer.","ip":"The :attribute must be a valid IP address.","ipv4":"The :attribute must be a valid IPv4 address.","ipv6":"The :attribute must be a valid IPv6 address.","json":"The :attribute must be a valid JSON string.","lt":{"array":"The :attribute must have less than :value items.","file":"The :attribute must be less than :value kilobytes.","numeric":"The :attribute must be less than :value.","string":"The :attribute must be less than :value characters."},"lte":{"array":"The :attribute must not have more than :value items.","file":"The :attribute must be less than or equal :value kilobytes.","numeric":"The :attribute must be less than or equal :value.","string":"The :attribute must be less than or equal :value characters."},"max":{"array":"The :attribute may not have more than :max items.","file":"The :attribute may not be greater than :max kilobytes.","numeric":"The :attribute may not be greater than :max.","string":"The :attribute may not be greater than :max characters."},"mimes":"The :attribute must be a file of type: :values.","mimetypes":"The :attribute must be a file of type: :values.","min":{"array":"The :attribute must have at least :min items.","file":"The :attribute must be at least :min kilobytes.","numeric":"The :attribute must be at least :min.","string":"The :attribute must be at least :min characters."},"not_in":"The selected :attribute is invalid.","not_regex":"The :attribute format is invalid.","numeric":"The :attribute must be a number.","present":"The :attribute field must be present.","regex":"The :attribute format is invalid.","required":"The :attribute field is required.","required_if":"The :attribute field is required when :other is :value.","required_unless":"The :attribute field is required unless :other is in :values.","required_with":"The :attribute field is required when :values is present.","required_with_all":"The :attribute field is required when :values are present.","required_without":"The :attribute field is required when :values is not present.","required_without_all":"The :attribute field is required when none of :values are present.","same":"The :attribute and :other must match.","size":{"array":"The :attribute must contain :size items.","file":"The :attribute must be :size kilobytes.","numeric":"The :attribute must be :size.","string":"The :attribute must be :size characters."},"starts_with":"The :attribute must start with one of the following: :values","string":"The :attribute must be a string.","timezone":"The :attribute must be a valid zone.","unique":"The :attribute has already been taken.","uploaded":"The :attribute failed to upload.","url":"The :attribute format is invalid.","uuid":"The :attribute must be a valid UUID."}};

/***/ }),

/***/ "./resources/js sync recursive \\.vue$/":
/*!***********************************!*\
  !*** ./resources/js sync \.vue$/ ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var map = {
	"./auth/Login.vue": "./resources/js/auth/Login.vue",
	"./auth/Register.vue": "./resources/js/auth/Register.vue",
	"./auth/invitation/AcceptInvitation.vue": "./resources/js/auth/invitation/AcceptInvitation.vue",
	"./auth/invitation/AcceptInvitationUnlogged.vue": "./resources/js/auth/invitation/AcceptInvitationUnlogged.vue",
	"./auth/invitation/InvalidInvitationLink.vue": "./resources/js/auth/invitation/InvalidInvitationLink.vue",
	"./auth/invitation/InvitationLinkAlreadyAccepted.vue": "./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue",
	"./company/adminland/ShowAccount.vue": "./resources/js/company/adminland/ShowAccount.vue",
	"./company/adminland/audit/ShowAccountAudit.vue": "./resources/js/company/adminland/audit/ShowAccountAudit.vue",
	"./company/adminland/employee/CreateAccountEmployee.vue": "./resources/js/company/adminland/employee/CreateAccountEmployee.vue",
	"./company/adminland/employee/ShowAccountEmployees.vue": "./resources/js/company/adminland/employee/ShowAccountEmployees.vue",
	"./company/adminland/position/ShowAccountPositions.vue": "./resources/js/company/adminland/position/ShowAccountPositions.vue",
	"./company/adminland/team/ShowAccountTeams.vue": "./resources/js/company/adminland/team/ShowAccountTeams.vue",
	"./company/company/CreateCompany.vue": "./resources/js/company/company/CreateCompany.vue",
	"./company/company/employee/show/AssignEmployeePosition.vue": "./resources/js/company/company/employee/show/AssignEmployeePosition.vue",
	"./company/company/employee/show/AssignEmployeeTeam.vue": "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue",
	"./company/company/employee/show/ShowCompanyEmployee.vue": "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue",
	"./company/company/employee/show/ShowCompanyEmployeeHierarchy.vue": "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue",
	"./company/company/employee/show/ShowEmployeeLogs.vue": "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue",
	"./company/company/team/ShowCompanyTeam.vue": "./resources/js/company/company/team/ShowCompanyTeam.vue",
	"./company/dashboard/ShowCompany.vue": "./resources/js/company/dashboard/ShowCompany.vue",
	"./components/form/LoadingButton.vue": "./resources/js/components/form/LoadingButton.vue",
	"./components/header/HeaderMenu.vue": "./resources/js/components/header/HeaderMenu.vue",
	"./components/icons/IconDelete.vue": "./resources/js/components/icons/IconDelete.vue",
	"./home/Home.vue": "./resources/js/home/Home.vue",
	"./partials/Errors.vue": "./resources/js/partials/Errors.vue",
	"./partials/Layout.vue": "./resources/js/partials/Layout.vue"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./resources/js sync recursive \\.vue$/";

/***/ }),

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! exports provided: i18n */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "i18n", function() { return i18n; });
/* harmony import */ var vue_snotify__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue-snotify */ "./node_modules/vue-snotify/vue-snotify.esm.js");
/* harmony import */ var vue_snotify_styles_simple_css__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-snotify/styles/simple.css */ "./node_modules/vue-snotify/styles/simple.css");
/* harmony import */ var vue_snotify_styles_simple_css__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(vue_snotify_styles_simple_css__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-i18n */ "./node_modules/vue-i18n/dist/vue-i18n.esm.js");
/* harmony import */ var _public_js_langs_en_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../public/js/langs/en.json */ "./public/js/langs/en.json");
var _public_js_langs_en_json__WEBPACK_IMPORTED_MODULE_3___namespace = /*#__PURE__*/__webpack_require__.t(/*! ../../public/js/langs/en.json */ "./public/js/langs/en.json", 1);
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
__webpack_require__(/*! ./bootstrap */ "./resources/js/bootstrap.js");

window.Vue = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.runtime.js");
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

var files = __webpack_require__("./resources/js sync recursive \\.vue$/");

files.keys().map(function (key) {
  return Vue.component(key.split('/').pop().split('.')[0], files(key)["default"]);
}); // toaster



Vue.use(vue_snotify__WEBPACK_IMPORTED_MODULE_0__["default"]); // i18n


Vue.use(vue_i18n__WEBPACK_IMPORTED_MODULE_2__["default"]);

var i18n = new vue_i18n__WEBPACK_IMPORTED_MODULE_2__["default"]({
  locale: 'en',
  // set locale
  fallbackLocale: 'en',
  messages: {
    'en': _public_js_langs_en_json__WEBPACK_IMPORTED_MODULE_3__
  }
}); // Start Turbolinks

__webpack_require__(/*! turbolinks */ "./node_modules/turbolinks/dist/turbolinks.js").start(); // Boot the Vue component


document.addEventListener('turbolinks:load', function (event) {
  var root = document.getElementById('app');

  if (window.vue) {
    window.vue.$destroy(true);
  }

  window.vue = new Vue({
    i18n: i18n,
    render: function render(h) {
      return h(Vue.component(root.dataset.component), {
        props: JSON.parse(root.dataset.props)
      });
    }
  }).$mount(root);
});

/***/ }),

/***/ "./resources/js/auth/Login.vue":
/*!*************************************!*\
  !*** ./resources/js/auth/Login.vue ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Login_vue_vue_type_template_id_3d143c20___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Login.vue?vue&type=template&id=3d143c20& */ "./resources/js/auth/Login.vue?vue&type=template&id=3d143c20&");
/* harmony import */ var _Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Login.vue?vue&type=script&lang=js& */ "./resources/js/auth/Login.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Login_vue_vue_type_template_id_3d143c20___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Login_vue_vue_type_template_id_3d143c20___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/auth/Login.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/auth/Login.vue?vue&type=script&lang=js&":
/*!**************************************************************!*\
  !*** ./resources/js/auth/Login.vue?vue&type=script&lang=js& ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Login.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Login.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/auth/Login.vue?vue&type=template&id=3d143c20&":
/*!********************************************************************!*\
  !*** ./resources/js/auth/Login.vue?vue&type=template&id=3d143c20& ***!
  \********************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_3d143c20___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Login.vue?vue&type=template&id=3d143c20& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Login.vue?vue&type=template&id=3d143c20&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_3d143c20___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_3d143c20___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/auth/Register.vue":
/*!****************************************!*\
  !*** ./resources/js/auth/Register.vue ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Register_vue_vue_type_template_id_d7dc2e88___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Register.vue?vue&type=template&id=d7dc2e88& */ "./resources/js/auth/Register.vue?vue&type=template&id=d7dc2e88&");
/* harmony import */ var _Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Register.vue?vue&type=script&lang=js& */ "./resources/js/auth/Register.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Register_vue_vue_type_template_id_d7dc2e88___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Register_vue_vue_type_template_id_d7dc2e88___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/auth/Register.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/auth/Register.vue?vue&type=script&lang=js&":
/*!*****************************************************************!*\
  !*** ./resources/js/auth/Register.vue?vue&type=script&lang=js& ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Register.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Register.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/auth/Register.vue?vue&type=template&id=d7dc2e88&":
/*!***********************************************************************!*\
  !*** ./resources/js/auth/Register.vue?vue&type=template&id=d7dc2e88& ***!
  \***********************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_template_id_d7dc2e88___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Register.vue?vue&type=template&id=d7dc2e88& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/Register.vue?vue&type=template&id=d7dc2e88&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_template_id_d7dc2e88___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Register_vue_vue_type_template_id_d7dc2e88___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/auth/invitation/AcceptInvitation.vue":
/*!***********************************************************!*\
  !*** ./resources/js/auth/invitation/AcceptInvitation.vue ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AcceptInvitation_vue_vue_type_template_id_f02ca1a4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AcceptInvitation.vue?vue&type=template&id=f02ca1a4& */ "./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=template&id=f02ca1a4&");
/* harmony import */ var _AcceptInvitation_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AcceptInvitation.vue?vue&type=script&lang=js& */ "./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AcceptInvitation_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AcceptInvitation_vue_vue_type_template_id_f02ca1a4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AcceptInvitation_vue_vue_type_template_id_f02ca1a4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/auth/invitation/AcceptInvitation.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitation_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AcceptInvitation.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitation_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=template&id=f02ca1a4&":
/*!******************************************************************************************!*\
  !*** ./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=template&id=f02ca1a4& ***!
  \******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitation_vue_vue_type_template_id_f02ca1a4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AcceptInvitation.vue?vue&type=template&id=f02ca1a4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitation.vue?vue&type=template&id=f02ca1a4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitation_vue_vue_type_template_id_f02ca1a4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitation_vue_vue_type_template_id_f02ca1a4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/auth/invitation/AcceptInvitationUnlogged.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/auth/invitation/AcceptInvitationUnlogged.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AcceptInvitationUnlogged_vue_vue_type_template_id_7a44d529___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529& */ "./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529&");
/* harmony import */ var _AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AcceptInvitationUnlogged.vue?vue&type=script&lang=js& */ "./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AcceptInvitationUnlogged_vue_vue_type_template_id_7a44d529___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AcceptInvitationUnlogged_vue_vue_type_template_id_7a44d529___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/auth/invitation/AcceptInvitationUnlogged.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AcceptInvitationUnlogged.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_template_id_7a44d529___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/AcceptInvitationUnlogged.vue?vue&type=template&id=7a44d529&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_template_id_7a44d529___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AcceptInvitationUnlogged_vue_vue_type_template_id_7a44d529___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/auth/invitation/InvalidInvitationLink.vue":
/*!****************************************************************!*\
  !*** ./resources/js/auth/invitation/InvalidInvitationLink.vue ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _InvalidInvitationLink_vue_vue_type_template_id_04a4e526___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./InvalidInvitationLink.vue?vue&type=template&id=04a4e526& */ "./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=template&id=04a4e526&");
/* harmony import */ var _InvalidInvitationLink_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./InvalidInvitationLink.vue?vue&type=script&lang=js& */ "./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _InvalidInvitationLink_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _InvalidInvitationLink_vue_vue_type_template_id_04a4e526___WEBPACK_IMPORTED_MODULE_0__["render"],
  _InvalidInvitationLink_vue_vue_type_template_id_04a4e526___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/auth/invitation/InvalidInvitationLink.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_InvalidInvitationLink_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./InvalidInvitationLink.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_InvalidInvitationLink_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=template&id=04a4e526&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=template&id=04a4e526& ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_InvalidInvitationLink_vue_vue_type_template_id_04a4e526___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./InvalidInvitationLink.vue?vue&type=template&id=04a4e526& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvalidInvitationLink.vue?vue&type=template&id=04a4e526&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_InvalidInvitationLink_vue_vue_type_template_id_04a4e526___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_InvalidInvitationLink_vue_vue_type_template_id_04a4e526___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue":
/*!************************************************************************!*\
  !*** ./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _InvitationLinkAlreadyAccepted_vue_vue_type_template_id_187028cf___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf& */ "./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf&");
/* harmony import */ var _InvitationLinkAlreadyAccepted_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js& */ "./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _InvitationLinkAlreadyAccepted_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _InvitationLinkAlreadyAccepted_vue_vue_type_template_id_187028cf___WEBPACK_IMPORTED_MODULE_0__["render"],
  _InvitationLinkAlreadyAccepted_vue_vue_type_template_id_187028cf___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_InvitationLinkAlreadyAccepted_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_InvitationLinkAlreadyAccepted_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf& ***!
  \*******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_InvitationLinkAlreadyAccepted_vue_vue_type_template_id_187028cf___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/auth/invitation/InvitationLinkAlreadyAccepted.vue?vue&type=template&id=187028cf&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_InvitationLinkAlreadyAccepted_vue_vue_type_template_id_187028cf___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_InvitationLinkAlreadyAccepted_vue_vue_type_template_id_187028cf___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/bootstrap.js":
/*!***********************************!*\
  !*** ./resources/js/bootstrap.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

window._ = __webpack_require__(/*! lodash */ "./node_modules/lodash/lodash.js");
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

var token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
// import Echo from 'laravel-echo'
// window.Pusher = require('pusher-js');
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

/***/ }),

/***/ "./resources/js/company/adminland/ShowAccount.vue":
/*!********************************************************!*\
  !*** ./resources/js/company/adminland/ShowAccount.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowAccount_vue_vue_type_template_id_beebbd4a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true& */ "./resources/js/company/adminland/ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true&");
/* harmony import */ var _ShowAccount_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowAccount.vue?vue&type=script&lang=js& */ "./resources/js/company/adminland/ShowAccount.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ShowAccount_vue_vue_type_style_index_0_id_beebbd4a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css& */ "./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ShowAccount_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowAccount_vue_vue_type_template_id_beebbd4a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowAccount_vue_vue_type_template_id_beebbd4a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "beebbd4a",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/adminland/ShowAccount.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/adminland/ShowAccount.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/company/adminland/ShowAccount.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccount.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css&":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css& ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_style_index_0_id_beebbd4a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=style&index=0&id=beebbd4a&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_style_index_0_id_beebbd4a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_style_index_0_id_beebbd4a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_style_index_0_id_beebbd4a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_style_index_0_id_beebbd4a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_style_index_0_id_beebbd4a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/adminland/ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/company/adminland/ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_template_id_beebbd4a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/ShowAccount.vue?vue&type=template&id=beebbd4a&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_template_id_beebbd4a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccount_vue_vue_type_template_id_beebbd4a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/adminland/audit/ShowAccountAudit.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/company/adminland/audit/ShowAccountAudit.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowAccountAudit_vue_vue_type_template_id_6cf0d3e4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true& */ "./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true&");
/* harmony import */ var _ShowAccountAudit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowAccountAudit.vue?vue&type=script&lang=js& */ "./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ShowAccountAudit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowAccountAudit_vue_vue_type_template_id_6cf0d3e4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowAccountAudit_vue_vue_type_template_id_6cf0d3e4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "6cf0d3e4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/adminland/audit/ShowAccountAudit.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountAudit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountAudit.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountAudit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true& ***!
  \**************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountAudit_vue_vue_type_template_id_6cf0d3e4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/audit/ShowAccountAudit.vue?vue&type=template&id=6cf0d3e4&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountAudit_vue_vue_type_template_id_6cf0d3e4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountAudit_vue_vue_type_template_id_6cf0d3e4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/adminland/employee/CreateAccountEmployee.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/company/adminland/employee/CreateAccountEmployee.vue ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CreateAccountEmployee_vue_vue_type_template_id_152dace3_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true& */ "./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true&");
/* harmony import */ var _CreateAccountEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CreateAccountEmployee.vue?vue&type=script&lang=js& */ "./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _CreateAccountEmployee_vue_vue_type_style_index_0_id_152dace3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css& */ "./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _CreateAccountEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CreateAccountEmployee_vue_vue_type_template_id_152dace3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _CreateAccountEmployee_vue_vue_type_template_id_152dace3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "152dace3",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/adminland/employee/CreateAccountEmployee.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateAccountEmployee.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css&":
/*!************************************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css& ***!
  \************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_style_index_0_id_152dace3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=style&index=0&id=152dace3&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_style_index_0_id_152dace3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_style_index_0_id_152dace3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_style_index_0_id_152dace3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_style_index_0_id_152dace3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_style_index_0_id_152dace3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true&":
/*!**********************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true& ***!
  \**********************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_template_id_152dace3_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/CreateAccountEmployee.vue?vue&type=template&id=152dace3&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_template_id_152dace3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateAccountEmployee_vue_vue_type_template_id_152dace3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/adminland/employee/ShowAccountEmployees.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/company/adminland/employee/ShowAccountEmployees.vue ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowAccountEmployees_vue_vue_type_template_id_e5bd15be_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true& */ "./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true&");
/* harmony import */ var _ShowAccountEmployees_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowAccountEmployees.vue?vue&type=script&lang=js& */ "./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ShowAccountEmployees_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowAccountEmployees_vue_vue_type_template_id_e5bd15be_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowAccountEmployees_vue_vue_type_template_id_e5bd15be_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "e5bd15be",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/adminland/employee/ShowAccountEmployees.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountEmployees_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountEmployees.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountEmployees_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true& ***!
  \*********************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountEmployees_vue_vue_type_template_id_e5bd15be_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/employee/ShowAccountEmployees.vue?vue&type=template&id=e5bd15be&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountEmployees_vue_vue_type_template_id_e5bd15be_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountEmployees_vue_vue_type_template_id_e5bd15be_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/adminland/position/ShowAccountPositions.vue":
/*!**************************************************************************!*\
  !*** ./resources/js/company/adminland/position/ShowAccountPositions.vue ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowAccountPositions_vue_vue_type_template_id_0a3c956b_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true& */ "./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true&");
/* harmony import */ var _ShowAccountPositions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowAccountPositions.vue?vue&type=script&lang=js& */ "./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ShowAccountPositions_vue_vue_type_style_index_0_id_0a3c956b_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css& */ "./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ShowAccountPositions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowAccountPositions_vue_vue_type_template_id_0a3c956b_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowAccountPositions_vue_vue_type_template_id_0a3c956b_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "0a3c956b",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/adminland/position/ShowAccountPositions.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountPositions.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css&":
/*!***********************************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_style_index_0_id_0a3c956b_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=style&index=0&id=0a3c956b&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_style_index_0_id_0a3c956b_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_style_index_0_id_0a3c956b_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_style_index_0_id_0a3c956b_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_style_index_0_id_0a3c956b_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_style_index_0_id_0a3c956b_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true& ***!
  \*********************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_template_id_0a3c956b_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/position/ShowAccountPositions.vue?vue&type=template&id=0a3c956b&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_template_id_0a3c956b_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountPositions_vue_vue_type_template_id_0a3c956b_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/adminland/team/ShowAccountTeams.vue":
/*!******************************************************************!*\
  !*** ./resources/js/company/adminland/team/ShowAccountTeams.vue ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowAccountTeams_vue_vue_type_template_id_6dd31a03_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true& */ "./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true&");
/* harmony import */ var _ShowAccountTeams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowAccountTeams.vue?vue&type=script&lang=js& */ "./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ShowAccountTeams_vue_vue_type_style_index_0_id_6dd31a03_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css& */ "./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ShowAccountTeams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowAccountTeams_vue_vue_type_template_id_6dd31a03_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowAccountTeams_vue_vue_type_template_id_6dd31a03_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "6dd31a03",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/adminland/team/ShowAccountTeams.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountTeams.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css& ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_style_index_0_id_6dd31a03_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=style&index=0&id=6dd31a03&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_style_index_0_id_6dd31a03_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_style_index_0_id_6dd31a03_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_style_index_0_id_6dd31a03_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_style_index_0_id_6dd31a03_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_style_index_0_id_6dd31a03_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true& ***!
  \*************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_template_id_6dd31a03_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/adminland/team/ShowAccountTeams.vue?vue&type=template&id=6dd31a03&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_template_id_6dd31a03_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowAccountTeams_vue_vue_type_template_id_6dd31a03_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/company/CreateCompany.vue":
/*!********************************************************!*\
  !*** ./resources/js/company/company/CreateCompany.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CreateCompany_vue_vue_type_template_id_5c04b4e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CreateCompany.vue?vue&type=template&id=5c04b4e2& */ "./resources/js/company/company/CreateCompany.vue?vue&type=template&id=5c04b4e2&");
/* harmony import */ var _CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./CreateCompany.vue?vue&type=script&lang=js& */ "./resources/js/company/company/CreateCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _CreateCompany_vue_vue_type_template_id_5c04b4e2___WEBPACK_IMPORTED_MODULE_0__["render"],
  _CreateCompany_vue_vue_type_template_id_5c04b4e2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/company/CreateCompany.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/company/CreateCompany.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/company/company/CreateCompany.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateCompany.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/CreateCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/company/CreateCompany.vue?vue&type=template&id=5c04b4e2&":
/*!***************************************************************************************!*\
  !*** ./resources/js/company/company/CreateCompany.vue?vue&type=template&id=5c04b4e2& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_template_id_5c04b4e2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateCompany.vue?vue&type=template&id=5c04b4e2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/CreateCompany.vue?vue&type=template&id=5c04b4e2&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_template_id_5c04b4e2___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateCompany_vue_vue_type_template_id_5c04b4e2___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeePosition.vue":
/*!*******************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeePosition.vue ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AssignEmployeePosition_vue_vue_type_template_id_59756b39_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true& */ "./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true&");
/* harmony import */ var _AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssignEmployeePosition.vue?vue&type=script&lang=js& */ "./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _AssignEmployeePosition_vue_vue_type_style_index_0_id_59756b39_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css& */ "./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AssignEmployeePosition_vue_vue_type_template_id_59756b39_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AssignEmployeePosition_vue_vue_type_template_id_59756b39_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "59756b39",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/company/employee/show/AssignEmployeePosition.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css&":
/*!****************************************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_59756b39_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=style&index=0&id=59756b39&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_59756b39_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_59756b39_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_59756b39_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_59756b39_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_style_index_0_id_59756b39_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true& ***!
  \**************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_template_id_59756b39_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeePosition.vue?vue&type=template&id=59756b39&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_template_id_59756b39_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeePosition_vue_vue_type_template_id_59756b39_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeeTeam.vue ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AssignEmployeeTeam_vue_vue_type_template_id_6813d326_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true& */ "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true&");
/* harmony import */ var _AssignEmployeeTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AssignEmployeeTeam.vue?vue&type=script&lang=js& */ "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _AssignEmployeeTeam_vue_vue_type_style_index_0_id_6813d326_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css& */ "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _AssignEmployeeTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AssignEmployeeTeam_vue_vue_type_template_id_6813d326_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AssignEmployeeTeam_vue_vue_type_template_id_6813d326_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "6813d326",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/company/employee/show/AssignEmployeeTeam.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeTeam.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css&":
/*!************************************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css& ***!
  \************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_style_index_0_id_6813d326_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=style&index=0&id=6813d326&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_style_index_0_id_6813d326_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_style_index_0_id_6813d326_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_style_index_0_id_6813d326_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_style_index_0_id_6813d326_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_style_index_0_id_6813d326_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true&":
/*!**********************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true& ***!
  \**********************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_template_id_6813d326_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/AssignEmployeeTeam.vue?vue&type=template&id=6813d326&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_template_id_6813d326_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AssignEmployeeTeam_vue_vue_type_template_id_6813d326_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue":
/*!****************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployee.vue ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowCompanyEmployee_vue_vue_type_template_id_0bc2ef2a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true& */ "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true&");
/* harmony import */ var _ShowCompanyEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowCompanyEmployee.vue?vue&type=script&lang=js& */ "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ShowCompanyEmployee_vue_vue_type_style_index_0_id_0bc2ef2a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css& */ "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ShowCompanyEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowCompanyEmployee_vue_vue_type_template_id_0bc2ef2a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowCompanyEmployee_vue_vue_type_template_id_0bc2ef2a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "0bc2ef2a",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/company/employee/show/ShowCompanyEmployee.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployee.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css&":
/*!*************************************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css& ***!
  \*************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_style_index_0_id_0bc2ef2a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=style&index=0&id=0bc2ef2a&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_style_index_0_id_0bc2ef2a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_style_index_0_id_0bc2ef2a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_style_index_0_id_0bc2ef2a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_style_index_0_id_0bc2ef2a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_style_index_0_id_0bc2ef2a_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true& ***!
  \***********************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_template_id_0bc2ef2a_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployee.vue?vue&type=template&id=0bc2ef2a&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_template_id_0bc2ef2a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployee_vue_vue_type_template_id_0bc2ef2a_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue":
/*!*************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowCompanyEmployeeHierarchy_vue_vue_type_template_id_329a59ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true& */ "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true&");
/* harmony import */ var _ShowCompanyEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js& */ "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ShowCompanyEmployeeHierarchy_vue_vue_type_style_index_0_id_329a59ba_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css& */ "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ShowCompanyEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowCompanyEmployeeHierarchy_vue_vue_type_template_id_329a59ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowCompanyEmployeeHierarchy_vue_vue_type_template_id_329a59ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "329a59ba",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_style_index_0_id_329a59ba_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=style&index=0&id=329a59ba&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_style_index_0_id_329a59ba_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_style_index_0_id_329a59ba_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_style_index_0_id_329a59ba_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_style_index_0_id_329a59ba_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_style_index_0_id_329a59ba_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true&":
/*!********************************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true& ***!
  \********************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_template_id_329a59ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowCompanyEmployeeHierarchy.vue?vue&type=template&id=329a59ba&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_template_id_329a59ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyEmployeeHierarchy_vue_vue_type_template_id_329a59ba_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue":
/*!*************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowEmployeeLogs.vue ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowEmployeeLogs_vue_vue_type_template_id_37f72ce6_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true& */ "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true&");
/* harmony import */ var _ShowEmployeeLogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowEmployeeLogs.vue?vue&type=script&lang=js& */ "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ShowEmployeeLogs_vue_vue_type_style_index_0_id_37f72ce6_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css& */ "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ShowEmployeeLogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowEmployeeLogs_vue_vue_type_template_id_37f72ce6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowEmployeeLogs_vue_vue_type_template_id_37f72ce6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "37f72ce6",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/company/employee/show/ShowEmployeeLogs.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowEmployeeLogs.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css&":
/*!**********************************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_style_index_0_id_37f72ce6_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=style&index=0&id=37f72ce6&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_style_index_0_id_37f72ce6_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_style_index_0_id_37f72ce6_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_style_index_0_id_37f72ce6_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_style_index_0_id_37f72ce6_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_style_index_0_id_37f72ce6_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true&":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true& ***!
  \********************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_template_id_37f72ce6_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/employee/show/ShowEmployeeLogs.vue?vue&type=template&id=37f72ce6&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_template_id_37f72ce6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowEmployeeLogs_vue_vue_type_template_id_37f72ce6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/company/team/ShowCompanyTeam.vue":
/*!***************************************************************!*\
  !*** ./resources/js/company/company/team/ShowCompanyTeam.vue ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowCompanyTeam_vue_vue_type_template_id_30c3260d_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true& */ "./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true&");
/* harmony import */ var _ShowCompanyTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowCompanyTeam.vue?vue&type=script&lang=js& */ "./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ShowCompanyTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowCompanyTeam_vue_vue_type_template_id_30c3260d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowCompanyTeam_vue_vue_type_template_id_30c3260d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "30c3260d",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/company/team/ShowCompanyTeam.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyTeam.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyTeam_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true& ***!
  \**********************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyTeam_vue_vue_type_template_id_30c3260d_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/company/team/ShowCompanyTeam.vue?vue&type=template&id=30c3260d&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyTeam_vue_vue_type_template_id_30c3260d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompanyTeam_vue_vue_type_template_id_30c3260d_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/company/dashboard/ShowCompany.vue":
/*!********************************************************!*\
  !*** ./resources/js/company/dashboard/ShowCompany.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ShowCompany_vue_vue_type_template_id_4fcdcc85_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true& */ "./resources/js/company/dashboard/ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true&");
/* harmony import */ var _ShowCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ShowCompany.vue?vue&type=script&lang=js& */ "./resources/js/company/dashboard/ShowCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ShowCompany_vue_vue_type_style_index_0_id_4fcdcc85_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css& */ "./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ShowCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ShowCompany_vue_vue_type_template_id_4fcdcc85_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ShowCompany_vue_vue_type_template_id_4fcdcc85_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "4fcdcc85",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/company/dashboard/ShowCompany.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/company/dashboard/ShowCompany.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/company/dashboard/ShowCompany.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompany.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css&":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css& ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_style_index_0_id_4fcdcc85_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=style&index=0&id=4fcdcc85&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_style_index_0_id_4fcdcc85_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_style_index_0_id_4fcdcc85_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_style_index_0_id_4fcdcc85_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_style_index_0_id_4fcdcc85_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_style_index_0_id_4fcdcc85_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/company/dashboard/ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/company/dashboard/ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_template_id_4fcdcc85_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/company/dashboard/ShowCompany.vue?vue&type=template&id=4fcdcc85&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_template_id_4fcdcc85_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ShowCompany_vue_vue_type_template_id_4fcdcc85_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/form/LoadingButton.vue":
/*!********************************************************!*\
  !*** ./resources/js/components/form/LoadingButton.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _LoadingButton_vue_vue_type_template_id_48e2f524___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=template&id=48e2f524& */ "./resources/js/components/form/LoadingButton.vue?vue&type=template&id=48e2f524&");
/* harmony import */ var _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LoadingButton.vue?vue&type=script&lang=js& */ "./resources/js/components/form/LoadingButton.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LoadingButton_vue_vue_type_template_id_48e2f524___WEBPACK_IMPORTED_MODULE_0__["render"],
  _LoadingButton_vue_vue_type_template_id_48e2f524___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/form/LoadingButton.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/form/LoadingButton.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/form/LoadingButton.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./LoadingButton.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/form/LoadingButton.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/form/LoadingButton.vue?vue&type=template&id=48e2f524&":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/form/LoadingButton.vue?vue&type=template&id=48e2f524& ***!
  \***************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_48e2f524___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./LoadingButton.vue?vue&type=template&id=48e2f524& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/form/LoadingButton.vue?vue&type=template&id=48e2f524&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_48e2f524___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_LoadingButton_vue_vue_type_template_id_48e2f524___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/header/HeaderMenu.vue":
/*!*******************************************************!*\
  !*** ./resources/js/components/header/HeaderMenu.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _HeaderMenu_vue_vue_type_template_id_6e7694b3_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true& */ "./resources/js/components/header/HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true&");
/* harmony import */ var _HeaderMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./HeaderMenu.vue?vue&type=script&lang=js& */ "./resources/js/components/header/HeaderMenu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _HeaderMenu_vue_vue_type_style_index_0_id_6e7694b3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css& */ "./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _HeaderMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _HeaderMenu_vue_vue_type_template_id_6e7694b3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _HeaderMenu_vue_vue_type_template_id_6e7694b3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "6e7694b3",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/header/HeaderMenu.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/header/HeaderMenu.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/header/HeaderMenu.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./HeaderMenu.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css&":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css& ***!
  \****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_style_index_0_id_6e7694b3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=style&index=0&id=6e7694b3&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_style_index_0_id_6e7694b3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_style_index_0_id_6e7694b3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_style_index_0_id_6e7694b3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_style_index_0_id_6e7694b3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_style_index_0_id_6e7694b3_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/header/HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/header/HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_template_id_6e7694b3_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/header/HeaderMenu.vue?vue&type=template&id=6e7694b3&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_template_id_6e7694b3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_HeaderMenu_vue_vue_type_template_id_6e7694b3_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/icons/IconDelete.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/icons/IconDelete.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IconDelete_vue_vue_type_template_id_4cd6cf84_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true& */ "./resources/js/components/icons/IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true&");
/* harmony import */ var _IconDelete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IconDelete.vue?vue&type=script&lang=js& */ "./resources/js/components/icons/IconDelete.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _IconDelete_vue_vue_type_style_index_0_id_4cd6cf84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css& */ "./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _IconDelete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IconDelete_vue_vue_type_template_id_4cd6cf84_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _IconDelete_vue_vue_type_template_id_4cd6cf84_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "4cd6cf84",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/icons/IconDelete.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/icons/IconDelete.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/icons/IconDelete.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./IconDelete.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css& ***!
  \***************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_style_index_0_id_4cd6cf84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--6-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--6-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=style&index=0&id=4cd6cf84&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_style_index_0_id_4cd6cf84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_style_index_0_id_4cd6cf84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_style_index_0_id_4cd6cf84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_style_index_0_id_4cd6cf84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_style_index_0_id_4cd6cf84_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/icons/IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/icons/IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true& ***!
  \*************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_template_id_4cd6cf84_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/icons/IconDelete.vue?vue&type=template&id=4cd6cf84&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_template_id_4cd6cf84_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IconDelete_vue_vue_type_template_id_4cd6cf84_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/home/Home.vue":
/*!************************************!*\
  !*** ./resources/js/home/Home.vue ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Home_vue_vue_type_template_id_dacc1fbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Home.vue?vue&type=template&id=dacc1fbe&scoped=true& */ "./resources/js/home/Home.vue?vue&type=template&id=dacc1fbe&scoped=true&");
/* harmony import */ var _Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Home.vue?vue&type=script&lang=js& */ "./resources/js/home/Home.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Home_vue_vue_type_style_index_0_id_dacc1fbe_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css& */ "./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Home_vue_vue_type_template_id_dacc1fbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Home_vue_vue_type_template_id_dacc1fbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "dacc1fbe",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/home/Home.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/home/Home.vue?vue&type=script&lang=js&":
/*!*************************************************************!*\
  !*** ./resources/js/home/Home.vue?vue&type=script&lang=js& ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css& ***!
  \*********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_dacc1fbe_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=style&index=0&id=dacc1fbe&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_dacc1fbe_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_dacc1fbe_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_dacc1fbe_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_dacc1fbe_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_style_index_0_id_dacc1fbe_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/home/Home.vue?vue&type=template&id=dacc1fbe&scoped=true&":
/*!*******************************************************************************!*\
  !*** ./resources/js/home/Home.vue?vue&type=template&id=dacc1fbe&scoped=true& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_dacc1fbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Home.vue?vue&type=template&id=dacc1fbe&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/home/Home.vue?vue&type=template&id=dacc1fbe&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_dacc1fbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Home_vue_vue_type_template_id_dacc1fbe_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/partials/Errors.vue":
/*!******************************************!*\
  !*** ./resources/js/partials/Errors.vue ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Errors_vue_vue_type_template_id_45e1651a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Errors.vue?vue&type=template&id=45e1651a& */ "./resources/js/partials/Errors.vue?vue&type=template&id=45e1651a&");
/* harmony import */ var _Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Errors.vue?vue&type=script&lang=js& */ "./resources/js/partials/Errors.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Errors_vue_vue_type_template_id_45e1651a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Errors_vue_vue_type_template_id_45e1651a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/partials/Errors.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/partials/Errors.vue?vue&type=script&lang=js&":
/*!*******************************************************************!*\
  !*** ./resources/js/partials/Errors.vue?vue&type=script&lang=js& ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Errors.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Errors.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/partials/Errors.vue?vue&type=template&id=45e1651a&":
/*!*************************************************************************!*\
  !*** ./resources/js/partials/Errors.vue?vue&type=template&id=45e1651a& ***!
  \*************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_template_id_45e1651a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Errors.vue?vue&type=template&id=45e1651a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Errors.vue?vue&type=template&id=45e1651a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_template_id_45e1651a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Errors_vue_vue_type_template_id_45e1651a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/partials/Layout.vue":
/*!******************************************!*\
  !*** ./resources/js/partials/Layout.vue ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Layout_vue_vue_type_template_id_10a60d4e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Layout.vue?vue&type=template&id=10a60d4e&scoped=true& */ "./resources/js/partials/Layout.vue?vue&type=template&id=10a60d4e&scoped=true&");
/* harmony import */ var _Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Layout.vue?vue&type=script&lang=js& */ "./resources/js/partials/Layout.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _Layout_vue_vue_type_style_index_0_id_10a60d4e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css& */ "./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Layout_vue_vue_type_template_id_10a60d4e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Layout_vue_vue_type_template_id_10a60d4e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "10a60d4e",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/partials/Layout.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/partials/Layout.vue?vue&type=script&lang=js&":
/*!*******************************************************************!*\
  !*** ./resources/js/partials/Layout.vue?vue&type=script&lang=js& ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css& ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_10a60d4e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=style&index=0&id=10a60d4e&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_10a60d4e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_10a60d4e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_10a60d4e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_10a60d4e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_style_index_0_id_10a60d4e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/partials/Layout.vue?vue&type=template&id=10a60d4e&scoped=true&":
/*!*************************************************************************************!*\
  !*** ./resources/js/partials/Layout.vue?vue&type=template&id=10a60d4e&scoped=true& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_template_id_10a60d4e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Layout.vue?vue&type=template&id=10a60d4e&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/partials/Layout.vue?vue&type=template&id=10a60d4e&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_template_id_10a60d4e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Layout_vue_vue_type_template_id_10a60d4e_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/regis/htdocs/homas/resources/js/app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! /Users/regis/htdocs/homas/resources/sass/app.scss */"./resources/sass/app.scss");


/***/ })

},[[0,"/js/manifest","/js/vendor"]]]);