<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Quản lý vật tư</h1>
	</div>
	<div class="content-header-right">
		<a href="supplies-add.php" class="btn btn-primary btn-sm">Thêm vật tư</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-hover table-striped">
					<thead class="thead-dark">
							<tr>
								<th width="10">#</th>
								<th>Hình ảnh</th>
								<th >Tên vật tư</th>
								<th> Mã vật tư</th>
								<th>Mã QR?</th>
								<th>Xuất xứ</th>
								<th>Giá</th>
								<th>Mô tả</th>
								<th>Thành phần</th>
								<th>Công dụng</th>
								<th>Hướng dẫn sử dụng</th>
								<th>Điều kiện bảo quản</th>
								
                              
                              
								
								
								
								<th width="80">Hành động</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT * FROM tbl_supplies ORDER BY id DESC");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:82px;"><img src="images/<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['hinhanh']; ?>" style="width:80px;"></td>
									<td><?php echo $row['tenvt']; ?></td>
									<td><?php echo $row['mavt']; ?></td>
									
									<td>
										<img src="images/<?php echo $row['qrcode']; ?>" width="100" height="100" alt="">
									</td>
									<td><?php echo $row['xuatxu']; ?></td>
									<td><?php echo $row['gia']; ?></td>
									<td><?php echo $row['mota']; ?></td>
									<td><?php echo $row['thanhphan']; ?></td>
									<td><?php echo $row['congdung']; ?></td>
									<td><?php echo $row['huongdansudung']; ?></td>
									<td><?php echo $row['dieukienbaoquan']; ?></td>
									
                                   







									
									
									<!-- <td>
										<input name="link_qrcode" type="hidden" value="http://localhost/nongsanquenha.com/admin/images/<?= $row['qrcode']?>">
										<input class="btn btn-info" type="button" name="nut" value="Tải qrcode">										
										<a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="product-delete.php?id=<?php echo $row['p_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
									</td> -->
									<td>
								<input id="link_qrcode" name="link_qrcode" type="hidden" value="http://localhost/nongsanquenha.com/admin/images/<?php echo $row['qrcode']?>">
								<input type="hidden" id="mavt" name="mavt" value="<?php echo $row['mavt'];?>">
								<button style="margin-bottom:4px;" class="btn btn-info btn-xs" type="button" onclick="downloadQRCode()">Tải qrcode</button>
								<a href="supplies-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Chỉnh sửa</a>
								<a href="#" class="btn btn-danger btn-xs" data-href="supplies-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Xóa</a>  
							</td>
							<?php } ?>							
							</tbody>
							</table>
							</div>
							</div>
							</div>
							</div>
							</section>

<script>
    function downloadQRCode() {
        var link_qrcode = document.getElementById('link_qrcode').value;
        var mavt = document.getElementById('mavt').value;
        var nhanhieu = "QRCODENONGSAN_VT";
        var a = document.createElement('a');
        a.href = link_qrcode;
        a.download = nhanhieu + '-' + mavt + '.jpg'; // Tên tệp tin với mã sản phẩm
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Xác nhận xóa vật tư</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vật tư này?</p>
                <p style="color:red;">Vật tư này sẽ bị xóa hoàn toàn khỏi cơ sở dữ liệu</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <a class="btn btn-danger btn-ok">Xóa vật tư</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>