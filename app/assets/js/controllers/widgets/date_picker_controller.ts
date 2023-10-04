import { Controller } from "@hotwired/stimulus";
import { Datepicker as DatePickerJS } from "vanillajs-datepicker";
// @ts-ignore
import fr from "vanillajs-datepicker/locales/fr";

export default class extends Controller<HTMLInputElement> {
  connect() {
    this.init();
  }

  init() {
    Object.assign(DatePickerJS.locales, fr);

    new DatePickerJS(this.element, {
      autohide: true,
      language: "fr",
      orientation: "auto",
    });
  }
}
