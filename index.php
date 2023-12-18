<?php

require "./vendor/autoload.php";

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Gotenberg\Gotenberg;
use Gotenberg\Stream;

print_r($_FILES);

if (!empty($_POST['submit'])) {
  $pathFile = $_FILES['file']['tmp_name'];
  echo $pathFile;
  die();
  $old_text = $_POST['old_text'];
  $new_text = $_POST['new_text'];
  $temp_docx_filename = uniqid() . ".docx";

  // mencari text yang memiliki awalan '{' dan akhiran '}'
  $phpword = new TemplateProcessor($pathFile);
  $phpword->setMacroChars('{{', '}}');
  $phpword->setValue($old_text, $new_text);

  //$pathFile = 'template.docx';
  //$phpword = new TemplateProcessor($pathFile);
  //$phpword->setMacroChars('{', '}');
  //$phpword->setValue('no', 'CONTOH_AJA/KODE_KLASIFIKASI');


  // menyimpan file yang telah diubah
  $phpword->saveAs($temp_docx_filename);


  // convert doc to pdf
  //\PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
  //\PhpOffice\PhpWord\Settings::setPdfRendererPath(realpath(__DIR__) . '/vendor/dompdf/dompdf');

  //$reader = IOFactory::createReader('Word2007');
  //$oPHPword = $reader->load('./hasil_replace.docx');

  //$writer = IOFactory::createWriter($oPHPword, 'PDF');
  //$writer->save('./sample.pdf');


  $apiUrl = 'http://localhost:3000';
  $request = Gotenberg::libreOffice($apiUrl)
      ->convert(Stream::path($temp_docx_filename));
  $filename = Gotenberg::save($request, './');


  readfile($filename);
  die();
}

?>


<!DOCTYPE html>
<html>
<body>

<form action="./" method="post" enctype="multipart/form-data">
  Select docx to upload:
  <input type="file" name="file" >
  <br/>

  <label for="">old text:</label>
  <input type="text" name="old_text">
  <br/>

  <label for="">new text:</label>
  <input type="text" name="new_text">
  <br/>

  <input type="submit" value="Proses file" name="submit">
</form>

</body>
</html>

