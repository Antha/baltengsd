function w3_open() {
  $("#main").removeClass("main-sidebar-close");
  $("#main").addClass("main-sidebar-open");
  $("#footer").removeClass("main-sidebar-close");
  $("#footer").addClass("main-sidebar-open");
  $("#mySidebar").removeClass("sidebar-close");
  $("#mySidebar").addClass("sidebar-open");
  $(".dashboard-menu").addClass("main-sidebar-open");
  $(".dashboard-menu").removeClass("main-sidebar-close");
  document.getElementById("openNav").style.display = "none";
}
function w3_close() {
  $("#main").removeClass("main-sidebar-open");
  $("#main").addClass("main-sidebar-close");
  $("#footer").addClass("main-sidebar-close");
  $("#footer").removeClass("main-sidebar-open");
  $("#mySidebar").removeClass("sidebar-open");
  $("#mySidebar").addClass("sidebar-close");
  $(".dashboard-menu").removeClass("main-sidebar-open");
  $(".dashboard-menu").addClass("main-sidebar-close");
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("openNav").style.display = "inline-block";
}
