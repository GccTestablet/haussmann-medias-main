import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["collectionContainer"];

  static values = {
    index: Number,
    prototype: String,
  };

  declare readonly collectionContainerTarget: HTMLUListElement;
  declare indexValue: number;
  declare readonly prototypeValue: string;

  addCollectionElement() {
    const item = document.createElement("li");
    item.innerHTML = this.prototypeValue.replace(/__name__/g, String(this.indexValue));
    this.collectionContainerTarget.appendChild(item);
    this.indexValue++;

    this.addFormDeleteLink(item);
  }

  removeCollectionElement(e: Event) {
    const item = (e.target as HTMLButtonElement).closest("li");
    item.remove();
  }

  private addFormDeleteLink = (item: HTMLLIElement) => {
    const removeFormButton = document.createElement("button");
    removeFormButton.classList.add("btn", "btn-danger");
    removeFormButton.innerText = "Delete";

    item.append(removeFormButton);

    removeFormButton.addEventListener("click", (e) => {
      e.preventDefault();
      // remove the li for the tag form
      item.remove();
    });
  };
}
