<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

<h2 class="text-center">Oversigt</h2>
<!-- 2 oversigter der viser stats -->
<!-- HENT DATA TIL HVILKEN ORDRER -->

<?php
$orderid = $_SESSION['order_id'];
?>

<!-- PHP til at tilføje afgøre om ordre er godkendt eller ej -->
<?php

$farve = NULL;
$statustekst = NULL;

if(isset($_POST['order_approved']))
{
    //INDSÆTTER DATO TIL HVORNÅR ORDREN BLEV GODKENDT.
     $order_approveddate = $_POST['order_approved'];

     $sqlapproved = "UPDATE psv2_orders SET order_approved = '$order_approveddate' WHERE id = '$orderid'";
     $resultapproved = mysqli_query($dbCon,$sqlapproved);

     $sqlsetrejectednull = "UPDATE psv2_orders SET order_rejected = NULL WHERE id = '$orderid'";
     $resultsetrejectednull = mysqli_query($dbCon,$sqlsetrejectednull);

     $statustekst = "Samtlige produktbilleder er godkendt den" . " " . date("Y-m-d");
     $farve = "success";


}elseif(isset($_POST['orderrejectcomment'])) {
     //INDSÆTTER DATO FOR HVORNÅR ORDREN BLEV AFVIST.
     $order_rejectdate = date("Y-m-d");
     $sqlrejected = "UPDATE psv2_orders SET order_rejected = '$order_rejectdate' WHERE id = '$orderid'";
     $resultrejected = mysqli_query($dbCon,$sqlrejected);
     
     //SIKRER ORDRER IKKE ER GODKENDT SAMTIDIG.
     $sqlsettapprovednull = "UPDATE psv2_orders SET order_approved = NULL WHERE id = '$orderid'";
     $resultsetapprovednull = mysqli_query($dbCon,$sqlsettapprovednull);


     //informerer brugeren om ordren er afvist me tekst.
     $statustekst = "Ordren er afvist den" . " " . date("Y-m-d");
     $farve = "danger";


     //INDSÆTTER KOMMENTAREN VED STYLE_1
     $orderreject_comment = $_POST['orderrejectcomment'];
     //henter hvilken styles der skal afvises, og hvor kommentaren skal placeres.
     $order_style1 = $_POST['style_1_line'];
     $sqlsetorderlinescomment = "UPDATE psv2_orders_lines SET orderrejectcomment = '$orderreject_comment' WHERE style_1 = '$order_style1'";
     $resultsetordercomment = mysqli_query($dbCon,$sqlsetorderlinescomment);

}

?>


<!-- SKRIV DATA TIL STATS -->
<div class="container text-center mt-4">
    <div class="row justify-content-md-center">
        <div class="col-lg-4">
            <h6>Ordrer</h6>
            <span class="badge badge-pill badge-dark">#<?php echo $orderid;?></span>
            <h6 class="mt-2"></h6>
            <span class="badge badge-pill badge-<?php echo $farve;?>"><?php echo $statustekst;?></span>
        </div>
    </div>
</div>

<!-- TABLE TIL ORDRELISTE -->
<div class="m-3">
    <form method="post">
        <button onClick="" type="submit" class="btn btn-success knapstorrelse pull-right mb-2" name="order_approved"
            value="<?php echo date("Y-m-d"); ?>">Godkend alle produktbilleder</button>
    </form>

    <table class="table mx-auto mt-5">
        <thead class="thead-dark">
            <tr class="text-center">
                <th class="sorter-false filter-false">Produktbilleder</th>
                <th class="mx-5" data-placeholder="&#x1F50D;">&nbsp;&nbsp;&nbsp;Linie&nbsp;&nbsp;&nbsp;</th>
                <th class="sorter-false" data-placeholder="&#x1F50D;">Styles</th>
                <th class="filter-false sorter-false">Kommentarfelt</th>
                <th class="filter-false sorter-false">Godkend/afvis</th>
            </tr>
        </thead>

        <!-- KODE TIL TABLEFOOTER -->
        <tfoot>
            <tr>
                <th colspan="7" class="ts-pager">
                    <div class="form-inline">
                        <div class="btn-group btn-group-sm mx-1" role="group">
                            <button type="button" class="btn btn-secondary first" title="first">⇤</button>
                            <button type="button" class="btn btn-secondary prev" title="previous">←</button>
                        </div>
                        <span class="pagedisplay"></span>
                        <div class="btn-group btn-group-sm mx-1" role="group">
                            <button type="button" class="btn btn-secondary next" title="next">→</button>
                            <button type="button" class="btn btn-secondary last" title="last">⇥</button>
                        </div>
                        <select class="form-control-sm custom-select px-4 mx-1 pagesize" title="Select page size">
                            <option selected="selected" value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                        </select>
                        <select class="form-control-sm custom-select px-4 mx-1 pagenum"
                            title="Select page number"></select>
                    </div>
                </th>
            </tr>
        </tfoot>

        <tbody>
            <?php
     $query = "SELECT * FROM psv2_orders_lines, psv2_orders WHERE psv2_orders_lines.order_id = '$orderid' AND psv2_orders.id = '$orderid'";
     $result = mysqli_query($dbCon, $query) or die(mysqli_error($dbCon));
     while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
     ?>
            <tr>
                <td class="text-center">
                    <!-- // Der laves en echo, hvor der udskrives ordrebilledernes destinationer.. -->
                    <span class='zoom ex2'>
                        <img src="img/ordrer/<?php echo $orderid,"-", $rowcompany['company'], " ", $row['product'],"/", $row['style_1'],"1",".jpg";?>"
                            alt="..." class="img-fluid imgsize">
                    </span>
                    <span class='zoom ex2'>
                        <img src="img/ordrer/<?php echo $orderid,"-", $rowcompany['company'], " ", $row['product'],"/", $row['style_1'],"2",".jpg";?>"
                            alt="..." class="img-fluid imgsize">
                    </span>
                </td>
                <td class="text-center"><?php echo $row['line'];?></td>
                <td>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h5>Style 1</h5>
                            <?php echo $row['style_1'];?>
                        </li>
                        <li class="list-group-item">
                            <h5>Style 2</h5>
                            <?php echo $row['style_2']; ?>
                        </li>
                        <li class="list-group-item">
                            <h5>Style 3</h5>
                            <?php echo $row['style_3']; ?>
                        </li>
                        <li class="list-group-item">
                            <h5>Style 4</h5>
                            <?php echo $row['style_4']; ?>
                        </li>
                    </ul>
                </td>
                <form method="post" action="feedback.php" class="feedbackknap">
                    <td class="text-center">
                        <div class="container">
                            <div class="form-group">
                                <textarea class="form-control" rows="10" cols="50" placeholder="Skriv problemet her..."
                                    name="orderrejectcomment"></textarea>                 
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="submit" name="style1" value="hej" class="btn btn-dark">Indsend
                            feedback</button>
                    </td>
                    </form>
     
            </tr>
            <?php
     }
     ?>

        </tbody>
    </table>
</div>