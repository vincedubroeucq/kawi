"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance"); }

function _iterableToArray(iter) { if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } }

document.addEventListener('DOMContentLoaded', function () {
  /**
   * Skip link focus fix
   */
  (function () {
    var isIe = /(trident|msie)/i.test(navigator.userAgent);

    if (isIe && document.getElementById && window.addEventListener) {
      window.addEventListener('hashchange', function () {
        var id = location.hash.substring(1),
            element;
        if (!/^[A-z0-9_-]+$/.test(id)) return;
        element = document.getElementById(id);

        if (element) {
          if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
          }

          element.focus();
        }
      }, false);
    }
  })();
  /**
   * Menu toggle handler
   */


  var toggles = document.querySelectorAll('.toggle');

  _toConsumableArray(toggles).forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      var id = toggle.getAttribute('aria-controls');
      var target = document.querySelector("#".concat(id));
      target.classList.toggle('open');
      target.setAttribute('aria-expanded', target.classList.contains('open'));
      var toggles = document.querySelectorAll("[aria-controls=\"".concat(id, "\"]"));
      toggles.forEach(function (toggle) {
        toggle.setAttribute('aria-expanded', target.classList.contains('open'));
      });
    });
  });
  /**
   * Closes any open sidebar on ESC key
   */


  document.addEventListener('keyup', function (e) {
    if (e.key == "Esc" || e.keyCode == 27) {
      var opened = document.querySelectorAll('.open');

      if (opened) {
        _toConsumableArray(toggles).forEach(function (toggle) {
          toggle.setAttribute('aria-expanded', 'false');
        });

        _toConsumableArray(opened).forEach(function (open) {
          open.classList.remove('open');
        });
      }
    }
  });
  /**
   * Closes sidebar when tapped outside.
   */

  document.addEventListener('click', function (e) {
    var menuArea = document.querySelector('.menu-area');
    var toggle = document.querySelector('.menu-toggle');
    if (!menuArea.classList.contains('open')) return;
    if (e.path.includes(toggle)) return;

    if (e.clientX < window.innerWidth - menuArea.offsetWidth) {
      menuArea.classList.remove('open');
      document.querySelectorAll('.menu-toggle').forEach(function (toggle) {
        toggle.setAttribute('aria-expanded', 'false');
      });
    }
  }); // Close the menu when the last focusable element is blurred

  var menuArea = document.querySelector('.menu-area');
  var focusables = menuArea.querySelectorAll('a', 'select', 'input', 'button', 'textarea');
  var lastFocusable = focusables[focusables.length - 1];

  lastFocusable.onblur = function (e) {
    menuArea.classList.remove('open');
    document.querySelectorAll('.menu-toggle').forEach(function (toggle) {
      toggle.setAttribute('aria-expanded', 'false');
    });
  };
});
