<?php require_once('header.php'); ?>

<?php

    require_once '../phpqrcode/qrlib.php';

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
    } else {
    	$valid = 0;
        $error_message .= 'You must have to select a featured photo<br>';
    }


    if($valid == 1) {
		//Tạo đường dẫn và tên qr code
		$path = '../admin/images/';
		$qrcode_name = $_POST['masanpham'].'-'.time().".png";
		$qrcode = $path.$qrcode_name;
		

    	$statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product'");
		$statement->execute();
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$ai_id=$row[10];
		}

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
		        	$statement->execute(array($final_name1[$i],$ai_id));
		        }
            }            
        }

		$final_name = 'product-featured-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

		//Saving data into the main table tbl_product
		$statement = $pdo->prepare("INSERT INTO tbl_product(
										p_name,
										masanpham,
										vungsanxuat,
										nhaxuong,
										vattu,
										traigiong,
										gia,
										xuatxu,
										
										p_featured_photo,
										p_description,
										-- p_short_description,
										-- p_feature,
										p_condition,
										-- p_return_policy,
										p_total_view,
										-- p_is_featured,
										p_is_active,
										ecat_id,
										qrcode
									) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$statement->execute(array(
										$_POST['p_name'],
										$_POST['masanpham'],
										$_POST['vungsanxuat'],
										$_POST['nhaxuong'],
										$_POST['vattu'],
										$_POST['traigiong'],
										$_POST['gia'],
										$_POST['xuatxu'],
										
										$final_name,
										$_POST['p_description'],
										// $_POST['p_short_description'],
										// $_POST['p_feature'],
										$_POST['p_condition'],
										// $_POST['p_return_policy'],
										0,
										// $_POST['p_is_featured'],
										$_POST['p_is_active'],
										$_POST['ecat_id'],
										$qrcode_name	
									));

		// Lấy ID của bản ghi vừa được thêm vào
		$ai_id = $pdo->lastInsertId();

		$url = "http://192.168.154.129/nongsanquenha.com/product.php?id=".$ai_id;
		QRcode::png($url,$qrcode,'L', 4,0);
        if(isset($_POST['size'])) {
			foreach($_POST['size'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$ai_id));
			}
		}

		if(isset($_POST['color'])) {
			foreach($_POST['color'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$ai_id));
			}
		}
	
    	$success_message = 'Thêm sản phẩm thành công.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Thêm sản phẩm</h1>
	</div>
	<div class="content-header-right">
		<a href="product.php" class="btn btn-primary btn-sm">Tổng quan</a>
	</div>
</section>


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
					<div class="box-body">
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
										<option value="<?php echo $row['tcat_id']; ?>"><?php echo $row['tcat_name']; ?></option>
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
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Danh mục cuối<span>*</span></label>
							<div class="col-sm-4">
								<select name="ecat_id" class="form-control select2 end-cat">
									<option value="">Danh mục cuối</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Tên sản phẩm<span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="p_name" class="form-control">
							</div>
						</div>	

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Mã sản phẩm</label>
							<div class="col-sm-4">
								<input type="text" name="masanpham" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Giá</label>
							<div class="col-sm-4">
								<input type="text" name="gia" class="form-control">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Xuất xứ</label>
							<div class="col-sm-4">
								<input type="text" name="xuatxu" class="form-control">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Hình ảnh<span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thông tin sản phẩm</label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"></textarea>
							</div>
						</div>
						
						<div class="form-group">
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
										<option value="<?= $id?>"><?php echo $tenvsx; ?></option>
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
										<option value="<?= $id?>"><?php echo $tennx; ?></option>
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
										<option value="<?= $id?>"><?php echo $tenvt; ?></option>
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
										<option value="<?= $id?>"><?php echo $tengiong; ?></option>
									<?php }?>
									</select>
								</div>



							</div>
						</div>

						</div>

						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Xác thực</label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"></textarea>
							</div>
						</div>

						
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Trạng thái hoạt động</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0">Không hoạt động</option>
									<option value="1">Hoạt động</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Thêm sản phẩm</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>