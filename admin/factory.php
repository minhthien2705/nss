<?php require_once('header.php'); ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Quản lý nhà xưởng</h1>
	</div>
	<div class="content-header-right">
		<a href="factory-add.php" class="btn btn-primary btn-sm">Thêm nhà xưởng</a>
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
								<th >Tên nhà xưởng</th>
								<th> Mã nhà xưởng</th>
								<th>Mã QR?</th>
								<th>Người đại diện</th>
								<th>Điện thoại liên hệ</th>
								<th>Địa chỉ</th>
								<th>Bản đồ</th>
								<th>Diện tích</th>
								<th>Sản lượng</th>
								<th>Vùng sản xuất đang liên kết</th>
								<th>Thuộc doanh nghiệp</th>
								<th>Kỹ thuật viên</th>
								<th>Thông tin chung</th>
                              
								
								
								
								<th width="80">Hành động</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							$statement = $pdo->prepare("SELECT * FROM tbl_factory ORDER BY id DESC");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr>
									<td><?php echo $i; ?></td>
									<td style="width:82px;"><img src="images/<?php echo $row['hinhanh']; ?>" alt="<?php echo $row['hinhanh']; ?>" style="width:80px;"></td>
									<td><?php echo $row['tennx']; ?></td>
									<td><?php echo $row['manx']; ?></td>
									
									<td>
										<img src="images/<?php echo $row['qrcode']; ?>" width="100" height="100" alt="">
									</td>
									<td><?php echo $row['nguoidaidien']; ?></td>
									<td><?php echo $row['dienthoailienhe']; ?></td>
									<td><?php echo $row['diachi']; ?></td>
									<td><?php echo $row['map']; ?></td>
									<td><?php echo $row['dientich']; ?></td>
									<td><?php echo $row['sanluong']; ?></td>
									<td><?php echo $row['vungsanxuatdanglienket']; ?></td>
									<td><?php echo $row['thuocdoanhnghiep']; ?></td>
									<td><?php echo $row['kythuatvien']; ?></td>
							     	<td><?php echo $row['thongtinchung']; ?></td>







									
									
									<!-- <td>
										<input name="link_qrcode" type="hidden" value="http://localhost/nongsanquenha.com/admin/images/<?= $row['qrcode']?>">
										<input class="btn btn-info" type="button" name="nut" value="Tải qrcode">										
										<a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
										<a href="#" class="btn btn-danger btn-xs" data-href="product-delete.php?id=<?php echo $row['p_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>  
									</td> -->
									<td>
								<input id="link_qrcode" name="link_qrcode" type="hidden" value="http://localhost/nongsanquenha.com/admin/images/<?php echo $row['qrcode']?>">
								<input type="hidden" id="manx" name="manx" value="<?php echo $row['manx'];?>">
								<button style="margin-bottom:4px;" class="btn btn-info btn-xs" type="button" onclick="downloadQRCode()">Tải qrcode</button>
								<a href="factory-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-xs">Chỉnh sửa</a>
								<a href="#" class="btn btn-danger btn-xs" data-href="factory-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Xóa</a>  
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
        var manx = document.getElementById('manx').value;
        var nhanhieu = "QRCODENONGSAN_NX";
        var a = document.createElement('a');
        a.href = link_qrcode;
        a.download = nhanhieu + '-' + manx + '.jpg'; // Tên tệp tin với mã sản phẩm
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
                <h4 class="modal-title" id="myModalLabel">Xác nhận xóa nhà xưởng</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa nhà xưởng này?</p>
                <p style="color:red;">Nhà xưởng này sẽ bị xóa hoàn toàn khỏi cơ sở dữ liệu</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <a class="btn btn-danger btn-ok">Xóa nhà xưởng</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>