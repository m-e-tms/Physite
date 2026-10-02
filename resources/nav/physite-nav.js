(function () {
  "use strict";

  var script = document.currentScript;
  if (!script) {
    script = document.querySelector("script[data-physite-nav]");
  }
  if (!script || script.dataset.booted === "1") return;
  script.dataset.booted = "1";

  var scriptUrl = new URL(script.src, window.location.href);
  var root = new URL("../../", scriptUrl);

  function page(path) {
    return new URL(path, root).href;
  }

  if (!document.querySelector("link[data-physite-nav-css]")) {
    var css = document.createElement("link");
    css.rel = "stylesheet";
    css.href = new URL("physite-nav.css", scriptUrl).href;
    css.setAttribute("data-physite-nav-css", "");
    document.head.appendChild(css);
  }

  document.documentElement.classList.add("physite-nav-on");

  var nav = document.querySelector("nav.physite-nav");
  if (!nav) {
    nav = document.createElement("nav");
    nav.className = "physite-nav";
    document.body.insertBefore(nav, document.body.firstChild);
  }
  nav.setAttribute("aria-label", "Physite");
  nav.replaceChildren();

  var needed = 60;
  var currentPad = parseFloat(window.getComputedStyle(document.body).paddingTop) || 0;
  if (currentPad < needed) {
    document.body.style.paddingTop = needed + "px";
  }

  function el(tag, className, text) {
    var node = document.createElement(tag);
    if (className) node.className = className;
    if (text) node.textContent = text;
    return node;
  }

  function isHere(href) {
    try {
      var there = new URL(href, window.location.href);
      var strip = function (value) {
        return decodeURIComponent(value).replace(/\\/g, "/").replace(/\/+$/, "").toLowerCase();
      };
      return strip(there.pathname) === strip(window.location.pathname);
    } catch (err) {
      return false;
    }
  }

  function jump(label, path, hint) {
    var anchor = el("a", "physite-jump", label);
    anchor.href = page(path);
    if (hint) anchor.appendChild(el("small", "", hint));
    if (isHere(anchor.href)) anchor.setAttribute("aria-current", "page");
    return anchor;
  }

  var brand = el("a", "physite-brand");
  brand.href = page("infra/Hub_Themen/Hub_Themen.html");
  brand.setAttribute("aria-label", "Physite Start");
  brand.innerHTML =
    '<span class="physite-mark" aria-hidden="true">' +
    '<svg viewBox="0 0 24 24" fill="none">' +
    '<circle cx="12" cy="12" r="2.2" fill="white"/>' +
    '<ellipse cx="12" cy="12" rx="9" ry="4.2" stroke="white" stroke-width="1.6"/>' +
    '<ellipse cx="12" cy="12" rx="9" ry="4.2" stroke="white" stroke-width="1.6" transform="rotate(60 12 12)"/>' +
    "</svg></span>";
  var brandText = el("span", "physite-brand-text");
  brandText.appendChild(el("strong", "", "Physite"));
  brandText.appendChild(el("span", "", "Physik"));
  brand.appendChild(brandText);
  nav.appendChild(brand);

  var toggle = el("button", "physite-toggle");
  toggle.type = "button";
  toggle.setAttribute("aria-expanded", "false");
  toggle.setAttribute("aria-label", "Menü öffnen");
  toggle.innerHTML = '<span class="physite-toggle-box"></span>';
  nav.appendChild(toggle);

  var bar = el("div", "physite-bar");
  nav.appendChild(bar);

  var items = [];

  function addMenu(label, panel) {
    var item = el("div", "physite-item");
    var button = el("button", "physite-menu-btn");
    button.type = "button";
    var menuLabel = el("span", "physite-menu-label", label);
    button.appendChild(menuLabel);
    button.appendChild(el("span", "physite-caret"));
    button.setAttribute("aria-expanded", "false");
    var panelId = "physite-panel-" + items.length;
    panel.id = panelId;
    button.setAttribute("aria-controls", panelId);
    item.appendChild(button);
    item.appendChild(panel);
    bar.appendChild(item);
    items.push({ item: item, button: button, panel: panel });

    button.addEventListener("click", function (event) {
      event.stopPropagation();
      var willOpen = !item.classList.contains("is-open");
      closeMenus();
      if (willOpen) setOpen(item, true);
    });

    return item;
  }

  function setOpen(item, open) {
    item.classList.toggle("is-open", open);
    var button = item.querySelector(".physite-menu-btn");
    if (button) button.setAttribute("aria-expanded", open ? "true" : "false");
  }

  function closeMenus() {
    items.forEach(function (entry) { setOpen(entry.item, false); });
  }

  function menuOf(label, entries) {
    var panel = el("div", "physite-panel");
    entries.forEach(function (entry) {
      panel.appendChild(jump(entry[0], entry[1]));
    });
    var item = addMenu(label, panel);
    if (panel.querySelector("[aria-current='page']")) {
      item.querySelector(".physite-menu-label").prepend(el("span", "physite-dot"));
    }
    return item;
  }

  menuOf("Stufen", [
    ["Mittelstufe", "infra/Sub_Hubs/Sub_Hub_Mittelstufe.html"],
    ["E-Jahrgang", "infra/Sub_Hubs/Sub_Hub_EJahrgang.html"],
    ["Oberstufe", "infra/Sub_Hubs/Sub_Hub_Oberstufe.html"]
  ]);

  menuOf("Themen", [
    ["Mechanik", "infra/Sub_Hubs/Sub_Hub_Mechanik.html"],
    ["Schwingungen und Wellen", "infra/Sub_Hubs/Sub_Hub_Schwingungen_Wellen.html"],
    ["E-Lehre und Magnetismus", "infra/Sub_Hubs/Sub_Hub_ELehre_Magnetismus.html"],
    ["Lichteigenschaften", "infra/Sub_Hubs/Sub_Hub_Lichteigenschaften.html"]
  ]);

  var spielen = el("div", "physite-panel");
  spielen.appendChild(el("p", "physite-label", "Spiele"));
  [
    ["Kopfrechnen", "content/games/Kopfrechnen_Unendlich/startfeld.html"],
    ["Rätsel", "content/games/raetsel/quiz_Startseite.html"],
    ["Stochastik", "content/games/Stochastik_Glücksspiel/STOCHASTIK_FERTIGGGGGGG.php"],
    ["Spiele-Übersicht", "infra/Sub_Hubs/Sub_Hub_Games.html"]
  ].forEach(function (entry) {
    spielen.appendChild(jump(entry[0], entry[1]));
  });
  var spielenItem = addMenu("Spielen", spielen);
  if (spielen.querySelector("[aria-current='page']")) {
    spielenItem.querySelector(".physite-menu-label").prepend(el("span", "physite-dot"));
  }

  var canHover = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
  var closeTimer = 0;
  if (canHover && window.matchMedia("(min-width: 721px)").matches) {
    items.forEach(function (entry) {
      entry.item.addEventListener("mouseenter", function () {
        if (window.innerWidth <= 720) return;
        window.clearTimeout(closeTimer);
        closeMenus();
        setOpen(entry.item, true);
      });
      entry.item.addEventListener("mouseleave", function () {
        closeTimer = window.setTimeout(function () { setOpen(entry.item, false); }, 160);
      });
    });
  }

  toggle.addEventListener("click", function (event) {
    event.stopPropagation();
    var open = !nav.classList.contains("is-menu-open");
    nav.classList.toggle("is-menu-open", open);
    toggle.setAttribute("aria-expanded", open ? "true" : "false");
    toggle.setAttribute("aria-label", open ? "Menü schließen" : "Menü öffnen");
    if (!open) closeMenus();
  });

  document.addEventListener("click", function (event) {
    if (!nav.contains(event.target)) {
      closeMenus();
      nav.classList.remove("is-menu-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Menü öffnen");
    }
  });

  document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape") return;
    closeMenus();
    nav.classList.remove("is-menu-open");
    toggle.setAttribute("aria-expanded", "false");
    toggle.setAttribute("aria-label", "Menü öffnen");
  });

  window.addEventListener("resize", function () {
    if (window.innerWidth > 720) {
      nav.classList.remove("is-menu-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Menü öffnen");
    }
  });
})();
