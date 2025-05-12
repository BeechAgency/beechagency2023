/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

if(typeof AnalyticsHandler !== 'function') {
	function AnalyticsHandler( event_name = 'ga_event', event_params = {}, user_props = {} ) {
		if(typeof window.dataLayer !== 'object' ) return false;

		let event = { 'event' : 'ga4_custom_event', 'event_name' : event_name, event_params, user_props }

		dataLayer.push(event);

		return true;
	}
}

// Function to set dark mode based on user preference
function toggleDarkMode() {
    const darkModeEnabled = localStorage.getItem('darkModeEnabled') === 'true';
	const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const body = document.body;

	
	let toggleClass = 'dark-mode';

	console.log(prefersDark, toggleClass);

	if(prefersDark) {
		//body.classList.remove(toggleClass);
		toggleClass = 'light-mode';
	}



    if ( darkModeEnabled ) {
      body.classList.remove(toggleClass);
      localStorage.setItem("darkModeEnabled", "false");
	  AnalyticsHandler('darkmode_toggle', { dark_mode_enabled : false });

    } else {
      body.classList.add(toggleClass);
      localStorage.setItem("darkModeEnabled", "true");
	  AnalyticsHandler('darkmode_toggle', { dark_mode_enabled : true });
    }
}

// Handle Dark mode
const lozadObserver = lozad(); // lazy loads elements with default selector as '.lozad'

// Check the initial user preference
document.addEventListener('DOMContentLoaded', (e) => {
	
  	lozadObserver.observe();
})


/*
 * Follow Cursor
 */
function createCursorFollower() {
	const leFollower = document.createElement('div');
	
	leFollower.classList.add('cursor-follower');
	leFollower.textContent = 'DRAGON BIATCH';
	document.body.appendChild(leFollower);

	document.addEventListener("mousemove", (e) => {

	var x = e.clientX;
	var y = e.clientY;

	leFollower.style.left = `${x}px`;
	leFollower.style.top = `${y}px`;
	
	})
}


( function() {
	const darkmodeInput = document.getElementById( 'darkmodeInput');

	// Return early if the navigation doesn't exist.
	if ( ! darkmodeInput ) {
		return;
	}

	const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

	let className = 'dark-mode'

	// Function to handle dark mode changes
	function handleDarkModeChange(event) {
		console.log("dark mode changed", className);

		if (event.matches) {
			// Dark mode is enabled
			document.body.classList.add(className);
			className = 'light-mode';
			AnalyticsHandler('darkmode_toggle', { dark_mode_enabled : false });
		} else {
			// Dark mode is not enabled
			document.body.classList.remove(className);
			className = "dark-mode";
			AnalyticsHandler('darkmode_toggle', { dark_mode_enabled : true });
		}

		console.log(className);
	}
	
	// Add a listener for changes to prefers-color-scheme
	window.matchMedia('(prefers-color-scheme: dark)').addListener(handleDarkModeChange);

	// Apply the initial user preference
	if (prefersDark) {
		document.body.classList.add(className);
	} else {
		document.body.classList.remove(className);
	}
	
	const body = document.body;
	const darkModeEnabled = localStorage.getItem('darkModeEnabled') === 'true';
	
	if(darkModeEnabled) { 
		body.classList.add(className); 
		darkmodeInput.checked = true;
	}
	

	darkmodeInput.addEventListener('click', (e) => {
		const checked = e.currentTarget.checked;

		toggleDarkMode();
	})
	

	/* Cycle Classes for Selection on Click */
	const classes = [
		'selection-rorange',
		'selection-yellow',
		'selection-pink',
		'selection-green',
		'selection-blue'
	];
	let currentIndex = 0;

	body.addEventListener('click', function () {
		body.classList.remove(classes[currentIndex]);
		currentIndex = (currentIndex + 1) % classes.length;
		body.classList.add(classes[currentIndex]);
	});

}() );

( function() {
	// Handle ToC when on pages.
	document.addEventListener('DOMContentLoaded', function () {
		console.log('Doing the thing')
		// Get all headings and TOC items
		const headings = document.querySelectorAll('.wp-block-beech-article-text h2, .wp-block-beech-article-text h3');
		const tocItems = document.querySelectorAll('.toc li');

		//console.log('ITEMS', headings, tocItems)

		// Create an Intersection Observer
		const observer = new IntersectionObserver((entries) => {
			entries.forEach(entry => {
				const id = entry.target.id;
				const tocLink = document.querySelector(
					`a[href="#${id}"]`
				); //document.querySelector(`#toc-item-${id}`);

				if (entry.isIntersecting) {
					//console.log('INTERESECTING!', id, tocLink);

					//const id = entry.target.id;

					tocItems.forEach(item => item.classList.remove('active'));
					if (tocLink) {
						tocLink.parentElement.classList.add('active');
					}
				}
			});
		}, { threshold: 0.5 });

		// Observe each heading
		headings.forEach(heading => observer.observe(heading));
	});

	document.addEventListener('DOMContentLoaded', function () {
		const shareButtons = document.querySelectorAll('.social-sharing-buttons a');
		const copyLinkButton = document.querySelector('.copy-link-button');
		const copyToast = document.querySelector('#copy-toast');

		shareButtons.forEach(button => {
			button.addEventListener('click', function (event) {
				if (!this.classList.contains('copy-link-button')) {
					event.preventDefault();
					const width = 600, height = 400;
					const left = (screen.width / 2) - (width / 2);
					const top = (screen.height / 2) - (height / 2);
					const url = this.href;
					window.open(url, 'Share', `width=${width},height=${height},top=${top},left=${left}`);
				}
			});
		});

		if (copyLinkButton) {
			copyLinkButton.addEventListener('click', function (event) {
				event.preventDefault();
				const url = this.getAttribute('data-url');

				navigator.clipboard.writeText(url).then(() => {
					//alert('Link copied to clipboard!');
					copyToast.classList.add('active');

					setTimeout( ()=> {
						copyToast.classList.remove('active');
					}, 1500)

				}).catch(err => {
					console.error('Could not copy text: ', err);
				});
			});
		}
	});

}() );

