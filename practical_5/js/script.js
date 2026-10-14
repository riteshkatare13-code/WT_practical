const heading = document.getElementById("heading");
const message = document.getElementById("message");
const image = document.getElementById("myImage");
const dateTime = document.getElementById("datetime");

document.getElementById("changeText").addEventListener("click", function () {
    heading.textContent = "JavaScript DOM Manipulation";
    message.textContent =
        "The content has been changed using JavaScript.";
});

document.getElementById("changeColor").addEventListener("click", function () {
    message.style.color = "white";
    message.style.backgroundColor = "darkblue";
    message.style.padding = "20px";
});

document.getElementById("toggleImage").addEventListener("click", function () {
    image.hidden = !image.hidden;
});

document.getElementById("showDate").addEventListener("click", function () {
    dateTime.textContent =
        "Current date and time: " + new Date().toLocaleString();
});