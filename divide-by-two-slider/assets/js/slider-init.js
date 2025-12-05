jQuery(document).ready(function ($) {
	
	  // Make slides sortable
	  $('.dbt-slides-repeater').sortable({
		handle: '.dbt-slide-handle',
		placeholder: 'dbt-slide-placeholder',
		forcePlaceholderSize: true
	  });
	
	  $('.dbt-slider-wrapper').each(function () {
		const settingsData = $(this).data('settings');
		let settings;
		if (typeof settingsData === 'string') {
		  try {
			settings = JSON.parse(settingsData);
		  } catch (e) {
			console.error('Invalid JSON in data-settings:', settingsData);
			return;
		  }
		} else if (typeof settingsData === 'object' && settingsData !== null) {
		  settings = settingsData;
		} else {
		  return;
		}
	
		const flkty = new Flickity(this, settings);
	
		// Resize once after images load
		$(window).on('load', function () {
			  // Resize all Flickity sliders
			  $('.dbt-slider-wrapper').each(function () {
				const flkty = $(this).data('flickity'); // get the Flickity instance
				if (flkty) flkty.resize();
			  });
			
			  // Refresh scroll animations (AOS)
			  if (typeof AOS !== 'undefined') {
				AOS.refresh();
			  }
			});
	
		// Debounced resize on select
		let resizeTimeout;
		flkty.on('select', function () {
		  clearTimeout(resizeTimeout);
		  resizeTimeout = setTimeout(() => {
			flkty.resize();
		  }, 100); // adjust timeout as needed
		});
	  });
	
	});