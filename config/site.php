<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Scripts
    |--------------------------------------------------------------------------
    |
    | Define all JavaScript files needed for your site.
    |
    */

    'scripts' => [
        ['src' => 'assets/plugins/jquery/jquery.min.js'],
        ['src' => 'assets/plugins/jquery-ui/jquery-ui.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/bootstrap/js/bootstrap.bundle.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/chart.js/Chart.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/sparklines/sparkline.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/jqvmap/jquery.vmap.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/jqvmap/maps/jquery.vmap.usa.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/jquery-knob/jquery.knob.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/moment/moment.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/daterangepicker/daterangepicker.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/summernote/summernote-bs4.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables/jquery.dataTables.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-responsive/js/dataTables.responsive.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-buttons/js/dataTables.buttons.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/jszip/jszip.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/pdfmake/pdfmake.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/pdfmake/vfs_fonts.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-buttons/js/buttons.html5.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-buttons/js/buttons.print.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/datatables-buttons/js/buttons.colVis.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js', 'attribute' => 'defer'],
        ['src' => 'assets/dist/js/adminlte.js', 'attribute' => 'defer'],
        ['src' => 'assets/dist/js/pages/dashboard.js', 'attribute' => 'defer'],
        ['src' => 'assets/dist/js/main.js', 'attribute' => 'defer'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    |
    | Define default and directional (LTR/RTL) CSS files.
    |
    */

    'styles' => [
        'default' => [
            'assets/plugins/fontawesome-free/css/all.min.css',
            'assets/dist/css/all.min.css',
            'assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
            'assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css',
            'assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css',
            'assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
            'assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'assets/plugins/jqvmap/jqvmap.min.css',
            'assets/dist/css/adminlte.min.css',
            'assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
            'assets/plugins/daterangepicker/daterangepicker.css',
            'assets/plugins/summernote/summernote-bs4.min.css',
            'assets/dist/css/Style-Admin.css',

        ],

        'directions' => [
            'ltr' => [],
            'rtl' => [],
        ],
    ],

];
