import { Controller } from "@hotwired/stimulus";

interface HTMLTomSelectElement extends HTMLSelectElement {
  tomselect: any;
}

export default class extends Controller<HTMLElement> {
  static targets = ["button"];

  declare readonly buttonTarget: HTMLFormElement;
  declare readonly hasButtonTarget: boolean;

  connect(): void {
    this.init();
  }

  init(): void {
    if (!this.hasButtonTarget) {
      return;
    }

    this.buttonTarget.addEventListener("click", () => {
      const form = this.buttonTarget.closest("form");

      form.querySelectorAll("input[type=text], textarea").forEach((element: HTMLInputElement) => {
        element.value = "";
        element.innerHTML = "";
      });

      form.querySelectorAll("select").forEach((element: HTMLTomSelectElement) => {
        if (element.tomselect) {
          element.tomselect.clear();
        } else {
          element.selectedIndex = 0;
        }
      });

      form.submit();
    });
  }
}
