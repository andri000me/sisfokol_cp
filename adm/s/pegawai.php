<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/admin.html");

nocache;

//nilai
$filenya = "pegawai.php";
$judul = "[SETTING] Data Pegawai/Staff/Guru";
$judulku = "$judul";
$judulx = $judul;
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek batal
if ($_POST['btnBTL'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}





//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?kunci=$kunci";
	xloc($ke);
	exit();
	}




//nek entri baru
if ($_POST['btnBARU'])
	{
	//re-direct
	$ke = "$filenya?s=baru&kd=$x";
	xloc($ke);
	exit();
	}







//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$e_nip = cegah($_POST['e_nip']);
	$e_nama = cegah($_POST['e_nama']);
	$e_status = cegah($_POST['e_status']);
	$e_jabatan = cegah($_POST['e_jabatan']);

	$namabaru = "$kd-1.jpg";



	//nek null
	if ((empty($e_nip)) OR (empty($e_nama)) OR (empty($e_status)) OR (empty($e_jabatan)))
		{
		//re-direct
		$pesan = "Belum Ditulis. Harap Diulangi...!!";
		$ke = "$filenya?s=$s&kd=$kd";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//jika update
		if ($s == "edit")
			{
			mysql_query("UPDATE cp_m_pegawai SET nip = '$e_nip', ".
							"nama = '$e_nama', ".
							"status = '$e_status', ".
							"jabatan = '$e_jabatan' ".
							"WHERE kd = '$kd'");

			//update
			mysql_query("UPDATE cp_m_pegawai SET filex1 = '$namabaru', ".
							"postdate = '$today' ".
							"WHERE kd = '$kd'");

			//re-direct
			xloc($filenya);
			exit();
			}



		//jika baru
		if ($s == "baru")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM cp_m_pegawai ".
									"WHERE nip = '$e_nip'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//re-direct
				$pesan = "Sudah Ada. Silahkan Ganti Yang Lain...!!";
				$ke = "$filenya?s=baru&kd=$kd";
				pekem($pesan,$ke);
				exit();
				}
			else
				{
				mysql_query("INSERT INTO cp_m_pegawai(kd, nip, nama, status, jabatan, postdate) VALUES ".
								"('$kd', '$e_nip', '$e_nama', '$e_status', '$e_jabatan', '$today')");

								
				//update
				mysql_query("UPDATE cp_m_pegawai SET filex1 = '$namabaru', ".
								"postdate = '$today' ".
								"WHERE kd = '$kd'");
											

				//re-direct
				xloc($filenya);
				exit();
				}
			}
		}
	}




//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);
	$ke = "$filenya?page=$page";

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysql_query("DELETE FROM cp_m_pegawai ".
						"WHERE kd = '$kd'");
		}

	//auto-kembali
	xloc($filenya);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();


//require
require("../../template/js/jumpmenu.js");
require("../../template/js/checkall.js");
require("../../template/js/swap.js");
?>


  
  <script>
  	$(document).ready(function() {
    $('#table-responsive').dataTable( {
        "scrollX": true
    } );
} );
  </script>
  
