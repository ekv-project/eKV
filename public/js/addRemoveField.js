/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/addRemoveField.js":
/*!****************************************!*\
  !*** ./resources/js/addRemoveField.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

if (document.querySelector('#addField')) {
  var addFieldButton = document.querySelector('#addField');
  var fieldType = addFieldButton.getAttribute('data-field-type');
  var increment = 1;
  addFieldButton.addEventListener('click', function () {
    if (fieldType == 'courseGrade') {
      var parentNode = document.querySelector('#courseGrade');
      var childNode = document.createElement('div');
      var child = '<div class="col border border-dark mt-1 d-flex flex-column justify-content-center align-items-center"><div class="form-floating m-2"><input type="text" class="form-control" id="coursesCode" name="coursesCode[]" placeholder="coursesCode" required><label for="coursesCode">Kod Kursus</label></div><div class="form-floating m-2"><input type="text" class="form-control" id="creditHour" name="creditHour[]" placeholder="creditHour" required><label for="creditHour">Jam Kredit</label></div><div class="form-floating m-2"><input type="text" class="form-control" id="gradePointer" name="gradePointer[]" placeholder="gradePointer" required><label for="gradePointer">Nilai Gred</label></div><button type="button" id="removeField" data-remove-field-type="courseGrade" data-remove-field-id="courseGrade" class="btn btn-danger m-1 fs-hvr-shrink">Keluarkan</button></div>';
      childNode.innerHTML = child;
      parentNode.appendChild(childNode);
      increment++;
    }
  });
} // Remove Button
// Event Delegation


document.querySelector("#courseGrade").addEventListener('click', function (e) {
  if (e.target.id == 'removeField') {
    e.target.parentNode.parentNode.parentNode.removeChild(e.target.parentNode.parentNode);
  }
});

/***/ }),

/***/ 2:
/*!**********************************************!*\
  !*** multi ./resources/js/addRemoveField.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Projects\Web\e-KV\dev\resources\js\addRemoveField.js */"./resources/js/addRemoveField.js");


/***/ })

/******/ });