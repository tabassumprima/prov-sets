/*=========================================================================================
    File Name: form-file-uploader.js
    Description: dropzone
    --------------------------------------------------------------------------------------
    Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

Dropzone.autoDiscover = false;

$(function () {
  'use strict';

    var file_button_next = document.getElementById('file_button_next');
    var main_error_div =  document.getElementById('main_error_div');
    var file_error_message =  document.getElementById('file_error_message');
    var uploadZone = document.getElementById('dpz-single-file');
    var original_file = document.getElementById('original_file');
    var json_file = document.getElementById('json_file');
    var file_download_link = document.getElementById('file_download_link');
    var file_view = document.getElementById('file_view');
    var file_processed_success = document.getElementById('file_processed_success');
    var recommended_discount_rate = document.getElementById('recommended_discount_rate_hid');
    var rec_discount_rate_txt_value = document.getElementById('rec_discount_rate_txt_value');
    var agg_discount_rate = document.getElementById('agg_discount_rate');
    var long_salary_rate = document.getElementById('long_salary_rate');

    let myDropzone = new Dropzone('#dpz-single-file', {
        paramName: 'file', // The name that will be used to transfer the file
        maxFiles: 1,
        disablePreviews: true,
        autoProcessQueue: true,
        timeout: 0,

        success : function(file, response){

            if(response.success == false){
                file_error_message.innerText = response.errorMessage;
                main_error_div.style.display = 'block';
                file_button_next.disabled = true;
            }
            else if(response.success == true){
                transform();

                var recommendation = JSON.parse(response.data);
                file_processed_success.value = true;
                file_button_next.disabled = false;
                main_error_div.style.display = 'none';
                uploadZone.style.display = 'none';
                file_view.style.display = 'block';
                original_file.value = response.stored_file_name;
                json_file.value = response.json_file_name;
                file_download_link.href = '/download/' + response.stored_file_name;

            }
        },

        sending : function(file, xhr, formData){
            formData.append('identifier', document.getElementById('valuation_identifier').value);
            formData.append('fileName', file.name);
        }

    });

    myDropzone.on("complete", function(file) {
        myDropzone.removeFile(file);
    });


    var stepx = document.getElementById('current_step');
    var token = document.getElementById('token');
    var id = document.getElementById('valuation_identifier');

    var loading_modal_title = document.getElementById('loading_modal_title');
    var loading_modal_text = document.getElementById('loading_modal_text');

    function transform(){

        loading_modal_title.innerText = "Processing Employee(s) Data";
        loading_modal_text.innerText = "Validating the employee(s)’ data for any errors. Please wait.";

        $('#discount_rate_modal').modal(
            {
                show: true,
                backdrop: 'static',
                keyboard: false,
                dismissible:false,
            });

        $.ajax({
            url: "/valuation/transform/" + id.value,
            type: "POST",
            data: {
                id: id.value,
                current_step: stepx.value,
                _token: token.value,

            },
            timeout: 6000,
            dataType: "json",
            success: function (data) {
                console.log(data);
                if(data.is_processing === true){

                    $('#discount_rate_modal').modal(
                        {
                            show: true,
                            backdrop: 'static',
                            keyboard: false,
                            dismissible:false,
                        });

                    //get processing information
                    getProcessingStatus(id.value);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                file_error_message.innerText = 'Something went wrong while processing';
                main_error_div.style.display = 'block';
                file_button_next.disabled = true;
                $('#discount_rate_modal').modal({show: false});
                return false;
            }
        });
    }

    function getProcessingStatus(id){
        console.log("im here");

        $.ajax({
            url: "/valuation/getstatus/" + id,
            type: "GET",
            data: {
                _token: token.value,
            },
            timeout: 6000,
            dataType: "json",
            success: function(data) {
                console.log(data);

                // Schedule the next request when the current one's complete
                if(data.success === false && data.is_processing === true) {
                    setTimeout(function () {
                        getProcessingStatus(id);
                    }, 2000);
                }
                else{
                    setTimeout(function(){
                        $('#discount_rate_modal').modal('hide');
                    },3000);
                }

                if(data.success === true && data.is_processing === false) {
                    var response = JSON.parse(data.data);
                    console.log(response);
                    recommended_discount_rate.value = response.recommended_discount_rate;
                    rec_discount_rate_txt_value.innerText = response.recommended_discount_rate;
                    agg_discount_rate.value = response.recommended_discount_rate;
                    long_salary_rate.value = response.recommended_salary_increase_long ;

                    if(response.command == 'report') {
                       window.location.replace("/report/show/"+response.id);
                    }
                }

            }
        });
    }



});
