<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>" class="csrf">
  	<title></title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

  	<link rel="stylesheet" href="datatable/css/dataTables.bootstrap.css">
  	<link rel="stylesheet" href="datatable/css/dataTables.bootstrap4.min.css">
  	<link rel="stylesheet" href="sweetalert2/sweetalert2.min.css">
  
<body class="">

<div class="container">
  	<div class="row">
	  	<div class="col-md-8">
	  		<div class="card">
	  			<div class="card-header">
	  				<h2>Datatble</h2>
	  			</div>
	  			<div class="card-body">
	  				<table class="table table-hover" id="countries-table">
	                    <thead>
	                         <th>Sr. Nu</th>
	                         <th>Country name</th>
	                         <th>Capital city</th>
	                         <th>Actions</th>
	                    </thead>
	                    <tbody></tbody>
	                </table>
	  			</div>
	  		</div>
	  	</div>
	  	<div class="col-md-4">
	  		<div class="card">
	  			<div class="card-header">
	  				<h2>Vertical (basic) form</h2>
	  			</div>
	  			<div class="card-body">
				  	<form id="addCountryForm" action="<?= route_to('add.country'); ?>" method="post">
					    <?= csrf_field(); ?>
					    <div class="form-group">
					      	<label for="">Country Name</label>
					      	<input type="text" class="form-control" id="country_name" placeholder="Enter Country" name="country_name">
				    		<span class="text-danger country_name_error"></span>
				    	</div>
					    <div class="form-group">
					      	<label for="">Capital Name</label>
					      	<input type="password" class="form-control" id="capital_city" placeholder="Enter capital" name="capital_city">
					    	<span class="text-danger capital_city_error"></span>
					    </div>
					    <button type="submit" class="btn btn-block btn-primary">Submit</button>
				  	</form>
				</div>
			</div>

		</div>
	</div>
</div>


<div class="modal fade editCountry" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <form action="<?= route_to('update.country'); ?>" method="post" id="update-country-form">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="cid">
                           <div class="form-group">
                              <label for="">Country name</label>
                              <input type="text" class="form-control" name="country_name" placeholder="Enter country name">
                              <span class="text-danger error-text country_name_error"></span>
                           </div>
                           <div class="form-group">
                               <label for="">Capital city</label>
                               <input type="text" class="form-control" name="capital_city" placeholder="Enter capital city"> 
                               <span class="text-danger error-text capital_city_error"></span>
                           </div>
                           <div class="form-group">
                              <button type="submit" class="btn btn-block btn-success">Save Changes</button>
                           </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="datatable/js/jquery.dataTables.min.js"></script>
<script src="datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script type="text/javascript">
	var csrfName = $('meta.csrf').attr('name'); //CSRF TOKEN NAME
 var csrfHash = $('meta.csrf').attr('content'); //CSRF HASH

	$('#addCountryForm').submit(function(e){
        e.preventDefault();
        var form = this;
        $.ajax({
           	url:$(form).attr('action'),
           	method:$(form).attr('method'),
           	data:new FormData(form),
           	processData:false,
           	dataType:'json',
           	contentType:false,
           	beforeSend:function(){
              	$(form).find('span.error-text').text('');
           	},
           	success:function(data){
                if($.isEmptyObject(data.error)){
                    if(data.code == 1){
                    	alert(data.msg);
                        $(form)[0].reset();
                        $('#countries-table').DataTable().ajax.reload(null, false);
                    }else{
                        alert(data.msg);
                    }
                }else{
                    $.each(data.error, function(prefix, val){
                        $(form).find('span.'+prefix+'_error').text(val);
                    });
                }
           }
        });
   });

	$('#countries-table').DataTable({
       "processing":true,
       "serverSide":true, 
       "ajax":"<?= route_to('get.all.countries'); ?>",
       "dom":"lBfrtip",
       stateSave:true,
       info:true,
       
       "iDisplayLength":5,
       "pageLength":5,
       "aLengthMenu":[[5,10,25,50,-1],[5,10,25,50,"All"]],
       "fnCreatedRow": function(row, data, index){
           $('td',row).eq(0).html(index+1);
       }
   });

	$(document).on('click','#updateCountryBtn', function(){
       var country_id = $(this).data('id');
        
        $.post("<?= route_to('get.country.info') ?>",{country_id:country_id, [csrfName]:csrfHash}, function(data){
            //   alert(data.results.country_name);

            $('.editCountry').find('form').find('input[name="cid"]').val(data.results.id);
            $('.editCountry').find('form').find('input[name="country_name"]').val(data.results.country_name);
            $('.editCountry').find('form').find('input[name="capital_city"]').val(data.results.capital_city);
            $('.editCountry').find('form').find('span.error-text').text('');
            $('.editCountry').modal('show');
        },'json');

    
   });

   $('#update-country-form').submit(function(e){
       e.preventDefault();
       var form = this;

       $.ajax({
           url: $(form).attr('action'),
           method:$(form).attr('method'),
           data: new FormData(form),
           processData: false,
           dataType:'json',
           contentType:false,
           beforeSend:function(){
               $(form).find('span.error-text').text('');
           },
           success:function(data){

               if($.isEmptyObject(data.error)){

                   if(data.code == 1){
                    $('#countries-table').DataTable().ajax.reload(null, false);
                     $('.editCountry').modal('hide');
                   }else{
                       alert(data.msg);
                   }

               }else{
                   $.each(data.error, function(prefix, val){
                       $(form).find('span.'+prefix+'_error').text(val);
                   });
               }
           }
       });
   });


   $(document).on('click', '#deleteCountryBtn', function(){
       var country_id = $(this).data('id');
       var url = "<?= route_to('delete.country'); ?>";

       swal.fire({

           title:'Are you sure?',
           html:'You want to delete this country',
           showCloseButton:true,
           showCancelButton:true,
           cancelButtonText:'Cancel',
           confirmButtonText:'Yes, delete',
           cancelButtonColor:'#d33',
           confirmButtonColor:'#556eeb',
           width:300,
           allowOutsideClick:false

       }).then(function(result){
            if(result.value){

                $.post(url,{[csrfName]:csrfHash, country_id:country_id}, function(data){
                     if(data.code == 1){
                        $('#countries-table').DataTable().ajax.reload(null, false);
                     }else{
                         alert(data.msg);
                     }
                },'json');
            }
       });
   });


</script>
</body>
</html>

