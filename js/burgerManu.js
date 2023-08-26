const homeDown = document.getElementById("homedown");
const homeDownMain = document.getElementById("homedownMain");
// alert("hi");
const homeUp = document.getElementById("homeup");
const homeUpMain = document.getElementById("homeupMain");

homeDown.addEventListener("click", (e) => {
  homeDownMain.classList.add("hidden");
  homeUpMain.classList.remove("hidden");
});

homeUp.addEventListener("click", (e) => {
  homeUpMain.classList.add("hidden");
  homeDownMain.classList.remove("hidden");
});
