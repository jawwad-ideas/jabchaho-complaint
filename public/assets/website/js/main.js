/**
* Template Name: Nova
* Template URL: https://bootstrapmade.com/nova-bootstrap-business-template/
* Updated: Mar 17 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

document.addEventListener('DOMContentLoaded', () => {
  "use strict";

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Sticky header on scroll
   */
  const selectHeader = document.querySelector('#header');
  if (selectHeader) {
    document.addEventListener('scroll', () => {
      window.scrollY > 100 ? selectHeader.classList.add('sticked') : selectHeader.classList.remove('sticked');
    });
  }

  /**
   * Mobile nav toggle
   */
  const mobileNavShow = document.querySelector('.mobile-nav-show');
  const mobileNavHide = document.querySelector('.mobile-nav-hide');

  document.querySelectorAll('.mobile-nav-toggle').forEach(el => {
    el.addEventListener('click', function(event) {
      event.preventDefault();
      mobileNavToogle();
    })
  });

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavShow.classList.toggle('d-none');
    mobileNavHide.classList.toggle('d-none');
  }

  /**
   * Toggle mobile nav dropdowns
   */
  const navDropdowns = document.querySelectorAll('.navbar .dropdown > a');

  navDropdowns.forEach(el => {
    el.addEventListener('click', function(event) {
      if (document.querySelector('.mobile-nav-active')) {
        event.preventDefault();
        this.classList.toggle('active');
        this.nextElementSibling.classList.toggle('dropdown-active');

        let dropDownIndicator = this.querySelector('.dropdown-indicator');
        dropDownIndicator.classList.toggle('bi-chevron-up');
        dropDownIndicator.classList.toggle('bi-chevron-down');
      }
    })
  });

  /**
   * Scroll top button
   */
  const scrollTop = document.querySelector('.scroll-top');
  if (scrollTop) {
    const togglescrollTop = function() {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
    window.addEventListener('load', togglescrollTop);
    document.addEventListener('scroll', togglescrollTop);
    scrollTop.addEventListener('click', window.scrollTo({
      top: 0,
      behavior: 'smooth'
    }));
  }

  

  /**
   * Init swiper slider with 1 slide at once in desktop view
   */
  new Swiper('.slides-1', {
    speed: 600,
    loop: false,
    autoplay:false,
    navigation:false,
    pagination:false
    // autoplay: {
    //   delay: 5000,
    //   disableOnInteraction: false
    // },
    // slidesPerView: 'auto',
    // pagination: {
    //   el: '.swiper-pagination',
    //   type: 'bullets',
    //   clickable: true
    // },
    // navigation: {
    //   nextEl: '.swiper-button-next',
    //   prevEl: '.swiper-button-prev',
    // }
  });

  /**
   * Init swiper slider with 3 slides at once in desktop view
   */
  new Swiper('.slides-3-mna', {
    speed: 300,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: true
    },
    slidesPerView: 'auto',
    spaceBetween:20,
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    navigation: {
      nextEl: '.swiper-button-next-mna',
      prevEl: '.swiper-button-prev-mna',
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 40
      },

      1200: {
        slidesPerView: 3,
      }
    }
  });


  new Swiper('.slides-3-mpa', {
    speed: 300,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: true
    },
    slidesPerView: 'auto',
    spaceBetween:20,
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    },
    navigation: {
      nextEl: '.swiper-button-next-mpa',
      prevEl: '.swiper-button-prev-mpa',
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 40
      },

      1200: {
        slidesPerView: 3,
      }
    }
  });

  new Swiper(".myLeadersSwiper", {
    slidesPerView: 1,
    spaceBetween: 10,
    grabCursor: true,
    centeredSlides: true,
    loop: true,
    // autoplay: {
    //   delay: 5000,
    //   disableOnInteraction: true
    // },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
      navigation: {
      nextEl: '.leader-button-next',
      prevEl: '.leader-button-prev',
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      768: {
        slidesPerView: 4,
        spaceBetween: 40,
      },
      1024: {
        slidesPerView: 5,
        spaceBetween: 50,
      },
    },

    // effect: "coverflow",
    // grabCursor: true,
    // centeredSlides: true,
    // loop: true,
    // autoplay: {
    //   delay: 5000,
    //   disableOnInteraction: true
    // },
    // slidesPerView: "auto",
    // coverflowEffect: {
    //   rotate: 50,
    //   stretch: 0,
    //   depth: 100,
    //   modifier: 1,
    //   slideShadows: true,
    // },
    // navigation: {
    //   nextEl: '.leader-button-next',
    //   prevEl: '.leader-button-prev',
    // },
    // breakpoints: {
    //   600: {
    //     slidesPerView: 2,
    //     spaceBetween: 5
    //   },

    //   320: {
    //     slidesPerView: 2,
    //     spaceBetween: 5
    //   },

    //   1200: {
    //     slidesPerView: 3,
    //   }
    // }
  });

  

});