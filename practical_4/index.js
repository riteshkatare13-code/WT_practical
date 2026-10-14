var colors = ["#dfefff", "#ffe8b3", "#e6ffe6", "#ffe0e0"];
var i = 0;

document.getElementById("colorBtn").onclick = function () {
  var cards = document.querySelectorAll(".card");
  for (var c = 0; c < cards.length; c++) cards[c].style.backgroundColor = colors[i];
  i = (i + 1) % colors.length;
  document.getElementById("msg").innerText = "Card background changed!";
};

document.getElementById("borderBtn").onclick = function () {
  var boxes = document.querySelectorAll(".box1, .box2, .box3");
  for (var b = 0; b < boxes.length; b++) {
    boxes[b].style.borderColor = boxes[b].style.borderColor === "red" ? "black" : "red";
  }
  document.getElementById("msg").innerText = "Box borders toggled!";
};
