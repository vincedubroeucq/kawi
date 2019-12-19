"use strict";

document.addEventListener('DOMContentLoaded', function () {
  var multiCheckboxControls = document.querySelectorAll('.customize-control-multi-checkbox');

  if (multiCheckboxControls) {
    multiCheckboxControls.forEach(function (control) {
      control.addEventListener('change', function (e) {
        var values = [];
        var hiddenField = control.querySelector('[type="hidden"]');
        var checkboxes = control.closest('.customize-control').querySelectorAll('[type="checkbox"]:checked');
        checkboxes.forEach(function (checkbox) {
          values.push(checkbox.value);
        });
        hiddenField.value = values.join();
        hiddenField.dispatchEvent(new Event('change'));
      });
    });
  }
});