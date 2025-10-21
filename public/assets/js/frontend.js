document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".draggable");
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener("mousedown", (e) => {
        isDown = true;
        slider.classList.add("active");
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener("mouseleave", () => {
        isDown = false;
        slider.classList.remove("active");
    });

    slider.addEventListener("mouseup", () => {
        isDown = false;
        slider.classList.remove("active");
    });

    slider.addEventListener("mousemove", (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 3; //scroll-fast
        slider.scrollLeft = scrollLeft - walk;
    });

    // Mengambil elemen yang diperlukan
    const menuButton = document.querySelector(".menu-button");
    const sidebar = document.querySelector(".sidebar");
    const closeButton = document.querySelector(".close-button");
    const sidebarBackground = document.getElementById("outside");

    // Fungsi untuk membuka sidebar
    const openSidebar = () => {
        sidebar.classList.remove("sidebar-hidden");
        sidebar.classList.add("sidebar-visible");
        sidebarBackground.classList.add("visible"); // Menampilkan background
    };

    // Fungsi untuk menutup sidebar
    const closeSidebar = () => {
        sidebar.classList.remove("sidebar-visible");
        sidebar.classList.add("sidebar-hidden");
        sidebarBackground.classList.remove("visible"); // Menyembunyikan background
    };

    // Event listener untuk membuka sidebar
    menuButton.addEventListener("click", openSidebar);

    // Event listener untuk menutup sidebar melalui tombol close
    closeButton.addEventListener("click", closeSidebar);

    // Event listener untuk menutup sidebar ketika background diklik
    sidebarBackground.addEventListener("click", function () {
        console.log("test");
    });
});

$(document).ready(function () {
    let page = 1; // Halaman awal

    $("#load-moree").on("click", function () {
        page++; // Tambah halaman
        let searchQuery = document.querySelector('input[name="q"]').value;
        $.ajax({
            url: "/?page=" + page + "&q=" + encodeURIComponent(searchQuery),
            type: "GET",
            success: function (data) {
                console.log(data);
                // Tambahkan blog baru ke kontainer
                data.data.forEach(function (blog) {
                    if (blog.status != 1) return;

                    let userRoleAllowed = (blog.special_role =
                        1 ||
                        (isAuthenticated &&
                            userHasRole([
                                "super-admin",
                                "administrator",
                                "mentor",
                                "koordinator",
                            ])));
                    if (!userRoleAllowed) return;
                    let thumbnail = blog.thumbnail
                        ? blog.thumbnail.startsWith("http")
                            ? blog.thumbnail
                            : "/storage/" + blog.thumbnail
                        : "/assets/media/icons/duotune/modul/File_Explorer_Icon.webp";

                    $("#blog-container").append(`
                        <div class="col">
                            <div class="card border border-secondary-subtle shadow-sm rounded-lg h-100 border-hover mx-auto" style="cursor: pointer; max-width: 22rem;">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center" style="border-bottom: none">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center overflow-hidden bg-light position-relative" style="width: 40px; height: 40px;" data-bs-toggle="tooltip" data-bs-placement="top" title="${
                                                blog.user?.name ?? "Author"
                                            }">
                                                ${
                                                    blog.user?.avatar
                                                        ? `<img src="/media/avatars/${
                                                              blog.user.avatar
                                                          }" alt="${
                                                              blog.user.name ??
                                                              "Author"
                                                          }" class="img-fluid w-100 h-100 object-fit-cover">`
                                                        : `<span class="d-flex align-items-center justify-content-center w-100 h-100 bg-danger text-white fw-bold">${
                                                              blog.user?.name
                                                                  ? blog.user.name
                                                                        .charAt(
                                                                            0
                                                                        )
                                                                        .toUpperCase()
                                                                  : "?"
                                                          }</span>`
                                                }
                                            </div>
                                        </div>
                                        <a href="/blogs/category/${
                                            blog.category?.slug ?? "#"
                                        }" class="ms-2 text-muted text-decoration-none hover-color">— ${blog.category?.name ?? "Uncategorized"}</a>
                                    </div>
                                    <button class="btn p-0 btn-bookmark" data-blog-id="${
                                        blog.id
                                    }">
                                        <i class="${
                                            blog.is_bookmarked
                                                ? "fas fa-bookmark text-primary"
                                                : "far fa-bookmark"
                                        } fs-1"></i>
                                    </button>
                                </div>
                                <div class="card-body  pt-2 ps-6 pe-6 pb-5 ">
                                    <a href="/${blog.slug}">
                                        <img src="${thumbnail}" alt="${blog.title}" class="img-fluid rounded w-100 mb-3" style="height: 160px; object-fit: cover;">
                                        <h5 class="fw-bold fs-3">${
                                            blog.title.length > 40
                                                ? blog.title.substring(0, 40) +
                                                  "..."
                                                : blog.title
                                        }</h5>
                                    </a>
                                    <p class="text-muted small mb-3">${new Date(
                                        blog.created_at
                                    ).toLocaleString("en-US", {
                                        month: "short",
                                        day: "numeric",
                                        year: "numeric",
                                        hour: "numeric",
                                        minute: "numeric",
                                    })}</p>
                                    <div class="d-flex flex-wrap">
                                        ${blog.tags
                                            .slice(0, 5)
                                            .map(
                                                (tag) => `
                                            <a href="/blogs/tag/${tag.slug}" class="badge bg-light  me-2 mb-2 text-decoration-none" style="color: #5a6771;">#${tag.name}</a>
                                        `
                                            )
                                            .join("")}
                                        ${
                                            blog.tags.length > 5
                                                ? `
                                            <span class="badge bg-secondary text-white me-2 mb-2 popover-btn" data-bs-toggle="popover" data-bs-html="true" data-bs-placement="top" data-popover-content="#popover-content-${
                                                blog.id
                                            }" style="cursor: pointer">
                                                +${blog.tags.length - 5} more
                                            </span>
                                            <div id="popover-content-${
                                                blog.id
                                            }" class="d-none">
                                                ${blog.tags
                                                    .slice(5)
                                                    .map(
                                                        (tag) => `
                                                    <a href="/blogs/tag/${tag.slug}" class="d-block text-decoration-none text-dark badge">#${tag.name}</a>
                                                `
                                                    )
                                                    .join("")}
                                            </div>
                                        `
                                                : ""
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                });
                if (!data.next_page_url) {
                    $("#load-moree").hide();
                }
            },
            error: function () {
                alert("Error loading more blogs.");
            },
        });
    });
});

// Bookmark functionality
// Bookmark functionality (Menggunakan event delegation)
$(document).on("click", ".btn-bookmark", function () {
    if (!isAuthenticated) {
        window.location.href = "/page/login"; // Redirect jika belum login
        return;
    }
    let button = $(this);
    let icon = button.find("i");
    let blogId = button.data("blog-id");
    icon.toggleClass("fas fa-bookmark text-primary");

    $.ajax({
        url: "/page/bookmark",
        type: "POST",
        data: {
            blog_id: blogId,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Ambil token dari meta tag
        },
        success: function (response) {
            if (response.status === "added") {
                icon.removeClass("far fa-bookmark text-gray-500").addClass(
                    "fas fa-bookmark text-blue-500"
                );
            } else {
                icon.removeClass("fas fa-bookmark text-blue-500").addClass(
                    "far fa-bookmark text-gray-500"
                );
            }
        },
        error: function (xhr) {
            console.error("Terjadi kesalahan:", xhr);
        },
    });
});
