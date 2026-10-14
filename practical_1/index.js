document.getElementById("year").textContent = new Date().getFullYear();


document.getElementById("learnMoreBtn").addEventListener("click", function () {
  document.getElementById("about").scrollIntoView({ behavior: "smooth" });
});


document.getElementById("contactForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const message = document.getElementById("message").value.trim();
  const formMsg = document.getElementById("formMsg");

  if (name === "" || email === "" || message === "") {
    formMsg.textContent = "Please fill in all fields.";
    formMsg.style.color = "red";
    return;
  }

  formMsg.textContent = "Thank you, " + name + "! Your message has been sent.";
  formMsg.style.color = "green";

  this.reset();
});