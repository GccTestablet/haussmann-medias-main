import { Controller } from "@hotwired/stimulus";

export default class extends Controller<HTMLTableElement> {
  static targets = ["rowAll", "input"];

  declare readonly rowAllTargets: HTMLInputElement[];
  declare readonly inputTargets: HTMLInputElement[];

  fillRow({ currentTarget, params: { row } }) {
    this.inputTargets.forEach((element) => {
      const rowId = parseInt(element.getAttribute("data-row-id"));
      if (rowId !== row) {
        return;
      }

      element.value = currentTarget.value;
    });
  }
}
