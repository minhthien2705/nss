<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['tcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a top level category<br>";
    }

    if(empty($_POST['mcat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select a mid level category<br>";
    }

    if(empty($_POST['ecat_id'])) {
        $valid = 0;
        $error_message .= "You must have to select an end level category<br>";
    }

    if(empty($_POST['p_name'])) {
        $valid = 0;
        $error_message .= "Product name can not be empty<br>";
    }

    

    $path = $_FILES['p_featured_photo']['name'];
    $path_tmp = $_FILES['p_featured_photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $file_name = basename( $path, '.' . $ext );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0;
            $error_message .= 'You must have to upload jpg, jpeg, gif or png file<br>';
        }
    }


    if($valid == 1) {

    	if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) )
        {

        	$photo = array();
            $photo = $_FILES['photo']["name"];
            $photo = array_values(array_filter($photo));

        	$photo_temp = array();
            $photo_temp = $_FILES['photo']["tmp_name"];
            $photo_temp = array_values(array_filter($photo_temp));

        	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_photo'");
			$statement->execute();
			$result = $statement->fetchAll();
			foreach($result as $row) {
				$next_id1=$row[10];
			}
			$z = $next_id1;

            $m=0;
            for($i=0;$i<count($photo);$i++)
            {
                $my_ext1 = pathinfo( $photo[$i], PATHINFO_EXTENSION );
		        if( $my_ext1=='jpg' || $my_ext1=='png' || $my_ext1=='jpeg' || $my_ext1=='gif' ) {
		            $final_name1[$m] = $z.'.'.$my_ext1;
                    move_uploaded_file($photo_temp[$i],"../assets/uploads/product_photos/".$final_name1[$m]);
                    $m++;
                    $z++;
		        }
            }

            if(isset($final_name1)) {
            	for($i=0;$i<count($final_name1);$i++)
		        {
		        	$statement = $pdo->prepare("INSERT INTO tbl_product_photo (photo,p_id) VALUES (?,?)");
		        	$statement->execute(array($final_name1[$i],$_REQUEST['id']));
		        }
            }            
        }

        if($path == '') {
        	$statement = $pdo->prepare("UPDATE tbl_product SET 
        							p_name=?, 
        							masanpham=?,
									gia=?,
									xuatxu=?,
									vungsanxuat=?,
									nhaxuong=?,
									vattu=?,
									traigiong=?,


        							p_description=?,
        							-- p_short_description=?,
        							p_condition=?,
        							-- p_return_policy=?,
        							-- p_is_featured=?,
        							p_is_active=?,
        							ecat_id=?

        							WHERE p_id=?");
        	$statement->execute(array(
        							$_POST['p_name'],
        							$_POST['masanpham'],
									$_POST['gia'],
									$_POST['xuatxu'],
									$_POST['vungsanxuat'],
									$_POST['nhaxuong'],
									$_POST['vattu'],
									$_POST['traigiong'],


        							$_POST['p_description'],
        							// $_POST['p_short_description'],
        							$_POST['p_condition'],
        							// $_POST['p_return_policy'],
        							// $_POST['p_is_featured'],
        							$_POST['p_is_active'],
        							$_POST['ecat_id'],
        							$_REQUEST['id']
        						));
        } else {

        	unlink('../assets/uploads/'.$_POST['current_photo']);

			$final_name = 'product-featured-'.$_REQUEST['id'].'.'.$ext;
        	move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );


        	$statement = $pdo->prepare("UPDATE tbl_product SET 
        							p_name=?, 
									masanpham=?,
									gia=?,
									xuatxu=?,
									vungsanxuat=?,
									nhaxuong=?,
									vattu=?,
									traigiong=?,
        							
        							p_featured_photo=?,
        							p_description=?,
        							-- p_short_description=?,
        						
        							p_condition=?,
        							-- p_return_policy=?,
        							-- p_is_featured=?,
        							p_is_active=?,
        							ecat_id=?

        							WHERE p_id=?");
        	$statement->execute(array(
        							$_POST['p_name'],
        							
        							$final_name,
        							$_POST['p_description'],
        							// $_POST['p_short_description'],
									$_POST['masanpham'],
									$_POST['gia'],
									$_POST['xuatxu'],
									$_POST['vungsanxuat'],
									$_POST['nhaxuong'],
									$_POST['vattu'],
									$_POST['traigiong'],
        							
        							$_POST['p_condition'],
        							// $_POST['p_return_policy'],
        							// $_POST['p_is_featured'],
        							$_POST['p_is_active'],
        							$_POST['ecat_id'],
        							$_REQUEST['id']
        						));
        }
		

        if(isset($_POST['size'])) {

        	$statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));

			foreach($_POST['size'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$_REQUEST['id']));
			}
		} else {
			$statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));
		}

		if(isset($_POST['color'])) {
			
			$statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));

			foreach($_POST['color'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$_REQUEST['id']));
			}
		} else {
			$statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
        	$statement->execute(array($_REQUEST['id']));
		}
	
    	$success_message = 'Product is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Product</h1>
	</div>
	<div class="content-header-right">
		<a href="product.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$p_name = $row['p_name'];
	
	$masanpham = $row['masanpham'];
	$gia = $row['gia'];
	$xuatxu = $row['xuatxu'];
	$vungsanxuat = $row['vungsanxuat'];
	$nhaxuong = $row['nhaxuong'];
	$vattu = $row['vattu'];
	$traigiong = $row['traigiong'];

	$p_featured_photo = $row['p_featured_photo'];
	$p_description = $row['p_description'];
	// $p_short_description = $row['p_short_description'];
	$p_condition = $row['p_condition'];
	// $p_return_policy = $row['p_return_policy'];
	// $p_is_featured = $row['p_is_featured'];
	$p_is_active = $row['p_is_active'];
	$ecat_id = $row['ecat_id'];
}

