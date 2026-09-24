/**
 * WP ContentKit - Classic Editor Modal & Inline HTML Generator
 */

(function ($) {
	'use strict';

	var templatesData = (window.wpckModalData && window.wpckModalData.templates) ? window.wpckModalData.templates : {};
	var i18n = (window.wpckModalData && window.wpckModalData.i18n) ? window.wpckModalData.i18n : {};

	var WPCK_Modal = {
		currentTemplate: 'important',
		currentFormData: {},
		activeEditor: null,
		currentView: 'visual', // 'visual' or 'code'

		init: function () {
			this.cacheElements();
			this.bindEvents();
			this.selectTemplate('important');
		},

		cacheElements: function () {
			this.$backdrop = $('#wpck-modal-backdrop');
			this.$chips = $('.wpck-template-chip');
			this.$fieldsContainer = $('#wpck-modal-fields');
			this.$previewViewport = $('#wpck-preview-viewport');
			this.$codeViewport = $('#wpck-code-viewport');
			this.$codeTextarea = $('#wpck-generated-html');
			this.$viewToggles = $('.wpck-preview-toggle');
			this.$btnInsert = $('#wpck-btn-insert');
			this.$btnCopy = $('#wpck-btn-copy');
			this.$copyFeedback = $('#wpck-copy-feedback');
			this.$btnClose = $('.wpck-modal-close-btn, #wpck-btn-cancel');
		},

		bindEvents: function () {
			var self = this;

			// Open modal via media button or any button with .wpck-open-modal-btn
			$(document).on('click', '.wpck-open-modal-btn', function (e) {
				e.preventDefault();
				self.open();
			});

			// Chip click (Template selector)
			$(document).on('click', '.wpck-template-chip', function (e) {
				e.preventDefault();
				var templateId = $(this).data('template');
				self.selectTemplate(templateId);
			});

			// View Toggle (Visual / Code)
			$(document).on('click', '.wpck-preview-toggle', function (e) {
				e.preventDefault();
				var view = $(this).data('view');
				self.switchView(view);
			});

			// Close modal
			this.$btnClose.on('click', function (e) {
				e.preventDefault();
				self.close();
			});

			this.$backdrop.on('click', function (e) {
				if ($(e.target).is('#wpck-modal-backdrop')) {
					self.close();
				}
			});

			// ESC key to close
			$(document).on('keydown', function (e) {
				if (e.keyCode === 27 && self.$backdrop.hasClass('wpck-open')) {
					self.close();
				}
			});

			// Insert into editor button
			this.$btnInsert.on('click', function (e) {
				e.preventDefault();
				self.insertIntoEditor();
			});

			// Copy HTML button
			this.$btnCopy.on('click', function (e) {
				e.preventDefault();
				self.copyHtml();
			});
		},

		open: function (editor) {
			if (editor) {
				this.activeEditor = editor;
			}
			this.$backdrop.addClass('wpck-open').show();
			this.updatePreview();
		},

		close: function () {
			this.$backdrop.removeClass('wpck-open').hide();
		},

		switchView: function (view) {
			this.currentView = view;
			this.$viewToggles.removeClass('active');
			this.$viewToggles.filter('[data-view="' + view + '"]').addClass('active');

			if (view === 'code') {
				this.$previewViewport.hide();
				this.$codeViewport.show();
				this.updateCodeView();
			} else {
				this.$codeViewport.hide();
				this.$previewViewport.show();
			}
		},

		selectTemplate: function (templateId) {
			if (!templatesData[templateId]) {
				templateId = 'important';
			}

			this.currentTemplate = templateId;
			this.$chips.removeClass('active');
			this.$chips.filter('[data-template="' + templateId + '"]').addClass('active');

			this.renderFields(templateId);
			this.updatePreview();
		},

		renderFields: function (templateId) {
			var self = this;
			var template = templatesData[templateId];
			if (!template) {
				return;
			}
			var fields = template.fields || {};
			var html = '';

			this.currentFormData = {};

			$.each(fields, function (key, field) {
				self.currentFormData[key] = field.default || '';

				html += '<div class="wpck-form-group">';
				html += '<label class="wpck-form-label" for="wpck_f_' + key + '">' + escapeHtml(field.label) + '</label>';

				if (field.type === 'textarea') {
					html += '<textarea class="wpck-form-textarea wpck-field-input" id="wpck_f_' + key + '" data-key="' + key + '" placeholder="' + escapeHtml(field.placeholder || '') + '">' + escapeHtml(field.default || '') + '</textarea>';
				} else if (field.type === 'select') {
					html += '<select class="wpck-form-select wpck-field-input" id="wpck_f_' + key + '" data-key="' + key + '">';
					$.each(field.options || {}, function (optVal, optLabel) {
						var selected = optVal === field.default ? ' selected' : '';
						html += '<option value="' + optVal + '"' + selected + '>' + escapeHtml(optLabel) + '</option>';
					});
					html += '</select>';
				} else if (field.type === 'color') {
					html += '<input type="text" class="wpck-form-input wpck-color-field wpck-field-input" id="wpck_f_' + key + '" data-key="' + key + '" value="' + escapeHtml(field.default || '#ffffff') + '">';
				} else {
					var inputType = field.type === 'number' ? 'number' : 'text';
					html += '<input type="' + inputType + '" class="wpck-form-input wpck-field-input" id="wpck_f_' + key + '" data-key="' + key + '" value="' + escapeHtml(field.default || '') + '" placeholder="' + escapeHtml(field.placeholder || '') + '">';
				}

				html += '</div>';
			});

			this.$fieldsContainer.html(html);

			// Init WordPress Color Pickers if available
			this.$fieldsContainer.find('.wpck-color-field').each(function () {
				var $input = $(this);
				if ($.fn.wpColorPicker) {
					$input.wpColorPicker({
						change: function (event, ui) {
							var key = $input.data('key');
							self.currentFormData[key] = ui.color.toString();
							self.updatePreview();
						},
						clear: function () {
							var key = $input.data('key');
							self.currentFormData[key] = '';
							self.updatePreview();
						}
					});
				}
			});

			// Input change listeners
			this.$fieldsContainer.find('.wpck-field-input').on('input change', function () {
				var key = $(this).data('key');
				self.currentFormData[key] = $(this).val();
				self.updatePreview();
			});
		},

		generateInlineHtml: function () {
			var tpl = this.currentTemplate;
			var d = this.currentFormData || {};

			switch (tpl) {
				case 'important':
					var title = d.title || 'Poin Penting:';
					var itemsRaw = d.items || '';
					var lines = itemsRaw.split('\n').map(function (s) { return s.trim(); }).filter(function (s) { return s.length > 0; });
					var bg = d.bg_color || '#f8fafc';
					var border = d.border_color || '#2563eb';

					var listHtml = '';
					if (lines.length) {
						listHtml += '<ul style="margin: 10px 0 0 0; padding-left: 20px; list-style-type: disc; color: #334155; line-height: 1.6;">';
						lines.forEach(function (l) {
							listHtml += '<li style="margin-bottom: 6px;">' + escapeHtml(l) + '</li>';
						});
						listHtml += '</ul>';
					}

					return '<div style="background: ' + escapeHtml(bg) + '; border-left: 4px solid ' + escapeHtml(border) + '; padding: 18px 20px; border-radius: 8px; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">\n' +
						'  <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">' + escapeHtml(title) + '</div>\n' +
						'  ' + listHtml + '\n' +
					'</div>';

				case 'author':
					var name = d.name || 'Nama Penulis';
					var role = d.role || '';
					var bio = d.bio || '';
					var avatarUrl = d.avatar_url || '';
					var linkText = d.link_text || '';
					var linkUrl = d.link_url || '';
					var aBg = d.bg_color || '#ffffff';
					var aBorder = d.border_color || '#e2e8f0';

					var avatarHtml = '';
					if (avatarUrl) {
						avatarHtml = '<div style="margin-right: 18px; flex-shrink: 0;"><img src="' + escapeHtml(avatarUrl) + '" alt="' + escapeHtml(name) + '" style="width: 72px; height: 72px; border-radius: 50%; object-fit: cover; border: 2px solid ' + escapeHtml(aBorder) + '; display: block;" /></div>';
					}

					var linkHtml = '';
					if (linkUrl && linkText) {
						linkHtml = '<p style="margin: 10px 0 0 0; font-size: 14px;"><a href="' + escapeHtml(linkUrl) + '" target="_blank" rel="noopener noreferrer" style="color: #2563eb; text-decoration: underline; font-weight: 600;">' + escapeHtml(linkText) + ' &rarr;</a></p>';
					}

					return '<div style="background: ' + escapeHtml(aBg) + '; border: 1px solid ' + escapeHtml(aBorder) + '; padding: 22px; border-radius: 12px; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">\n' +
						'  <div style="display: flex; align-items: center; flex-wrap: wrap;">\n' +
						(avatarHtml ? '    ' + avatarHtml + '\n' : '') +
						'    <div style="flex: 1; min-width: 200px;">\n' +
						'      <h4 style="margin: 0 0 4px 0; font-size: 18px; font-weight: 700; color: #0f172a;">' + escapeHtml(name) + '</h4>\n' +
						(role ? '      <div style="font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px;">' + escapeHtml(role) + '</div>\n' : '') +
						(bio ? '      <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.55;">' + escapeHtml(bio) + '</p>\n' : '') +
						(linkHtml ? '      ' + linkHtml + '\n' : '') +
						'    </div>\n' +
						'  </div>\n' +
					'</div>';

				case 'reviewed_by':
					var revName = d.reviewer_name || 'Nama Reviewer';
					var profession = d.profession || '';
					var desc = d.description || '';
					var rLinkText = d.link_text || '';
					var rLinkUrl = d.link_url || '';
					var rBg = d.bg_color || '#fafafa';
					var rBorder = d.border_color || '#888888';

					var rLinkHtml = '';
					if (rLinkUrl) {
						var disp = rLinkText || rLinkUrl;
						rLinkHtml = '<p style="margin: 12px 0 0 0; font-size: 14px; color: #475569;">Lihat profil lengkap: <a href="' + escapeHtml(rLinkUrl) + '" target="_blank" rel="noopener noreferrer" style="color: #2563eb; font-weight: 600; text-decoration: underline;">' + escapeHtml(disp) + '</a></p>';
					}

					return '<div style="border: 2px dashed ' + escapeHtml(rBorder) + '; padding: 20px; border-radius: 12px; background: ' + escapeHtml(rBg) + '; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">\n' +
						'  <h4 style="margin: 0 0 4px 0; font-size: 17px; font-weight: 700; color: #1e293b;">Ditinjau Oleh: ' + escapeHtml(revName) + '</h4>\n' +
						(profession ? '  <strong style="font-size: 13px; color: #475569; display: block; margin-bottom: 8px;">' + escapeHtml(profession) + '</strong>\n' : '') +
						(desc ? '  <p style="margin: 0; font-size: 14px; color: #334155; line-height: 1.6;">' + escapeHtml(desc) + '</p>\n' : '') +
						(rLinkHtml ? '  ' + rLinkHtml + '\n' : '') +
					'</div>';

				case 'related_content':
					var badge = d.badge || 'Baca Juga:';
					var artTitle = d.article_title || '';
					var artUrl = d.article_url || '#';
					var relBg = d.bg_color || '#eff6ff';
					var relBorder = d.border_color || '#bfdbfe';

					return '<div style="background: ' + escapeHtml(relBg) + '; border: 1px solid ' + escapeHtml(relBorder) + '; border-radius: 8px; padding: 14px 18px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box; display: flex; align-items: baseline; flex-wrap: wrap; gap: 8px;">\n' +
						'  <span style="background: #2563eb; color: #ffffff; font-size: 12px; font-weight: 700; padding: 3px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">' + escapeHtml(badge) + '</span>\n' +
						'  <a href="' + escapeHtml(artUrl) + '" style="color: #1e3a8a; font-size: 15px; font-weight: 600; text-decoration: underline; line-height: 1.4;">' + escapeHtml(artTitle) + '</a>\n' +
					'</div>';

				case 'note':
					var noteTitle = d.title || 'Catatan:';
					var noteContent = d.content || '';
					var nBg = d.bg_color || '#fffbeb';
					var nBorder = d.border_color || '#f59e0b';

					return '<div style="background: ' + escapeHtml(nBg) + '; border-left: 4px solid ' + escapeHtml(nBorder) + '; padding: 16px 18px; border-radius: 6px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">\n' +
						'  <div style="font-weight: 700; color: #92400e; font-size: 15px; margin-bottom: 4px;">' + escapeHtml(noteTitle) + '</div>\n' +
						'  <div style="color: #78350f; font-size: 14px; line-height: 1.6;">' + escapeHtml(noteContent) + '</div>\n' +
					'</div>';

				case 'warning':
					var warnTitle = d.title || 'Peringatan:';
					var warnContent = d.content || '';
					var wBg = d.bg_color || '#fef2f2';
					var wBorder = d.border_color || '#ef4444';

					return '<div style="background: ' + escapeHtml(wBg) + '; border-left: 4px solid ' + escapeHtml(wBorder) + '; padding: 16px 18px; border-radius: 6px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">\n' +
						'  <div style="font-weight: 700; color: #991b1b; font-size: 15px; margin-bottom: 4px;">' + escapeHtml(warnTitle) + '</div>\n' +
						'  <div style="color: #7f1d1d; font-size: 14px; line-height: 1.6;">' + escapeHtml(warnContent) + '</div>\n' +
					'</div>';

				case 'simple_info':
					var infoTitle = d.title || 'Informasi Tambahan';
					var infoContent = d.content || '';
					var iBg = d.bg_color || '#f0fdf4';
					var iBorder = d.border_color || '#bbf7d0';

					return '<div style="background: ' + escapeHtml(iBg) + '; border: 1px solid ' + escapeHtml(iBorder) + '; padding: 16px 18px; border-radius: 8px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;">\n' +
						'  <div style="font-weight: 700; color: #166534; font-size: 15px; margin-bottom: 4px;">' + escapeHtml(infoTitle) + '</div>\n' +
						'  <div style="color: #14532d; font-size: 14px; line-height: 1.6;">' + escapeHtml(infoContent) + '</div>\n' +
					'</div>';

				case 'custom':
				default:
					var cTitle = d.title || '';
					var cContent = (d.content || '').replace(/\n/g, '<br>');
					var cLinkUrl = d.link_url || '';
					var cLinkText = d.link_text || cLinkUrl;
					var cBg = d.bg_color || '#f8fafc';
					var cTextColor = d.text_color || '#1e293b';
					var cBorderColor = d.border_color || '#cbd5e1';
					var cBorderWidth = parseInt(d.border_width, 10) || 2;
					var cBorderStyle = d.border_style || 'solid';
					var cRadius = parseInt(d.border_radius, 10) || 10;
					var cPadding = parseInt(d.padding, 10) || 20;

					var cLinkHtml = '';
					if (cLinkUrl && cLinkText) {
						cLinkHtml = '<p style="margin: 12px 0 0 0; font-size: 14px;"><a href="' + escapeHtml(cLinkUrl) + '" target="_blank" rel="noopener noreferrer" style="color: ' + escapeHtml(cTextColor) + '; font-weight: 600; text-decoration: underline;">' + escapeHtml(cLinkText) + '</a></p>';
					}

					var cStyle = 'background: ' + escapeHtml(cBg) + '; color: ' + escapeHtml(cTextColor) + '; border: ' + cBorderWidth + 'px ' + escapeHtml(cBorderStyle) + ' ' + escapeHtml(cBorderColor) + '; border-radius: ' + cRadius + 'px; padding: ' + cPadding + 'px; margin: 24px 0; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Oxygen-Sans, Ubuntu, Cantarell, \'Helvetica Neue\', sans-serif; box-sizing: border-box;';

					return '<div style="' + cStyle + '">\n' +
						(cTitle ? '  <h4 style="margin: 0 0 8px 0; font-size: 17px; font-weight: 700; color: ' + escapeHtml(cTextColor) + ';">' + escapeHtml(cTitle) + '</h4>\n' : '') +
						(cContent ? '  <div style="font-size: 14px; line-height: 1.6;">' + cContent + '</div>\n' : '') +
						(cLinkHtml ? '  ' + cLinkHtml + '\n' : '') +
					'</div>';
			}
		},

		updatePreview: function () {
			var html = this.generateInlineHtml();
			if (this.$previewViewport.length) {
				this.$previewViewport.html(html);
			}
			if (this.$codeTextarea.length) {
				this.$codeTextarea.val(html);
			}
		},

		updateCodeView: function () {
			var html = this.generateInlineHtml();
			if (this.$codeTextarea.length) {
				this.$codeTextarea.val(html);
			}
		},

		insertIntoEditor: function () {
			var html = this.generateInlineHtml();
			var inserted = false;

			// 1. Check passed editor or active TinyMCE
			var editor = this.activeEditor || (window.tinyMCE ? window.tinyMCE.activeEditor : null);

			if (editor && !editor.isHidden()) {
				editor.execCommand('mceInsertContent', false, '\n' + html + '\n');
				inserted = true;
			} else {
				// 2. Text mode / textarea fallback
				var $textarea = $('#content');
				if ($textarea.length) {
					var el = $textarea.get(0);
					var pos = el.selectionStart || 0;
					var endPos = el.selectionEnd || pos;
					var val = $textarea.val();

					$textarea.val(val.substring(0, pos) + '\n' + html + '\n' + val.substring(endPos));
					el.selectionStart = el.selectionEnd = pos + html.length + 2;
					$textarea.trigger('input').trigger('change');
					inserted = true;
				}
			}

			this.close();
		},

		copyHtml: function () {
			var html = this.generateInlineHtml();
			var self = this;

			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(html).then(function () {
					self.showCopySuccess();
				}).catch(function () {
					self.fallbackCopy(html);
				});
			} else {
				self.fallbackCopy(html);
			}
		},

		fallbackCopy: function (text) {
			var $temp = $('<textarea>');
			$('body').append($temp);
			$temp.val(text).select();
			try {
				document.execCommand('copy');
				this.showCopySuccess();
			} catch (err) {
				// Failed
			}
			$temp.remove();
		},

		showCopySuccess: function () {
			var self = this;
			var origText = this.$btnCopy.html();

			this.$copyFeedback.stop(true, true).fadeIn(200);
			this.$btnCopy.html('<span class="dashicons dashicons-yes"></span> ' + (i18n.copied || 'Tersalin!'));

			setTimeout(function () {
				self.$copyFeedback.fadeOut(300);
				self.$btnCopy.html(origText);
			}, 2500);
		}
	};

	function escapeHtml(string) {
		if (typeof string !== 'string') {
			string = String(string || '');
		}
		var entityMap = {
			'&': '&amp;',
			'<': '&lt;',
			'>': '&gt;',
			'"': '&quot;',
			"'": '&#39;'
		};
		return string.replace(/[&<>"']/g, function (s) {
			return entityMap[s];
		});
	}

	window.WPCK_Modal = WPCK_Modal;

	$(document).ready(function () {
		WPCK_Modal.init();
	});

})(jQuery);
