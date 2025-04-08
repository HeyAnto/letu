// Quantity Forms
document.addEventListener("turbo:load", () => {
  const container = document.getElementById("quantity-container");
  const addButton = document.getElementById("add-quantity");
  const prototype = container.dataset.prototype;
  let index = container.querySelectorAll(".form-group").length;

  const createHeader = () => {
    const headerDiv = document.createElement("div");
    headerDiv.classList.add(
      "flex",
      "flex-row",
      "justify-between",
      "items-center"
    );

    const title = document.createElement("h2");
    title.textContent = "Ingrédient";

    const removeButton = document.createElement("button");
    removeButton.type = "button";
    removeButton.textContent = "✖";
    removeButton.classList.add("btn", "btn-red");

    headerDiv.append(title, removeButton);
    return { headerDiv, removeButton };
  };

  const createQuantityForm = () => {
    const formDiv = document.createElement("div");
    formDiv.classList.add("form-group", "gap-7");

    const { headerDiv, removeButton } = createHeader();
    removeButton.addEventListener("click", () => formDiv.remove());

    const fieldsWrapper = document.createElement("div");
    fieldsWrapper.innerHTML = prototype.replace(/__name__/g, index++);

    formDiv.append(headerDiv, fieldsWrapper);
    container.appendChild(formDiv);
  };

  const initializeExistingForms = () => {
    container.querySelectorAll(".form-group").forEach((div) => {
      const { headerDiv, removeButton } = createHeader();
      removeButton.addEventListener("click", () => div.remove());
      div.prepend(headerDiv);
    });
  };

  addButton.addEventListener("click", createQuantityForm);
  initializeExistingForms();
});

// Step Forms
document.addEventListener("turbo:load", () => {
  const container = document.getElementById("step-container");
  const addButton = document.getElementById("add-step");
  const prototype = container.dataset.prototype;
  let index = container.querySelectorAll(".form-group").length;

  const createHeader = () => {
    const headerDiv = document.createElement("div");
    headerDiv.classList.add(
      "flex",
      "flex-row",
      "justify-between",
      "items-center"
    );

    const title = document.createElement("h2");
    title.textContent = "Étape";

    const removeButton = document.createElement("button");
    removeButton.type = "button";
    removeButton.textContent = "✖";
    removeButton.classList.add("btn", "btn-red");

    headerDiv.append(title, removeButton);
    return { headerDiv, removeButton };
  };

  const createStepForm = () => {
    const formDiv = document.createElement("div");
    formDiv.classList.add("form-group", "gap-7");

    const { headerDiv, removeButton } = createHeader();
    removeButton.addEventListener("click", () => formDiv.remove());

    const fieldsWrapper = document.createElement("div");
    fieldsWrapper.innerHTML = prototype.replace(/__name__/g, index++);

    formDiv.append(headerDiv, fieldsWrapper);
    container.appendChild(formDiv);
  };

  const initializeExistingForms = () => {
    container.querySelectorAll(".form-group").forEach((div) => {
      const { headerDiv, removeButton } = createHeader();
      removeButton.addEventListener("click", () => div.remove());
      div.prepend(headerDiv);
    });
  };

  addButton.addEventListener("click", createStepForm);
  initializeExistingForms();
});
