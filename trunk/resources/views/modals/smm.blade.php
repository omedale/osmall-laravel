
<style type="text/css">
    @media only screen and (max-width: 299px) {
        .modal_responsive{
            width: 100% !important;
        }
        #custom_message_smm{
            width: 100% !important;
        }
        .btn-bl{
            max-width: 80% !important;
        }

    }
    @media only screen and (min-width: 300px) and (max-width: 350px) {
        .modal_responsive{
            width: 100% !important;
        }
        #custom_message_smm{
            width: 130% !important;
        }
        .btn-bl{
            max-width: 80% !important;
            }

    }
     @media only screen and (min-width: 351px) and (max-width: 450px) {
        .modal_responsive{
            width: 100% !important;
        }
        #custom_message_smm{
            width: 180% !important;
        }
        .btn-bl{
            max-width: 80% !important;
            }

    }
     @media only screen and (min-width: 451px) and (max-width: 600px) {
        .modal_responsive{
            width: 100% !important;
        }
        #custom_message_smm{
            width: 200% !important;
        }
        .btn-bl{
            max-width: 80% !important;
            }

    }
    #custom_message_smm{
        width: 540px;
    }
    .btn-social{
        max-width: 100% !important; 
    }
</style>
<div id="socialModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal_responsive">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">SMM</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12" style="margin:5px;">
                <label>Please enter a custom message.
                <textarea class="form-control" id="custom_message_smm" ></textarea>
                </label><br>
                 <a class="btn  btn-social btn-facebook product-selected blast" id="blast">
                <i class="fa fa-facebook"></i> Share on Facebook
              </a>
            </div>
        </div>

        
       
      </div>
      <input type="hidden" id="smm_product_id" value="">
      <input type="hidden" id="smm_user_id" value="">
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div class="modal fade" id="smmArmyNotif" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 40%">
     <div class="modal-content" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">SMM Army Summary</h4>
         </div>     
        <div class="modal-body">
            <div id="myBody2">
                <table class="table ">
                    <tr>
                        <th>Sucessful</th>
                        <td>&nbsp;</td>
                        <td id="success_try"></td>
                        <td>&nbsp;</td>
                        <td id="success_pr"></td>
                    </tr>
                    <tr>
                        <th>Failed</th>
                        <td>&nbsp;</td>
                        <td id="failed_try"></td>
                        <td>&nbsp;</td>
                        <td id="failed_pr"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

     </div>
    </div>
</div>


{{-- AJAC CALL FOR SMM SAVE --}}
 <script type="text/javascript">
 $(document).ready(function(){
    $('.blast-none').click(function(){
        $('#socialModal').modal('show');
        $('#smm_product_id').val($(this).attr('data-pid'));
        $('#smm_user_id').val($(this).attr('data-uid'));
    });
    $('.blast').click(function(){
        var product_id=$('#smm_product_id').val();
        var user_id=$('#smm_user_id').val();

        $('#socialModal').modal('hide');
        var custom_message=$('#custom_message_smm').val();
        // alert(product_id);
        $('#bspin').removeClass('hidden');
        $.ajax({
            url:JS_BASE_URL+'/check/login',
            type:'GET',
            success:function (response) {
                if (response.status=='failure') {
                    toastr.info(response.long_message)
                }
                if (response.status=='success') {
                $.ajax({
                url: JS_BASE_URL + '/smedia/marketer',
                type: 'GET',
                data: {product_id: product_id,custom_message:custom_message,user_id:user_id},
                success:function(response){
                    if (response==-1) {
                     var newresponse= "You have not registered for SMM services. <br>Would you like to register now? <br><button type='button' id='smmyes' class='btn btn-success'>Yes</button> <button id='smmno' class='btn btn-default'>No</button>";
                            toastr.warning(newresponse,{timeout:3000});
                                $('#smmno').click(function(){
                                    toastr.clear();
                                });
                                $('#smmyes').click(function(){
                                    toastr.clear();
                                    // alert("Yeyy you clocled");
                                    newwindow = window.open("{{URL::route('fbtoken')}}", 'Link Token', 'height=400,width=auto');
                                     toastr.clear();
                                    if (window.focus) {
                                        newwindow.focus()
                                    }
                                    $('#bspin').addClass('hidden');
                                    return false;
                                    });
                                $('#bspin').addClass('hidden');

                    } else if(response==-2){
						var newresponse= "Warning: You have to wait to share your next product.";
						toastr.warning(newresponse);
					}
                    else{
                        // $('#bspin').addClass('hidden');
                        // toastr.info(response);
                        if (response.mode=="army") {
                            success=response.count_success;
                            failed=response.count_failed;
                            total=success+failed;
                            success_pr=(success/total)*100 +"%";
                            failed_pr=(failed/total)*100 +"%";
                            $('#success_try').text(response.count_success);
                            $('#failed_try').text(response.count_failed);
                            $('#success_pr').text(success_pr);
                            $('#failed_pr').text(failed_pr);
                            $('#smmArmyNotif').modal('show');
                            
                        }else{
                            toastr.info(response.long_message);
                        }
                        $('#bspin').addClass('hidden');

                    };

                },
                error:function(r){
                    $('#bspin').addClass('hidden');
                }
        });
                }
            }
        });

    });
 });
</script>