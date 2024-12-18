document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
  })
});

function maskCPF(input) {
  let value = input.value;
  value = value.replace(/\D/g, "");
  value = value.replace(/(\d{3})(\d)/, "$1.$2");
  value = value.replace(/(\d{3})(\d)/, "$1.$2");
  value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
  input.value = value;
}

function unmaskCPF(input) {
  input.value = input.value.replace(/\D/g, "");
}

document.addEventListener('DOMContentLoaded', function () {
  const cpfInput = document.getElementById('client_cpf');
  if (cpfInput) {
    cpfInput.addEventListener('input', function () {
      maskCPF(cpfInput);
    });
  }

  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function () {
      unmaskCPF(cpfInput);
    });
  }
});

// document.addEventListener("DOMContentLoaded", function () {
//   const imagePreviewInput = document.getElementById("image_preview_input");
//   const preview = document.getElementById("image_preview");
//   const imagePreviewSubmit = document.getElementById("image_preview_submit");

//   if (!(imagePreviewInput && preview)) return;

//   imagePreviewInput.style.display = "none";
//   imagePreviewSubmit.style.display = "none";

//   preview.addEventListener("click", function () {
//     imagePreviewInput.click();
//   });

//   imagePreviewInput.addEventListener("change", function (event) {
//     const file = event.target.files[0];
//     if (file) {
//       const reader = new FileReader();
//       reader.onload = function (e) {
//         document.getElementById("image_preview").src = e.target.result;
//         imagePreviewSubmit.style.display = "block";
//       };
//       reader.readAsDataURL(file);
//     }
//   });
// });
