<?php require_once('header.php'); ?>

<?php

require_once '../phpqrcode/qrlib.php';

if (isset($_POST['form1'])) {

	if (isset($_FILES['p_featured_photo']) && $_FILES['p_featured_photo']['name'] != "") {
		$file_extension = pathinfo($_FILES["p_featured_photo"]["name"], PATHINFO_EXTENSION);
		$allowed_extension = array("jpg", "jpeg", "png", "gif");

		if (!in_array($file_extension, $allowed_extension)) {
			echo '<script>alert("Chỉ cho phép tải lên các tệp JPG, JPEG, PNG và GIF!")</script>';
		} else {
			$upload_path = "images/";
			$file_name = basename($_FILES["p_featured_photo"]["name"]);
			$target_path = $upload_path . $file_name;

			if (move_uploaded_file($_FILES["p_featured_photo"]["tmp_name"], $target_path)) {
				// Nếu tải lên thành công, thêm tên file vào database
				$_POST['p_featured_photo'] = $file_name;
			} else {
				echo '<script>alert("Không thể tải lên tệp!")</script>';
			}
		}
	}

	//Tạo đường dẫn và tên qr code
	$path = 'images/';
	$qrcode_name = $_POST['mavung'] . '-' . time() . ".png";
	$qrcode = $path . $qrcode_name;

	//Thêm feature nào thì nhớ add vô cái insert với cái execute, nhớ thêm ? ở value 
	$statement = $pdo->prepare("insert into tbl_area (tenvsx,mavung,dienthoai,qrcode,diachi,map,dientich,soluongdukien,soluongcaycon,thoigiannuoitrong,thongtinchung,nguoidaidien,hinhanh,thuocdoanhnghiep,kythuatvien) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	if ($statement->execute(array($_POST['tenvsx'], $_POST['mavung'], $_POST['dienthoai'], $qrcode_name, $_POST['diachi'], $_POST['map'], $_POST['dientich'], $_POST['soluongdukien'], $_POST['soluongcaycon'], $_POST['thoigiannuoitrong'], $_POST['thongtinchung'], $_POST['nguoidaidien'], $_POST['p_featured_photo'], $_POST['thuocdoanhnghiep'], $_POST['kythuatvien']))) {
		// Lấy ID của bản ghi vừa được thêm vào
		$ai_id = $pdo->lastInsertId();
		#sua qr code
		$url = "http://192.168.154.129/nongsanquenha.com/area-single.php?id=" . $ai_id;
		QRcode::png($url, $qrcode, 'L', 4, 2);
		if (isset($_POST['size'])) {
			foreach ($_POST['size'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_area_size (size_id,p_id) VALUES (?,?)");
				$statement->execute(array($value, $ai_id));
			}
		}
		echo '<script>
                alert("Thêm vùng sản xuất thành công!!");
                window.location.href = "area.php";
            </script>';
	} else {
		echo '<script>
                alert("Thêm vùng sản xuất không thành công!!");
            </script>';
	}
}
?>

<script src="js/api.js"></script>

<section class="content-header">
	<div class="content-header-left">
		<h1>Thêm</h1>
	</div>
	<div class="content-header-right">
		<a href="area.php" class="btn btn-primary btn-sm">Tổng quan</a>
	</div>
</section>


<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if ($error_message) : ?>
				<div class="callout callout-danger">

					<p>
						<?php echo $error_message; ?>
					</p>
				</div>
			<?php endif; ?>

			<?php if ($success_message) : ?>
				<div class="callout callout-success">

					<p><?php echo $success_message; ?></p>
				</div>
			<?php endif; ?>

			<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Tên vùng sản xuất <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="tenvsx" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Mã vùng <span></span></label>
							<div class="col-sm-4">
								<input type="text" name="mavung" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Người đại diện</label>
							<div class="col-sm-4">
								<select name="nguoidaidien" id="nguoidaidien" onchange="getEmail()">
									<?php $sta = $pdo->prepare("SELECT * FROM tbl_user");
									$sta->execute();
									$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

									foreach ($ketqua as $cot) {
										$id = $cot['id'];
										$hoten = $cot['full_name'];
									?>
										<option value="<?= $id ?>"><?php echo $hoten; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Điện thoại</label>
							<div class="col-sm-4">
								<input type="text" name="dienthoai" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Địa chỉ</label>
							<div class="col-sm-4">
								<input type="text" name="diachi" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Bản đồ</label>
							<div class="col-sm-4">
								<input type="text" name="map" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Diện tích</label>
							<div class="col-sm-4">
								<input type="text" name="dientich" class="form-control">
							</div>
						</div>





						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Số lượng dự kiến</label>
							<div class="col-sm-4">
								<input type="text" name="soluongdukien" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Số lượng cây con</label>
							<div class="col-sm-4">
								<input type="text" name="soluongcaycon" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thời gian nuôi trồng</label>
							<div class="col-sm-4">
								<input type="text" name="thoigiannuoitrong" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="lblprovince" class="col-sm-3 control-label">Tỉnh/Thành phố</label>
							<div class="col-sm-4">
								<select name="province" id="province" class="form-control col-sm-4" onchange="handleChange(this)">
									<option value="">Chọn tỉnh/thành phố</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="lbldistrict" class="col-sm-3 control-label">Quận/Huyện</label>
							<div class="col-sm-4">
								<select name="district" id="district" class="form-control col-sm-4" onchange="handleChangeDistrict(this)">
									<option value="">Chọn quận/huyện</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="lblwards" class="col-sm-3 control-label">Phường/Xã</label>
							<div class="col-sm-4">
								<select name="wards" id="wards" class="form-control col-sm-4">
									<option value="">Chọn xã/phường</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thuộc doanh nghiệp</label>
							<div class="col-sm-4">
								<select name="thuocdoanhnghiep" id="thuocdoanhnghiep" onchange="getEmail()">
									<?php $sta = $pdo->prepare("SELECT * FROM tbl_enterprise");
									$sta->execute();
									$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

									foreach ($ketqua as $cot) {
										$id = $cot['id'];
										$tendn = $cot['full_name'];
									?>
										<option value="<?= $id ?>"><?php echo $tendn; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>


						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Kỹ thuật viên</label>
							<div class="col-sm-4">
								<select name="kythuatvien" id="kythuatvien" onchange="getEmail()">
									<?php $sta = $pdo->prepare("SELECT * FROM tbl_technicians");
									$sta->execute();
									$ketqua = $sta->fetchAll(PDO::FETCH_ASSOC);

									foreach ($ketqua as $cot) {
										$id = $cot['id'];
										$tenktv = $cot['full_name'];
									?>
										<option value="<?= $id ?>"><?php echo $tenktv; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>



						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Thông tin chung</label>
							<div class="col-sm-4">
								<input type="text" name="thongtinchung" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Featured Photo <span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>







						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Thêm vùng sản xuất</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>