<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//jika edit / baru
if (($s == "baru") OR ($s == "edit"))
	{
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysql_query("SELECT * FROM cp_m_pegawai ".
						"WHERE kd = '$kdx'");
	$rowx = mysql_fetch_assoc($qx);
	$e_nip = balikin($rowx['nip']);
	$e_nama = balikin($rowx['nama']);
	$e_status = balikin($rowx['status']);
	$e_jabatan = balikin($rowx['jabatan']);
	$e_filex1 = balikin($rowx['filex1']);


	//jika edit / baru
	//nek null foto
	if (empty($e_filex1))
		{
		$nil_foto = "$sumber/template/img/bg-black.png";
		}
	else
		{
		$nil_foto = "$sumber/filebox/pegawai/$kd/$e_filex1";
		}
		
			?>
	
	
	
	<div class="row">

	<div class="col-md-6">
		
	<?php
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	
	
	<p>
	NIP : 
	<br>
	<input name="e_nip" type="text" value="'.$e_nip.'" size="10" class="btn btn-warning">
	</p>
	
	
	
	<p>
	Nama : 
	<br>
	<input name="e_nama" type="text" value="'.$e_nama.'" size="30" class="btn btn-warning">
	</p>
	
	
	<p>
	Status : 
	<br>
	<input name="e_status" type="text" value="'.$e_status.'" size="30" class="btn btn-warning">
	</p>
	
	
	
	<p>
	Jabatan : 
	<br>
	<input name="e_jabatan" type="text" value="'.$e_jabatan.'" size="30" class="btn btn-warning">
	</p>
	<p>
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	
	<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
	</p>
	
	
	</form>';
	
	?>
		
	
	
	</div>
	
	<div class="col-md-6">
	

	
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  


	<style type="text/css">
	.thumb-image{
	 float:left;
	 width:150px;
	 height:150px;
	 position:relative;
	 padding:5px;
	}
	</style>
	
	
	
	
		<table border="0" cellspacing="0" cellpadding="3">
		<tr valign="top">
		<td width="100">
			<div id="image-holder"></div>
		</td>
		

		</tr>
		</table>
	
	<script>
	$(document).ready(function() {
		
		
	        $("#image_upload").on('change', function() {
	          //Get count of selected files
	          var countFiles = $(this)[0].files.length;
	          var imgPath = $(this)[0].value;
	          var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
	          var image_holder = $("#image-holder");
	          image_holder.empty();
	          if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
	            if (typeof(FileReader) != "undefined") {
	              //loop for each file selected for uploaded.
	              for (var i = 0; i < countFiles; i++) 
	              {
	                var reader = new FileReader();
	                reader.onload = function(e) {
	                  $("<img />", {
	                    "src": e.target.result,
	                    "class": "thumb-image"
	                  }).appendTo(image_holder);
	                }
	                image_holder.show();
	                reader.readAsDataURL($(this)[0].files[i]);
	              }
	              
	
		    
	            } else {
	              alert("This browser does not support FileReader.");
	            }
	          } else {
	            alert("Pls select only images");
	          }
	        });
	        
	        


	        
	        
	        
	      });
	</script>

	<?php
	echo '<div id="loading" style="display:none">
	<img src="'.$sumber.'/template/img/progress-bar.gif" width="100" height="16">
	</div>
	
	
   <form method="post" id="upload_image" enctype="multipart/form-data">
	<input type="file" name="image_upload" id="image_upload" class="btn btn-warning" />

   </form>
   
   <hr>';
	
	?>
	
	
	<script>  
	$(document).ready(function(){
		
		
		
	       $('#image-holder').load("<?php echo $sumber;?>/adm/s/i_pegawai.php?aksi=lihat1&kd=<?php echo $kd;?>");

	
	
	        
	    $('#upload_image').on('change', function(event){
	     event.preventDefault();
	     
			$('#loading').show();
	
	
		
		     $.ajax({
		      url:"i_pegawai_upload.php?kd=<?php echo $kd;?>",
		      method:"POST",
		      data:new FormData(this),
		      contentType:false,
		      cache:false,
		      processData:false,
		      success:function(data){
				$('#loading').hide();
		       $('#preview').load("<?php echo $sumber;?>/adm/s/i_pegawai.php?aksi=lihat&kd=<?php echo $kd;?>");
		       	
		      }
		     })
		    });
		    
		    
	});  
	</script>




	</div>
	
	</div>


	<?php
	}
	



else
	{
	//jika null
	if (empty($kunci))
		{
		$sqlcount = "SELECT * FROM cp_m_pegawai ".
						"ORDER BY nama ASC";
		}
		
	else
		{
		$sqlcount = "SELECT * FROM cp_m_pegawai ".
						"WHERE nip LIKE '%$kunci%' ".
						"OR nama LIKE '%$kunci%' ".
						"OR status LIKE '%$kunci%' ".
						"OR jabatan LIKE '%$kunci%' ".
						"ORDER BY nama ASC";
		}
		
		
	
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlresult = $sqlcount;
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);
	
	
	
	echo '<form action="'.$filenya.'" method="post" name="formx">
	<p>
	<input name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-danger">
	</p>


	<p>
	<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning">
	<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
	</p>
		
	
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td width="20">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td width="50"><strong><font color="'.$warnatext.'">NIP</font></strong></td>
	<td width="250"><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">STATUS</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">JABATAN</font></strong></td>
	<td><strong><font color="'.$warnatext.'">IMAGE</font></strong></td>
	
	</tr>
	</thead>
	<tbody>';
	
	if ($count != 0)
		{
		do 
			{
			if ($warna_set ==0)
				{
				$warna = $warna01;
				$warna_set = 1;
				}
			else
				{
				$warna = $warna02;
				$warna_set = 0;
				}
	
			$nomer = $nomer + 1;
			$i_kd = nosql($data['kd']);
			$i_nip = balikin($data['nip']);
			$i_nama = balikin($data['nama']);
			$i_status = balikin($data['status']);
			$i_jabatan = balikin($data['jabatan']);
			$i_filex1 = balikin($data['filex1']);
	
	
			$nil_foto1 = "$sumber/filebox/pegawai/$i_kd/$i_filex1";

			
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
	        </td>
			<td>
			<a href="'.$filenya.'?s=edit&page='.$page.'&kd='.$i_kd.'"><img src="'.$sumber.'/template/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			<td>'.$i_nip.'</td>
			<td>'.$i_nama.'</td>
			<td>'.$i_status.'</td>
			<td>'.$i_jabatan.'</td>
			<td>
			
			<img src="'.$nil_foto1.'" width="150">
			
			
			</td>
	        </tr>';
			}
		while ($data = mysql_fetch_assoc($result));
		}
	
	
	echo '</tbody>
	  </table>
	  </div>
	
	
	<table width="500" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	<strong><font color="#FF0000">'.$count.'</font></strong> Data. '.$pagelist.'
	<br>

	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	
	<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
	<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
	<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
	</td>
	</tr>
	</table>
	</form>';
	}








//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");


//null-kan
xclose($koneksi);
exit();
?>