document.addEventListener("DOMContentLoaded", function () {
    console.log("MUST Timetable System Loaded Successfully");

    // Enable Bootstrap Tooltips
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle Navbar Toggle
    let navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener("click", function () {
            document.querySelector('.navbar-collapse').classList.toggle("show");
        });
    }
});
