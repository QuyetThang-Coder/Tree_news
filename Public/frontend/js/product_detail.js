// btn-login
$("#btn_addcart").on('click',function() {
    var quantity = $(".product_quantity").val();
    var product = $("#product_text").val();
    $.ajax ({
        url: "index.php?controller=product_detail&product="+product+"&action=add_cart",
        type: "POST",
        data: {quantity: quantity},
        success: function(data) {
            if (data == "Thành công") {
                // Swal.fire({
                //     position: 'center',
                //     icon: 'success',
                //     title: "Thêm giỏ hàng thành công",
                //     showConfirmButton: false,
                //     iconColor: '#30a702',
                //     timer: 800
                // })
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
                    text: "Thêm giỏ hàng thành công"
                });
                // if ($("#cart_sum").val() == 0) {
                //     location.reload();
                // }
            }
            else if (data == "Thất bại") {
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
                    icon: "error",
                    iconColor: '#CC0000',
                    title: "Thông báo",
                    text: "Thêm sản phẩm thất bai. Vui lòng thử lại !!"
                });
            }
            else if (data == "Đã tồn tại") {
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
                    icon: "error",
                    iconColor: '#CC0000',
                    title: "Thông báo",
                    text: "Sản phẩm đã có trong giỏ hàng !!"
                });
            }              
            else if (data == false) {
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
                    text: "vui lòng đăng nhập !!"
                });
            }      
            $(".div_cart").load("index.php .txt_cart");
            $(".cart-list").load("index.php .cart-content");
        }
    });
});