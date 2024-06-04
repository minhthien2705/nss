<?php require_once('header1.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<div class="page-banner" style="background: #178512; padding-top: 0px; padding-bottom: 0px;">
    <div class="inner">
        <h1>Nhà Xưởng</h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
          
            <div class="col-md-12">
                
                <h3>Tất cả nhà xưởng</h3>
                <div class="product product-cat">
                    <div class="row">
                            
                        <?php
                            $statement = $pdo->prepare("SELECT * FROM tbl_factory");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                            if(!$result){
                                echo '<div class="pl_15">Không tìm thấy nhà xưởng nào!!</div>';
                            }else{

                            foreach ($result as $row) {
                        ?>
                        
                        <div class="col-md-3 item item-product-cat">
                            <div class="inner">
                                <div class="thumb">
                                    <div class="photo" style="background-image:url(img/<?= $row['hinhanh']?>);"></div>
                                    <div class="overlay"></div>
                                </div>
                                <div class="text">
                                    <h3><a href="factory-single.php?id=<?= $row['id']?>"><?php echo $row['tennx'] ?></a></h3>
                                    <p><a href="factory-single.php?id=<?= $row['id']?>"><?php echo $row['manx'] ?></a></p>
                                </div>
                            </div>
                        </div>
                    <?php }
                    }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>





<?php require_once('footer.php'); ?>