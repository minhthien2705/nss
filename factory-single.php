<?php require_once('header1.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_factory WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if( $total == 0 ) {
        header('location: index.php');
        exit;
    }
}





foreach($result as $row) {
    $tennx = $row['tennx'];
    $manx = $row['manx'];
    $hinhanh = $row['hinhanh'];
    $nguoidaidien = $row['nguoidaidien'];
    $diachi = $row['diachi'];
    $dienthoailienhe = $row['dienthoailienhe'];
    $dientich = $row['dientich'];
    $thongtinchung = $row['thongtinchung'];
    $map = $row['map'];
    $sanluong = $row['sanluong'];
    $vungsanxuatdanglienket = $row['vungsanxuatdanglienket'];
    $thuocdoanhnghiep = $row['thuocdoanhnghiep'];
    $kythuatvien = $row['kythuatvien'];
    
    //Thêm feature ở đây
    $qrcode = $row['qrcode'];
    

}

    $sta = $pdo->prepare("SELECT * FROM tbl_user WHERE id=?");
    $sta->execute(array($nguoidaidien));
    $ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

foreach($ketqua as $cot){
    $hoten = $cot['full_name'];
    $email = $cot['email'];
    
}

$sta = $pdo->prepare("SELECT * FROM tbl_area WHERE id=?");
    $sta->execute(array($vungsanxuatdanglienket));
    $ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

foreach($ketqua as $cot){
    $tenvsx = $cot['tenvsx'];       
}

$sta = $pdo->prepare("SELECT * FROM tbl_enterprise WHERE id=?");
$sta->execute(array($thuocdoanhnghiep));
$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

foreach($ketqua as $cot){
$tendn = $cot['full_name'];
$emaildn = $cot['email'];

}

$sta = $pdo->prepare("SELECT * FROM tbl_technicians WHERE id=?");
$sta->execute(array($kythuatvien));
$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

foreach($ketqua as $cot){
$tenktv = $cot['full_name'];
$emailktv = $cot['email'];

}




if(isset($_POST['form_review'])) {
    
    $statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=? AND cust_id=?");
    $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
    $total = $statement->rowCount();
    
    if($total) {
        $error_message = LANG_VALUE_68; 
    } else {
        $statement = $pdo->prepare("INSERT INTO tbl_rating (p_id,cust_id,comment,rating) VALUES (?,?,?,?)");
        $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id'],$_POST['comment'],$_POST['rating']));
        $success_message = LANG_VALUE_163;    
    }
    
}

?>

<?php
if($error_message1 != '') {
    echo "<script>alert('".$error_message1."')</script>";
}
if($success_message1 != '') {
    echo "<script>alert('".$success_message1."')</script>";
    header('location: product.php?id='.$_REQUEST['id']);
}
?>


