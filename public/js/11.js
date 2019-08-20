(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[11],{

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




/***/ })

}]);