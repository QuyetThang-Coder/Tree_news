
<!-- Body -->
<div id="newsDetail">
    <div class="newsDetail">
        <!--  -->
        <div class="container">
            <div class="row">
                <div class="col col-xl-9 introduce-content newsDetail-left">
                    <?php 
                        foreach ($news_detail as $news_detail) {
                            echo $news_detail['content_news'];
                        }
                    ?>
                </div>

                <!-- NewsDetail right -->
                <div class="col col-xl-3 newsDetail-right">
                    <div class="newsDetail-right-heading">
                        <h4 class="h3">Tin mới nhất</h4>
                    </div>
                    <?php foreach ($latest as $item) { ?>
                        <div class="row newsDetail-right-item">
                            <div class="col col-xl-5 position-relative overflow-hidden newsDetail-item-left">
                                <a href="news_detail.html"><img class="img-fluid img-square rounded lazy" src="admin/Public/uploads/<?php echo $item['image_news']; ?>" alt="<?php echo $item['title_news']; ?>"></a>
                            </div>
                            <div class="col col-xl-7 newsDetail-item-right">
                                <a href="news_detail.html"><h4><?php echo $item['title_news']; ?></h4></a>
                                <p><?php echo $item['description_news']; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>

        <!--  -->
        <div class="home-bottom">
            <div class="container">
                <div class="home-bottom-share">
                    <a href="mailto:caycanhstore@gmail.com"><h6>Chia sẻ <i class="bi bi-envelope-fill"></i></h6></a>
                </div>
                <div class="home-bottom-content">
                    <div class="row">
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic1.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Chất lượng hàng đầu</h6>                                       
                                <p>Cam kết tất cả sản phẩm chính hãng 100%</p>                                       
                            </div>
                        </div>
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic2.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Giao hàng siêu nhanh</h6>                                       
                                <p>Chúng tôi cam kết giao hàng nhanh nhất đến khách hàng</p>                                       
                            </div>
                        </div>
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic3.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Mua hàng tiết kiệm</h6>                                       
                                <p>Giảm giá & khuyến mại với ưu đãi cực lớn</p>                                       
                            </div>
                        </div>
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic4.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Hỗ trợ online 24/7</h6>                                       
                                <p>Gọi ngay 0946.312.559 để được tư vấn</p>                                       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
