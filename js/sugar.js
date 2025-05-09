(function () {
  const locomotiveScroll = new LocomotiveScroll(
    {
      lenisOptions : {
        lerp: 0.1,
        wheelMultiplier: 0.72
      }
    }
  );
})();


document.addEventListener("DOMContentLoaded", () => {
  
  Splitting({

    target: "h1",

    by: "words",

    key: null,
  });
  

  Splitting({
    /* target: String selector, Element, Array of Elements, or NodeList */
    target: "h2.wp-block-heading, .cta-inner h2",
    /* by: String of the plugin name */
    by: "words",
    /* key: Optional String to prefix the CSS variables */
    key: null,
  });

  Splitting({
    target: ".showcase__items-list",
    by: "items",
    matching : "li",
    key: null
  });

  /* Observer stuff */
  const animatedElements = document.querySelectorAll(
    ".wp-block-heading, .cta-inner h2, .showcase__items-list, .wp-block-beech-team-member, .outcome-item, .has-letterbox-layout, .wp-block-beech-text-row .hr, .wp-block-beech-text-row figure.wp-block-image, .is-style-big-boiz .accordion-item, .wp-block-beech-images figure.wp-block-image"
  );

  // Options for the Intersection Observer
  const options = {
    threshold: 0.25, // Adjust threshold as needed
  };

  const handleIntersection = (entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("in-view");
      }

      if (
        entry.target.classList.contains(
          "wp-elements-12a7b62d7ff77894e3987aed10c11b80"
        )
      ) {
        if (
          entry.isVisible === false &&
          entry.isIntersecting === false &&
          entry.boundingClientRect.top > 0
        ) {
          console.log(entry.target, entry.isVisible, entry.boundingClientRect.top);
        }
      }
        if (false) {
          if (
            entry.target === previousElement &&
            entry.isVisible === false &&
            entry.isIntersecting === false &&
            entry.boundingClientRect.top > 0
          ) {
          }
        }

    });
  };

  // Create Intersection Observer
  const observer = new IntersectionObserver(handleIntersection, options);

  // Observe each .wp-block-beech-count-indicator element
  animatedElements.forEach((element) => {
    observer.observe(element);
  });


  var laxImages = document.querySelectorAll(".bb-card img:not(.video-poster)");



  new simpleParallax(laxImages, {
    delay: 0.2,
    scale: 1.2,
    transition: "ease",
    orientation: "down"
  });

  //console.log("banana split bitch v3");
});;