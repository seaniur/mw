(function () {
  "use strict";

  /* ---------- Header: scroll shadow ---------- */
  var header = document.getElementById("site-header");
  var mobileMenu = document.getElementById("mobile-menu");
  var menuToggle = document.getElementById("mobile-menu-toggle");
  var iconMenu = document.getElementById("icon-menu");
  var iconClose = document.getElementById("icon-close");
  var menuOpen = false;

  function updateHeaderState() {
    if (!header) return;
    var scrolled = window.scrollY > 12;
    header.classList.toggle("scrolled", scrolled || menuOpen);
  }

  if (header) {
    updateHeaderState();
    window.addEventListener("scroll", updateHeaderState, { passive: true });
  }

  /* ---------- Mobile menu toggle ---------- */
  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener("click", function () {
      menuOpen = !menuOpen;
      mobileMenu.classList.toggle("open", menuOpen);
      menuToggle.setAttribute("aria-expanded", String(menuOpen));
      menuToggle.setAttribute("aria-label", menuOpen ? "Close menu" : "Open menu");
      if (iconMenu) iconMenu.classList.toggle("hidden", menuOpen);
      if (iconClose) iconClose.classList.toggle("hidden", !menuOpen);
      updateHeaderState();
    });

    mobileMenu.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        menuOpen = false;
        mobileMenu.classList.remove("open");
        menuToggle.setAttribute("aria-expanded", "false");
        menuToggle.setAttribute("aria-label", "Open menu");
        if (iconMenu) iconMenu.classList.remove("hidden");
        if (iconClose) iconClose.classList.add("hidden");
        updateHeaderState();
      });
    });
  }

  /* ---------- Scroll-reveal (mirrors the old Framer Motion Reveal/Stagger) ---------- */
  var prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  var revealEls = document.querySelectorAll(".reveal");

  if (prefersReducedMotion || !("IntersectionObserver" in window)) {
    revealEls.forEach(function (el) {
      el.classList.add("is-visible");
    });
  } else {
    var revealObserver = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1, rootMargin: "-10% 0px -10% 0px" }
    );
    revealEls.forEach(function (el) {
      revealObserver.observe(el);
    });
  }

  /* ---------- Animated counters (homepage stats) ---------- */
  var counters = document.querySelectorAll(".counter");

  function animateCounter(el) {
    var target = parseFloat(el.getAttribute("data-value") || "0");
    var prefix = el.getAttribute("data-prefix") || "";
    var suffix = el.getAttribute("data-suffix") || "";
    var format = el.getAttribute("data-format") === "true";

    if (prefersReducedMotion) {
      el.textContent = prefix + (format ? target.toLocaleString("en-US") : target) + suffix;
      return;
    }

    var duration = 1400;
    var start = null;

    function ease(t) {
      // Matches Framer Motion's [0.16, 1, 0.3, 1] "ease out" feel closely enough.
      return 1 - Math.pow(1 - t, 3);
    }

    function step(timestamp) {
      if (start === null) start = timestamp;
      var progress = Math.min((timestamp - start) / duration, 1);
      var value = Math.round(target * ease(progress));
      el.textContent = prefix + (format ? value.toLocaleString("en-US") : value) + suffix;
      if (progress < 1) {
        window.requestAnimationFrame(step);
      }
    }

    window.requestAnimationFrame(step);
  }

  if (counters.length) {
    if (prefersReducedMotion || !("IntersectionObserver" in window)) {
      counters.forEach(animateCounter);
    } else {
      var counterObserver = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              animateCounter(entry.target);
              counterObserver.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.1, rootMargin: "-10% 0px -10% 0px" }
      );
      counters.forEach(function (el) {
        counterObserver.observe(el);
      });
    }
  }

  /* ---------- World map: mobile tap tooltip ---------- */
  var mapTooltip = document.getElementById("map-tooltip");
  document.querySelectorAll(".map-marker").forEach(function (marker) {
    var label = marker.getAttribute("data-label");
    if (!label || !mapTooltip) return;

    function show() {
      mapTooltip.textContent = label;
      mapTooltip.classList.remove("hidden");
    }
    function hide() {
      mapTooltip.classList.add("hidden");
    }

    marker.addEventListener("mouseenter", show);
    marker.addEventListener("mouseleave", hide);
    marker.addEventListener("focus", show);
    marker.addEventListener("blur", hide);
    marker.addEventListener("touchstart", show, { passive: true });
  });

  /* ---------- Contact form ---------- */
  var form = document.getElementById("contact-form");
  if (form && window.metwiserContact) {
    var errorBox = document.getElementById("contact-form-error");
    var errorText = document.getElementById("contact-form-error-text");
    var submitLabel = document.getElementById("contact-form-submit-label");
    var successPanel = document.getElementById("contact-form-success");
    var submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      if (errorBox) errorBox.classList.add("hidden");
      if (submitButton) submitButton.disabled = true;
      if (submitLabel) submitLabel.textContent = "Sending...";

      var formData = new FormData(form);
      formData.set("nonce", metwiserContact.nonce);

      fetch(metwiserContact.ajaxUrl, {
        method: "POST",
        body: formData,
      })
        .then(function (response) {
          return response.json().then(function (json) {
            return { ok: response.ok, json: json };
          });
        })
        .then(function (result) {
          if (!result.ok || !result.json || !result.json.success) {
            var message =
              (result.json && result.json.data && result.json.data.message) ||
              "Something went wrong. Please try again.";
            throw new Error(message);
          }
          form.classList.add("hidden");
          if (successPanel) {
            successPanel.classList.remove("hidden");
            successPanel.classList.add("flex");
          }
        })
        .catch(function (err) {
          if (errorText) errorText.textContent = err.message || "Something went wrong. Please try again or email us directly.";
          if (errorBox) errorBox.classList.remove("hidden");
        })
        .finally(function () {
          if (submitButton) submitButton.disabled = false;
          if (submitLabel) submitLabel.textContent = "Send Message";
        });
    });
  }
})();