/* Mobile Menu */
( function() {
	const siteHeader = document.getElementById( 'masthead');
	const menuButton = document.getElementById( 'menuButton');
	const body = document.body;

	if(!menuButton) return false;

	menuButton.addEventListener('click', (e) => {
		e.preventDefault();

	  	AnalyticsHandler('menu_toggle', { menu_direction : !siteHeader.classList.contains('open') ? 'Open Menu':'Close Menu' });

		siteHeader.classList.toggle('open');
		body.classList.toggle('menu-open');
		body.classList.add('menu-transition');

		setTimeout(()=> {
			body.classList.remove('menu-transition');
		}, 500)
	})

	let theWord = '';

	document.addEventListener('keydown', function(event) {
		const wordToMatch = 'metal';
	  
		if ( wordToMatch.includes(event.key) ) {
		  // Check if the key matches the first letter of 'metal'

		  theWord += event.key;

		  if ( theWord === wordToMatch ) {
			  console.log('The word "metal" has been typed.');
			  body.classList.toggle('metal');
			  AnalyticsHandler('easter_egg', { easter_egg : 'Metal Mode Enabled' });
		  }
		} else {
			theWord = '';
		}
	  });
}() );

function count(element) {
    const to = Number(element.innerText);
    let n = 0;

    const increment = () => {
        if (n <= to) {
            element.innerText = n;
            n++;
            setTimeout(increment, 150);
        }
    };

    increment();
}


/** Ajax Post List */
class CategoryFilter {
    constructor(buttonSelector, resultContainerSelector) {
		this.container = document.querySelectorAll('.content-list').length > 0 ? document.querySelectorAll('.content-list')[0] : null;
		
		this.init();
    }

	init() {
		if(!this.container) return false;

		this.buttons = this.container.querySelectorAll('.filter-list a');
        this.resultContainer = this.container.querySelector('.list-output');

		if(!this.buttons || !this.resultContainer) return false;

		this.bindEvents();
	}

    bindEvents() {
        this.buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();

				this.deactivateButtons();
				button.parentElement.classList.add('active');

				console.log(button.dataset);
                const termId = button.dataset.termId;
				const taxonomy = button.dataset.taxonomy;
				const postType = button.dataset.postType;

				console.log(termId, taxonomy);

                this.loadPosts(termId, taxonomy, postType);
            });
        });
    }

    loadPosts(termId, taxonomy = 'category', postType = 'post') {
        const formData = new FormData();
        formData.append('action', 'load_category_posts');
        formData.append('nonce', ajaxParams.nonce);
        formData.append('term_id', termId);
		formData.append('taxonomy', taxonomy);
		formData.append('post_type', postType);

		this.setMinHeight();

		this.resultContainer.classList.add('clearing');
		
		setTimeout(() => {
			this.resultContainer.innerHTML = '';	
			this.resultContainer.classList.remove('clearing');

			

			fetch(ajaxParams.ajax_url, {
				method: 'POST',
				body: formData
			})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					this.resultContainer.innerHTML = data.data;

					lozadObserver.observe();

				} else {
					this.resultContainer.innerHTML = `<p>${data.data}</p>`;
				}
			})
			.catch(err => {
				this.resultContainer.innerHTML = `<p>Error loading posts.</p>`;
				console.error(err);
			});



		}, 210);
		

		//console.log(formData);

        
    }

	deactivateButtons() {
		this.buttons.forEach(button => {
			button.parentElement.classList.remove('active');
		});
	}

	setMinHeight() {
		const firstCard = this.resultContainer.querySelector('.bb-card');
		const minHeight = firstCard.offsetHeight;
		
		console.log('Min Heiught',minHeight, this.resultContainer, firstCard);

		this.resultContainer.style.minHeight = `${minHeight}px`;
	}
}

document.addEventListener('DOMContentLoaded', () => {
    new CategoryFilter('.category-button', '#ajax-post-container');
});