<?php 
// echo "<pre>";
// print_r($getAllEmployeewiseD);
// exit();
?>
<style type="text/css">
    div.dataTables_wrapper  div.dataTables_filter {
  
  float: left;
  text-align: left;
}
</style>
<script>
 $(document).ready(function() {

    $('#example').DataTable( {
        "paging": false,
        "pagingType": "full_numbers",
        "pageLength":"All",          //   "ordering": false,
         "autoWidth": false,
         "initComplete": function () {
            this.api().columns([3, 4,5,6]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo('#example_filter')
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
          

            // Total filtered rows on the selected column (code part added)
            var sumCol4Filtered = display.map(el => data[el][7]).reduce((a, b) => intVal(a) + intVal(b), 0 );
          
            // Update footer
            $( api.column( 7 ).footer() ).html(
                '$'+pageTotal +' ( total)'
            );
        }
    } );
} );
</script>
<div class="main-content">
    <div class="main-content-inner">

        <div class="page-content">
            <div class="row">
                <div class="table-header">
                    Employee List
                </div>

                <div>
                    <table id="example" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th colspan="2" width="30%">Personal Details</th>
                                <th colspan="2" width="5%">Designation</th>
                                <th width="10%">Employee Type</th>
                                <th width="10%">Account Details</th>
                                <th width="10%">Joining Date</th>
                                <th width="5%">Current Salary</th>
                                <th width="20%">Photos & Signature</th>
                                <th width="5%">HR Comments</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0;
                            foreach ($getAllEmployeewiseD as $key => $value): 
                                $total += $value->salary; ?>

                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td style="border-left: none; ">
                                      <strong><label>Name of Employee: &nbsp; </label></strong> <?php echo $value->name; ?>   </br>
                                        <label>Father Name: &nbsp;  </label><?php echo $value->fathersName; ?> </br>
                                        <label>Mother Name: &nbsp; </label><?php echo $value->mothersName; ?> </br>
                                        <label>Present Address: &nbsp; </label><?php echo $value->presentAddress; ?> </br>
                                        <label>Spouse Name: &nbsp; </label><?php echo $value->spouseName; ?> </br>
                                        <label>Spouse Number: &nbsp; </label><?php echo $value->spouseNumber; ?> </br>
                                        <label>Emergency Contact: &nbsp; </label><?php echo $value->emargencyContact; ?> </br>
                                        <label>Date of Birth: &nbsp; </label><?php echo $value->dateOfBirth; ?> </br>
                                        <label>NID: </label><?php echo $value->nationalId; ?> </br>

                                    </td>
                                    <td><label>Email: &nbsp; </label><?php echo $value->emailAddress; ?> </br>
                                        <label>Personal Mobile: &nbsp; </label><?php echo $value->personalMobile; ?> </br>
                                        <label>Res: &nbsp; </label><?php echo $value->res; ?> </br>
                                        <label>Work Mobile: &nbsp; </label><?php echo $value->officeMobile; ?> </br>
                                        <label>Gender: &nbsp; </label><?php echo $value->gender; ?> </br>
                                        <label>Religion: &nbsp; </label><?php echo $value->religion; ?> </br>
                                        <label>Marital Status: &nbsp; </label><?php echo $value->maritalStatus; ?> </br>
                                        <label>Blood Group: &nbsp; </label><?php echo $value->bloodGroup; ?> </br>
                                        <label>Latest Education: &nbsp; </label><?php echo $value->education; ?> </br>
                                </td>
                                    <td>
                                      <label>Designation:</label> <?php echo $value->DesignationName; ?> </br>  
                                       <label>Department:</label><?php echo $value->departmentName; ?> </br> 
                                    </td>

                                    <td>
                                        <label>Identification: </label></br>
                                        <label>Employee ID: &nbsp; </label><?php echo $value->employeeId; ?> </br>
                                        <label>Employee Type: &nbsp; </label><?php echo $value->employeeType; ?> </br>
                                    </td>
                                    <td>
                                        <label>Account Details: &nbsp; </label></br>
                                        <label>Account Name: &nbsp; </label> </br>
                                         <label>Bank Name: &nbsp; </label></br>
                                        <label> Account No: &nbsp; </label><?php echo $value->AccountNo; ?> </br>
                                    </td>
                                    <td>
                                         <label>Performance: &nbsp; </label></br>
                                        <label>Joining Date: &nbsp; </label><?php echo $value->joiningDate; ?> </br>
                                        <label>Last Assessement: &nbsp; </label>
                                    </td>
                                    <td><?php echo $value->salary; ?></td>

                                    <td><img src="<?php echo base_url('uploads/employee/' . $value->profile); ?>" class="thumbnail" height="100px" width=""> </br>
                                        <label>Signature</label>
                                    </td>
                                    <td><?php echo $value->others; ?></td>
                                    
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
            <tr>
                <th colspan="7" style="text-align:right">Total: </th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>

<script>
$(document).ready(function() {
 // DataTable initialisation
 $('#example31').DataTable(
     {
         "paging": false,
           "pagingType": "full_numbers",
        "pageLength":"All",          //   "ordering": false,
         "autoWidth": false,
         "retrieve": true,
            "searching": true,
            "bFiltered": true,
            
            "ordering": false,
            "filter": true,
            "info":     false,
         "initComplete": function () {
            this.api().columns([2, 3, 4]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo('#example_filter')
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
          "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 8;
                while(j < nb_cols){
                    var pageTotal = api
                .column( j, { page: 'current'} )
                .data()
                .reduce( function (a, b) {

                    return parseFloat(a,2) + parseFloat(b,2);
                }, 0 );
          // Update footer
          $( api.column( j ).footer() ).html( parseFloat(pageTotal));
                    j++;
                }
            }     }
 );
});
</script>

<script type="text/javascript">
 $(document).ready(function () {
    $("#examplew input").on( 'keyup', function () {
    tableInstance.search( this.value ).draw(); // try this easy code and check if works at first
} );
 
     


    var $tableEle = $('#examplew');
    var groupCol = 6;
    


    //Initiate DataTable
    $tableList = $tableEle.DataTable({
            "retrieve": true,
            "searching": true,
            "bFiltered": true,
            "paging":   false,
            "ordering": false,
            "filter": true,
            "info":     false,
            "orderFixed": [
            [groupCol, 'asc']
        ],
            "columnDefs": [{
            "orderable": false,
            "targets": 0
        }, {
            "visible": false,
            "targets": groupCol
        }],
         "initComplete": function () {
            this.api().columns([2, 3, 4,5]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo('#example_filter')
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
            "drawCallback": function (settings) {
            var that = this;
            if (settings.bSorted || settings.bFiltered) {
                this.$('td:first-child', {
                    "filter": "applied"
                }).each(function (i) {
                    that.fnUpdate(i + 1, this.parentNode, 0, false, false);
                });
            }

            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var rowsData = api.rows({
                page: 'current'
            }).data();

            var last = null;
            var subTotal = new Array();
            var grandTotal = new Array();
            var groupID = -1;

           

                     
        },

        "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 5;
                while(j < nb_cols){
                    var pageTotal = api
                .column( j, { page: 'current'} )
                .data()
                .reduce( function (a, b) {

                    return parseFloat(a,2) + parseFloat(b,2);
                }, 0 );
          // Update footer
          $( api.column( j ).footer() ).html( parseFloat(pageTotal));
                    j++;
                }
            }
    });
});
</script>

<script type="text/javascript">
   $(document).ready(function() {
    $('#example2').DataTable( {
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 8 ).footer() ).html(
                '$'+pageTotal +' ( $'+ total +' total)'
            );
        }
    } );
} );
</script>