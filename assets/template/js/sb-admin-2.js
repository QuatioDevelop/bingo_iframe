(function($) {
  "use strict"; // Start of use strict
  flexFont();
  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
      $("#support-btn").css("left", "40px");
    };
    supportBtnPos();
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
      $("#support-btn").css("left", "40px");
      $("#chat-movile-btn").css("visibility", "visible");
    };
    if ($(window).width() > 768) {
      $("#chat-movile-btn").css("visibility", "hidden");
      if ($(".sidebar").hasClass("toggled")) {
        $("#support-btn").css("left", "120px");
      }else
      {
        $("#support-btn").css("left", "240px");
      }
    }
    flexFont();
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

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
  supportBtnPos();
})(jQuery); // End of use strict

function supportBtnPos(){
  if ($(window).width() < 768) {
    $("#chat-movile-btn").css("visibility", "visible");
  }
  if ($(window).width() < 768 && !$(".sidebar").hasClass("toggled")) {
    $("#support-btn").css("left", "40px");
  }else
  {
    if ($(window).width() < 768 && $(".sidebar").hasClass("toggled")) {
      $("#support-btn").css("left", "40px");
    }else
    {
      if($(".sidebar").hasClass("toggled"))
      {
        $("#support-btn").css("left", "120px");
      }else
      {
        $("#support-btn").css("left", "240px");
      }
    }
  }
}

function flexFont () {
    var divs = document.getElementsByClassName("flexFont");
    var ball = document.getElementById("b-B1");
    var w = ball && ball.offsetWidth > 0 ? ball.offsetWidth : 43;
    for(var i = 0; i < divs.length; i++) {
        var relFontsize = w*0.4;
        //divs[i].style.fontSize = relFontsize+'px';
        //divs[i].style.top = (relFontsize*2)+'px';
    }
}