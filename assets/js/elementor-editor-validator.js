/**
 * WP ContentKit - Elementor Editor Validator Helper
 */

(function ($) {
	'use strict';

	$(window).on('elementor:init', function () {
		if (elementor && elementor.hooks) {
			elementor.hooks.addAction('panel/open_editor/widget/wpck_smart_toc', function (panel, model, view) {
				// Panel opened for Smart TOC widget
			});
		}
	});

})(jQuery);
