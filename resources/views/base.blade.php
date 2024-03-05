<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'EDI Local Costing')</title>
    <link rel="icon" href="{{asset('images/icon-light-small.jpg')}}" type="image/icon type">
    <link rel="stylesheet" href="{{asset('css/colors.css')}}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>

    <!--  Select 2 Bootstrap Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>

    <!-- DevExtreme theme Light-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/devextreme/22.2.3/css/dx.material.blue.light.compact.css" rel="stylesheet">

    <!-- DevExtreme theme dark-->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/devextreme/22.2.3/css/dx.material.blue.dark.compact.css" rel="stylesheet"> --}}

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .dx-datagrid .dx-link {
            color: var(--encore-red) !important;
        }

        .dx-pager .dx-page-sizes .dx-selection, .dx-pager .dx-pages .dx-selection {
            background-color: var(--encore-red) !important;
            color: var(--encore-light);
        }

        .dx-button-mode-text.dx-button-default {
            color: var(--encore-red) !important;
        }

        .dx-datagrid-filter-panel .dx-datagrid-filter-panel-clear-filter, .dx-datagrid-filter-panel .dx-datagrid-filter-panel-text {
            color: var(--encore-red) !important;
        }

        .dx-datagrid-filter-panel .dx-icon-filter {
            color: var(--encore-red) !important;
        }

        .dx-checkbox-checked .dx-checkbox-icon {
            color: var(--encore-light);
            background-color: var(--encore-red) !important;
        }

        .dx-checkbox-indeterminate .dx-checkbox-icon {
            color: var(--encore-light);
            background-color: var(--encore-red) !important;
        }

        .encore-bg-red{
            color: var(--encore-light);
            background-color: var(--encore-red) !important;
        }

        .encore-bg-dark{
            color: var(--encore-light);
            background-color: var(--encore-dark) !important;
        }

        .encore-bg-light{
            color: var(--encore-dark);
            background-color: var(--encore-light) !important;
        }

        .encore-text-red{
            color: var(--encore-red) !important;
        }

        .encore-text-dark{
            color: var(--encore-dark) !important;
        }

        .encore-text-light{
            color: var(--encore-light) !important;
        }

        .encore-bg-green{
            color: var(--encore-dark);
            background-color: var(--encore-costing-green) !important;
        }

        .encore-bg-grey{
            color: var(--encore-dark);
            background-color: var(--encore-costing-grey) !important;
        }

        .encore-bg-blue{
            color: var(--encore-dark);
            background-color: var(--encore-costing-blue) !important;
        }
    </style>


</head>
<body class="vh-100 h-100">
    @yield('page')
</body>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<!-- Jquery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>

<!-- DevExtreme library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/devextreme/22.2.3/js/dx.all.js"></script>

<!-- Select 2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<!-- Excel -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.1.1/exceljs.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<!-- File Saver -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>

@yield('scripts')

<script>
    // Logout function
    $('#logout').click(function() {
        $.ajax({
            url: '{!!url("/endsession")!!}',
            type: "GET",
            data: {

            },
            success: function(data) {
                window.location = ('{!!url("/")!!}');
            },
        });
    });
</script>

</html>