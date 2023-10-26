import { Controller } from "@hotwired/stimulus";

export default class extends Controller<HTMLTableElement> {
  static targets = ["button", "row", "expandableRow"];

  declare readonly buttonTarget: HTMLButtonElement;
  declare readonly expandableRowTargets: HTMLDivElement[];

  expand({ params: { work } }) {
    this.expandableRowTargets.forEach((element) => {
      const workId = element.getAttribute("data-work-id");
      if (work !== parseInt(workId)) {
        return;
      }

      element.classList.toggle("d-none");
    });

    this.buttonTarget.querySelector("i").classList.toggle("rotate-90");
  }
}
