import { Controller } from "@hotwired/stimulus";

export default class extends Controller<HTMLTableElement> {
  static targets = ["columnAll", "rowAll", "checkbox"];

  declare readonly columnAllTargets: HTMLInputElement[];
  declare readonly rowAllTargets: HTMLInputElement[];
  declare readonly checkboxTargets: HTMLInputElement[];

  connect() {
    this.init();
  }

  init() {
    this.updateRowState();
    this.updateColumnState();

    this.checkboxTargets.forEach((element) => {
      element.addEventListener("input", () => {
        this.updateRowState();
        this.updateColumnState();
      });
    });
  }

  toggleRow({ currentTarget, params: { row } }) {
    this.checkboxTargets.forEach((element) => {
      const rowId = parseInt(element.getAttribute("data-row-id"));
      if (rowId !== row) {
        return;
      }

      element.checked = currentTarget.checked;
    });

    this.updateColumnState();
  }

  toggleColumn({ currentTarget, params: { column } }) {
    this.checkboxTargets.forEach((element) => {
      const columnId = parseInt(element.getAttribute("data-column-id"));
      if (columnId !== column) {
        return;
      }

      element.checked = currentTarget.checked;
    });

    this.updateRowState();
  }

  private updateRowState() {
    this.rowAllTargets.forEach((element) => {
      const rowId = parseInt(element.getAttribute("data-row-id"));
      const state = this.getRowState(rowId);

      if (null === state) {
        element.indeterminate = true;

        return;
      }

      element.indeterminate = false;
      element.checked = state;
    });
  }

  private getRowState(rowId: number): boolean | null {
    const states = [];

    this.checkboxTargets.forEach((element) => {
      if (rowId !== parseInt(element.getAttribute("data-row-id"))) {
        return;
      }

      if (!states.includes(element.checked)) {
        states.push(element.checked);
      }
    });

    if (states.length > 1) {
      return null;
    }

    return states[0];
  }

  private updateColumnState() {
    this.columnAllTargets.forEach((element) => {
      const columnId = parseInt(element.getAttribute("data-column-id"));
      const state = this.getColumnState(columnId);

      if (null === state) {
        element.indeterminate = true;

        return;
      }

      element.indeterminate = false;
      element.checked = state;
    });
  }

  private getColumnState(columnId: number): boolean | null {
    const states = [];

    this.checkboxTargets.forEach((element) => {
      if (columnId !== parseInt(element.getAttribute("data-column-id"))) {
        return;
      }

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
