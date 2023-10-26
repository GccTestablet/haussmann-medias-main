import { Controller } from "@hotwired/stimulus";
import tippy from "tippy.js";

import "tippy.js/dist/tippy.css";
import "tippy.js/themes/light-border.css";

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLElement> {
  static values = {
    content: String,
  };

  declare readonly positionValue: string;
  declare readonly contentValue: string;

  connect() {
    this.init();
  }

  init() {
    tippy(this.element, {
      theme: "light-border",
      allowHTML: true,
      content: this.contentValue,
      placement: "auto",
      hideOnClick: true,
      trigger: "click",
    });
  }
}
