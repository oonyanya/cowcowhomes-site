(function() {
  window.addEventListener('resize', function() {
    var windowWidth;
    if (typeof $ === "undefined") {
      return;
    }
    windowWidth = window.innerWidth;
    if (windowWidth >= 768) {
      $('.dropdown-content').hide();
      $('.dropdown-parent').hover((function() {
        $('.dropdown-content').show();
      }), function() {
        $('.dropdown-content').hide();
      });
      $('.top_menu').show();
    } else {
      $('.top_menu').hide();
      $('.global_nav').click(function() {
        $('.top_menu').toggle();
      });
    }
  });

  window.addEventListener('load', function() {
    var event;
    event = new CustomEvent("resize");
    return window.dispatchEvent(event);
  });

}).call(this);