    $(document).ready(function () {
        $('.select2').select2({
            width: '100%',
            dropdownParent: $('body')
        });
        $(document).on('shown.bs.modal', function (e) {
            $(e.target).find('.select2').each(function () {
                $(this).select2({
                    width: '100%',
                    dropdownParent: $(e
                        .target)
                });
            });
        });

        $('#exportTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
        
    });