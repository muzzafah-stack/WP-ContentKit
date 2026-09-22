/**
 * WP ContentKit - Smart TOC Frontend Script
 * Pure Vanilla JavaScript (<2KB, Zero jQuery).
 */

(function () {
	'use strict';

	function initWPContentKitTOC() {
		var containers = document.querySelectorAll('.wpck-toc-container');
		if (!containers.length) {
			return;
		}

		containers.forEach(function (container) {
			var toggleBtn = container.querySelector('.wpck-toc-toggle-btn');
			var body = container.querySelector('.wpck-toc-body');
			var links = container.querySelectorAll('.wpck-toc-list a');
			var isSmoothScroll = container.getAttribute('data-smooth-scroll') === 'true';
			var scrollOffset = parseInt(container.getAttribute('data-scroll-offset'), 10) || 80;

			// 1. Collapsible Toggle
			if (toggleBtn && body) {
				toggleBtn.addEventListener('click', function (e) {
					e.preventDefault();
					var isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
					if (isExpanded) {
						body.style.display = 'none';
						toggleBtn.setAttribute('aria-expanded', 'false');
					} else {
						body.style.display = 'block';
						toggleBtn.setAttribute('aria-expanded', 'true');
					}
				});
			}

			// 2. Smooth Scroll with Sticky Header Offset
			if (links.length) {
				links.forEach(function (link) {
					link.addEventListener('click', function (e) {
						var href = link.getAttribute('href');
						if (!href || href.charAt(0) !== '#') {
							return;
						}

						var targetId = href.substring(1);
						var targetElement = document.getElementById(targetId);

						if (targetElement && isSmoothScroll) {
							e.preventDefault();
							var elementPosition = targetElement.getBoundingClientRect().top;
							var offsetPosition = elementPosition + window.pageYOffset - scrollOffset;

							window.scrollTo({
								top: offsetPosition,
								behavior: 'smooth'
							});

							// Update browser hash without jump
							if (history.pushState) {
								history.pushState(null, null, href);
							}
						}
					});
				});

				// 3. Active Heading Observer
				setupActiveHeadingObserver(links, scrollOffset);
			}
		});
	}

	function setupActiveHeadingObserver(links, scrollOffset) {
		var headingMap = [];

		links.forEach(function (link) {
			var href = link.getAttribute('href');
			if (href && href.charAt(0) === '#') {
				var target = document.getElementById(href.substring(1));
				if (target) {
					headingMap.push({
						link: link,
						item: link.closest('.wpck-toc-item'),
						element: target
					});
				}
			}
		});

		if (!headingMap.length) {
			return;
		}

		var ticking = false;

		function updateActiveHeading() {
			var scrollPos = window.pageYOffset + scrollOffset + 20;
			var currentActive = null;

			for (var i = 0; i < headingMap.length; i++) {
				var item = headingMap[i];
				var elemTop = item.element.offsetTop;

				if (scrollPos >= elemTop) {
					currentActive = item;
				} else {
					break;
				}
			}

			headingMap.forEach(function (item) {
				if (item.item) {
					item.item.classList.remove('wpck-active');
				}
			});

			if (currentActive && currentActive.item) {
				currentActive.item.classList.add('wpck-active');
			}

			ticking = false;
		}

		window.addEventListener('scroll', function () {
			if (!ticking) {
				window.requestAnimationFrame(updateActiveHeading);
				ticking = true;
			}
		}, { passive: true });

		// Initial check
		updateActiveHeading();
	}

	// Initialize on DOM Ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initWPContentKitTOC);
	} else {
		initWPContentKitTOC();
	}
})();
