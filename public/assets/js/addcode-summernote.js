// document.addEventListener("DOMContentLoaded", function () {
//     function addCopyButtons() {
//         document.querySelectorAll("pre").forEach(pre => {
//             if (!pre.parentElement.classList.contains("code-block")) {
//                 let container = document.createElement("div");
//                 container.className = "relative code-block";

//                 let copyBtn = document.createElement("button");
//                 copyBtn.className = "copy-btn absolute top-2 right-2 p-1 bg-transparent border-none cursor-pointer";
//                 let icon = document.createElement("i");
//                 icon.className = "fa fa-copy code-badge-copy-icon text-white";
//                 copyBtn.appendChild(icon);

//                 pre.parentElement.insertBefore(container, pre);
//                 container.appendChild(copyBtn);
//                 container.appendChild(pre);
//             }
//         });

//         // Event listener untuk tombol copy
//         document.querySelectorAll(".copy-btn").forEach(btn => {
//             btn.addEventListener("click", function () {
//                 let pre = this.nextElementSibling;
//                 let codeText = pre.innerText;

//                 navigator.clipboard.writeText(codeText).then(() => {
//                     let icon = this.querySelector("i");

//                     // Pastikan ikon berubah menjadi centang hijau dengan benar
//                     icon.classList.replace("fa-copy", "fa-check");
//                     icon.classList.replace("text-white", "text-green-500"); // Tailwind
//                     icon.classList.replace("text-white", "text-success"); // Bootstrap (jika digunakan)

//                     setTimeout(() => {
//                         icon.classList.replace("fa-check", "fa-copy");
//                         icon.classList.replace("text-green-500", "text-white"); // Tailwind
//                         icon.classList.replace("text-success", "text-white"); // Bootstrap
//                     }, 2000);
//                 }).catch(err => console.error("Error copying text:", err));
//             });
//         });
//     }

//     addCopyButtons();
// });