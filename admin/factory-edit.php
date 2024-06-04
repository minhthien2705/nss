<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])){

	if(isset($_FILES['p_featured_photo']) && $_FILES['p_featured_photo']['name'] != "") {
		$file_extension = pathinfo($_FILES["p_featured_photo"]["name"], PATHINFO_EXTENSION);
		$allowed_extension = array("jpg", "jpeg", "png", "gif");
	
		if(!in_array($file_extension, $allowed_extension)) {
			echo '<script>alert("Chỉ cho phép tải lên các tệp JPG, JPEG, PNG và GIF!")</script>';
		} else {
			$upload_path = "images/";
			$file_name = basename($_FILES["p_featured_photo"]["name"]);
			$target_path = $upload_path . $file_name;
	
			if(move_uploaded_file($_FILES["p_featured_photo"]["tmp_name"], $target_path)) {
				// Nếu tải lên thành công, thêm tên file vào database
				$_POST['p_featured_photo'] = $file_name;
			} else {
				echo '<script>alert("Không thể tải lên tệp!")</script>';
			}
		}
	}









    //Thêm feature nào thì nhớ add vô cái update
        $statement = $pdo->prepare("UPDATE tbl_factory SET tennx=?, dienthoailienhe=?, manx=?, hinhanh=?, nguoidaidien=?, diachi=?, dientich=?, sanluong=?, thongtinchung=?, map=?, vungsanxuatdanglienket=?, thuocdoanhnghiep=?, kythuatvien=?   WHERE id=?");
        if($statement->execute(array($_POST['tennx'],$_POST['dienthoailienhe'],$_POST['manx'],$_POST['p_featured_photo'],$_POST['nguoidaidien'],$_POST['diachi'],$_POST['dientich'],$_POST['sanluong'],$_POST['thongtinchung'],$_POST['map'],$_POST['vungsanxuatdanglienket'],$_POST['thuocdoanhnghiep'],$_POST['kythuatvien'],$_REQUEST['id']))){
            echo '<script>
                    alert("Chỉnh sửa thành công!!");
                    window.location.href = "factory.php";
                </script>';
        }else{
            echo '<script>
                    alert("Chỉnh sửa không thành công!!");
                </script>';
        }
        
    
}

