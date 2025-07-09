    $(document).ready(function () {
        $('.select2').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
        });

        $(document).on('shown.bs.modal', function (e) {
            $(e.target).find('.select2').each(function () {
                $(this).select2({
                    // width: '100%',
                    theme: 'bootstrap-5',
                    dropdownParent: $(e
                        .target)
                });
            });
        });

        $('#exportTable').DataTable({
            responsive: {
                details: {
                    type: 'column',
                    target: 0
                }
            },
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0
            }],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            order: [1, 'asc']
        });

    });