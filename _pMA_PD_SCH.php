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
        if($user_user_type == "A")
        {
?>        
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>TMSC Query Supplier Information System v.0.1</title>

                <?php require_once("include/library.php"); ?>
                <?php require_once("pMA_PDLT_Script.php"); ?>
            </head>
            
            <!--<body style='background-color:black;'>-->
            <body style='background-color:LightSteelBlue;'>
                <!-- Begin Body page -->
                <div class="container">
                    <br>
                    <!-- Begin Static navbar -->
                    <?php require_once("include/menu_navbar.php"); ?>
                    <!-- End Static navbar -->

                    <!-- Begin content page-->
                    <!-- ----------------------------------- -->
                    <div class="row">

                        <div class="col-lg-6 col-lg-offset-3">
                            <div class="panel panel-primary" id="panel-header">
                                <div class="panel-heading">
                                    Maintaining Production Schedule
                                </div>

                                <div class="panel-body">
                                    
                                    <!--<form method="post" action="">-->
                                    <!--<form method="post" action="pMA_PD_SCH_View.php" target='_blank'>-->
                                    <form method="post" id='sel-pd-ord-no-form'>
                                        </iframe>                                        
                                        <div class="form-group">
                                            <label for="title">Select Production Order No.:</label>
                                            <select name="invoice_no" class="form-control" style="width:500px" required>
                                                <option value="">--- Select Production Orer No. ---</option>

                                                <?php                        
                                                    require_once('include/db_Conn.php');

                                                    $strSql = "SELECT `Order` ";
                                                    $strSql .= "FROM tpdt_trn_pd_sch ";
                                                    $strSql .= "WHERE `Pd Status` = 'O' ";
                                                    $strSql .= "ORDER BY `Order` DESC ";
                                                    echo $strSql . "<br>";

                                                    $statement = $conn->prepare( $strSql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));  
                                                    $statement->execute();  
                                                    $nRecCount = $statement->rowCount();
                                                    //echo $nRecCount . " records <br>";
                                                        
                                                    if ($nRecCount >0)
                                                    {
                                                        while($ds = $statement->fetch(PDO::FETCH_NAMED))
                                                        {
                                                            echo "<option value='" . $ds['Order']. "'>" 
                                                            . $ds['Order']
                                                            . "</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <button type="submit" style="float: right; margin:2px;" class="btn btn-success">
                                                    <span class="fa fa-edit fa-lg">&nbsp&nbsp&nbspEdit</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <br>


                                    <form method='post' id='insert-pd-sch-form'>
                                        <input type="hidden" id="editpdOrdNo" name="editpdOrdNo">
                                        <input type="hidden" id="editpdVersion" name="editpdVersion">
                                        <input type="hidden" id="editbatchSize" name="editbatchSize">
                                        <input type="hidden" id="editmrpController" name="editmrpController">

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label>Material Code</label>
                                                <input type="text" id="matCode" name ="matCode" class='form-control' disabled>
                                                <input type="hidden" id="editmatCode" name="editmatCode">
                                            </div>
                                            
                                            <div class="col-lg-8">
                                                <label>Material Name</label>
                                                <input type="text" id="matName" name ="matName" class='form-control' disabled>
                                                <input type="hidden" id="editmatName" name="editmatName">
                                            </div>

                                            <div class="col-lg-4">
                                            </div>

                                            <div class="col-lg-3">
                                                <label>Lot No.</label>
                                                <input type="text" id="lotNo" name ="lotNo" class='form-control' disabled>
                                                <input type="hidden" id="editlotNo" name="editlotNo">
                                            </div>
                                            
                                            <div class="col-lg-3">
                                                <label>RX-No.</label>                                    
                                                <select id ='rxNo' name="rxNo" class="form-control" required>
                                                    <option value="DC-211">DC-211</option>
                                                    <option value="DC-311">DC-311</option>
                                                    <option value="DC-411">DC-411</option>
                                                    <option value="DC-441">DC-441</option>
                                                    <option value="DC-611">DC-611</option>
                                                    <option value="DC-622">DC-622</option>
                                                    <option value="-">-</option>
                                                    <option value="9011">9011</option>
                                                    <option value="9031">9031</option>
                                                    <option value="9042">9042</option>
                                                    <option value="9051">9051</option>
                                                    <option value="9061">9061</option>
                                                    <option value="9071">9071</option>
                                                    <option value="9072">9072</option>
                                                    <option value="9081">9081</option>
                                                    <option value="9091">9091</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-2">
                                                <label>PD-LT</label>
                                                <input type="number" id="pdLeadTime" name ="pdLeadTime" class='form-control' disabled>
                                                <input type="hidden" id="editpdLeadTime" name="editpdLeadTime">
                                            </div>

                                            <div class="col-lg-4">
                                            </div>

                                            <div class="col-lg-4">
                                                <label>Basic Start Date</label>
                                                <input type="date" id="basicStartDate" name ="basicStartDate" class='form-control' disabled>
                                                <input type="hidden" id="editbasicStartDate" name="editbasicStartDate">
                                            </div>

                                            <div class="col-lg-4">
                                                <label>Basic Start Time</label>
                                                <input type="time" id="basicStartTime" name ="basicStartTime" class='form-control' required>
                                            </div>
                                            
                                            <div class="col-lg-4">
                                            </div>

                                            <div class="col-lg-4">
                                                <label>Basic Finish Date</label>
                                                <input type="date" id="basicFinishDate" name ="basicFinishDate" class='form-control' disabled>
                                                <input type="hidden" id="editbasicFinishDate" name="editbasicFinishDate">
                                            </div>

                                            <div class="col-lg-4">
                                                <label>Basic Finsh Time</label>
                                                <input type="time" id="basicFinishTime" name ="basicFinishTime" class='form-control' disabled>
                                                <input type="hidden" id="editbasicFinishTime" name="editbasicFinishTime">
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <br>
                                            <div class="col-lg-10">
                                            </div>
                                            <div class="col-lg-2">
                                                <input type="submit" id='insert' class='btn btn-success'>
                                            </div>
                                        </div>
                                    </form>

                                </div>                                
                            </div>
                        </div>


                    </div>
                    <!-- ----------------------------------- -->
                    <!-- End content page -->
                </div>  
                <!-- End Body page -->

                <!-- Logout Modal-->
                <?php require_once("include/modal_logout.php"); ?>

                <!-- Change Password Modal-->
                <?php require_once("include/modal_chgpassword.php"); ?>
                
                
                


                <script>
                    $(document).ready(function(){
                        $("#edit-pd-sch-form").submit(function(event) 
                        {
                            alert('You submit edit-pd-sch-form !');
                            $('#chgpasswordModal').modal('show');
                            

                            /*
                            var pd_ord_no = $(this).attr("order");                            
                            $.ajax({
                                url: "pCOOIS_Fetch.php",
                                method: "post",
                                data: {pd_ord_no: pd_ord_no},
                                dataType: "json",
                                success: function(data)
                                {
                                    console.log(data['Material Number']);
                                    console.log(data['Material description']);
                                    console.log(data['Production Version']);
                                    console.log(data['Order quantity']);
                                    console.log(data['pd_lead_time']);
                                    
                                    $('#editpdOrdNo').val(data['Order']);
                                    $('#editpdVersion').val(data['Production Version']);
                                    $('#editbatchSize').val(data['Order quantity']);
                                    $('#editmrpController').val(data['MRP controller']);

                                    $('#editmatCode').val(data['Material Number']);
                                    $('#editmatName').val(data['Material description']);
                                    $('#editlotNo').val(data['Batch']);
                                    $('#editbasicStartDate').val(data['Basic start date']);
                                    $('#editpdLeadTime').val(data['pd_lead_time']);

                                    $('#matCode').val(data['Material Number']);
                                    $('#matName').val(data['Material description']);
                                    $('#lotNo').val(data['Batch']);
                                    $('#basicStartDate').val(data['Basic start date']);
                                    $('#basicStartTime').val(data['Basic start time']);
                                    $('#pdLeadTime').val(data['pd_lead_time']);

                                    $('#rxNo').val(data['RX No']);

                                    $('#insert_coois_modal').modal('show');
                                }  
                            */                          
                        });

                        /* attach a submit handler to the form */
                        $("#insert-coois-form").submit(function(event) {
                            //alert("You are submitting insert form");

                            /* stop form from submitting normally */
                            /*
                            event.preventDefault();
                            
                            console.log( $( this ).serialize() );
                            
                            $.ajax({
                                url: "pCOOIS_Insert.php",
                                method: "post",
                                data: $('#insert-coois-form').serialize(),

                                beforeSend:function()
                                {
                                    $('#insert').val('Insert...')
                                },

                                success: function(data)
                                {
                                    if (data == '') 
                                    {
                                        $('#insert-coois-form')[0].reset();
                                        $('#insert_coois_modal').modal('hide');
                                        location.reload();
                                    }
                                    else
                                    {
                                        alert(data);
                                        location.reload();
                                    }
                                }
                            });
                            */
                        });

                        $('.edit_coois_data').click(function(){
                            //alert('You are clicking Edit COOIS Data !');
                            //$('#insert_coois_modal').modal('show');

                            /*
                            var pd_ord_no = $(this).attr("order");                            
                            $.ajax({
                                url: "pCOOIS_Fetch.php",
                                method: "post",
                                data: {pd_ord_no: pd_ord_no},
                                dataType: "json",
                                success: function(data)
                                {
                                    console.log(data['Material Number']);
                                    console.log(data['Material description']);
                                    console.log(data['Production Version']);
                                    console.log(data['Order quantity']);
                                    console.log(data['pd_lead_time']);
                                    
                                    $('#editpdOrdNo').val(data['Order']);
                                    $('#editpdVersion').val(data['Production Version']);
                                    $('#editbatchSize').val(data['Order quantity']);
                                    $('#editmrpController').val(data['MRP controller']);

                                    $('#editmatCode').val(data['Material Number']);
                                    $('#editmatName').val(data['Material description']);
                                    $('#editlotNo').val(data['Batch']);
                                    $('#editbasicStartDate').val(data['Basic start date']);
                                    $('#editpdLeadTime').val(data['pd_lead_time']);

                                    $('#matCode').val(data['Material Number']);
                                    $('#matName').val(data['Material description']);
                                    $('#lotNo').val(data['Batch']);
                                    $('#basicStartDate').val(data['Basic start date']);
                                    $('#basicStartTime').val(data['Basic start time']);
                                    $('#pdLeadTime').val(data['pd_lead_time']);

                                    $('#rxNo').val(data['RX No']);

                                    $('#insert_coois_modal').modal('show');
                                }
                            });
                            */                          
                        });

                        $('.view_data').click(function(){
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

                        $('.delete_data').click(function(){
                            /*
                            var code = $(this).attr("emp_code");            
                            var lConfirm = confirm("Do you want to delete this record?");
                            if (lConfirm)
                            {                
                                $.ajax({
                                    url: "pMA_User_delete.php",
                                    method: "post",
                                    data: {id: code},
                                    success: function(data){
                                        location.reload();
                                    }
                                });  
                            }
                            */
                        });
                        
                        /*------------------------------------------------------------ */
                        /*-- calculate basic finish start date and basic finish time --*/
                        /*------------------------------------------------------------ */
                        $("#basicStartTime").keyup(function()
                        {
                            /*
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
                            $("#basicFinishTime").attr('value', stopTime);
                            $("#editbasicFinishTime").attr('value', stopTime);
                            */
                        });
                    });

                </script>
            </body>
        </html>
<?php
        }
        else
        {
            echo "<script> alert('You are not authorization for this menu ... Please contact your administrator!'); window.location.href='pMain.php'; </script>";
        }
    }
?>