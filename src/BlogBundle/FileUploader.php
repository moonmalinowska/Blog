<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 26.05.16
 * Time: 17:14
 *
 * File Uploader
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader.
 *
 * @package BlogBundle
 * @author Monika Malinowska
 */
class FileUploader extends UploadedFile
{
    /**
     * Target directory
     *
     * @var $targetDir
     */
    private $targetDir;

    /**
     * FileUploader constructor.
     * @param string $targetDir
     */
    public function __construct($targetDir)
    {
        return __DIR__ . '/../../../../web/assetic/images/' . $this->targetDir = $targetDir;
        //$this->targetDir = $targetDir;
    }
}
