<script>
    let key = "{{ $repaterKey }}";
    $(`#${key}`).repeater({
        initEmpty: false,

        defaultValues: {
            'text-input': 'foo'
        },

        show: function() {            
            $(this).slideDown();

            // Re-init select2
            $(this).find('[data-kt-repeater="select2"]').select2();

            initializeScripts();
            
            const context = $(this); 
            const formNumber = getFormNumberFromName(context.find('[name*="[product_id]"]').attr('name'));

            // set default values for the new kpis 
            const settings = [
                { name: 'pervious_quarters', value: '1' },
                { name: 'product_id', value: 'all' },
                { name: 'mine_type_ids[]', value: 'all' },
                // { name: 'module_id', value: 'all' },
                { name: 'module_id', value: 1 },
                { name: 'hemisphere', value: 'all' },
                { name: 'region', value: 'all' },
                { name: 'subregion', value: 'all' },
                { name: 'is_camp', value: 'all' },
                { name: 'milling_method', value: 'all' },
                { name: 'underground_mining_method', value: 'all' },
                { name: 'underground_mining_operator', value: 'all' },
                { name: 'open_pit_mining_operator', value: 'all' },
                { name: 'outliers', value: '3' },
                { name: 'is_hide_outliers', value: '0' },
                { name: 'benchmark_type', value: 'PG' },
                { name: 'remove_zeros', value: '1' }
            ];

            settings.forEach(setting => {
                context.find(`[name*="${setting.name}"]`).val(setting.value).trigger('change');
            });

            // Initialize scripts only for the newly added item
            initializeSelect2(context.find('[name*="mine_type_ids"]'));
        },

        hide: function(deleteElement) {
            $(this).slideUp(deleteElement);
        },

        ready: function() {
            // Init select2
            $('[data-kt-repeater="select2"]').select2();

            // Re-init select2
            selectInputs = $('[data-kt-repeater="select2"]').select2();
        }
    });
</script>