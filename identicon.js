function makeIdenticon() {
  //   scrollTo(0, 0);
  $("html, body").animate({ scrollTop: 0 }, 300, "swing");
  setTimeout(function() {
    html2canvas(document.querySelector("#capture"), {
      scale: 10
    }).then(function(canvas) {
      var download = document.getElementById("download");
      download.href = canvas.toDataURL();
      download.click();
    });
  }, 500);
}
