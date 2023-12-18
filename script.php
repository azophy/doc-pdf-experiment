<?php

require "./vendor/autoload.php";

//use \PHPOffice\PHPWord\TemplateProcessor;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;

$pathFile = 'sertifikat.docx';
$old_text = 'NOMOR_SURAT';
$new_text = '23423/kodekode/diskom';
//$pathFile = $_FILES['file']['tmp_name'];
//$old_text = $_POST['old_text'];
//$new_text = $_POST['new_text'];
$temp_docx_filename = uniqid() . ".docx";
$result_dir = './';

$phpword = new TemplateProcessor($pathFile);
$phpword->setMacroChars('{{', '}}');
$phpword->setValue($old_text, $new_text);
// menyimpan file yang telah diubah
$phpword->saveAs($temp_docx_filename);


use Gotenberg\Gotenberg;
use Gotenberg\Stream;

$apiUrl = 'http://localhost:3000';
$request = Gotenberg::libreOffice($apiUrl)
    ->convert(Stream::path($temp_docx_filename));
$filename = Gotenberg::save($request, $result_dir);
echo "resulting file is: $filename";
