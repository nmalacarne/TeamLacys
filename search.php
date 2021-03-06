<?php
	include "header.php";
	include_once "Products/products.php";
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
		if(isset($_POST['searchString'])){
            
			$_SESSION['searchString'] = $_POST['searchString'];
			
			$stmt = $DBH->query("select p.id, p.name, p.description, p.image_location, p.price, p.date_added, pr.percentage,pr.expiration_date
									from products p
									left join promotions pr on pr.product_id = p.id and pr.expiration_date >= CURDATE()	
									where name like '%" . $_SESSION['searchString'] . "%'
									or product_category like '%" . $_SESSION['searchString'] . "%'
									or article_category like '%" . $_SESSION['searchString'] . "%'
									or gender_category like '%" . $_SESSION['searchString'] . "%'
									order by p.date_added desc");
									
			$stmt_count_sql = $DBH->query("select count(*) from products where name like '%" . $_SESSION['searchString'] . "%'
									or product_category like '%" . $_SESSION['searchString'] . "%'
									or article_category like '%" . $_SESSION['searchString'] . "%'
									or gender_category like '%" . $_SESSION['searchString'] . "'");
			
			
		}
	}
if(!isset($stmt)){
	$stmt = null;
}
if(!isset($_SESSION['searchString'])){
	$_SESSION['searchString'] = null;
}

$per_row = 4;
?>

<div class="container-fluid">
	<div class="row">
	
		<div class="col-sm-8 col-sm-offset-2">
			<table class = "table table-product">
			<?php
			
			
			//$count1 = $stmt->fetch();			
			$count1 = $stmt_count_sql->fetch();
			
			$i = 0;
										
			echo "<br/>\n";
			if($stmt->rowCount() > 0){
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {	
					
					echo '<td>';
					
					// If the item is added in inventory within last 7 days 
					if (strtotime($row['date_added']) > strtotime('-7 days')) {
						echo displayNewProductIcon();
					}	
					
					echo displayProducts($row['id'], $row['image_location'], $row['name']);
					echo displayPrice($row['price'], $row['percentage'], $row['expiration_date']);
													
					echo "<br/><br/>\n";											
					echo '</td>';

					// display pants in 4 columns
					if (++$i % $per_row == 0 && $i >0 && $i < $count1) {
					echo '<tr></tr>';
					}
				}
			}else{
				echo "<p>That search did not return any results.</p>";
			}
		?>                        
		</table>
			
	   
	   
		</div>
	</div>
</div>