<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product">
					<div class="row">
						<div class="col-md-5">
							<ul class="prod-slider">
                                
								<li style="background-image: url(img/<?php echo $hinhanh; ?>);">
                                    <a class="popup" href="assets/uploads/<?php echo $p_featured_photo; ?>"></a>
								</li>
                                <?php
                                // $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                // $statement->execute(array($_REQUEST['id']));
                                // $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                // foreach ($result as $row) {
                                    ?>
                                    <!-- <li style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>);"> -->
                                        <!-- <a class="popup" href="assets/uploads/product_photos/<?php echo $row['photo']; ?>"></a> -->
                                    <!-- </li> -->
                                    <?php
                                // }
                                ?>
							</ul>
							<!-- <div id="prod-pager">
								<a data-slide-index="0" href=""><div class="prod-pager-thumb" style="background-image: url(assets/uploads/<?php echo $p_featured_photo; ?>"></div></a>
                                <?php
                                // $i=1;
                                // $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                                // $statement->execute(array($_REQUEST['id']));
                                // $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                // foreach ($result as $row) {
                                    ?>
                                    <a data-slide-index="<?php echo $i; ?>" href=""><div class="prod-pager-thumb" style="background-image: url(assets/uploads/product_photos/<?php echo $row['photo']; ?>"></div></a>
                                    <?php
                                    // $i++;
                                // }
                                ?>
							</div> -->
						</div>
						<div class="col-md-7">
							<div class="p-title"><h2><?php echo $tennx; ?></h2></div>
                            <!-- <img src="admin/images/<?php echo $qrcode ?>" alt="qrcode"> -->
							<!-- <div class="p-review">
								<div class="rating">
                                    <?php
                                    if($avg_rating == 0) {
                                        echo '';
                                    }
                                    elseif($avg_rating == 1.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    } 
                                    elseif($avg_rating == 2.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 3.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        ';
                                    }
                                    elseif($avg_rating == 4.5) {
                                        echo '
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star-half-o"></i>
                                        ';
                                    }
                                    else {
                                        for($i=1;$i<=5;$i++) {
                                            ?>
                                            <?php if($i>$avg_rating): ?>
                                                <i class="fa fa-star-o"></i>
                                            <?php else: ?>
                                                <i class="fa fa-star"></i>
                                            <?php endif; ?>
                                            <?php
                                        }
                                    }                                    
                                    ?>
                                </div>
							</div> -->
							<div class="p-short-des">
								<p>
									<?php echo $manx; ?>
								</p>
							</div>
                            <img src="admin/images/<?php echo $qrcode ?>" alt="qrcode">
                            <form action="" method="post">
                            <div class="p-quantity">
                                <div class="row">
                                    <?php if(isset($size)): ?>
                                    <div class="col-md-12 mb_20">
                                        <?php echo LANG_VALUE_52; ?> <br>
                                        <select name="size_id" class="form-control select2" style="width:auto;">
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_size");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                if(in_array($row['size_id'],$size)) {
                                                    ?>
                                                    <option value="<?php echo $row['size_id']; ?>"><?php echo $row['size_name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(isset($color)): ?>
                                    <div class="col-md-12">
                                        <?php echo LANG_VALUE_53; ?> <br>
                                        <select name="color_id" class="form-control select2" style="width:auto;">
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_color");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                if(in_array($row['color_id'],$color)) {
                                                    ?>
                                                    <option value="<?php echo $row['color_id']; ?>"><?php echo $row['color_name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>

                                </div>
                                
                            </div>
                        </div>	
					</div>

					<div class="row" style="border-top: 1px solid #ccc;margin-top:10px">
						<div class="col-md-12">
							<!-- Nav tabs -->
							<!-- <ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?php echo LANG_VALUE_59; ?></a></li>
								<li role="presentation"><a href="#feature" aria-controls="feature" role="tab" data-toggle="tab"><?php echo LANG_VALUE_60; ?></a></li>
                                <li role="presentation"><a href="#condition" aria-controls="condition" role="tab" data-toggle="tab"><?php echo LANG_VALUE_61; ?></a></li>
                                <li role="presentation"><a href="#return_policy" aria-controls="return_policy" role="tab" data-toggle="tab"><?php echo LANG_VALUE_62; ?></a></li>
                               <li role="presentation"><a href="#review" aria-controls="review" role="tab" data-toggle="tab"><?php echo LANG_VALUE_63; ?></a></li>
							</ul> -->

							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
                                    <h3>Người đại diện:</h3>
									<p>
                                        <?php
                                        if($nguoidaidien == '') {
                                            echo LANG_VALUE_70;
                                        } else {
                                            echo $hoten;
                                        }
                                        ?>
									</p>
								</div>
                                <div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
                                    <h3>Email:</h3>
									<p>
                                        <?php
                                        if($email == '') {
                                            echo "Không có dữ liệu!!";
                                        } else {
                                            echo $email;
                                        }
                                        ?>
									</p>
								</div>
                                <div role="tabpanel" class="tab-pane active" id="feature" style="margin-top: -30px;">
                                    <h3>Điện thoại liên hệ:</h3>
                                    <p>
                                        <?php
                                        if($dienthoailienhe == '') {
                                            echo LANG_VALUE_71;
                                        } else {
                                            echo $dienthoailienhe;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane active" id="condition" style="margin-top: -30px;">
                                    <h3>Địa chỉ:</h3>
                                    <p>
                                        <?php
                                        if($diachi == '') {
                                            echo LANG_VALUE_72;
                                        } else {
                                            echo $diachi;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane active" id="condition" style="margin-top: -30px;">
                                    <h3>Bản đồ:</h3>
                                    <p>
                                        <?php
                                        if($map == '') {
                                            echo LANG_VALUE_72;
                                        } else {
                                            echo $map;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane active" id="return_policy" style="margin-top: -30px;">
                                    <h3>Diện tích:</h3>
                                    <p>
                                        <?php
                                        if($dientich == '') {
                                            echo LANG_VALUE_73;
                                        } else {
                                            echo $dientich;
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane active" id="return_policy" style="margin-top: -30px;">
                                    <h3>Sản lượng:</h3>
                                    <p>
                                        <?php
                                        if($sanluong == '') {
                                            echo "không có dữ liệu";
                                        } else {
                                            echo $sanluong;
                                        }
                                        ?>
                                    </p>
                                </div>

                                <div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
                                    <h3>Vùng sản xuất đang liên kết:</h3>
									<p>
                                        <?php
                                        if($tenvsx == '') {
                                            echo "Không có dữ liệu";
                                        } else {
                                            echo $tenvsx;
                                        }
                                        ?>
									</p>
								</div>

                                <div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
                                    <h3>Thuộc doanh nghiệp:</h3>
									<p>
                                        <?php
                                        if($thuocdoanhnghiep == '') {
                                            echo "Không tìm thấy doanh nghiệp nào?";
                                        } else {
                                            echo $tendn;
                                        }
                                        ?>
									</p>
								</div>
                                <div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
                                    <h3>Email:</h3>
									<p>
                                        <?php
                                        if($email == '') {
                                            echo "Không có dữ liệu!!";
                                        } else {
                                            echo $emaildn;
                                        }
                                        ?>
									</p>
								</div>

                                
                                <div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
                                    <h3>Kỹ thuật viên:</h3>
									<p>
                                        <?php
                                        if($kythuatvien == '') {
                                            echo "Không tìm thấy kỹ thuật viên nào?";
                                        } else {
                                            echo $tenktv;
                                        }
                                        ?>
									</p>
								</div>
                                <div role="tabpanel" class="tab-pane active" id="description" style="margin-top: -30px;">
                                    <h3>Email:</h3>
									<p>
                                        <?php
                                        if($email == '') {
                                            echo "Không có dữ liệu!!";
                                        } else {
                                            echo $emailktv;
                                        }
                                        ?>
									</p>
								</div>
                                


                               
                                <div role="tabpanel" class="tab-pane active" id="return_policy" style="margin-top: -30px;">
                                    <h3>Thông tin chung:</h3>
                                    <p>
                                        <?php
                                        if($thongtinchung == '') {
                                            echo "không có dữ liệu";
                                        } else {
                                            echo $thongtinchung;
                                        }
                                        ?>
                                    </p>
                                </div>


								<div role="tabpanel" class="tab-pane " id="review" style="margin-top: -30px;">

                                    <div class="review-form">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * 
                                                            FROM tbl_rating t1 
                                                            JOIN tbl_customer t2 
                                                            ON t1.cust_id = t2.cust_id 
                                                            WHERE t1.p_id=?");
                                        $statement->execute(array($_REQUEST['id']));
                                        $total = $statement->rowCount();
                                        ?>
                                        <h2><?php echo LANG_VALUE_63; ?> (<?php echo $total; ?>)</h2>
                                        <?php
                                        if($total) {
                                            $j=0;
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                $j++;
                                                ?>
                                                <div class="mb_10"><b><u><?php echo LANG_VALUE_64; ?> <?php echo $j; ?></u></b></div>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th style="width:170px;"><?php echo LANG_VALUE_75; ?></th>
                                                        <td><?php echo $row['cust_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_76; ?></th>
                                                        <td><?php echo $row['comment']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo LANG_VALUE_78; ?></th>
                                                        <td>
                                                            <div class="rating">
                                                                <?php
                                                                for($i=1;$i<=5;$i++) {
                                                                    ?>
                                                                    <?php if($i>$row['rating']): ?>
                                                                        <i class="fa fa-star-o"></i>
                                                                    <?php else: ?>
                                                                        <i class="fa fa-star"></i>
                                                                    <?php endif; ?>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php
                                            }
                                        } else {
                                            echo LANG_VALUE_74;
                                        }
                                        ?>
                                        
                                        <h2><?php echo LANG_VALUE_65; ?></h2>
                                        <?php
                                        if($error_message != '') {
                                            echo "<script>alert('".$error_message."')</script>";
                                        }
                                        if($success_message != '') {
                                            echo "<script>alert('".$success_message."')</script>";
                                        }
                                        ?>
                                        <?php if(isset($_SESSION['customer'])): ?>

                                            <?php
                                            $statement = $pdo->prepare("SELECT * 
                                                                FROM tbl_rating
                                                                WHERE p_id=? AND cust_id=?");
                                            $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
                                            $total = $statement->rowCount();
                                            ?>
                                            <?php if($total==0): ?>
                                            <form action="" method="post">
                                            <div class="rating-section">
                                                <input type="radio" name="rating" class="rating" value="1" checked>
                                                <input type="radio" name="rating" class="rating" value="2" checked>
                                                <input type="radio" name="rating" class="rating" value="3" checked>
                                                <input type="radio" name="rating" class="rating" value="4" checked>
                                                <input type="radio" name="rating" class="rating" value="5" checked>
                                            </div>                                            
                                            <div class="form-group">
                                                <textarea name="comment" class="form-control" cols="30" rows="10" placeholder="Write your comment (optional)" style="height:100px;"></textarea>
                                            </div>
                                            <input type="submit" class="btn btn-default" name="form_review" value="<?php echo LANG_VALUE_67; ?>">
                                            </form>
                                            <?php else: ?>
                                                <span style="color:red;"><?php echo LANG_VALUE_68; ?></span>
                                            <?php endif; ?>


                                        <?php else: ?>
                                            <p class="error">
												<?php echo LANG_VALUE_69; ?> <br>
												<a href="login.php" style="color:red;text-decoration: underline;"><?php echo LANG_VALUE_9; ?></a>
											</p>
                                        <?php endif; ?>                         
                                    </div>

								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>