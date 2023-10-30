import { Controller } from "@hotwired/stimulus";
import { easepick, PresetPlugin, RangePlugin, AmpPlugin } from "@easepick/bundle";

export default class extends Controller<HTMLInputElement> {
  static values = {
    dateFormat: {
      type: String,
      default: "DD/MM/YYYY",
    },
  };

  declare readonly dateFormatValue: string;

  connect() {
    this.init();
  }

  init() {
    const currentYear = new Date().getFullYear();
    new easepick.create({
      element: this.element,
      lang: "fr-FR",
      format: this.dateFormatValue,
      css: [
        "https://cdn.jsdelivr.net/npm/@easepick/core@1.2.1/dist/index.css",
        "https://cdn.jsdelivr.net/npm/@easepick/amp-plugin@1.2.1/dist/index.css",
        "https://cdn.jsdelivr.net/npm/@easepick/range-plugin@1.2.1/dist/index.css",
        "https://cdn.jsdelivr.net/npm/@easepick/preset-plugin@1.2.1/dist/index.css",
      ],
      zIndex: 2,
      plugins: [RangePlugin, PresetPlugin, AmpPlugin],
      RangePlugin: {
        locale: {
          one: "jour",
          other: "jours",
        },
      },
      PresetPlugin: {
        customLabels: ["Aujourd'hui", "Hier", "7 derniers jours", "30 derniers jours", "Ce mois ci", "Mois précédent"],
      },
      AmpPlugin: {
        dropdown: {
          months: true,
          years: true,
          minYear: currentYear - 50,
          maxYear: currentYear,
        },
        darkMode: false,
        resetButton: true,
      },
    });
  }
}
