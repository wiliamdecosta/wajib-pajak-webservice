<div id="modal_upload_file" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
		    <!-- modal title -->
			<div class="modal-header no-padding">
				<div class="table-header">
					<span class="form-add-edit-title"> Upload File </span>
				</div>
			</div>

			<!-- modal body -->
			<form method="post" action="" id="form-upload-file" enctype="multipart/form-data">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="modal-body">
			   <div class="form-group">
					<label class="control-label col-md-3">Upload Excel
					</label>
					<div class="col-md-7">
						<div class="input-group">
						<input type="hidden" placeholder="Upload File disini" name="schema_id" id="upload_input" class="form-control required" aria-required="true">
						<input type="file" id="excel_trans_cust" name="excel_trans_cust" required/>
						</div>
					</div>
				</div>
				<div class="checkbox">
					<label class="control-label">
						<a href="<?php echo base_url().'template_upload/contoh_format_file_transaksi_harian_WP.xls';?>">Template Excel</a>
					</label>
				</div>
			</div>

			<!-- modal footer -->
			<div class="modal-footer no-margin-top">
			    <div class="bootstrap-dialog-footer">
			        <div class="bootstrap-dialog-footer-buttons">

						<button class="btn btn-md radius-4">
        					<i class="ace-icon fa fa-check"></i>
        					Submit
        				</button>
						<button class="btn btn-danger btn-md radius-4" data-dismiss="modal">
        					<i class="ace-icon fa fa-times"></i>
        					Close
        				</button>
    				</div>
				</div>
			</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.end modal -->

<script>
	function modal_upload_file_show(){
		$("#modal_upload_file").modal("toggle");
	};

    $('#form-upload-file').submit(function(e){
		var url_submit = "<?php echo WS_JQGRID.'pelaporan_pajak.pelaporan_pajak_controller/upload_fileexcel'; ?>"
		var formData = new FormData($(this)[0]);

		var date = $("#datepicker").datepicker('getDate');
		var date1 = $("#datepicker2").datepicker('getDate');
		var start_period = moment(date).format('YYYY-MM-DD');
		var end_period = moment(date1).format('YYYY-MM-DD');

		formData.append('start_period', start_period);
		formData.append('end_period', end_period);
		formData.append('p_vat_type_dtl_id', <?php echo $this->session->userdata('vat_type_dtl') ?>);
		if ($('#rincian').find(':selected').val() != "")
		{
			formData.append('p_vat_type_dtl_cls_id',$('#rincian').find(':selected').val() );
		} else
		{
			formData.append('p_vat_type_dtl_cls_id', '');
		}


		$.ajax({
			url: url_submit,
			type:'POST',
			dataType:'json',
			data: formData,
			success: function(response) {
				// console.log(response);
				// $('#modal_upload_file').modal('toggle');
				swal('Informasi',response.message,'info');
				if (response.success == true){
					$('#hasExcelUploaded').val(1);
					$('#omzet_value').val(response.omzet_value);
					$('#omzet_value_mask').val(  formatRupiahCurrency($('#omzet_value').val())  );

					// ________________________ Hitung Perihal Denda, Omzet, Total Bayar
					var vat_type_dtl = $('#rincian').val();
					if(vat_type_dtl != ""){
						vat_pct = $('#rincian').find(':selected').data('id');
						$('#val_pajak').val( parseFloat((vat_pct * parseInt($('#omzet_value').val())) / 100).toFixed(2) );
						$('#val_pajak_mask').val(formatRupiahCurrency( $('#val_pajak').val() ));

						$.ajax({
							url: "<?php echo WS_JQGRID ?>pelaporan_pajak.pelaporan_pajak_controller/p_vat_type_dtl",
							datatype: "json",
							type: "POST",
							success: function (response) {
								// var data = $.parseJSON(response);
								var data = response;
								if(vat_pct == 0 || vat_pct == null ){
									$('#val_pajak').val( parseFloat((data.rows[0].vat_pct * parseInt($('#omzet_value').val())) / 100).toFixed(2) );
									$('#val_pajak_mask').val(formatRupiahCurrency( $('#val_pajak').val() ));
								}

								var date_denda_signed = false;
								var now_date = moment($('#datepicker').val()).format("YYYY-DD-");
								var get_date = moment($('#datepicker').val()).format("DD-YYYY");
								getFinedStart(now_date, get_date);
							}
						});

					}else{

						$('#rincian_form').hide(100);
						$.ajax({
							url: "<?php echo WS_JQGRID ?>pelaporan_pajak.pelaporan_pajak_controller/p_vat_type_dtl",
							datatype: "json",
							type: "POST",
							success: function (response) {
								// var data = $.parseJSON(response);
								var data = response;
								
								$('#val_pajak').val( parseFloat((data.rows[0].vat_pct * parseInt($('#omzet_value').val())) / 100).toFixed(2) );
								$('#val_pajak_mask').val(formatRupiahCurrency( $('#val_pajak').val() ));

								var date_denda_signed = false;
								var now_date = moment($('#datepicker').val()).format("YYYY-DD-");
								var get_date = moment($('#datepicker').val()).format("DD-YYYY");
								getFinedStart(now_date, get_date);
							}
						});

					}
					// ________________________
				}
			},
			error: function(response) {
						return false;
			alert("AJAX Gagal")},
			cache:false,
			contentType:false,
			processData:false
		});
		$('#modal_upload_file').modal('hide');
		return false;
	});
</script>