$statement = $pdo->prepare("SELECT * 
                        FROM tbl_end_category t1
                        JOIN tbl_mid_category t2
                        ON t1.mcat_id = t2.mcat_id
                        JOIN tbl_top_category t3
                        ON t2.tcat_id = t3.tcat_id
                        WHERE t1.ecat_id=?");
$statement->execute(array($ecat_id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $tcat_id = $row['tcat_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$size_id[] = $row['size_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$color_id[] = $row['color_id'];
}
?>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
			
			<p>
			<?php echo $error_message; ?>
			</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
			
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

				<div class="box box-info">
					
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Danh mục đầu<span>*</span></label>
							<div class="col-sm-4">
								<select name="tcat_id" class="form-control select2 top-cat">
		                            <option value="">Danh mục đầu</option>
		                            <?php
		                            $statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
		                            $statement->execute();
		                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
		                            foreach ($result as $row) {
		                                ?>
		                                <option value="<?php echo $row['tcat_id']; ?>" <?php if($row['tcat_id'] == $tcat_id){echo 'selected';} ?>><?php echo $row['tcat_name']; ?></option>
		                                <?php
		                            }
		                            ?>
		                        </select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Danh mục giữa<span>*</span></label>
							<div class="col-sm-4">
								<select name="mcat_id" class="form-control select2 mid-cat">
		                            <option value="">Danh mục giữa</option>
		                            <?php
		                            $statement = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = ? ORDER BY mcat_name ASC");
		                            $statement->execute(array($tcat_id));
		                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
		                            foreach ($result as $row) {
		                                ?>
		                                <option value="<?php echo $row['mcat_id']; ?>" <?php if($row['mcat_id'] == $mcat_id){echo 'selected';} ?>><?php echo $row['mcat_name']; ?></option>
		                                <?php
		                            }
		                            ?>
		                        </select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Danh mục cuối<span>*</span></label>
							<div class="col-sm-4">
								<select name="ecat_id" class="form-control select2 end-cat">
		                            <option value="">Danh mục cuối</option>
		                            <?php
		                            $statement = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id = ? ORDER BY ecat_name ASC");
		                            $statement->execute(array($mcat_id));
		                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);   
		                            foreach ($result as $row) {
		                                ?>
		                                <option value="<?php echo $row['ecat_id']; ?>" <?php if($row['ecat_id'] == $ecat_id){echo 'selected';} ?>><?php echo $row['ecat_name']; ?></option>
		                                <?php
		                            }
		                            ?>
		                        </select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Product Name <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_name" class="form-control" value="<?php echo $p_name; ?>">
							</div>
						</div>	

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Mã sản phẩm <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="masanpham" class="form-control" value="<?php echo $masanpham; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Giá<span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="gia" class="form-control" value="<?php echo $gia; ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Xuất xứ <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="xuatxu" class="form-control" value="<?php echo $xuatxu; ?>">
							</div>
						</div>
						
						
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Ảnh hiện tại</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<img src="../assets/uploads/<?php echo $p_featured_photo; ?>" alt="" style="width:150px;">
								<input type="hidden" name="current_photo" value="<?php echo $p_featured_photo; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thay đổi hình ảnh </label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						
							
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thông tin sản phẩm</label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"><?php echo $p_description; ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Vùng sản xuất</label>
							<div class="col-sm-8">
								<div class="col-sm-8">
									<select name="vungsanxuat" id="vungsanxuat" onchange="getEmail()">
									<?php  $sta = $pdo->prepare("SELECT * FROM tbl_area");
									$sta->execute();
									$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

										foreach($ketqua as $cot){
											$id = $cot['id'];
											$tenvsx = $cot['tenvsx'];
									?>
										<option <?php if($id==$vungsanxuat) {echo "selected"; } else{echo "";}  ?> value="<?= $id?>"><?php echo $tenvsx; ?></option>
									<?php }?>
									</select>
										</div>


							</div>
						</div>


						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Nhà xưởng</label>
							<div class="col-sm-8">
				

								<div class="col-sm-4">
									<select name="nhaxuong" id="nhaxuong" onchange="getEmail()">
									<?php  $sta = $pdo->prepare("SELECT * FROM tbl_factory");
									$sta->execute();
									$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

										foreach($ketqua as $cot){
											$id = $cot['id'];
											$tennx = $cot['tennx'];
									?>
										<option <?php if($id==$nhaxuong) {echo "selected"; } else{echo "";}  ?> value="<?= $id?>"><?php echo $tennx; ?></option>
									<?php }?>
									</select>
								</div>

								
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Vật tư</label>
							<div class="col-sm-8">
								<div class="col-sm-4">
									<select name="vattu" id="vattu" onchange="getEmail()">
									<?php  $sta = $pdo->prepare("SELECT * FROM tbl_supplies");
									$sta->execute();
									$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

										foreach($ketqua as $cot){
											$id = $cot['id'];
											$tenvt = $cot['tenvt'];
									?>
										<option <?php if($id==$vattu) {echo "selected"; } else{echo "";}  ?> value="<?= $id?>"><?php echo $tenvt; ?></option>
									<?php }?>
									</select>
								</div>

								
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Trại giống</label>
							<div class="col-sm-8">
								<div class="col-sm-4">
									<select name="traigiong" id="traigiong" onchange="getEmail()">
									<?php  $sta = $pdo->prepare("SELECT * FROM tbl_hatchery");
									$sta->execute();
									$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

										foreach($ketqua as $cot){
											$id = $cot['id'];
											$tengiong = $cot['tengiong'];
									?>
										<option <?php if($id==$traigiong) {echo "selected"; } else{echo "";}  ?> value="<?= $id?>"><?php echo $tengiong; ?></option>
									<?php }?>
									</select>
								</div>



							</div>
						</div>

						
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Xác thực</label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"><?php echo $p_condition; ?></textarea>
							</div>
						</div>
						
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Trạng thái hoạt động</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0" <?php if($p_is_active == '0'){echo 'selected';} ?>>Không hoạt động</option>
									<option value="1" <?php if($p_is_active == '1'){echo 'selected';} ?>>Hoạt động</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Cập nhật sản phẩm</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>