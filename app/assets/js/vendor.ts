import { Dropdown } from "bootstrap";

document.querySelectorAll(".dropdown-toggle").forEach((element) => {
  new Dropdown(element, {
    popperConfig: {
      strategy: "fixed",
    },
  });
});
