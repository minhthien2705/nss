<?php require_once('header.php'); ?>

<?php

    require_once '../phpqrcode/qrlib.php';

if(isset($_POST['form1'])) {

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

    //Tạo đường dẫn và tên qr code
		$path = 'images/';
		$qrcode_name = $_POST['magiong'].'-'.time().".png";
		$qrcode = $path.$qrcode_name;

	//Thêm feature nào thì nhớ add vô cái insert với cái execute, nhớ thêm ? ở value 
    $statement = $pdo->prepare("insert into tbl_hatchery (tengiong,magiong,qrcode,hinhanh,xuatxu,gia,mota,huongdansudung,vungsanxuat) values (?,?,?,?,?,?,?,?,?)");
    if($statement->execute(array($_POST['tengiong'],$_POST['magiong'],$qrcode_name,$_POST['p_featured_photo'],$_POST['xuatxu'],$_POST['gia'],$_POST['mota'],$_POST['huongdansudung'],$_POST['vungsanxuat']))){
        // Lấy ID của bản ghi vừa được thêm vào
		$ai_id = $pdo->lastInsertId();
        #sua qr code
		$url = "http://192.168.154.129/nongsanquenha.com/hatchery-single.php?id=".$ai_id;
		QRcode::png($url,$qrcode,'L', 4,2);
		if(isset($_POST['size'])) {
			foreach($_POST['size'] as $value) {
				$statement = $pdo->prepare("INSERT INTO tbl_area_size (size_id,p_id) VALUES (?,?)");
				$statement->execute(array($value,$ai_id));
			}
		}
        echo '<script>
                alert("Thêm trại giống thành công!!");
                window.location.href = "hatchery.php";
            </script>';
    }else{
        echo '<script>
                alert("Thêm trại giống không thành công!!");
            </script>';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Thêm</h1>
	</div>
	<div class="content-header-right">
		<a href="supplies.php" class="btn btn-primary btn-sm">View All</a>
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
							<label for="" class="col-sm-3 control-label">Tên trại giống <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="tengiong" class="form-control">
							</div>
						</div>	
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Mã giống <span></span></label>
							<div class="col-sm-4">
								<input type="text" name="magiong" class="form-control">
							</div>
						</div>	
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Featured Photo <span>*</span></label>
							<div class="col-sm-4" style="padding-top:4px;">
								<input type="file" name="p_featured_photo">
							</div>
						</div>

						 <div class="form-group">
							<label for="" class="col-sm-3 control-label">Xuất xứ</label>
							<div class="col-sm-4">
								<input type="text" name="xuatxu" class="form-control">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Giá</label>
							<div class="col-sm-4">
								<input type="text" name="gia" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Mô tả</label>
							<div class="col-sm-4">
                            <textarea name="mota" id="mota" cols="30" rows="10" class="form-control" ></textarea>
							</div>
						</div>
						
                        <div class="form-group">
							<label for="" class="col-sm-3 control-label">Hướng dẫn sử dụng</label>
							<div class="col-sm-4">
							<textarea name="huongdansudung" id="huongdansudung" cols="30" rows="10" class="form-control" ></textarea>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">Vùng sản xuất đang liên kết</label>
							<div class="col-sm-4">
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

                    
				
						
						
						
						
						
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Thêm trại giống</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>