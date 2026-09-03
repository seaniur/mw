/**
 * Powers every Avin_Customize_Repeater_Control instance in the
 * Customizer's controls pane: renders each row from its field schema,
 * handles add/remove/reorder, and keeps the underlying Customizer
 * setting (one JSON string per repeater) in sync on every change so the
 * 'refresh' transport picks it up. Vanilla JS — 'customize-controls' is
 * only a script dependency because wp.customize() has to be ready before
 * this file runs, not because anything here needs jQuery.
 */
(function () {
	'use strict';

	function fieldMarkup(field, rowIndex, value) {
		if (field.type === 'checkbox') {
			return (
				'<label class="avin-repeater-field avin-repeater-field-checkbox">' +
				'<input type="checkbox" data-field="' + field.key + '"' + (value ? ' checked' : '') + '> ' +
				escapeHtml(field.label) +
				'</label>'
			);
		}

		if (field.type === 'image') {
			var attachmentId = value ? parseInt(value, 10) : 0;
			return (
				'<div class="avin-repeater-field avin-repeater-field-image" data-field="' + field.key + '">' +
				'<span class="avin-repeater-field-label">' + escapeHtml(field.label) + '</span>' +
				'<div class="avin-repeater-image-preview" data-image-preview></div>' +
				'<input type="hidden" data-image-value value="' + (attachmentId || '') + '">' +
				'<button type="button" class="button" data-image-select>' + (window.avinRepeaterL10n ? window.avinRepeaterL10n.selectImage : 'Select Image') + '</button> ' +
				'<button type="button" class="button-link" data-image-clear>' + (window.avinRepeaterL10n ? window.avinRepeaterL10n.clear : 'Clear') + '</button>' +
				'</div>'
			);
		}

		if (field.type === 'textarea') {
			return (
				'<label class="avin-repeater-field">' +
				'<span class="avin-repeater-field-label">' + escapeHtml(field.label) + '</span>' +
				'<textarea data-field="' + field.key + '" rows="2" placeholder="' + escapeHtml(field.placeholder || '') + '">' + escapeHtml(value || '') + '</textarea>' +
				'</label>'
			);
		}

		if (field.type === 'select') {
			var optionsHtml = '<option value="">' + escapeHtml(field.placeholder || '—') + '</option>';
			(field.options || []).forEach(function (opt) {
				var selected = String(value || '') === String(opt.value) ? ' selected' : '';
				optionsHtml += '<option value="' + escapeHtml(opt.value) + '"' + selected + '>' + escapeHtml(opt.label) + '</option>';
			});
			return (
				'<label class="avin-repeater-field">' +
				'<span class="avin-repeater-field-label">' + escapeHtml(field.label) + '</span>' +
				'<select data-field="' + field.key + '">' + optionsHtml + '</select>' +
				'</label>'
			);
		}

		var inputType = field.type === 'url' ? 'url' : field.type === 'number' ? 'number' : 'text';
		return (
			'<label class="avin-repeater-field">' +
			'<span class="avin-repeater-field-label">' + escapeHtml(field.label) + '</span>' +
			'<input type="' + inputType + '" data-field="' + field.key + '" value="' + escapeHtml(value || '') + '" placeholder="' + escapeHtml(field.placeholder || '') + '">' +
			'</label>'
		);
	}

	function escapeHtml(str) {
		return String(str == null ? '' : str).replace(/[&<>"']/g, function (ch) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
		});
	}

	function rowMarkup(fields, rowIndex, rowData) {
		var html = '<div class="avin-repeater-row" data-avin-repeater-row>';
		html += '<div class="avin-repeater-row-fields">';
		fields.forEach(function (field) {
			html += fieldMarkup(field, rowIndex, rowData[field.key]);
		});
		html += '</div>';
		html += '<div class="avin-repeater-row-actions">';
		html += '<button type="button" class="button-link" data-avin-repeater-up title="Move up">&uarr;</button>';
		html += '<button type="button" class="button-link" data-avin-repeater-down title="Move down">&darr;</button>';
		html += '<button type="button" class="button-link avin-repeater-remove" data-avin-repeater-remove title="Remove">&#10005;</button>';
		html += '</div>';
		html += '</div>';
		return html;
	}

	function initRepeater(container) {
		var settingId = container.dataset.setting;
		var fields = JSON.parse(container.dataset.fields || '[]');
		var initialRows = JSON.parse(container.dataset.value || '[]');
		var rowsEl = container.querySelector('[data-avin-repeater-rows]');
		var addBtn = container.querySelector('[data-avin-repeater-add]');

		function readRow(rowEl) {
			var row = {};
			fields.forEach(function (field) {
				if (field.type === 'checkbox') {
					var cb = rowEl.querySelector('[data-field="' + field.key + '"]');
					row[field.key] = !!(cb && cb.checked);
				} else if (field.type === 'image') {
					var input = rowEl.querySelector('[data-field="' + field.key + '"] [data-image-value]');
					row[field.key] = input ? input.value : '';
				} else {
					var el = rowEl.querySelector('[data-field="' + field.key + '"]');
					row[field.key] = el ? el.value : '';
				}
			});
			return row;
		}

		function sync() {
			var rows = [];
			rowsEl.querySelectorAll('[data-avin-repeater-row]').forEach(function (rowEl) {
				rows.push(readRow(rowEl));
			});
			if (window.wp && wp.customize) {
				wp.customize(settingId, function (setting) {
					if (setting) {
						setting.set(JSON.stringify(rows));
					}
				});
			}
		}

		function renderThumb(previewEl, attachment) {
			previewEl.innerHTML = '';
			if (!attachment) {
				return;
			}
			var img = document.createElement('img');
			img.src = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
			img.alt = '';
			previewEl.appendChild(img);
		}

		function bindImagePicker(fieldEl) {
			var selectBtn = fieldEl.querySelector('[data-image-select]');
			var clearBtn = fieldEl.querySelector('[data-image-clear]');
			var valueInput = fieldEl.querySelector('[data-image-value]');
			var previewEl = fieldEl.querySelector('[data-image-preview]');
			var frame = null;

			selectBtn.addEventListener('click', function (e) {
				e.preventDefault();
				if (!window.wp || !wp.media) {
					return;
				}
				if (!frame) {
					frame = wp.media({ title: 'Select image', multiple: false });
					frame.on('select', function () {
						var attachment = frame.state().get('selection').first().toJSON();
						valueInput.value = attachment.id;
						renderThumb(previewEl, attachment);
						sync();
					});
				}
				frame.open();
			});

			clearBtn.addEventListener('click', function (e) {
				e.preventDefault();
				valueInput.value = '';
				previewEl.innerHTML = '';
				sync();
			});
		}

		function bindRow(rowEl) {
			rowEl.querySelectorAll('input, textarea').forEach(function (el) {
				el.addEventListener('input', sync);
				el.addEventListener('change', sync);
			});
			rowEl.querySelectorAll('.avin-repeater-field-image').forEach(bindImagePicker);

			rowEl.querySelector('[data-avin-repeater-remove]').addEventListener('click', function () {
				rowEl.remove();
				sync();
			});
			rowEl.querySelector('[data-avin-repeater-up]').addEventListener('click', function () {
				var prev = rowEl.previousElementSibling;
				if (prev) {
					rowsEl.insertBefore(rowEl, prev);
					sync();
				}
			});
			rowEl.querySelector('[data-avin-repeater-down]').addEventListener('click', function () {
				var next = rowEl.nextElementSibling;
				if (next) {
					rowsEl.insertBefore(next, rowEl);
					sync();
				}
			});
		}

		function addRow(rowData) {
			var index = rowsEl.querySelectorAll('[data-avin-repeater-row]').length;
			var wrapper = document.createElement('div');
			wrapper.innerHTML = rowMarkup(fields, index, rowData || {});
			var rowEl = wrapper.firstElementChild;
			rowsEl.appendChild(rowEl);

			// Populate image previews for existing values.
			fields.forEach(function (field) {
				if (field.type !== 'image' || !rowData || !rowData[field.key]) {
					return;
				}
				var fieldEl = rowEl.querySelector('.avin-repeater-field-image[data-field="' + field.key + '"]');
				var previewEl = fieldEl && fieldEl.querySelector('[data-image-preview]');
				if (previewEl && window.wp && wp.media) {
					var attachment = wp.media.attachment(rowData[field.key]);
					attachment.fetch().done(function () {
						renderThumb(previewEl, attachment.toJSON());
					});
				}
			});

			bindRow(rowEl);
		}

		initialRows.forEach(function (row) {
			addRow(row);
		});

		addBtn.addEventListener('click', function () {
			// New rows default every checkbox field (in practice, just
			// "enabled") to checked — an admin adding a box expects it to
			// show up, not to have to remember to flip it on.
			var defaults = {};
			fields.forEach(function (field) {
				if (field.type === 'checkbox') {
					defaults[field.key] = true;
				}
			});
			addRow(defaults);
			sync();
		});
	}

	function boot() {
		document.querySelectorAll('[data-avin-repeater]').forEach(initRepeater);
	}

	if (window.wp && wp.customize) {
		wp.customize.bind('ready', boot);
	} else {
		document.addEventListener('DOMContentLoaded', boot);
	}
})();
