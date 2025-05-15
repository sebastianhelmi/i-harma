import "./bootstrap";
import "bootstrap";
import Swal from "sweetalert2";
import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();
window.Swal = Swal;

document.addEventListener("DOMContentLoaded", () => {
    const sidebarCollapse = document.getElementById("sidebarCollapse");
    const sidebar = document.getElementById("sidebar");

    if (sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");

            // Save state to localStorage
            localStorage.setItem(
                "sidebarCollapsed",
                sidebar.classList.contains("collapsed")
            );
        });

        // Restore state from localStorage
        const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
        if (isCollapsed) {
            sidebar.classList.add("collapsed");
        }
    }
});
