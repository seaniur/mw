/**
 * Powers the product edit screen's repeater rows and wp.media pickers.
 * Vanilla JS + event delegation — no build step, no framework, so the
 * meta boxes keep working however WordPress core's admin JS evolves.
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

	function renumberRepeater(table) {
		var rows = table.querySelectorAll('[data-repeater-rows] > [data-repeater-row]');
		rows.forEach(function (row, index) {
			row.querySelectorAll('[name]').forEach(function (input) {
				input.name = input.name.replace(/\[(\d+|__INDEX__)\]/, '[' + index + ']');
			});
		});
	}

	function initRepeaters() {
		document.querySelectorAll('[data-repeater]').forEach(function (table) {
			var addBtn = table.parentElement.querySelector('[data-repeater-add]');
			var template = table.parentElement.querySelector('[data-repeater-template]');
			var body = table.querySelector('[data-repeater-rows]');
			if (!addBtn || !template || !body) {
				return;
			}

			addBtn.addEventListener('click', function () {
				var index = body.querySelectorAll('[data-repeater-row]').length;
				var html = template.innerHTML.replace(/__INDEX__/g, String(index));
				var tmp = document.createElement('tbody');
				tmp.innerHTML = html;
				var row = tmp.firstElementChild;
				body.appendChild(row);
				initMediaPicker(row.querySelector('[data-media-picker]'));
			});

			table.addEventListener('click', function (e) {
				var removeBtn = e.target.closest('[data-repeater-remove]');
				if (!removeBtn) {
					return;
				}
				e.preventDefault();
				var row = removeBtn.closest('[data-repeater-row]');
				if (body.querySelectorAll('[data-repeater-row]').length > 1) {
					row.remove();
				} else {
					row.querySelectorAll('input, textarea').forEach(function (input) {
						input.value = '';
					});
					var preview = row.querySelector('[data-media-preview]');
					if (preview) {
						preview.innerHTML = '';
					}
				}
				renumberRepeater(table);
			});
		});
	}

	function initMediaPicker(picker) {
		if (!picker || picker.dataset.mediaBound) {
			return;
		}
		picker.dataset.mediaBound = '1';

		var multiple = picker.dataset.multiple === '1';
		var preview = picker.querySelector('[data-media-preview]');
		var valueInput = picker.querySelector('[data-media-value]');
		var selectBtn = picker.querySelector('[data-media-select]');
		var clearBtn = picker.querySelector('[data-media-clear]');
		var frame = null;

		function renderThumb(attachment) {
			var span = document.createElement('span');
			span.className = 'avin-media-thumb';
			span.dataset.id = attachment.id;
			if (attachment.type === 'image') {
				var img = document.createElement('img');
				img.src = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
				img.alt = '';
				span.appendChild(img);
			} else {
				span.classList.add('avin-media-file');
				span.textContent = attachment.filename || attachment.title;
			}
			return span;
		}

		function setIds(ids) {
			valueInput.value = ids.join(',');
		}

		selectBtn.addEventListener('click', function (e) {
			e.preventDefault();
			if (!window.wp || !wp.media) {
				return;
			}
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media({
				title: multiple ? 'Select images' : 'Select a file',
				multiple: multiple,
			});
			frame.on('select', function () {
				var selection = frame.state().get('selection').toJSON();
				if (multiple) {
					// The new selection replaces the picker's contents — matches
					// a typical "pick your gallery" mental model better than
					// silently appending on every reopen.
					var ids = [];
					var seen = {};
					preview.innerHTML = '';
					selection.forEach(function (att) {
						if (seen[att.id]) {
							return;
						}
						seen[att.id] = true;
						ids.push(att.id);
						preview.appendChild(renderThumb(att));
					});
					setIds(ids);
				} else {
					var att = selection[0];
					preview.innerHTML = '';
					preview.appendChild(renderThumb(att));
					valueInput.value = att.id;
				}
			});
			frame.open();
		});

		clearBtn.addEventListener('click', function (e) {
			e.preventDefault();
			preview.innerHTML = '';
			valueInput.value = '';
		});
	}

	onReady(function () {
		initRepeaters();
		document.querySelectorAll('[data-media-picker]').forEach(initMediaPicker);
	});
})();
