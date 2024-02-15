<?php
namespace App\Interfaces;

interface FileService {
	function about();
	function freeSpace();
	function getId();
    function usedSpace();
    function getBelongsTo();
	function download($dir, $fileId);
	function upload($dir, $file, $fName = null, $fExtension = null);
	function delete($dir, $fileId);
	function rename($dirPath,  $fileId, $name, $fileLocal);
    function move($dirOnCloud, $folderOnCloud, $destFolder);
    function deleteDirectory($dir);
	function makeDirectory($parentOnCloud, $name, $fileId);
	function getContentsOfDirectory($dir, $recursive = false);	
    function downloadStream($dir, $fileId);
}