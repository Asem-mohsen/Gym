<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    "use strict";
    let length = $('.table-head th').length - 1;
    var KTList = (function () {
        var t,e,o,n;
        return {
            init: function () {
                (n = document.querySelector("#kt_table")) &&
                    (n.querySelectorAll("tbody tr").forEach((t) => {
                        const e = t.querySelectorAll("td");
                        e[length].setAttribute("data-order", o);
                    }),
                    (t = $(n).DataTable({
                        info: !0,
                        order: [],
                        columnDefs: [
                            { orderable: !0, targets: 0 },
                            { orderable: !0, targets: length },
                        ],
                        "pageLength": 100
                    })).on("draw", function () {
                        KTMenu.init();
                    }),
                    document.querySelector('[data-kt-table-filter="search"]').addEventListener("keyup", function (e) {
                        t.search(e.target.value).draw();
                    }));
            },
        };
    })();
    KTUtil.onDOMContentLoaded(function () {
        KTList.init();
    });
</script>


<script>
    document.getElementById('search-input').addEventListener('input', function() {
        document.getElementById('search-hidden').value = this.value;
    });
    
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('filter-form').submit();
        }
    });
</script>