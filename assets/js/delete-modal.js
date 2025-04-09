document.addEventListener("turbo:load", function () {
  const modal = document.getElementById("confirmModal");
  const confirmButton = document.getElementById("confirmDelete");
  const cancelButton = document.getElementById("cancelDelete");

  document.querySelectorAll(".delete-action").forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();

      const deleteUrl = this.getAttribute("data-url");

      modal.style.display = "flex";

      confirmButton.onclick = function () {
        window.location.href = deleteUrl;
      };
    });
  });

  cancelButton.onclick = function () {
    modal.style.display = "none";
  };
});
