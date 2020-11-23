(function() {
  window.addEventListener("resize", function() {
    var windowWidth;
    windowWidth = window.innerWidth;
    if (windowWidth >= 768) {
      document.querySelector('.dropdown-content').style.display = "none";
      document.querySelector('.dropdown-parent').addEventListener("mouseover",function() {
        var element = document.querySelector('.dropdown-content');
        if(element.style.display == "none")
          element.style.display = "";
        else
          element.style.display = "none";
      });
      document.querySelector('.top_menu').style.display = "";
    } else {
      document.querySelector('.top_menu').style.display = "none";
      document.querySelector('.global_nav').addEventListener("click",function() {
        var element = document.querySelector('.top_menu');
        if(element.style.display == "none")
          element.style.display = "";
        else
          element.style.display = "none";
      });
    }
  });

  window.addEventListener('load', function() {
    var event;
    event = new CustomEvent("resize");
    return window.dispatchEvent(event);
  });

}).call(this);