<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/moment.min.js"></script>
<style>
.top-buffer { margin-top:7px; }
</style>

<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Pelaporan Pajak</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
<div class="space-4"></div>
	<div id="modal_lov_add_laporan" class="portlet light bordered">

		<!-- modal title -->
		<div class="portlet-title">
			<div class="caption font-red-sunglo">
				<span class="caption-subject bold uppercase">Form Pelaporan Pajak</span>
			</div>
		</div>
		<input type="hidden" id="modal_lov_add_laporan_id_val" value="" />
		<input type="hidden" id="modal_lov_add_laporan_code_val" value="" />
		<input type="hidden" id="month_id" value="" />
		<input type="hidden" id="t_cust_acc_dtl_trans_id" value="" />
		<!-- modal body -->
		<div class="portlet-body form">

			<form class="form-horizontal">
			  <div class="form-group">
				<label class="col-md-2 control-label">NPWPD:</label>
				<div class="col-md-3 input-group">
				  <input type="text" class="form-control" id="npwd" readonly="" value="<?php echo $this->session->userdata('npwd'); ?>">
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">PERIODE:</label>
				<div class="col-md-4 input-group">
				  <select id="months" class="form-control required"></select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Klasifikasi:</label>
				<div class="col-md-4 input-group">
				  <select id="klasifikasi" class="form-control"></select>
				</div>
			  </div>
			  <div class="form-group" id="rincian_form">
				<label class="col-md-2 control-label">Rincian:</label>
				<div class="col-md-4 input-group">
				  <select id="rincian" class="form-control"></select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Masa Pajak:</label>
				<div class="col-md-6 form-inline">
					<input class="form-control date-picker required" type="text" id="datepicker" readonly="">
					<label>s/d</label>
					<input class="form-control date-picker required" type="text" id="datepicker2" readonly="">
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-md-offset-2 col-md-6 input-group">
					<a class="btn green-jungle" id="isiformupload">Upload File Transaksi</a>
					<strong>atau</strong>
					<a class="btn green-jungle" id="isiformtransaksi">Isi Form Transaksi</a>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Nilai Omzet:</label>
				<div class="col-md-3 input-group" style="display: none;">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
						<input class="form-control" readonly="" id="omzet_value"  style="text-align:right;" >
				</div>

				<div class="col-md-3 input-group">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
						<input class="form-control" readonly="" id="omzet_value_mask"  style="text-align:right;" >
				</div>

			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Pajak yang Harus dibayar:</label>

				<div class="col-md-3 input-group" style="display: none;">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
					<input class="form-control" readonly=""  id="val_pajak" style="text-align:right;">
				</div>

				<div class="col-md-3 input-group">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
					<input class="form-control" readonly=""  id="val_pajak_mask" style="text-align:right;">
				</div>
				<strong id="info-nihil" class="green" style="display:none;">
                	Pembayaran Pajak Anda dikategorikan NIHIL
				</strong>
			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Denda:</label>

				<div class="col-md-3 input-group" style="display: none;">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
					<input class="form-control" readonly=""  id="val_denda" style="text-align:right;">
				</div>

				<div class="col-md-3 input-group">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
					<input class="form-control" readonly=""  id="val_denda_mask" style="text-align:right;">
				</div>

			  </div>
			  <div class="form-group">
				<label class="col-md-2 control-label">Total Bayar:</label>

				<div class="col-md-3 input-group" style="display: none;">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
					<input class="form-control" readonly=""  id="totalBayar" style="text-align:right;">
				</div>

				<div class="col-md-3 input-group">
					<span class="input-group-addon">
						<i><b>Rp</b></i>
					</span>
					<input class="form-control" readonly=""  id="totalBayar_mask" style="text-align:right;">
				</div>

			  </div>
			</form>

			<div class="form-actions">
				<div class="row">
					<div class="col-md-offset-10">
						<button class="btn btn-danger" type="button" id="submit-btn">Kirim Pelaporan Pajak</button>
						<input type="hidden" id="hasExcelUploaded" value=0 />
					</div>
				</div>
			</div>
		</div>
	</div><!-- /.end modal -->

<?php  $this->load->view('pelaporan_pajak/lov_form_harian.php'); ?>
<?php  $this->load->view('pelaporan_pajak/lov_upload_file.php'); ?>

