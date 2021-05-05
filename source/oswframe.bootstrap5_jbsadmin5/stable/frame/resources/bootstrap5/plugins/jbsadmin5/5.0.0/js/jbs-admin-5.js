$(function () {

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTopLeft, #sidebarToggleTopRight").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $("#jbsadmin-sidebar").toggleClass("show");
  });

  if ($(window).width() <= 768) {
    $("body").toggleClass("sidebar-toggled");
    $("#jbsadmin-sidebar").toggleClass("show");
  }

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

});
