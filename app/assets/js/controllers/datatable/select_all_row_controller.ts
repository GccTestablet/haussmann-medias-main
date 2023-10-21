import { Controller } from "@hotwired/stimulus";

export default class extends Controller<HTMLTableRowElement> {
  static targets = ["checkAll", "checkbox"];

  declare readonly checkAllTarget: HTMLInputElement;
  declare readonly checkboxTargets: HTMLInputElement[];

  connect() {
    this.init();
  }

  init() {
    this.updateState();
    this.checkboxTargets.forEach((element) => {
      element.addEventListener("input", () => {
        this.updateState();
      });
    });
  }

  toggleAll() {
    this.checkboxTargets.forEach((element) => {
      element.checked = this.checkAllTarget.checked;
    });
  }

  updateState() {
    const state = this.getState();
    if (null === state) {
      this.checkAllTarget.indeterminate = true;

      return;
    }

    this.checkAllTarget.indeterminate = false;
    this.checkAllTarget.checked = state;
  }

  getState(): boolean | null {
    const states = [];

    this.checkboxTargets.forEach((element) => {
      if (!states.includes(element.checked)) {
        states.push(element.checked);
      }
    });

    if (states.length > 1) {
      return null;
    }

    return states[0];
  }
}
