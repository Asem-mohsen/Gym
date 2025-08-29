<script src="{{ asset('assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- If you want Excel/PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    "use strict";
    let length = $('.table-head th').length - 1;

    var KTList = (function () {
        var t, n;

        return {
            init: function () {
                (n = document.querySelector("#kt_table")) &&
                    (n.querySelectorAll("tbody tr").forEach((row) => {
                        const e = row.querySelectorAll("td");
                        e[length].setAttribute("data-order", e[length].innerText);
                    }),

                    // Initialize DataTable
                    (t = $(n).DataTable({
                        info: true,
                        order: [],
                        pageLength: 100,
                        dom: 'Brtip', // enable buttons
                        buttons: [
                            { extend: 'copy', className: 'btn btn-light-primary btn-sm' },
                            { extend: 'csv', className: 'btn btn-light-info btn-sm' },
                            { extend: 'excel', className: 'btn btn-light-success btn-sm' },
                            { extend: 'pdf', className: 'btn btn-light-danger btn-sm' },
                            { extend: 'print', className: 'btn btn-light-dark btn-sm' }
                        ],
                        columnDefs: [
                            { orderable: true, targets: 0 },
                            { orderable: true, targets: length },
                        ],
                    }))
                    .on("draw", function () {
                        KTMenu.init();
                    }),

                    // Search input
                    document.querySelector('[data-kt-table-filter="search"]').addEventListener("keyup", function (e) {
                        t.search(e.target.value).draw();
                    }),

                    // Move buttons into Metronic toolbar (optional)
                    t.buttons().container().appendTo('#kt_table_wrapper .col-md-6:eq(0)')
                );
            },
        };
    })();

    KTUtil.onDOMContentLoaded(function () {
        KTList.init();
    });
</script>
