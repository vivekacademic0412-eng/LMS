document.addEventListener("DOMContentLoaded", function () {
    function openModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.add("open");
        modal.setAttribute("aria-hidden", "false");

        const focusTarget = modal.querySelector("input:not([type='hidden']), select, textarea, button");
        if (focusTarget) {
            window.setTimeout(function () {
                focusTarget.focus();
            }, 0);
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.remove("open");
        modal.setAttribute("aria-hidden", "true");
    }

    document.addEventListener("click", function (e) {
        const openBtn = e.target.closest("[data-modal-open]");
        if (openBtn) {
            const id = openBtn.getAttribute("data-modal-open");
            openModal(id);
            return;
        }

        const closeBtn = e.target.closest("[data-modal-close]");
        if (closeBtn) {
            const id = closeBtn.getAttribute("data-modal-close");
            closeModal(id);
            return;
        }

        const overlay = e.target.classList.contains("modal-overlay") ? e.target : null;
        if (overlay && overlay.classList.contains("open")) {
            overlay.classList.remove("open");
            overlay.setAttribute("aria-hidden", "true");
        }
    });

    document.addEventListener("keydown", function (e) {
        if (e.key !== "Escape") return;

        const activeModal = document.querySelector(".modal-overlay.open");
        if (activeModal) {
            activeModal.classList.remove("open");
            activeModal.setAttribute("aria-hidden", "true");
        }
    });

    document.querySelectorAll(".js-course-form").forEach(function (form) {
        const mainCategory = form.querySelector(".js-main-category");
        const subcategory = form.querySelector(".js-subcategory");

        if (!mainCategory || !subcategory) {
            return;
        }

        const subOptions = Array.from(subcategory.querySelectorAll("option[data-parent]"));
        const defaultCategory = mainCategory.dataset.defaultValue || "";
        const defaultSubcategory = subcategory.dataset.defaultValue || "";

        if (defaultCategory && !mainCategory.value) {
            const categoryExists = Array.from(mainCategory.options).some(function (option) {
                return option.value === defaultCategory;
            });

            if (categoryExists) {
                mainCategory.value = defaultCategory;
            }
        }

        if (defaultSubcategory && !subcategory.value) {
            const subcategoryExists = Array.from(subcategory.options).some(function (option) {
                return option.value === defaultSubcategory;
            });

            if (subcategoryExists) {
                subcategory.value = defaultSubcategory;
            }
        }

        function syncSubcategories() {
            const parentId = mainCategory.value;

            subOptions.forEach(function (option) {
                option.hidden = parentId === "" || option.getAttribute("data-parent") !== parentId;
            });

            subcategory.disabled = parentId === "";

            if (!subcategory.value) {
                return;
            }

            const selectedOption = subcategory.options[subcategory.selectedIndex];
            if (!selectedOption || selectedOption.getAttribute("data-parent") !== parentId) {
                subcategory.value = "";
            }
        }

        mainCategory.addEventListener("change", function () {
            subcategory.value = "";
            subcategory.dataset.defaultValue = "";
            syncSubcategories();
        });

        syncSubcategories();
    });

    const params = new URLSearchParams(window.location.search);
    const modalId = params.get("open_modal");

    if (modalId) {
        openModal(modalId);

        if (window.history && typeof window.history.replaceState === "function") {
            params.delete("open_modal");
            params.delete("create_category");
            params.delete("create_subcategory");

            const query = params.toString();
            const nextUrl = window.location.pathname + (query ? "?" + query : "") + window.location.hash;
            window.history.replaceState({}, document.title, nextUrl);
        }
    }
});
