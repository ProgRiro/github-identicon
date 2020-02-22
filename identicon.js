function makeIdenticon() {
  html2canvas(document.querySelector("#capture"), {
    scale: 10
  }).then(function(canvas) {
    var download = document.getElementById("download");
    download.href = canvas.toDataURL();
    download.click();
  });
}
