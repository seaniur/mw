/**
 * Header interactions: sticky/compact state, the Products mega menu, the
 * mobile nav drawer + accordion, and the search toggle. Vanilla JS, no
 * dependencies, everything reachable by click/tap and keyboard — nothing
 * here depends on :hover alone, per the brief's accessibility requirements.
 */
(function () {
	'use strict';

	function onReady(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	/* ---- Sticky/compact header --------------------------------------- */
	function initStickyHeader() {
		var header = document.querySelector('[data-site-header]');
		if (!header) {
			return;
		}
		var ticking = false;
		function update() {
			header.classList.toggle('is-compact', window.scrollY > 24);
			ticking = false;
		}
		window.addEventListener(
			'scroll',
			function () {
				if (!ticking) {
					window.requestAnimationFrame(update);
					ticking = true;
				}
			},
			{ passive: true }
		);
		update();
	}

	/* ---- Products mega menu -------------------------------------------
	 * Opens on click (works identically for touch, mouse, and keyboard).
	 * Closes on: clicking the trigger again, clicking outside, Escape, or
	 * activating a link inside — never on a stray mouseleave, so a cursor
	 * crossing the gap to the panel doesn't dismiss it.
	 */
	function initMegaMenu() {
		var trigger = document.querySelector('[data-mega-trigger]');
		var panel = document.querySelector('[data-mega-panel]');
		if (!trigger || !panel) {
			return;
		}

		function close() {
			panel.hidden = true;
			trigger.setAttribute('aria-expanded', 'false');
		}
		function open() {
			panel.hidden = false;
			trigger.setAttribute('aria-expanded', 'true');
		}

		trigger.addEventListener('click', function () {
			if (panel.hidden) {
				open();
			} else {
				close();
			}
		});

		document.addEventListener('click', function (e) {
			if (!panel.hidden && !panel.contains(e.target) && !trigger.contains(e.target)) {
				close();
			}
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && !panel.hidden) {
				close();
				trigger.focus();
			}
		});

		panel.addEventListener('click', function (e) {
			if (e.target.closest('a')) {
				close();
			}
		});
	}

	/* ---- Mobile nav drawer --------------------------------------------- */
	function initMobileNav() {
		var toggle = document.querySelector('[data-mobile-nav-toggle]');
		var drawer = document.querySelector('[data-mobile-nav]');
		if (!toggle || !drawer) {
			return;
		}
		var closeButtons = drawer.querySelectorAll('[data-mobile-nav-close]');

		function open() {
			drawer.hidden = false;
			requestAnimationFrame(function () {
				drawer.classList.add('is-open');
			});
			toggle.setAttribute('aria-expanded', 'true');
			document.documentElement.style.overflow = 'hidden';
		}
		function close() {
			drawer.classList.remove('is-open');
			toggle.setAttribute('aria-expanded', 'false');
			document.documentElement.style.overflow = '';
			window.setTimeout(function () {
				drawer.hidden = true;
			}, 220);
		}

		toggle.addEventListener('click', open);
		closeButtons.forEach(function (btn) {
			btn.addEventListener('click', close);
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
				close();
				toggle.focus();
			}
		});

		drawer.querySelectorAll('[data-accordion-trigger]').forEach(function (trigger) {
			var panel = document.getElementById(trigger.getAttribute('aria-controls'));
			if (!panel) {
				return;
			}
			trigger.addEventListener('click', function () {
				var isOpen = trigger.getAttribute('aria-expanded') === 'true';
				trigger.setAttribute('aria-expanded', String(!isOpen));
				panel.hidden = isOpen;
			});
		});
	}

	/* ---- Header search toggle ------------------------------------------ */
	function initSearchToggle() {
		var toggle = document.querySelector('[data-search-toggle]');
		var panel = document.querySelector('[data-search-panel]');
		if (!toggle || !panel) {
			return;
		}
		toggle.addEventListener('click', function () {
			var isOpen = panel.hasAttribute('data-open');
			if (isOpen) {
				panel.removeAttribute('data-open');
				toggle.setAttribute('aria-expanded', 'false');
			} else {
				panel.setAttribute('data-open', '');
				toggle.setAttribute('aria-expanded', 'true');
				var input = panel.querySelector('input[type="search"]');
				if (input) {
					window.setTimeout(function () {
						input.focus();
					}, 150);
				}
			}
		});
	}

	/* ---- "Request a Sample" hero CTA preselects the form's radio ------ */
	function initSampleRequestLinks() {
		document.querySelectorAll('[data-sample-request]').forEach(function (link) {
			link.addEventListener('click', function () {
				var form = document.querySelector('[data-inquiry-form]');
				if (!form) {
					return;
				}
				var sampleRadio = form.querySelector('input[name="request_type"][value="sample"]');
				if (sampleRadio) {
					sampleRadio.checked = true;
				}
			});
		});
	}

	onReady(function () {
		initStickyHeader();
		initMegaMenu();
		initMobileNav();
		initSearchToggle();
		initSampleRequestLinks();
	});
})();
