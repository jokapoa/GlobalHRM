<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script>
$(document).ready(function(){
	$('#dlg_add_candidate_interviews').on('show.bs.modal', function (e) {
	    $title = $(e.relatedTarget).attr('data-title');
	    $url   = $(e.relatedTarget).attr('data-applied_job_url');
	    $redirect_url = $(e.relatedTarget).attr('data-redirect');	    
		$('input[name="redirect_url"]').val($redirect_url);			
	    $(this).find('.modal-title').text($title);	    

	    $.ajax({
            type: "GET",
            url : $url,
            success: function (result) {
                var retData = JSON.parse(result.data);
                var success = result.success;
            	var optAvailableJobs = document.getElementById("applied_job_id");
            	/*
            	* Clear 
            	*/
            	optAvailableJobs.innerHTML ='';               
                if(success == true){
                	for (i = 0; i < retData.length; i++) { 
                		var option = document.createElement("option");
                		option.text  = '[ JD.'+ ( '00000000' + retData[i].id ).slice( -8 ) + ' ] '+ retData[i].titleName;
                		option.value = retData[i].id;
                		optAvailableJobs.add(option);
                	}
                }else{
                    alert(data);
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
        $mode = $(e.relatedTarget).attr('data-mode');
        //If in edit mode we need to load Interview's data
        if($mode==="edit"){			
        	$url = $(e.relatedTarget).attr('data-interview_detail_url');
    		$interview_id = $(e.relatedTarget).attr('data-id');
    	    $url   = $url+"/"+$interview_id;    	    
    		$.ajax({
                type: "GET",
                url : $url,
                success: function (result) {
                    var retData = JSON.parse(result.data);
                    var success = result.success;

                    if(success == true){
                    	/*
                    	* Location 
                    	*/
                    	var location = document.getElementById("interview_location");
                    	if(location !== null){
                    		location.value = retData.location;
                    	}
                    	/*
                    	* Interview ID
                    	*/
                    	var interviewID = document.getElementById("interviewer_id");
                    	if(interviewID !== null){
                    		interviewID.value = retData.interviewer_id;
                    	}
                    	/*
                    	* Interview Note
                    	*/
                    	var interviewNote = document.getElementById("interview_notes");
                    	if(interviewNote !== null){
                    		interviewNote.value = retData.notes;
                    	}
                    	/*
                    	* 2016/03/21 12:00 AM - 2016/03/21 11:59 PM  YYYY/MM/DD h:mm A
                    	*/
                    	var scheduled_date = document.getElementById("scheduled_date");
                    	if(scheduled_date !== null){
                    		$('#scheduled_date').data('daterangepicker').setStartDate(retData.scheduled_time);
                    		$('#scheduled_date').data('daterangepicker').setEndDate(retData.scheduled_time_end);
                    		scheduled_date.value = retData.scheduled_time + ' - ' + retData.scheduled_time_end;
                    	}
                    	var interviewState = document.getElementById("interview_state");
                    	if(interviewState !== null){
                    		interviewState.value = retData.interview_state;
                        }
                        /*
                        * Job Title
                        */
                    	var appliedJob = document.getElementById("applied_job_id");
                    	if(appliedJob !== null){
                    		appliedJob.value = retData.get_candidate_job_info.get_job_info.id;
                    		appliedJob.text  =	'[ JD.'+ ( '00000000' + retData.get_candidate_job_info.get_job_info.id ).slice( -8 ) + ' ] '
                    		+ retData.get_candidate_job_info.get_job_info.title.name;
                        }
                    	
                    }else{
                    	alert('Error:' + retData);
                    }              
                },
                error: function (data) {
                    alert('Error:' + data);
                }
            });
    	}else{

        	/*
        	* Location 
        	*/
        	var location = document.getElementById("interview_location");
        	if(location !== null){
        		location.value = '';
        	}
        	/*
        	* Interview ID
        	*/
        	var interviewID = document.getElementById("interviewer_id");
        	if(interviewID !== null){
        		interviewID.value = '';
        	}
        	/*
        	* Interview Note
        	*/
        	var interviewNote = document.getElementById("interview_notes");
        	if(interviewNote !== null){
        		interviewNote.value = '';
        	}
        	/*
        	* 2016/03/21 12:00 AM - 2016/03/21 11:59 PM  YYYY/MM/DD h:mm A
        	*/
        	var scheduled_date = document.getElementById("scheduled_date");
        	if(scheduled_date !== null){
        		var longDateFormat  = 'yyyy-MM-dd HH:mm:ss';	   	   
        		var start = jQuery.format.date(new Date(), longDateFormat);
        		var end = jQuery.format.date(new Date(), longDateFormat);
        		$('#scheduled_date').data('daterangepicker').setStartDate(start);
        		$('#scheduled_date').data('daterangepicker').setEndDate(end);
        		scheduled_date.value = start + ' - ' + end;
        	}
        	var interviewState = document.getElementById("interview_state");
        	if(interviewState !== null){
        		interviewState.selectedIndex = 0;
            }
            /*
            * Job Title
            */
        	var appliedJob = document.getElementById("applied_job_id");
        	if(appliedJob !== null){
        		appliedJob.selectedIndex = 0
            }        	
    	}
	     // Pass form reference to modal for submission on yes/ok
	    $form = $('form[name="Frm_Add_Interview"]');
	    $(this).find('.modal-footer #confirmSave').data('form', $form);
	});
	<!-- Form confirm (yes/ok) handler, submits form -->
	$('#dlg_add_candidate_interviews').find('.modal-footer #confirmSave').on('click', function(){	
	    $(this).data('form').submit();	      
	});
	$('#dlg_add_candidate_interviews').find('.modal-footer #confirmSave').submit(function(event){
	    // Cancels the form submission
	    event.preventDefault();	    
	    submitForm();
	});
})  
</script>


<div id='dlg_add_candidate_interviews'
	name='dlg_add_candidate_interviews' class='modal modal-primary fade'
	tabindex='-1' role="dialog"
	aria-labelledby="dlg_add_candidate_interviews" aria-hidden="true"
	data-width='760'>
	{!! Form::open(array('route' => 'recruitments.interviews.store',
	'accept-charset'=>'UTF-8', 'name' =>'Frm_Add_Interview',
	'class'=>'form-horizontal','style'=>'display:inline')) !!} {!!
	Form::hidden('candidate_id',
	!is_null($candidate)?$candidate->id:null)!!} {!!
	Form::hidden('interview_start_datetime')!!} {!!
	Form::hidden('interview_end_datetime')!!} {!!
	Form::hidden('redirect_url')!!}

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Primary Modal</h4>
			</div>

			<div class="modal-body">

				<div class="row">

					<div class="form-group" id="field_job_title_id">
						<label class="control-label col-sm-3" for="applied_job_id">{{
							trans('labels.recruitments.candidate_interviews.columns.candidate_job_id')
							}}<font class="text-red">*</font>
						</label>
						<div class="controls col-sm-6">
							<select type="select-one" class="form-control"
								id="applied_job_id" name="applied_job_id">
							</select>
						</div>
					</div>

					<div class="form-group" id="field_interview_state">
						<label class="control-label col-sm-3" for="interview_state">{{
							trans('labels.recruitments.candidate_interviews.columns.interview_state')
							}}<font class="text-red">*</font>
						</label>
						<div class="controls col-sm-6">
							<select type="select-one" class="form-control"
								id="interview_state" name="interview_state">
								<option value="1st Interview">1st Interview</option>
								<option value="2nd Interview">2nd Interview</option>
								<option value="3rd Interview">3rd Interview</option>
								<option value="Final Interview">Final Interview</option>
							</select>
						</div>
					</div>

					<div class="form-group" id="field_scheduledDate">
						<label class="control-label col-sm-3" for="scheduled_date">
							{{trans('labels.recruitments.candidate_interviews.columns.scheduled_time')
							}} </label>
						<div class="controls col-sm-6">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</div>
								<input type="text" class="form-control pull-right"
									id="scheduled_date">
							</div>
							<script type="text/javascript">
							    $(function () {
							        $('#scheduled_date').daterangepicker({
								        timePicker: true, 
								        timePickerIncrement: 30, 
								        format: 'YYYY/MM/DD h:mm A'
							        },
								    function(start, end, label) {											
											$('input[name="interview_start_datetime"]').val(start.format('YYYY-MM-DD hh:mm:ss'));		
											$('input[name="interview_end_datetime"]').val(end.format('YYYY-MM-DD hh:mm:ss'));		
								            console.log("A new date range was chosen: " + start.format('YYYY-MM-DD hh:mm:ss') + ' to ' + end.format('YYYY-MM-DD hh:mm:ss'));
								    }
									);
							    });
							</script>
						</div>
					</div>
					<div class="form-group" id="field_location">
						<label class="col-sm-3 control-label" for="interview_location">{{
							trans('labels.recruitments.candidate_interviews.columns.location')
							}}</label>
						<div class="controls col-sm-6">
							<input class="form-control" type="text" id="interview_location"
								name="interview_location"
								value="{{ old('interview_location') }}" validation="none">
						</div>
					</div>

					<div class="form-group" id="field_interviewer_id">
						<label class="control-label col-sm-3" for="interviewer_id">{{
							trans('labels.recruitments.candidate_interviews.columns.interviewer_id')
							}}<font class="text-red">*</font>
						</label>
						<div class="controls col-sm-6">
							<input class="form-control" type="text" id="interviewer_id"
								name="interviewer_id" value="{{ old('interviewer_id') }}"
								validation="none">
						</div>
					</div>

					<div class="form-group" id="field_result_id">
						<label class="control-label col-sm-3" for="result_id">{{
							trans('labels.recruitments.candidate_interviews.columns.result_id')
							}}<font class="text-red">*</font>
						</label>
						<div class="controls col-sm-6">
							<select type="select-one" class="form-control" id="result_id"
								name="result_id"> @foreach($mstInterviewResults as
								$interviewResult)
								<option value="{{$interviewResult->id}}">{{$interviewResult->name}}</option>
								@endforeach
							</select>
						</div>
					</div>


					<div class="form-group" id="field_note">
						<label class="control-label col-sm-3" for="interview_notes">{{
							trans('labels.recruitments.candidate_interviews.columns.notes')
							}} </label>
						<div class="controls col-sm-6">
							<textarea class="form-control" type="textarea" rows="4"
								id="interview_notes" name="interview_notes"
								value="{{ old('interview_notes') }}"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
				<button type="button" id="confirmSave" name="confirmSave"
					type='submit' class="btn btn-primary">Save changes</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	{!!Form::close()!!}
</div>
