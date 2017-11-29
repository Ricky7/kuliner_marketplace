<?php
  
  require_once "../class/db.php";
  require_once "../class/User.php";

  $user = new User($db);

  $user->cekLogin();

  if(isset($_REQUEST['id']))
  {
      $id = $_REQUEST['id'];
      extract($user->getBlogID($id)); 
  }

  if(isset($_POST['submit'])) {

      try {
        $user->editBlog(array(
            'judul' => $_POST['judul'],
            'isi' => $_POST['isi'],
            'lokasi' => htmlspecialchars($_POST['lokasi'], ENT_QUOTES),
            'jenis_konten' => $_POST['jenis']
          ), $id);
        header("Location: a_daftar_blog.php");
      } catch (Exception $e) {
      die($e->getMessage());
    }
  }

?>

<?php
  include "a_header.php";
?>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: 'textarea',
    relative_urls : false, // agar url tidak disingkat
    height: 500,
    theme: 'modern',
    plugins: [
      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen',
      'insertdatetime media nonbreaking save table contextmenu directionality',
      'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
    ],
    toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
    image_advtab: true,
    templates: [
      { title: 'Test template 1', content: 'Test 1' },
      { title: 'Test template 2', content: 'Test 2' }
    ],
    content_css: [
      '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
      '//www.tinymce.com/css/codepen.min.css'
    ]
   });
</script>
<h2 class="page-header">
  Edit Konten
</h2>
<div class="container">
  <form method="post">
    <label>Jenis Konten</label><br>
    <div class="row" style="padding-bottom:15px;">
      <div class="col-md-1">
        <input type="radio" name="jenis" <?php echo ($jenis_konten=='Blog')?'Checked':'' ?> value="Blog"> Blog<br>
      </div>
      <div class="col-md-1">
        <input type="radio" name="jenis" <?php echo ($jenis_konten=='Event')?'Checked':'' ?> value="Event"> Event<br>
      </div>
      <div class="col-md-10"></div>
    </div>
    <div class="row">
      <div class="col-md-10">
        <input type="text" name="judul" value="<?php echo $judul; ?>" Placeholder="Judul Maksimal 25 karakter" maxlength="25" class="form-control" required>
      </div>
      <div class="col-md-2"></div>
    </div>
    <div class="row" style="padding-top:20px">
      <div class="col-md-10">
        <textarea name="isi"><?php echo $isi; ?></textarea>
      </div>
      <div class="col-md-2"></div>
    </div>
    <div class="row" style="padding-top:20px">
      <div class="col-md-10">
        <input type="text" name="lokasi" value="<?php echo $lokasi; ?>" Placeholder="Isi dengan format google maps" class="form-control" required>
      </div>
      <div class="col-md-2"></div>
    </div>
    <div class="row" style="padding-top:20px">
      <div class="col-md-4"></div>
      <div class="col-md-2">
        <input type="submit" name="submit" value="Submit" class="form-control btn btn-primary btn-md">
      </div>
      <div class="col-md-6"></div>
    </div>
  </from>
</div>

<hr>

<?php
  include "a_footer.php";
?>
          
      