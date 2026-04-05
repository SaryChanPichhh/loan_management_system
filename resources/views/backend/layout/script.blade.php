<script src="{{
        asset('backend_assets/assets/libs/jquery/dist/jquery.min.js')
    }}"></script>
<script src="{{
        asset('backend_assets/assets/libs/popper.js/dist/umd/popper.min.js')
    }}"></script>
<script src="{{
        asset('backend_assets/assets/libs/bootstrap/dist/js/bootstrap.min.js')
    }}"></script>
<!-- apps -->
<!-- apps -->
<script src="{{
        asset('backend_assets/dist/js/app-style-switcher.js')
    }}"></script>
<script src="{{ asset('backend_assets/dist/js/feather.min.js') }}"></script>
<script src="{{
        asset(
            'backend_assets/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js'
        )
    }}"></script>
<script src="{{ asset('backend_assets/dist/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('backend_assets/dist/js/custom.min.js') }}"></script>
<!--This page JavaScript -->
<script src="{{
        asset('backend_assets/assets/extra-libs/c3/d3.min.js')
    }}"></script>
<script src="{{
        asset('backend_assets/assets/extra-libs/c3/c3.min.js')
    }}"></script>
<script src="{{
        asset('backend_assets/assets/libs/chartist/dist/chartist.min.js')
    }}"></script>
<script src="{{
        asset(
            'backend_assets/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js'
        )
    }}"></script>
<script src="{{
        asset(
            'backend_assets/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js'
        )
    }}"></script>

<script src="{{
        asset('backend_assets/dist/js/pages/dashboards/dashboard1.min.js')
    }}"></script>
<!--Morris JavaScript -->
<script src="{{
        asset('backend_assets/assets/libs/raphael/raphael.min.js')
    }}"></script>
<script src="{{
        asset('backend_assets/assets/libs/morris.js/morris.min.js')
    }}"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // Ensure preloader is hidden after page load
    $(document).ready(function () {
        $(".preloader").fadeOut();
    });

    // Handle Logout Confirmation
    function confirmLogout() {
        Swal.fire({
            title: 'ចាកចេញពីប្រព័ន្ធ?', // Logout?
            text: "តើអ្នកប្រាកដជាចង់ចាកចេញមែនទេ?", // Are you sure you want to logout?
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#5f76e8', // Primary color match
            cancelButtonColor: '#ff4f70', // Danger color
            confirmButtonText: 'បាទ/ចាស, ចាកចេញ (Yes, Logout)',
            cancelButtonText: 'បោះបង់ (Cancel)',
            background: '#fff',
            borderRadius: '1.25rem',
            customClass: {
                title: 'font-weight-bold text-dark',
                popup: 'rounded-xl shadow-lg border-0'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    // Fallback in case jQuery isn't ready
    window.addEventListener("load", function () {
        var preloader = document.querySelector(".preloader");
        if (preloader) {
            preloader.style.display = "none";
        }
    });
</script>
