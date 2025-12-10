jQuery(document).ready(function ($) {

	console.log('Slider Admin JS loaded');

	/**
	 * Initialize sortable
	 */
	function initSortable() {
		const repeater = $('.dbt-slides-repeater');
		if (repeater.length && typeof repeater.sortable === 'function') {
			repeater.sortable({
				placeholder: 'ui-state-highlight',
				handle: '.dbt-slide-handle',
	
				start: function () {
					destroyEditors();
				},
	
				update: function () {
					updateIndexes();
					initEditors();
					console.log('Sortable updated: editors destroyed, reindexed and re-initialized');
				}
			});
	
			// Initial setup
			updateIndexes();
			initEditors();
	
			console.log('Sortable initialized!');
		} else {
			console.log('Sortable not ready yet...');
		}
	}
	
	function initEditors() {
		$('.dbt-slide-item textarea').each(function () {
			const editorId = $(this).attr('id');
			if (!tinymce.get(editorId)) {
				tinymce.init({
					selector: `#${editorId}`,
					menubar: false,
					toolbar: 'bold italic underline | bullist numlist | link unlink | code | formatselect',
					plugins: 'lists link code',
					block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3',
					setup: function (editor) {
						editor.on('change', function () {
							tinymce.triggerSave();
						});
					}
				});
			}
		});
	}
	
	function updateIndexes() {
		$('.dbt-slide-item').each(function (index) {
			// Add handle if missing
			if ($(this).find('.dbt-slide-handle').length === 0) {
				$(this).prepend('<div class="dbt-slide-handle"><span class="dashicons dashicons-move"></span></div>');
			}
	
			// Update input names with new indexes
			$(this).find('input.dbt-slide-image').attr('name', function (_, oldName) {
				return oldName.replace(/\[\d+\]/, `[${index}]`);
			});
			$(this).find('textarea').attr('name', function (_, oldName) {
				return oldName.replace(/\[\d+\]/, `[${index}]`);
			});
	
			// Update textarea IDs to unique per index
			const textarea = $(this).find('textarea');
			const newId = `dbt-slides-${index}-caption`;
			textarea.attr('id', newId);
		});
	}
	
	function destroyEditors() {
		$('.dbt-slide-item textarea.wp-editor-area').each(function() {
			const id = $(this).attr('id');
			if (id && tinymce.get(id)) {
				tinymce.get(id).remove();
			}
		});
	}
	
	function initEditors() {
		$('.dbt-slide-item textarea.wp-editor-area').each(function() {
			const textareaId = $(this).attr('id');
			if (!textareaId) return;
	
			// Don't double-init
			if (!tinymce.get(textareaId)) {
				tinymce.init({
					selector: '#' + textareaId,
					menubar: false,
					plugins: 'lists link',
					toolbar: 'bold italic underline | bullist numlist | link unlink | formatselect',
					block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3',
					setup: function(editor) {
						editor.on('change', function() {
							tinymce.triggerSave();
						});
					}
				});
			}
		});
	}
	


	/**
	 * Remove and re-init TinyMCE for a textarea
	 */
	function initTinyMCE(editorId) {
		if (typeof tinymce !== 'undefined') {
			if (tinymce.get(editorId)) {
				tinymce.get(editorId).remove();
			}
			tinymce.init({
				selector: `#${editorId}`,
				menubar: false,
				toolbar: 'bold italic underline | bullist numlist | link unlink | code | formatselect',
				plugins: 'lists link code',
				block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3',
				setup: function (editor) {
					editor.on('change', function () {
						tinymce.triggerSave();
					});
				}
			});
		}
	}
	
	
	/**
	 * Image upload
	 */
	$(document).on('click', '.dbt-upload-image', function (e) {
		e.preventDefault();
		let button = $(this);
		let imageField = button.closest('.dbt-slide-item').find('.dbt-slide-image');

		let frame = wp.media({
			title: 'Select or Upload Image',
			button: { text: 'Use this image' },
			multiple: false
		});

		frame.on('select', function () {
			let attachment = frame.state().get('selection').first().toJSON();
			imageField.val(attachment.url).trigger('change');
		});

		frame.open();
	});

	/**
	 * Remove slide
	 */
	$(document).on('click', '.dbt-remove-slide', function (e) {
		e.preventDefault();
		$(this).closest('.dbt-slide-item').remove();
	});

	/**
	 * Add new slide
	 */
	$(document).on('click', '.dbt-add-slide', function (e) {
		e.preventDefault();

		let button = $(this);
		let container;

		// Detect context
		if (button.closest('.so-panels-dialog-wrapper').length) {
			container = button.closest('.so-panels-dialog-wrapper').find('.dbt-slides-repeater');
		} else {
			container = button.closest('form').find('.dbt-slides-repeater');
		}

		if (!container || container.length === 0) {
			console.log('No container found for new slides');
			return;
		}

		let fieldNameBase = button.data('name');
		let index = container.find('.dbt-slide-item').length;

		// Create editor ID matching PHP get_field_id format for consistency
		let editorId = `dbt-slides-${index}-caption`;

		let newSlideHTML = `
			<div class="dbt-slide-item" style="margin-bottom:15px; padding:10px; border:1px solid #ddd; background:white;">
			
			<div class="dbt-slide-header">
				<button type="button" class="dbt-toggle-slide">Toggle Slide</button>
			</div>
			
			<div class="dbt-slide-content">
			
				<div class="dbt-slide-handle"><span class="dashicons dashicons-move"></span></div>
				<p>
					<input class="widefat dbt-slide-image"
						name="${fieldNameBase}[${index}][image]"
						type="text" value="" />
					<button class="button dbt-upload-image">Select Image</button>
				</p>
				<p>
					<label>Caption:</label>
					<textarea id="${editorId}" class="widefat"
						name="${fieldNameBase}[${index}][caption]"></textarea>
				</p>
				<p>
					<button class="button dbt-remove-slide">Remove Slide</button>
					<button class="button dbt-duplicate-slide">Duplicate Slide</button>
				</p>
				
			</div>
			</div>`;
		
		container.append(newSlideHTML);
		
		setTimeout(() => {
			initTinyMCE(editorId);
			addImagePreviews();
			initSortable();
		
			// Force TinyMCE to save the initial empty content into textarea
			setTimeout(() => {
				if (typeof tinymce !== 'undefined') {
					let ed = tinymce.get(editorId);
					if (ed) {
						ed.save();
					}
				}
			}, 100);
		}, 50);
	});
	
	// DUPLICATE SLIDE
	function duplicateSlide(button) {
		let container;
	
		if (button.closest('.so-panels-dialog-wrapper').length) {
			container = button.closest('.so-panels-dialog-wrapper').find('.dbt-slides-repeater');
		} else {
			container = button.closest('form').find('.dbt-slides-repeater');
		}
		if (!container.length) return;
	
		let originalSlide = button.closest('.dbt-slide-item');
	
		// 🔹 Destroy ALL editors before cloning
		if (typeof destroyEditors === 'function') {
			destroyEditors();
		} else if (typeof tinymce !== 'undefined') {
			$('textarea').each(function () {
				let id = $(this).attr('id');
				if (id && tinymce.get(id)) {
					tinymce.get(id).remove();
				}
			});
		}
	
		// 2️⃣ Clone the slide
		let clone = originalSlide.clone();
	
		// 3️⃣ Give textarea in clone a new unique ID
		let lastTextarea = clone.find('textarea');
		let newId = 'dbt-editor-' + Date.now();
		lastTextarea.attr('id', newId);
	
		// 4️⃣ Append the clone
		originalSlide.after(clone);
	
		// 5️⃣ Reindex
		updateIndexes();
	
		// 🔹 Save TinyMCE state so WordPress knows about content
		if (typeof tinymce !== 'undefined') {
			tinymce.triggerSave();
		}
	
		// 6️⃣ Re-init editors for ALL textareas
		initEditors();
	
		addImagePreviews();
		initSortable();
	}
	
	// Bind click
	$(document).on('click', '.dbt-duplicate-slide', function (e) {
		e.preventDefault();
		duplicateSlide($(this));
	});
	
	

	/**
	 * Image previews
	 */
	function addImagePreviews() {
		$('.dbt-slide-item').each(function () {
			let $item = $(this);
			let $input = $item.find('.dbt-slide-image');

			if ($item.find('.dbt-image-preview').length === 0) {
				let $preview = $('<img class="dbt-image-preview">');
				$input.before($preview);
			}

			let timeout;
			$input.off('input.preview change.preview').on('input.preview change.preview', function () {
				clearTimeout(timeout);
				timeout = setTimeout(() => {
					let url = $(this).val();
					let $preview = $(this).siblings('.dbt-image-preview');

					if (url) {
						$preview.attr('src', url).show();
					} else {
						$preview.hide();
					}
				}, 300);
			});

			$input.trigger('input.preview');
		});
	}

	/**
	 * Init all when widget popup opens (SiteOrigin)
	 */
	$(document).on('panelsopen', function () {
		console.log('SiteOrigin widget popup opened');
		initSortable();
		addImagePreviews();
	
		$('.dbt-slide-item textarea.wp-editor-area').each(function() {
			var textareaId = $(this).attr('id');
			if (!textareaId) return;
	
			if (!tinymce.get(textareaId)) {
				tinymce.init({
					selector: '#' + textareaId,
					menubar: false,
					// Removed 'code' plugin to avoid 404
					plugins: 'lists link',
					toolbar: 'bold italic underline | bullist numlist | link unlink | formatselect',
					block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3',
					setup: function(editor) {
						editor.on('change', function() {
							tinymce.triggerSave();
						});
					}
				});
			}
		});
	});
	


	/**
	 * Custom CSS
	 */
	function injectCSS(css) {
		const style = document.createElement('style');
		style.type = 'text/css';
		style.appendChild(document.createTextNode(css));
		document.head.appendChild(style);
	}

	injectCSS(`
		.dbt-slide-handle {
			cursor: move;
			width: 20px;
			float: right;
		}
		.dbt-slide-item {
			margin-bottom: 15px;
			padding: 10px;
			border: 1px solid #ddd;
			background: white;
		}
		.dbt-image-preview {
			max-width: 40px;
			display: block;
			margin-bottom: 5px;
		}
		
		.quicktags-toolbar {
			display:none;
		}
		
		.dbt-slide-item p{
			margin:0;
		}
	`);
});