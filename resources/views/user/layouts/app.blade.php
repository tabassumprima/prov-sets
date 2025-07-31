<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Delta</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" href="{{ asset('app-assets/images/logo/logo-short.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">

    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/spinkit/spinkit.min.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/charts/chart-apex.css') }}"> --}}
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-file-manager.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">

    <!-- END: Page CSS-->

    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <!-- END: Custom CSS-->

    <style>
        li.dropdown-user .dropdown-menu {
            width: inherit !important;
        }
    </style>
    @yield('page-css')

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->

    @include('user.partials.header-bar')
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    @include('user.partials.sidebar')
    <!-- END: Main Menu-->


    <!-- BEGIN: Content-->
    @yield('content')
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy;
                {{ date('Y') }}<a class="ml-25" href="#">Delta</a><span class="d-none d-sm-inline-block">,
                    All
                    rights Reserved</span></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>

    <!-- END: Page Vendor JS-->




    <script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-repeater.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-input-mask.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>

    <!-- END: Theme JS-->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("button[type='submit'], #reset").forEach(function (button) {
        button.addEventListener("click", function (event) {
            if (button !== document.querySelector(".provisionButton") && button.getAttribute("data-toggle") === "modal") {
                return;
            }
            var form = button.closest("form");

            if (form && !form.checkValidity()) {
                return;
            }

            setTimeout(() => {
                button.disabled = true;
            }, 50);

        });
    });

    $(document).ajaxComplete(function () {
        document.querySelectorAll("button[type='submit'], #filters, #reset").forEach(function (button) {
            button.disabled = false;
        });
    });
});

        </script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
    <!--DataTable Script-->
    <script>
        $(document).ready(function() {
            $('.data-table').DataTable({
                "order": [] // Remove default sorting
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
    <!-- BEGIN: scripts-->
    @yield('scripts')
    <script>
        @if(Session::has('active_provision'))
        checkStatus()
        @endif

        function checkStatus() {
            $(document).ready(function() {
                $.ajax({
                    'url': '{{ route('user.provision') }}',
                    'type': 'get',
                    'dataType': 'json',
                    'success': function(res) {
                        // Check the status condition
                        if (res.status === 'started' || res.status === 'running' || res.status === 'rollback-inprogress') {
                            // Status condition met, do something
                            $('.provision-alert').removeClass('d-none')
                            if (res.type == 'import')
                            {
                                console.log(res)

                                if(res.status  == 'started' || res.status  == 'running')
                                    $('.provision-alert').find('a').html('Import Running')
                                else if(res.status  == 'rollback-inprogress')
                                    $('.provision-alert').find('a').html('Roll Back Running')
                            }
                            else if (res.type == 'provision')
                                $('.provision-alert').find('a').html('Provision Running')
                            else if (res.type == 'opening')
                                $('.provision-alert').find('a').html('Opening Running')
                            else if (res.type == 'posting')
                                $('.provision-alert').find('a').html('Posting Running')

                            setTimeout(checkStatus, 10000); // Adjust the delay as needed
                        } else if (res.status == 'pending') {
                            $('.provision-alert').addClass('d-none')
                            if (!res.refresh) {
                                location.reload()
                            }
                            // Status condition not met, call the function again after a delay
                        }
                        else{
                            $('.provision-alert').addClass('d-none')
                            location.reload()
                        }
                    },
                    'error': function(err) {
                        console.log(err)
                    }
                })
            })
        }

        // Check required fields
        function verifyRequiredField(field, fieldName) {
            if (!field.is(':disabled') && (field.val() === null || field.val() === '')) {
                toastr['error'](fieldName + ' is required.', 'Error', {
                    closeButton: true,
                    tapToDismiss: false,
                });
                return false;
            }
            return true;
        }
    </script>
    <!-- END: scripts-->
    <!--DataTable Script End-->
</body>
<!-- END: Body-->

</html>
