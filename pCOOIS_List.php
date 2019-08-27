<?php
    include_once('include/chk_Session.php');
    if($user_email == "")
    {
        echo "<script> 
                alert('Warning! Please Login!'); 
                window.location.href='login.php'; 
            </script>";
    }
    else
    {
?>        
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>TMSC Traking Production Time System v.0.1</title>

                <?php 
                    require_once("include/library.php");
                ?>
            </head>
            
            <!--<body style='background-color:black;'>-->
            <body style='background-color:LightSteelBlue;'>
                <!-- Begin Body page -->
                <div class="container">
                    <br>
                    <!-- Begin Static navbar -->
                    <?php require_once("include/submenu_navbar.php"); ?>
                    <!-- End Static navbar -->

                    <!-- Begin content page-->
                    <!------------------>
                    <!-- Graph for IR -->
                    <!------------------>
                    <div class="row">                        
                        <!----------------------------------->
                        <!-- show status of remainin COOIS -->
                        <!----------------------------------->
                        <div class="col-lg-12">
                            <div class='table-responsive'>    
                                <table class='table table-bordered table-hover' id='myTable' style='width:100%;' align="center">
                                    <thead> 
                                        <tr class='info'>
                                            <th class='text-center'>No.</th>
                                            <th class='text-center'>Order</th>
                                            <th class='text-center'>Material Code</th>
                                            <th class='text-center'>Material Name</th>
                                            <th class='text-center'>Lot No.</th>
                                            <th class='text-center'>Bacth Size</th>                                            
                                            <?php
                                                if ($_GET['mode'] == 2)
                                                { 
                                                    echo "<th class='text-center'>Basic Start Date</th>";
                                                    echo "<th class='text-center'>Basic Start Time</th>";
                                                    //echo "<th class='text-center'>RX No.</th>";
                                                    echo "<th class='text-center'>Pd-Group</th>";
                                                    echo "<th class='text-center'>Mode</th>";
                                                    //echo "<th class='text-center'>Delete</th>";
                                                }
                                                else
                                                {
                                                    echo "<th class='text-center'>SBU</th>";
                                                    echo "<th class='text-center'>Pd-Group</th>";
                                                    echo "<th class='text-center'>PD-LT</th>";
                                                }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                /*-------------------*/
                                                /*--- Query COOIS ---*/
                                                /*-------------------*/
                                                include_once('include/db_Conn.php');
                                                //echo $_GET['basic_start_date'] . "<br>";

                                                $strSql = "SELECT * " ;
                                                $strSql .= "FROM tpdt_trn_coois T " ;
                                                $strSql .= "JOIN tpdt_mas_pd_data M ON M.pd_code = T.`Material Number` ";
                                                switch ($_GET['mode'])
                                                {
                                                    /*--- Show all coois data for specific date ---*/
                                                    case 1:
                                                        $strSql .= "WHERE `Basic start date` = '" . $_GET['basic_start_date'] . "' ";
                                                        break;
                                                    /*--- Show only remaining coois data for specific date ---*/
                                                    case 2:
                                                        $strSql .= "WHERE `Delete flag` = 'N' ";
                                                        $strSql .= "AND `Basic start date` = '" . $_GET['basic_start_date'] . "' ";
                                                        break;
                                                }

                                                $keyWord = ['IRS','IRW','UU']; // the word you wanna to find
                                                $nElement = 1;                                                
                                                foreach($keyWord as $key)
                                                {                                                    
                                                    if (strpos($user_sbu, $key) > 0)
                                                    {
                                                        if($nElement == 1)
                                                        {
                                                            $strSql .= "AND ((pd_sbu ='" . $key . "') " ;
                                                        }
                                                        else
                                                        {
                                                            $strSql .= "OR (pd_sbu ='" . $key . "') " ;
                                                        }
                                                        $nElement += 1;
                                                    }
                                                }

                                                $strSql .= ") ";
                                                //$strSql .= "ORDER BY `Order` ";
                                                $strSql .= "ORDER BY pd_sbu, pd_group, `Material description`, Batch ";
                                                //echo $strSql . "<br>";

                                                $statement = $conn->prepare($strSql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
                                                $statement->execute();
                                                //$result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                                $nRecCount = $statement->rowCount();

                                                if ($nRecCount > 0)
                                                {
                                                    $nRow = 0;
                                                    while ($ds = $statement->fetch(PDO::FETCH_NAMED))
                                                    {
                                        ?>
                                                        <tr>
                                                            <td class='text-right'><?php echo $nRow+=1; ?></td>
                                                            <td><?php echo $ds['Order']; ?></td>
                                                            <td><?php echo $ds['Material Number']; ?></td>
                                                            <td><?php echo $ds['Material description']; ?></td>
                                                            <td class='text-center'><?php echo $ds['Batch']; ?></td>
                                                            <td class='text-right'><?php echo number_format($ds['Order quantity'],2,'.',','); ?></td>

                                                            <?php
                                                                /*-- mode = 2 is for modify data --*/
                                                                if ($_GET['mode'] == 2)
                                                                {
                                                                    echo "<td class='text-center'>" . date("d/M/Y", strtotime($ds['Basic start date'])) . "</td>";
                                                                    echo "<td class='text-center'>" . date("H:i", strtotime($ds['Basic start time'])) . "</td>";
                                                                    //echo "<td class='text-center'>" . $ds['RX No'] . "</td>";
                                                                    echo "<td class='text-center'>" . $ds['pd_group'] . "</td>";
                                                                    echo "<td class='text-center'>";
                                                                    echo "<a href='#' class='edit_coois_data' order= " . $ds['Order'] . ">";
                                                                    echo "<span class='fa fa-pencil-square-o fa-lg'>" . "&nbsp&nbsp" . "</span>";
                                                                    echo "</a>";
                                                                    echo "<a href='#' class='delete_coois_data' order= " . $ds['Order'] . ">";
                                                                    echo "<span class='fa fa-trash-o fa-lg'></span>";
                                                                    echo "</a>";
                                                                    echo "</td>";
                                                                    /*
                                                                    if($nRow == 1)
                                                                    {
                                                                        echo "<td class='text-center'>";
                                                                        echo "<a href='#' class='deleteByDate_coois_data' bStartDate= " . $ds['Basic start date'] . ">";
                                                                        echo "<span class='fa fa-trash fa-lg'></span>";                                                                        
                                                                        echo "</a>";
                                                                        echo "</td>";
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "<td class='text-center'>";
                                                                        echo "</td>";
                                                                    }
                                                                    */
                                                                }
                                                                else
                                                                {
                                                                    echo "<td class='text-center'>" . $ds['pd_sbu'] . "</td>";
                                                                    echo "<td>" . $ds['pd_group'] . "</td>";
                                                                    echo "<td class='text-center'>" . $ds['pd_lead_time'] . "</td>";
                                                                }
                                                            ?>
                                                        </tr>
                                        <?php
                                                    }            
                                                }
                                                else
                                                {
                                        ?>
                                                    <tr>                
                                                        <td class='text-center'>No data</td>                                                        
                                                    </tr> 
                                        <?php            
                                                }        
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>           
                    <!-- End content page -->
                </div>  
                <!-- End Body page -->
                

                <!------------------------>
                <!-- Update COOIS Modal -->
                <!------------------------>
                <div class="modal fade" id="update_coois_modal" tabindex="-1" role="dialog">
                    <!--<div class="modal-dialog modal-lg" role="document">-->
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">Edit COOIS Data :</h4>
                            </div>
                            
                            <div class="modal-body" id="detail"> 
                                <form method='post' id='update-coois-form'>                                    
                                    <input type="hidden" id="pdVersion" name="pdVersion">
                                    <input type="hidden" id="mrpController" name="mrpController">

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label>Material Code</label>
                                            <input type="text" id="matCode" class='form-control' disabled>
                                            <input type="hidden" id="editmatCode" name="editmatCode">
                                        </div>
                                        
                                        <div class="col-lg-8">
                                            <label>Material Name</label>
                                            <input type="text" id="matName" class='form-control' disabled>
                                            <input type="hidden" id="editmatName" name="editmatName">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label>order</label>
                                            <input type="text" id="pdOrdNo" class='form-control' disabled>
                                            <input type="hidden" id="editpdOrdNo" name="editpdOrdNo">
                                        </div>

                                        <div class="col-lg-3">
                                            <label>Lot No.</label>
                                            <input type="text" id="lotNo" class='form-control' disabled>
                                            <input type="hidden" id="editlotNo" name="editlotNo">
                                        </div>

                                        <div class="col-lg-3">
                                            <label>RX-No.</label>
                                            <select id ='rxNo' name="rxNo" class="form-control" required>
                                                
                                                <?php
                                                    $keyWord = ['IRS','IRW','UU']; // the word you wanna to find
                                                    $nElement = 1;
                                                    $cStrValue = '';

                                                    foreach($keyWord as $key)
                                                    {
                                                        echo $nElement;
                                                        if (strpos($user_sbu, $key) > 0)
                                                        {
                                                            switch ($key)
                                                            {
                                                                case 'IRS':
                                                                    $cStrValue .= "<option value='DC-211'>DC-211</option>";
                                                                    $cStrValue .= "<option value='DC-311'>DC-311</option>";
                                                                    $cStrValue .= "<option value='DC-411'>DC-411</option>";
                                                                    $cStrValue .= "<option value='DC-441'>DC-441</option>";
                                                                    $cStrValue .= "<option value='DC-611'>DC-611</option>";
                                                                    $cStrValue .= "<option value='DC-622'>DC-622</option>";
                                                                    break;
                                                                case 'IRW':
                                                                    $cStrValue .= "<option value='DC-9011'>DC-9011</option>";
                                                                    $cStrValue .= "<option value='DC-9021'>DC-9021</option>";
                                                                    $cStrValue .= "<option value='DC-9031'>DC-9031</option>";
                                                                    $cStrValue .= "<option value='DC-9041'>DC-9041</option>";
                                                                    $cStrValue .= "<option value='DC-9051'>DC-9051</option>";
                                                                    $cStrValue .= "<option value='DC-9061'>DC-9061</option>";
                                                                    $cStrValue .= "<option value='DC-9071'>DC-9071</option>";
                                                                    $cStrValue .= "<option value='DC-9081'>DC-9081</option>";
                                                                    $cStrValue .= "<option value='DC-9091'>DC-9091</option>";
                                                                    $cStrValue .= "<option value='FA-9014'>FA-9014</option>";
                                                                    $cStrValue .= "<option value='FA-9024'>FA-9024</option>";
                                                                    $cStrValue .= "<option value='FA-9034'>FA-9034</option>";
                                                                    $cStrValue .= "<option value='FA-9044'>FA-9044</option>";
                                                                    $cStrValue .= "<option value='FA-9054'>FA-9054</option>";
                                                                    $cStrValue .= "<option value='FA-9064'>FA-9064</option>";
                                                                    $cStrValue .= "<option value='FA-9074'>FA-9074</option>";
                                                                    break;
                                                                case 'UU':
                                                                    $cStrValue .= "<option value='DC-101'>DC-101</option>";
                                                                    $cStrValue .= "<option value='DC-111'>DC-111</option>";
                                                                    $cStrValue .= "<option value='DC-121'>DC-121</option>";
                                                                    $cStrValue .= "<option value='DC-122'>DC-122</option>";
                                                                    $cStrValue .= "<option value='DC-124'>DC-124</option>";
                                                                    $cStrValue .= "<option value='DC-141'>DC-141</option>";
                                                                    $cStrValue .= "<option value='DC-151'>DC-151</option>";
                                                                    $cStrValue .= "<option value='DC-161'>DC-161</option>";
                                                                    $cStrValue .= "<option value='DC-181'>DC-181</option>";
                                                                    $cStrValue .= "<option value='DC-191'>DC-191</option>";
                                                                    $cStrValue .= "<option value='FA-191'>FA-191</option>";
                                                                    break;
                                                            }
                                                            $nElement += 1;
                                                        }
                                                    }
                                                    echo $cStrValue;
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2">
                                            <label>PD-LT</label>
                                            <input type="number" id="pdLeadTime" class='form-control' style="text-align: right;" disabled>
                                            <input type="hidden" id="editpdLeadTime" name="editpdLeadTime">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>

                                        <div class="col-lg-4">
                                            <label>Basic Start Date</label>
                                            <input type="date" id="basicStartDate" name ="basicStartDate" class='form-control' >
                                            <!--<input type="hidden" id="editbasicStartDate" name="editbasicStartDate">-->
                                        </div>

                                        <div class="col-lg-4">
                                            <label>Basic Start Time</label>
                                            <input type="time" id="basicStartTime" name ="basicStartTime" class='form-control'>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label>Order Quantity</label>
                                            <input type="number" id="orderQuantity" class='form-control' style="text-align: right;" disabled>
                                            <input type="hidden" id="editorderQuantity" name="editorderQuantity">
                                        </div>

                                        <div class="col-lg-4">
                                            <label>Basic Finish Date</label>
                                            <input type="date" id="basicFinishDate" class='form-control' disabled>
                                            <input type="hidden" id="editbasicFinishDate" name="editbasicFinishDate">
                                        </div>

                                        <div class="col-lg-4">
                                            <label>Basic Finsh Time</label>
                                            <input type="time" id="basicFinishTime" class='form-control' disabled>
                                            <input type="hidden" id="editbasicFinishTime" name="editbasicFinishTime">
                                        </div>
                                    </div>

                                    <br>
                                    <hr>

                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>WS Start Date</label>
                                            <input type="date" id="wsStartDate" name ="wsStartDate" class='form-control' disabled>
                                            <input type="hidden" id="editwsStartDate" name="editwsStartDate">
                                        </div>

                                        <div class="col-lg-4">
                                            <label>WS Start Time</label>
                                            <input type="time" id="wsStartTime" name ="wsStartTime" class='form-control' disabled>
                                            <input type="hidden" id="editwsStartTime" name="editwsStartTime">
                                        </div>                                        
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>
                                        <div class="col-lg-4">
                                            <label>WS Finish Date</label>
                                            <input type="date" id="wsFinishDate" name ="wsFinishDate" class='form-control' >
                                        </div>

                                        <div class="col-lg-4">
                                            <label>WS Finish Time</label>
                                            <input type="time" id="wsFinishTime" name ="wsFinishTime" class='form-control'>
                                        </div>                                        
                                    </div>

                                    <div class="row">
                                        <br>
                                        <div class="col-lg-10">
                                        </div>
                                        <div class="col-lg-2">
                                            <input type="submit" id='update' class='btn btn-success'>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btnClose" data-dismiss="modal">Close</button>
                            </div>
                            -->                            
                        </div>
                    </div>
                </div>
                
                
                <!---------------->
                <!-- javaScript -->
                <!---------------->
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('.btnClose').click(function(){
                            alert('.btn-clode');
                            $('#insert-form')[0].reset();
                        })

                        /* attach a submit handler to the form */
                        $("#update-coois-form").submit(function(event) {
                            //alert("You are running #update-coois-form");

                            /* stop form from submitting normally */
                            event.preventDefault();
                            
                            console.log( $( this ).serialize() );
                            
                            $.ajax({
                                url: "pCOOIS_Update.php",
                                method: "post",
                                data: $('#update-coois-form').serialize(),

                                beforeSend:function()
                                {
                                    $('#update').val('Updating...')
                                },
                                
                                success: function(data)
                                {
                                    if (data == '') 
                                    {
                                        $('#update-coois-form')[0].reset();
                                        $('#update_coois_modal').modal('hide');
                                        location.reload();
                                    }
                                    else
                                    {
                                        alert(data);
                                        location.reload();
                                    }
                                }
                            });
                            
                        });

                        $('.edit_coois_data').click(function(){
                            //alert('You are runnaing .edit_coois_data !');
                            //$('#edit_coois_modal').modal('show');

                            var pd_ord_no = $(this).attr("order");                            
                            $.ajax({
                                url: "pCOOIS_Fetch.php",
                                method: "post",
                                data: {pd_ord_no: pd_ord_no},
                                dataType: "json",
                                success: function(data)
                                {
                                    /*
                                    console.log(data['Material Number']);
                                    console.log(data['Material description']);
                                    console.log(data['Production Version']);
                                    console.log(data['Order quantity']);
                                    console.log(data['pd_lead_time']);
                                    console.log(data['Order']);
                                    */

                                    $('#pdVersion').val(data['Production Version']);
                                    $('#mrpController').val(data['MRP controller']);
                                    
                                    $('#matCode').val(data['Material Number']);
                                    $('#editmatCode').val(data['Material Number']);

                                    $('#matName').val(data['Material description']);
                                    $('#editmatName').val(data['Material description']);

                                    $('#pdOrdNo').val(data['Order']);
                                    $('#editpdOrdNo').val(data['Order']);

                                    $('#lotNo').val(data['Batch']);
                                    $('#editlotNo').val(data['Batch']);

                                    $('#rxNo').val(data['RX No']);             

                                    $('#pdLeadTime').val(data['pd_lead_time']);
                                    $('#editpdLeadTime').val(data['pd_lead_time']);

                                    $('#basicStartDate').val(data['Basic start date']);
                                    $('#basicStartTime').val(data['Basic start time']);

                                    $('#orderQuantity').val(data['Order quantity']);
                                    $('#editorderQuantity').val(data['Order quantity']);
                                    
                                    $('#update_coois_modal').modal('show');
                                }
                            });                            
                        });

                        $('.view_data').click(function(){
                            alert('You are running .view_data');
                            /*
                            var code = $(this).attr("emp_code");
                            $.ajax({
                                url: "pMA_User_view.php",
                                method: "post",
                                data: {id: code},
                                success: function(data){
                                    $('#detail').html(data);
                                    $('#view_modal').modal('show');
                                }
                            });
                            */
                        });

                        $('.delete_coois_data').click(function(){
                            alert('You are running .delete_coois_data');

                            var pd_ord_no = $(this).attr("order");            
                            var lConfirm = confirm("Do you want to delete this record?");
                            if (lConfirm)
                            {                
                                $.ajax({
                                    url: "pCOOIS_Delete.php",
                                    method: "post",
                                    data: {pd_ord_no: pd_ord_no},
                                    success: function(data){
                                        location.reload();
                                    }
                                });  
                            } 

                        });

                        $('.deleteByDate_coois_data').click(function(){
                            alert('You are running .deleteBuDate_coois_data');

                            var bStartDate = $(this).attr("bStartDate");
                            var lConfirm = confirm("Do you want to delete all record?");
                            if (lConfirm)
                            {                
                                $.ajax({
                                    url: "pCOOIS_DeleteByDate.php",
                                    method: "post",
                                    data: {bStartDate: bStartDate},
                                    success: function(data){
                                        location.reload();
                                    }
                                });  
                            } 

                        });
                        
                        /*------------------------------------------------------------ */
                        /*-- calculate basic finish start date and basic finish time --*/
                        /*------------------------------------------------------------ */
                        //$("#basicStartTime").keyup(function()
                        $("#basicStartTime").change(function()
                        {
                            //alert('You are running #basicStartTime');

                            var startTime= new Date($('#basicStartDate').val() +  'T' + $('#basicStartTime').val());
                            //alert(startTime);
                            var pdlt = parseFloat($('#pdLeadTime').val())
                            //alert(pdlt);
                            startTime.setHours( startTime.getHours() + pdlt );
                            //alert(startTime);
                            Y = startTime.getFullYear();
                            //alert(Y);
                            M = startTime.getMonth() + 1;
                            M = ("0" + M).slice(-2);
                            //alert(M);
                            D = startTime.getDate();
                            D = ("0" + D).slice(-2);
                            //alert(D);
                            var stopDate = Y + '-' + M + '-' + D;

                            Hr = startTime.getHours();
                            Hr = ("0" + Hr).slice(-2);
                            //alert(Hr);
                            Min = startTime.getMinutes();
                            Min = ("0" + Min).slice(-2);
                            //alert(Min);
                            var stopTime = Hr + ':' + Min;

                            $("#basicFinishDate").attr('value', stopDate);
                            $("#editbasicFinishDate").attr('value', stopDate);
                            $("#wsStartDate").attr('value', stopDate);
                            $("#editwsStartDate").attr('value', stopDate);
                            $("#wsFinishDate").attr('value', stopDate);

                            $("#basicFinishTime").attr('value', stopTime);
                            $("#editbasicFinishTime").attr('value', stopTime);
                            $("#wsStartTime").attr('value', stopTime);
                            $("#editwsStartTime").attr('value', stopTime);
                            $("#wsFinishTime").attr('value', stopTime);
                        });
                    });
                </script>

            </body>
        </html>
<?php
    }
?>