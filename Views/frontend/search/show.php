<?php 
    $this -> view('frontend.header', 
                [
                    'category' => $category, 
                    'user' => $user, 
                    'sum_cart'   => $sum_cart,
                    'allCart'   => $allCart,
                ]);
    $this -> view('frontend.search.search',
                [
                    'category' => $category,
                    'getcategory' => $getcategory,
                    'product'  => $product,
                    'productSelling' => $productSelling,
                    'key' => $key,
                
                ]);
    $this -> view('frontend.footer');

?>