?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_factory WHERE id=?");
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
		<h1>Chỉnh sửa nhà xưởng</h1>
	</div>
	<div class="content-header-right">
		<a href="factory.php" class="btn btn-primary btn-sm">Xem tất cả</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_factory WHERE id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$tennx = $row['tennx'];
	$dienthoailienhe = $row['dienthoailienhe'];
	$manx = $row['manx'];
	$diachi = $row['diachi'];
	$dientich = $row['dientich'];
	$sanluong = $row['sanluong'];
	
	$thongtinchung = $row['thongtinchung'];
	$map = $row['map'];
	$hinhanh = $row['hinhanh'];
	$vungsanxuatdanglienket = $row['vungsanxuatdanglienket'];
	$nguoidaidien = $row['nguoidaidien'];
	$thuocdoanhnghiep = $row['thuocdoanhnghiep'];
	$kythuatvien = $row['kythuatvien'];
    
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
					<div class="box-body">
						<!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label">Top Level Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="tcat_id" class="form-control select2 top-cat">
		                            <option value="">Select Top Level Category</option>
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
							<label for="" class="col-sm-3 control-label">Mid Level Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="mcat_id" class="form-control select2 mid-cat">
		                            <option value="">Select Mid Level Category</option>
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
							<label for="" class="col-sm-3 control-label">End Level Category Name <span>*</span></label>
							<div class="col-sm-4">
								<select name="ecat_id" class="form-control select2 end-cat">
		                            <option value="">Select End Level Category</option>
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
						</div> -->
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Tên nhà xưởng <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="tennx" class="form-control" value="<?php echo $tennx; ?>">
							</div>
						</div>	
                         
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Mã nhà xưởng <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="manx" class="form-control" value="<?php echo $manx; ?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Người đại diện</label>
							<div class="col-sm-4">
								<select name="nguoidaidien" id="nguoidaidien" onchange="getEmail()">
								<?php  $sta = $pdo->prepare("SELECT * FROM tbl_user");
								$sta->execute();
								$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

									foreach($ketqua as $cot){
										$id = $cot['id'];
										$hoten = $cot['full_name'];
								?>
									<option value="<?= $id?>"><?php echo $hoten; ?></option>
								<?php }?>
								</select>
							</div>
						</div>

						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Ảnh hiện tại</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<img src="../admin/images/<?php echo $hinhanh; ?>" alt="" style="width:150px;">
								<input type="hidden" name="" value="<?php echo $hinhanh; ?>">
							</div>
						</div>



						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thay đổi ảnh <span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo" value="<?php echo $hinhanh; ?>">
							</div>
						</div>
						
							
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Điện thoại liên hệ</label>
							<div class="col-sm-4">
								<input type="text" name="dienthoailienhe" class="form-control" value="<?php echo $dienthoailienhe; ?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Địa chỉ <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="diachi" class="form-control" value="<?php echo $diachi; ?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Diện tích <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="dientich" class="form-control" value="<?php echo $dientich; ?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Sản lượng <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="sanluong" class="form-control" value="<?php echo $sanluong; ?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">vùng sản xuất đang liên kết</label>
							<div class="col-sm-4">
								<select name="vungsanxuatdanglienket" id="vungsanxuatdanglienket" onchange="getEmail()">
								<?php  $sta = $pdo->prepare("SELECT * FROM tbl_area");
								$sta->execute();
								$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

									foreach($ketqua as $cot){
										$id = $cot['id'];
										$tenvsx = $cot['tenvsx'];
								?>
									<option <?php if($id==$vungsanxuatdanglienket) {echo "selected"; } else{echo "";}  ?> value="<?= $id?>"><?php echo $tenvsx; ?></option>
								<?php }?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thuộc doanh nghiệp</label>
							<div class="col-sm-4">
								<select name="thuocdoanhnghiep" id="thuocdoanhnghiep" onchange="getEmail()">
								<?php  $sta = $pdo->prepare("SELECT * FROM tbl_enterprise");
								$sta->execute();
								$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

									foreach($ketqua as $cot){
										$id = $cot['id'];
										$tendn = $cot['full_name'];
								?>
									<option <?php if($id==$thuocdoanhnghiep) {echo "selected"; } else{echo "";}  ?> value="<?= $id?>"><?php echo $tendn; ?></option>
								<?php }?>
								</select>
							</div>
						</div>

                        <div class="form-group">
							<label for="" class="col-sm-3 control-label">Kỹ thuật viên</label>
							<div class="col-sm-4">
								<select name="kythuatvien" id="kythuatvien" onchange="getEmail()">
								<?php  $sta = $pdo->prepare("SELECT * FROM tbl_technicians");
								$sta->execute();
								$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

									foreach($ketqua as $cot){
										$id = $cot['id'];
										$tenktv = $cot['full_name'];
								?>
									<option <?php if($id==$kythuatvien) {echo "selected"; } else{echo "";}  ?> value="<?= $id?>"><?php echo $tenktv; ?></option>
								<?php }?>
								</select>
							</div>
						</div>
                       




						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thông tin chung <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="thongtinchung" class="form-control" value="<?php echo $thongtinchung; ?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Bản đồ</label>
							<div class="col-sm-4">
								<textarea name="map" id="map" cols="30" rows="10" class="form-control" ><?php echo $map; ?></textarea>
							</div>
						</div>
						<!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label">Select Color</label>
							<div class="col-sm-4">
								<select name="color[]" class="form-control select2" multiple="multiple">
									<?php
									$is_select = '';
									$statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
									$statement->execute();
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);			
									foreach ($result as $row) {
										if(isset($color_id)) {
											if(in_array($row['color_id'],$color_id)) {
												$is_select = 'selected';
											} else {
												$is_select = '';
											}
										}
										?>
										<option value="<?php echo $row['color_id']; ?>" <?php echo $is_select; ?>><?php echo $row['color_name']; ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Existing Featured Photo</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<img src="../assets/uploads/<?php echo $p_featured_photo; ?>" alt="" style="width:150px;">
								<input type="hidden" name="current_photo" value="<?php echo $p_featured_photo; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Change Featured Photo </label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Other Photos</label>
							<div class="col-sm-4" style="padding-top:4px;">
								<table id="ProductTable" style="width:100%;">
			                        <tbody>
			                        	<?php
			                        	$statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
			                        	$statement->execute(array($_REQUEST['id']));
			                        	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			                        	foreach ($result as $row) {
			                        		?>
											<tr>
				                                <td>
				                                    <img src="../assets/uploads/product_photos/<?php echo $row['photo']; ?>" alt="" style="width:150px;margin-bottom:5px;">
				                                </td>
				                                <td style="width:28px;">
				                                	<a onclick="return confirmDelete();" href="product-other-photo-delete.php?id=<?php echo $row['pp_id']; ?>&id1=<?php echo $_REQUEST['id']; ?>" class="btn btn-danger btn-xs">X</a>
				                                </td>
				                            </tr>
			                        		<?php
			                        	}
			                        	?>
			                        </tbody>
			                    </table>
							</div>
							<div class="col-sm-2">
			                    <input type="button" id="btnAddNew" value="Add Item" style="margin-top: 5px;margin-bottom:10px;border:0;color: #fff;font-size: 14px;border-radius:3px;" class="btn btn-warning btn-xs">
			                </div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Description</label>
							<div class="col-sm-8">
								<textarea name="p_description" class="form-control" cols="30" rows="10" id="editor1"><?php echo $p_description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Short Description</label>
							<div class="col-sm-8">
								<textarea name="p_short_description" class="form-control" cols="30" rows="10" id="editor1"><?php echo $p_short_description; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Features</label>
							<div class="col-sm-8">
								<textarea name="p_feature" class="form-control" cols="30" rows="10" id="editor3"><?php echo $p_feature; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Conditions</label>
							<div class="col-sm-8">
								<textarea name="p_condition" class="form-control" cols="30" rows="10" id="editor4"><?php echo $p_condition; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Return Policy</label>
							<div class="col-sm-8">
								<textarea name="p_return_policy" class="form-control" cols="30" rows="10" id="editor5"><?php echo $p_return_policy; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Featured?</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="0" <?php if($p_is_featured == '0'){echo 'selected';} ?>>No</option>
									<option value="1" <?php if($p_is_featured == '1'){echo 'selected';} ?>>Yes</option>
								</select> 
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Is Active?</label>
							<div class="col-sm-8">
								<select name="p_is_active" class="form-control" style="width:auto;">
									<option value="0" <?php if($p_is_active == '0'){echo 'selected';} ?>>No</option>
									<option value="1" <?php if($p_is_active == '1'){echo 'selected';} ?>>Yes</option>
								</select> 
							</div>
						</div> -->
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Cập nhật nhà xưởng</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>