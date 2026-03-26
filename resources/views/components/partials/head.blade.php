<!-- Required meta tags -->
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Favicon icon-->
<link rel="shortcut icon" type="image/png" href="{{ url('assets/images/logos/favicon.png') }}" />

<!-- Core Css -->
<link rel="stylesheet" href="{{ url('assets/css/styles.css') }}">
{{-- <script src="{{ url('assets/css/styles.css') }}"></script> --}}
{{-- @vite(['resources/scss/styles.scss']) --}}

{{-- Datatable --}}
<link rel="stylesheet" href="{{ url('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />

{{-- Select2 --}}
<link rel="stylesheet" href="{{ url('assets/libs/select2/dist/css/select2.min.css') }}">

{{-- Alert --}}
<link rel="stylesheet" href="{{ url('assets/css/iziToast.min.css') }}">