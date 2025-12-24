// Hide the loader and show content once the page is fully loaded
window.addEventListener("load", function () {
  document.getElementById("loading-spinner").style.display = "none";
  document.getElementById("content").style.display = "block";
});
