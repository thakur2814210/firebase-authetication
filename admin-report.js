jQuery(document).ready(function ($) {
    $('#grid_no_of_users').dataTable({
        "ajax": {
            "url": "includes/admin-report/r_no_of_users.php"
        },
        "columnDefs": [{
            className: "delete-account",
            "targets": [5]
        }],
        aoColumns: [{
                "mData": 0,
                "mRender": function (data, type, full) {
                    return full[0];
                }
            },
            {
                "mData": 1,
                "mRender": function (data, type, full) {
                    return full[1];
                }
            },
            {
                "mData": 2,
                "mRender": function (data, type, full) {
                    return full[2];
                }
            },
            {
                "mData": 3,
                "mRender": function (data, type, full) {
                    return full[3];
                }
            },
            {
                "mData": 4,
                "mRender": function (data, type, full) {
                    return full[4];
                }
            },
            {
                "mData": 5,
                "mRender": function (data, type, full) {
                    return '<a class="delete-account" data-email="' + full[2] + '" href="#">Delete account</a>';
                }
            }
        ]
    });
    $('#grid_no_of_business_cards').dataTable({
        "ajax": {
            "url": "includes/admin-report/r_no_of_business_cards.php"
        },
    });

    $(document).on('click', '.delete-account', function (e) {
        e.stopPropagation();

        let email = $(this).data('email');
        console.log('bota ', email);
        DeleteAccount(email);
    })

    function DeleteAccount(email) {
        const content = `Are you sure you want to delete ${email}? This action cannot be undone and will delete all info related to this user`;
        console.log(content);

        swal({
            title: 'Delete account',
            text: content,
            html: true,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#ff6603",
            confirmButtonText: 'Delete'
        }, (result) => {
            console.log('swal', result);
            if (result) {
                $.ajax({
                    type: 'post',
                    url: 'includes/admin-report/delete-account.php',
                    data: {email: email},
                    success: function (result) {
                        console.log(result);
                        window.location.reload();
                    },
                    error: function (err) {
                        console.log(JSON.stringify(err, null, 2));
                    }
                });
            }
        });
    }
});