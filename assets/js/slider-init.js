jQuery(document).ready(function ($) {
	
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
	
		const $wrapper = $(this);
	
		// Randomize slides if enabled
		if (settings.randomOrder) {
		  const $slides = $wrapper.children('.dbt-slide');
		  const slidesArr = $slides.toArray();
	
		  for (let i = slidesArr.length - 1; i > 0; i--) {
			const j = Math.floor(Math.random() * (i + 1));
			[slidesArr[i], slidesArr[j]] = [slidesArr[j], slidesArr[i]];
		  }
	
		  $wrapper.empty();
		  $wrapper.append(slidesArr);
	
		  settings.initialIndex = 0;
		}
	
		// Initialize Flickity as-is, no hacks
		const flkty = new Flickity(this, settings);
	
		// Resize after images load
		$(window).on('load', function () {
		  const flktyInstance = $wrapper.data('flickity');
		  if (flktyInstance) flktyInstance.resize();
	
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
		  }, 100);
		});
	
	  });
	
	});