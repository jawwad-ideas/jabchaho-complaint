  <!-- Modal -->
  <div class="modal" tabindex="-1" role="dialog" id="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="alert alert-danger" style="display:none"></div>
      <div class="modal-header">
      	
        <h5 class="modal-title">Change Password</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
            <p>
                This password is used to log into your account area as well as acts as a key in case you forget your master password 
                of your security applications.
            </p>  

            <div class="alert alert-danger"  id="error"   style="display:none"></div>
            <div class="alert alert-success" id="success" style="display:none"></div>
            <div class="row">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="current_password">Current Password</label>
                    <div class="col-sm-4">
                        <input type="password" autocomplete="off" class="form-control" id="current_password"  name="current_password" maxlength="20" value="{{old('current_password')}}">
                        <div id="currentPasswordDiv" class="text-danger" style="display:none" ></div>
                      </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="new_password">New Password</label>
                    <div class="col-sm-4">
                        <input type="password" autocomplete="off" class="form-control" id="new_password"  name="new_password" maxlength="20" value="{{old('new_password')}}">
                        <div id="newPasswordDiv" class="text-danger" style="display:none" ></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="new_confirm_password">Confirm Password</label>
                    <div class="col-sm-4">
                        <input type="password" autocomplete="off" class="form-control" id="new_confirm_password"  name="new_confirm_password" maxlength="20" value="{{old('new_confirm_password')}}">
                        <div id="newConfirmPasswordDiv" class="text-danger" style="display:none" ></div>
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button  class="btn btn-success" id="ajaxSubmit">Change Password</button>
        </div>
    </div>
  </div>
</div>

<style>
  .modal-dialog {
    max-width: 800px !important;
}
</style>

  <script>
         jQuery(document).ready(function(){
            jQuery('#ajaxSubmit').click(function(e){
               e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      //'X-CSRF-TOKEN': $("#_token").val()
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
               jQuery.ajax({
                  url: "{{ route('change.password.perform') }}",
                  method: 'post',
                  data: {
                    current_password: jQuery('#current_password').val(),
                    new_password: jQuery('#new_password').val(),
                    new_confirm_password: jQuery('#new_confirm_password').val()
                  },
                  success: function(result){
                      
                    $("#success").hide();
                      $('#error').hide();
                      $("#success").html('');
                      $('#error').html('');

                      $('#currentPasswordDiv').html('');
                      $('#newPasswordDiv').html('');
                      $('#newConfirmPasswordDiv').html('');

                    if(result.errors)
                  	{
                      var sessionErrorMessage = false;
                      //current_password
                      if(result.errors.current_password)
                      {
                        sessionErrorMessage = true;
                        $('#currentPasswordDiv').show();
                        $('#currentPasswordDiv').append(result.errors.current_password);
                      }

                      //new_password
                      if(result.errors.new_password)
                      {
                        sessionErrorMessage = true;
                        $('#newPasswordDiv').show();
                        $('#newPasswordDiv').append(result.errors.new_password);
                      }

                      //new_confirm_password
                      if(result.errors.new_confirm_password)
                      {
                        sessionErrorMessage = true;
                        $('#newConfirmPasswordDiv').show();
                        $('#newConfirmPasswordDiv').append(result.errors.new_confirm_password);
                      }

                      //sesion error message
                      if(!sessionErrorMessage)
                      {
                        $('#error').show();
                        $("#error").html(result.errors);
                      }

                  	}
                    else if(result.message){
                      $('#error').show();
                      $("#error").html(result.message);
                    }
                  	else
                  	{
                      $('#success').show();
                      $("#success").html(result.success);
                      resetFields();
                  	}
                  },
                  error: function (data, textStatus, errorThrown) {
                      //console.log(JSON.stringify(data));
                      //console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                      $('#error').show();
                  		$('#error').html('Unable to process request. Please refresh the page and try again!!');
                  }
                
                });
               });
            });


            function resetFields(){
              $('#current_password').val('');
              $('#new_password').val('');
              $('#new_confirm_password').val('');
            }
      </script>