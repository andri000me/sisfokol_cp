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
$filenya = "download.php";
$judul = "[MASTER] Data Download";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kd = nosql($_REQUEST['kd']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek baru
if ($_POST['btnBR'])
	{
	//nilai
	$ke = "$filenya?s=baru&kd=$x";
	
	
	//re-direct
	xloc($ke);
	exit();
	}



//nek batal
if ($_POST['btnBTL'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}



//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$page = nosql($_POST['page']);
	$e_kd = nosql($_POST['e_kd']);
	$e_nama = cegah($_POST['e_nama']);
	
	$filex_namex = strtolower($_FILES['filex_foto']['name']);

	


	//nek null
	if ((empty($e_nama)) OR (empty($filex_namex)))
		{
		//re-direct
		$pesan = "Belum Ditulis. Harap Diulangi...!!";
		$ke = "$filenya?s=$s&kd=$e_kd";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//deteksi .zip
		$ext_filex = substr($filex_namex, -4);



		if (($ext_filex == ".zip"))
			{
			//mengkopi file
			$namakuya = trim(seo_friendly_url($e_nama));
			$namabaru = "$namakuya.zip";
			$foldernya = "../../filebox/download/$e_kd/";
			
			
			//buat folder...
			if (!file_exists('../../filebox/download/'.$e_kd.'')) {
			    mkdir('../../filebox/download/'.$e_kd.'', 0777, true);
				}
		
					

			chmod($foldernya,0777);
			copy($_FILES['filex_foto']['tmp_name'],"../../filebox/download/$e_kd/$namabaru");


			
			//jika baru
			if ($s == "baru")
				{
				//insert
				mysql_query("INSERT INTO cp_m_download(kd, nama, filex, postdate) VALUES ".
								"('$e_kd', '$e_nama', '$namabaru', '$today')");


				//re-direct
				xloc($filenya);
				exit();
				}
				
				
					
					
			//jika update
			if ($s == "edit")
				{
				mysql_query("UPDATE cp_m_download SET nama = '$e_nama', ".
								"filex = '$namabaru', ".
								"postdate = '$today' ".
								"WHERE kd = '$e_kd'");
	
	
				//re-direct
				xloc($filenya);
				exit();
				}
	
					
					
			}
		else
			{
			//salah
			$pesan = "Bukan File .zip . Harap Diperhatikan...!!";
			$ke = "$filenya?s=$s&kd=$e_kd";
			pekem($pesan,$ke);
			exit();
			}

		}
	}

	
	
	

//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$jml = nosql($_POST['jml']);
	$ke = $filenya;

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysql_query("DELETE FROM cp_m_download ".
						"WHERE kd = '$kd'");
		}


	//auto-kembali
	xloc($ke);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();


?>


  
  <script>
  	$(document).ready(function() {
    $('#table-responsive').dataTable( {
        "scrollX": true
    } );
} );
  </script>
  
<?php
//require
require("../../template/js/jumpmenu.js");
require("../../template/js/checkall.js");
require("../../template/js/swap.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="'.$filenya.'" enctype="multipart/form-data" method="post" name="formx">


<p>
<input name="btnBR" type="submit" value="BUAT BARU" class="btn btn-danger">
<hr>
</p>';


if (($s == "baru") OR ($s == "edit"))
	{
	//edit
	$qx = mysql_query("SELECT * FROM cp_m_download ".
						"WHERE kd = '$kd'");
	$rowx = mysql_fetch_assoc($qx);
	$e_kd = nosql($rowx['kd']);
	$e_nama = balikin($rowx['nama']);
	$e_filex1 = balikin($rowx['filex']);


	
	echo '<table border="0" cellspacing="0" cellpadding="3" bgcolor="white">
	<tr valign="top">
	<td>
	
	<p>
	NAMA : 
	<br>
	<input name="e_nama" type="text" size="30" value="'.$e_nama.'" class="btn btn-warning">
	</p>


	<p>
	<input name="filex_foto" type="file" size="15" class="btn btn-warning">
	<br>
	NB. File .zip
	</p>
	
	

	<input name="s" type="hidden" value="'.$s.'">
	<input name="e_kd" type="hidden" value="'.$kd.'">
	<input name="page" type="hidden" value="'.$page.'">

	</td>
	</tr>
	</table>
	
	
	<p>
	<hr>	
	<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
	<hr>
	</p>';
	}
	
	
else
	{
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlcount = "SELECT * FROM cp_m_download ".
					"ORDER BY postdate DESC";
	$sqlresult = $sqlcount;
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);



	
	echo '<div class="table-responsive">          
		  <table class="table" border="1">
		    <thead>
				
	<tr bgcolor="'.$warnaheader.'">
	<td width="16">&nbsp;</td>
	<td width="30">&nbsp;</td>
	<td><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">FILE ZIP</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">POSTDATE</font></strong></td>
	</tr>
	
		    </thead>
		    <tbody>';
	
	if ($count != 0)
		{
		do {
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
			$e_kd = nosql($data['kd']);
			$e_nama = balikin($data['nama']);
			$filex1 = balikin($data['filex']);
			$e_postdate = balikin($data['postdate']);

	
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item'.$nomer.'" value="'.$e_kd.'">
	        </td>
			<td>
			<a href="'.$filenya.'?s=edit&kd='.$e_kd.'"><img src="'.$sumber.'/template/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			<td>'.$e_nama.'</td>
			<td>
			<p>
			[<a href="'.$sumber.'/filebox/download/'.$e_kd.'/'.$filex1.'">FILE ZIP</a>]
			</p>

			</td>
			<td>'.$e_postdate.'</td>
	        </tr>';
			}
		while ($data = mysql_fetch_assoc($result));
		}
	
	
	echo '</tbody>
		  </table>
		  </div>
		  
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	<strong><font color="#FF0000">'.$count.'</font></strong> Data. '.$pagelist.'
	<input name="jml" type="hidden" value="'.$count.'">
	<br>
	<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
	<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
	<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
	</td>
	</tr>
	</table>';
	}
	

echo '</form>';

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//null-kan
xclose($koneksi);
exit();
?>