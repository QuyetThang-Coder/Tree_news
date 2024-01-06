function delete_cart(id) {
    $.ajax ({
        url: "index.php?controller=cart&action=delete_cart",
        type: "POST",
        data: {id: id},
        success: function(data) {
            if(data == "Thành công") {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    iconColor: '#30a702',
                    title: "Thông báo",
                    text: "Xóa thành công",
                });
                if ($("#cart_sum").val() == 0) {
                    location.reload();
                }
            } else {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    iconColor: '#CC0000',
                    title: "Thất bại",
                    text: "Xóa không thành công",
                });
            }
            $(".table_cart").load(location.href + " .table_cart");
            $(".div_cart").load("index.php .txt_cart");
            $(".cart-list").load("index.php .cart-content");
        }
    })
}

function delete_product_cart(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
    });
      swalWithBootstrapButtons.fire({
            title: "Thông báo",
            text: "Bạn có muốn xóa sản phẩm này không?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Có",
            cancelButtonText: "Không",
            reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax ({
                url: "index.php?controller=cart&action=delete_cart",
                type: "POST",
                data: {id: id},
                success: function(data) {
                    if(data == "Thành công") {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            iconColor: '#30a702',
                            title: "Thông báo",
                            text: "Xóa thành công",
                        });
                        if ($("#cart_sum").val() == 0) {
                            location.reload();
                        }
                    } else {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            iconColor: '#CC0000',
                            title: "Thất bại",
                            text: "Xóa không thành công",
                        });
                    }
                    $(".table_cart").load(location.href + " .table_cart");
                    $(".div_cart").load("index.php .txt_cart");
                    $(".cart-list").load("index.php .cart-content");
                }
            })
        }
    });
}

function quantity_up(id, quantity, amount) {
    if(quantity == amount) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "warning",
            iconColor: '#FF9900',
            title: "Thông báo",
            text: "Sản phẩm chỉ còn " + amount + " cây",
        });
        return;
    }
    var quantity = quantity + 1;
    $.ajax ({
        url: "index.php?controller=cart&action=update_cart",
        type: "POST",
        data: {id, quantity},
        success: function(data) {
            if(data == "Thành công") {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    iconColor: '#30a702',
                    title: "Thông báo",
                    text: "Cập nhật thành công",
                });
                $(".table_cart").load(location.href + " .table_cart");
            }
            $(".div_cart").load("index.php .txt_cart");
            $(".cart-list").load("index.php .cart-content");
        }
    })
}

function quantity_down(id, quantity, amount) {
    if(quantity == 1) {
        return;
    }
    var quantity = quantity - 1;
    $.ajax ({
        url: "index.php?controller=cart&action=update_cart",
        type: "POST",
        data: {id, quantity},
        success: function(data) {
            if(data == "Thành công") {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "success",
                    iconColor: '#30a702',
                    title: "Thông báo",
                    text: "Cập nhật thành công",
                });
                $(".table_cart").load(location.href + " .table_cart");
            }
            $(".div_cart").load("index.php .txt_cart");
            $(".cart-list").load("index.php .cart-content");
        }
    })
}