<script type="text/javascript">

	$(document).ready(function(){
		//combo box months
		$.ajax({
			url: "<?php echo WS_JQGRID ?>pelaporan_pajak.pelaporan_pajak_controller/pelaporan_bulan",
			datatype: "json",
            type: "POST",
            success: function (response) {
				//var data = $.parseJSON(response);
				var data = response;
				i = 0;
				while(i < data.rows.length){
					var months = data.rows[i].code;
					var start_date = data.rows[i].start_date_string;
					var end_date = data.rows[i].end_date_string;
					var p_id = data.rows[i].p_finance_period_id;
					$('#months').append('<option value="'+ start_date +'" data-id="'+ end_date +'" data-idkey = "'+ p_id +'">' + months + '</option>');
					i++;
				}
				if(data.rows[0].start_date_string != null && data.rows[0].end_date_string != null ){
					$("#datepicker").datepicker('setDate',data.rows[0].start_date_string);
					$("#datepicker2").datepicker('setDate',data.rows[0].end_date_string	);
				};
			}
        });

		//on change combo box months
        $('#months').change(function(){
			StartDate = $('#months').find(':selected').val();
			EndDate = $('#months').find(':selected').data("id");
			$("#datepicker").datepicker('setDate',StartDate);
			$("#datepicker2").datepicker('setDate',EndDate);
			$('#omzet_value').val("");
			$('#omzet_value_mask').val("");
			$('#val_pajak').val("");
			$('#val_pajak_mask').val("");
			$('#val_denda').val("");
			$('#val_denda_mask').val("");
			$('#totalBayar').val("");
			$('#totalBayar_mask').val("");
		});

        //combo box klasifikasi
        $.ajax({
			url: "<?php echo WS_JQGRID ?>pelaporan_pajak.pelaporan_pajak_controller/p_vat_type_dtl",
			datatype: "json",
            type: "POST",
            success: function (response) {

					//var data = $.parseJSON(response);
					var data = response;

					vat_code_classification = data.rows[0].vat_code;
					vat_type_id = data.rows[0].p_vat_type_id;

					if(vat_type_id == 1)
					{
						$('#klasifikasi').append('<option selected value="KATERING">KATERING HOTEL</option>');
					} else if(vat_type_id == 2 && vat_code_classification != "KATERING")
					{
						$('#klasifikasi').append('<option selected value="KATERING">KATERING</option>');
					}

					if(vat_code_classification == "RUMAH MAKAN")
					{
						$('#klasifikasi').append('<option selected value="RESTORAN">RESTORAN</option>');
					} else
					{
						$('#klasifikasi').append('<option selected value='+ data.rows[0].vat_code +'>'+ data.rows[0].vat_code +'</option>');
					}

					$('#vat_pct').append('<option value='+ data.rows[0].vat_code +' data-id='+ data.rows[0].vat_pct +' >'+ data.rows[0].vat_code +'</option>');

				}
        });

        //combo box rincian
        $.ajax({
			url: "<?php echo WS_JQGRID ?>pelaporan_pajak.pelaporan_pajak_controller/p_vat_type_dtl_cls",
			datatype: "json",
            type: "POST",
            success: function (response) {
				//var data = $.parseJSON(response);
				var data = response;
				i=0;
				if (data.rows.length >0){
					while(i<data.rows.length){
						$('#rincian').append('<option value="'+ data.rows[i].vat_code +'" data-id='+ data.rows[i].vat_pct +'>'+ data.rows[i].vat_code +'</option>');
					i++;
					}
				} else{
					$('#rincian_form').hide(100);
					$('#rincian').append('<option value="" data-id= ""></option>');
				}
			}
        });

        //onchange rincian
        $('#rincian').change(function(){
        	var now_date = moment($('#datepicker').val()).format("YYYY-DD-");
			var get_date = moment($('#datepicker').val()).format("DD-YYYY");

			nilai_pajak = $('#rincian').find(':selected').data('id');
			$('#val_pajak').val(  $('#omzet_value').val() * nilai_pajak * 0.01);
			$('#val_pajak_mask').val(formatRupiahCurrency( $('#val_pajak').val() ));

			if($('#omzet_value').val() == 0)
			{
				$('#omzet_value').val( 0 );
				$('#omzet_value_mask').val( 0 );
			}

			if ($('#omzet_value').val() != 0)
			{
				$('#val_pajak').val(  $('#omzet_value').val() * nilai_pajak * 0.01);
				$('#val_pajak_mask').val(formatRupiahCurrency( $('#val_pajak').val() ));
			} else {
				$('#val_pajak').val(  0 );
				$('#val_pajak_mask').val( 0 );
			}

			if(($('#datepicker').val()).length  != 0 ){
				getFinedStart(now_date, get_date);
			}

		});


        //cek date sebelum mengisi form transaksi
        $('#isiformtransaksi').on('click',function() {
			var date = $("#datepicker").datepicker('getDate');
			var date1 = $("#datepicker2").datepicker('getDate');
			var dates = $("#datepicker").val();
			var dates1 = $("#datepicker2").val();
			var datesFormat = moment(date).format('YYYY-MM-DD');
			var dates1Format = moment(date1).format('YYYY-MM-DD');

			if ((dates.length != 0) && (dates1.length != 0)){
				var diffDays = Math.ceil((date1.getTime() - date.getTime())/1000/3600/24);
				var numDaysMonth = new Date(date1.getYear(), date1.getMonth()+1, 0).getDate();
				var division = parseInt($("#number").val())/numDaysMonth*diffDays;
					if (diffDays>=0){
						modal_lov_form_harian_show(date,date1,diffDays);
					} else {
						swal('error','Input masa pajak tidak valid. Penanggalan awal pajak harus lebih awal dari akhir pajak','error');
					}
			} else {
				swal('error','Isi terlebih dahulu periode masa pajak secara lengkap','error');
			}

	    });

        //form upload
	    $('#isiformupload').on('click',function() {
			var dates = $("#datepicker").val();
			var dates1 = $("#datepicker2").val();

			if ((dates.length != 0) && (dates1.length != 0)){
				modal_upload_file_show();
			} else	{
				swal('error','Isi terlebih dahulu periode masa pajak secara lengkap','error');
			}

	    });

	    //submit form
	    $('#submit-btn').on('click',function() {
	    	var nilai_total = $('#totalBayar').val();
	    	var omzet_value = $('#omzet_value').val();

	    	text_submit = 	"<h5>Wajib Pajak yang terhormat, "+
						"Anda Melaporkan pajak daerah untuk : </h5>"+
						"<pre style='text-align:left;'>" +
						"NPWPD 		 	: "+ $('#npwd').val() + "\n" +
						"Klasifikasi 		: "+ $('#klasifikasi').find(':selected').val() + "\n" +
						"Masa Pajak  		: "+ $('#months').find(':selected').text() + "\n" +
						"Pajak Pokok 		: Rp. "+ formatRupiahCurrency($('#val_pajak').val()) + "\n" +
						"Denda 		 	: Rp. "+ formatRupiahCurrency($('#val_denda').val()) + "\n" +
						"Jumlah Pajak yang harus dibayar : <b> Rp. "+  formatRupiahCurrency($('#totalBayar').val()) +"</b>"+
						"</pre>"+
						"<h5>Apakah anda yakin akan mengirim laporan dimaksud?</h5>";

			//pengecualian jika nilai restoran nihil
			var is_active_limit_restoran = '<?php echo $this->session->userdata('is_active_limit_restoran'); ?>';

			if((nilai_total.length == 0 || nilai_total == 0) && omzet_value == 0 && is_active_limit_restoran == 'N') {
				swal('Error','Harap mengisi data secara lengkap sebelum submit','error');
			}else{
				swal({
					title: "<b>Konfirmasi</b>",
					html: text_submit,
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Ya",
					cancelButtonText: "Tidak",
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger'
				}).then(function(){
					var dateTimeStart = new Date($("#datepicker").datepicker("getDate"));
					var dateTimeEnd = new Date($("#datepicker2").datepicker("getDate"));
					var strDateTimeStart = moment(dateTimeStart).format('DD-MM-YYYY');
					var strDateTimeStart2 = moment(dateTimeStart).format('DD-MM-YYYY');
					var strDateTimeEnd = moment(dateTimeEnd).format('DD-MM-YYYY');
					var p_vat_type_dtl_ids = "<?php echo $this->session->userdata('vat_type_dtl') ?>";
					var day = $("#datepicker").datepicker('getDate').getDate();
					var month = $("#datepicker").datepicker('getDate').getMonth();
					var year = $("#datepicker").datepicker('getDate').getYear();
					if( $('#klasifikasi').find(':selected').val() == "KATERING" ){
						p_vat_type_dtl_ids = 11;
					}

					var p_vat_type_dtl_cls_id_ = '';
					if($('#rincian').length > 0) {
						p_vat_type_dtl_cls_id_ = $('#rincian').find(':selected').val();
					}

					var items = new Array();

					items.push({
						'user_name' : '<?php echo $this->session->userdata('user_name'); ?>',
						'npwd' : '<?php echo $this->session->userdata('npwd'); ?>',
						't_cust_accounts_id' : parseInt(<?php echo $this->session->userdata('cust_account_id'); ?>),
						'finance_period' : $('#months').find(':selected').data("idkey"),
						'p_vat_type_dtl_id' : <?php echo $this->session->userdata('vat_type_dtl'); ?>,
						'p_vat_type_dtl_cls_id' : p_vat_type_dtl_cls_id_, /*modified by wiliam. p_vat_type_dtl_cls_id : ''*/
						'start_period' : strDateTimeStart2,
						'end_period' : strDateTimeEnd,
						'total_trans_amount' :  $('#omzet_value').val(),
						'total_vat_amount' : $('#totalBayar').val()
					});

					$.ajax({
						url: "<?php echo WS_JQGRID ?>pelaporan_pajak.pelaporan_pajak_controller/createSPTPD",
						datatype: "json",
						type: "POST",
						data: {
								end_period : strDateTimeEnd,
								items: JSON.stringify(items),
								t_cust_account_id : parseInt(<?php echo $this->session->userdata('cust_account_id'); ?>),
								npwd : '<?php echo $this->session->userdata('npwd'); ?>',
								p_finance_period : $('#months').find(':selected').val(),
								p_vat_type_dtl_cls_id : p_vat_type_dtl_cls_id_, /*modified by wiliam. p_vat_type_dtl_cls_id : ''*/
								p_vat_type_dtl_id : p_vat_type_dtl_ids,
								penalty_amount : $('#val_denda').find(':selected').val(),
								percentage : ($('#val_pajak').val()/ $('#omzet_value').val() * 100),
								start_period : 	strDateTimeStart,
								t_cust_account_id : <?php echo $this->session->userdata('cust_account_id');?>,
								total_amount :  $('#totalBayar').find(':selected').val(),
								total_trans_amount : $('#omzet_value').val(),
								total_vat_amount : $('#totalBayar').val()
							},
							success: function (response){
								//var data = $.parseJSON(response);
								var data = response;
								swal('info',data.items.o_mess,'info');
							}
					});

				}, function(dismiss){});
			}
	    });

	});

	function formatRupiahCurrency(total) {
	    var neg = false;
	    if(total < 0) {
	        neg = true;
	        total = Math.abs(total);
	    }
	    return (neg ? "-$" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
	}

	function getFinedStart(now_date, get_date){
		$.ajax({
			async: false,
			url: "<?php echo WS_JQGRID ?>pelaporan_pajak.pelaporan_pajak_controller/get_fined_start",
			datatype: "json",
			type: "POST",
			data:
			{
					nowdate: now_date, //moment($('#datepicker').val()).format("YYYY-DD-"),
					getdate: get_date //moment($('#datepicker').val()).format("DD-YYYY")
			},
			success: function (response)
			{
				//var data = $.parseJSON(response);
				var data = response;
				kelipatan_denda = data.rows[0].booldendamonth - 1;
				if(parseInt(data.rows[0].booldenda) >= 0)
				{
					if(parseInt(kelipatan_denda > 24)){
						kelipatan_denda = 24;
					};
					$('#val_denda').val( parseFloat( 0.02 * $('#val_pajak').val() * kelipatan_denda ).toFixed(2) );
					$('#totalBayar').val(  parseFloat(   $('#val_pajak').val()  )  + parseFloat(  $('#val_denda').val()   ) );
				}
				else
				{
						$('#val_denda').val(parseFloat(0));
						$('#totalBayar').val( parseFloat(   $('#val_pajak').val()    ).toFixed(2) );
				};
				$('#val_denda_mask').val(formatRupiahCurrency( $('#val_denda').val() ));
				$('#totalBayar_mask').val(formatRupiahCurrency( $('#totalBayar').val() ));
			}
		});
	}
</script>