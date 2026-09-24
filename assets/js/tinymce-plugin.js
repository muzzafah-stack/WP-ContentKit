/**
 * WP ContentKit - TinyMCE Plugin
 * Registers the Content Box button inside Classic Editor TinyMCE toolbar.
 *
 * @package WP_ContentKit
 */

(function () {
	'use strict';

	if (typeof tinymce !== 'undefined') {
		tinymce.PluginManager.add('wpck_box', function (editor) {
			editor.addButton('wpck_box', {
				title: 'Insert Content Box (WP ContentKit)',
				icon: 'wpck-box-icon dashicons-before dashicons-editor-kitchensink',
				onclick: function () {
					if (window.WPCK_Modal) {
						window.WPCK_Modal.open(editor);
					}
				}
			});
		});
	}
})();
