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

<script>
    // Ensure preloader is hidden after page load
    $(document).ready(function () {
        $(".preloader").fadeOut();
    });

    // Fallback in case jQuery isn't ready
    window.addEventListener("load", function () {
        var preloader = document.querySelector(".preloader");
        if (preloader) {
            preloader.style.display = "none";
        }
    });
</script>
