import { Controller } from "@hotwired/stimulus";

export default class extends Controller<HTMLSelectElement> {
  initialize() {
    this._onPreConnect = this._onPreConnect.bind(this);
  }

  connect() {
    this.element.addEventListener("autocomplete:pre-connect", this._onPreConnect);
  }

  disconnect() {
    this.element.removeEventListener("autocomplete:connect", this._onPreConnect);
  }

  _onPreConnect(event) {
    event.detail.options.render = {
      option: (data, escape) => {
        return "<div class='" + escape(data.$option.className) + "'>" + escape(data.text) + "</div>";
      },
    };
  }
}
